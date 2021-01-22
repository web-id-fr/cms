<?php

namespace Webid\Cms\Modules\Articles\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use OptimistDigital\NovaSettings\NovaSettings;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Tests\ArticlesTestCase;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCategoryCreator;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class ArticleCategoryTest extends ArticlesTestCase
{
    use TestsNovaResource, ArticleCategoryCreator, ArticleCreator;

    const ROUTE_SHOW = 'articles.categories.show';

    public function setUp(): void
    {
        parent::setUp();

        URL::defaults([
            'articles_slug' => config('articles.default_paths.articles'),
            'categories_slug' => config('articles.default_paths.categories'),
        ]);
    }

    /** @test */
    public function we_can_get_articles_list_for_a_given_category()
    {
        $category = $this->createArticleCategory(['name' => ['fr' => 'categorie']]);

        $this->createPublishedArticle(['slug' => ['fr' => 'mon-article']])->categories()->attach($category);
        $this->createPublishedArticle(['slug' => ['en' => 'my-other-article']])->categories()->attach($category);
        $this->createArticle([
            'slug' => [
                'fr' => 'article-non-publie',
            ],
            'status' => Article::_STATUS_DRAFT,
        ])->categories()->attach($category);

        $response = $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'category' => 'categorie']))
            ->assertSuccessful()
            ->assertViewIs('articles::article-category.show');

        $this->assertEquals('categorie', $response->viewData('category'));
        $this->assertCount(1, $response->viewData('articles'));
        $this->assertEquals('mon-article', $response->viewData('articles')[0]['slug']);

        // On vérifie que la catégorie n'est pas sensible à la casse
        $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'category' => 'CATEGORIE']))
            ->assertSuccessful()
            ->assertViewIs('articles::article-category.show');

        $this->assertEquals('categorie', $response->viewData('category'));
        $this->assertCount(1, $response->viewData('articles'));
        $this->assertEquals('mon-article', $response->viewData('articles')[0]['slug']);
    }

    /** @test */
    public function we_cannot_show_articles_for_inexisting_category()
    {
        $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'category' => 'inexisting']))
            ->assertNotFound();
    }

    /** @test */
    public function we_cannot_show_articles_for_a_category_in_another_lang()
    {
        $this->createArticleCategory(['name' => ['fr' => 'la-cat', 'en' => 'the-cat']]);

        $this->get(route(self::ROUTE_SHOW, ['lang' => 'en', 'category' => 'la-cat']))
            ->assertNotFound();
    }

    /** @test */
    public function route_lang_and_route_categories_root_slug_must_match()
    {
        $this->createArticleCategory(['name' => ['fr' => 'ma-cat']]);

        // On vérifie que ça fonctionne avec la valeur par défaut
        $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'category' => 'ma-cat']))
            ->assertSuccessful();

        NovaSettings::setSettingValue('articles_categories_root_slug', json_encode([
            'fr' => 'les-categories',
            'en' => 'the-categories',
        ]));

        // On vérifie que ça fonctionne avec la nouvelle valeur en config
        $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'categories_slug' => 'les-categories', 'category' => 'ma-cat']))
            ->assertSuccessful();

        // On vérifie qu'on ne peut pas utiliser une langue et le slug d'une autre langue
        $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'categories_slug' => 'the-categories', 'category' => 'ma-cat']))
            ->assertNotFound();
    }

    protected function getResourceName(): string
    {
        return 'article-categories';
    }

    protected function getModel(): Model
    {
        return $this->createArticleCategory();
    }
}
