<?php

namespace Webid\Cms\Modules\Articles\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Tests\ArticlesTestCase;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCreator;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCategoryCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class ArticleCategoryTest extends ArticlesTestCase
{
    use TestsNovaResource, ArticleCategoryCreator, ArticleCreator;

    const ROUTE_SHOW = 'articles.categories.show';

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

    protected function getResourceName(): string
    {
        return 'article-categories';
    }

    protected function getModel(): Model
    {
        return $this->createArticleCategory();
    }
}
