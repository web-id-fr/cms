<?php

namespace Webid\Cms\Modules\Articles\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use OptimistDigital\NovaSettings\NovaSettings;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Tests\ArticlesTestCase;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class ArticleTest extends ArticlesTestCase
{
    use TestsNovaResource,
        ArticleCreator;

    const ROUTE_INDEX = 'articles.index';
    const ROUTE_SHOW = 'articles.show';

    public function setUp(): void
    {
        parent::setUp();

        URL::defaults([
            'articles_slug' => config('articles.default_paths.articles'),
            'categories_slug' => config('articles.default_paths.categories'),
        ]);
    }

    /** @test */
    public function article_routes_contains_blog_path()
    {
        $path = rtrim(config('articles.default_paths.articles'), '/');

        $this->assertNotEmpty($path);
        $this->assertStringContainsString($path, route(self::ROUTE_INDEX, ['lang' => 'fr']));
        $this->assertStringContainsString($path, route(self::ROUTE_INDEX, ['lang' => 'fr', 'slug' => 'aaa']));
    }

    /** @test */
    public function we_can_access_articles_list()
    {
        $this->createArticle(['status' => Article::_STATUS_DRAFT]);
        $this->createPublishedArticle(['slug' => ['fr' => 'le-slug']]);
        $this->createPublishedArticle(['slug' => ['en' => 'dat-slug']]);

        $response = $this->get(route(self::ROUTE_INDEX, ['lang' => 'fr']))
            ->assertViewIs('articles::article.index');

        $viewData = $response->viewData('articles');

        $this->assertCount(1, $viewData);
        $this->assertEquals('le-slug', $viewData[0]['slug']);
    }

    /** @test */
    public function we_can_access_single_article()
    {
        $this->createPublishedArticle(['slug' => ['fr' => 'mon-slug']]);

        $this->get(route(self::ROUTE_SHOW, ['slug' => 'mon-slug', 'lang' => 'fr']))
            ->assertSuccessful()
            ->assertViewIs('articles::article.show');
    }

    /** @test */
    public function we_cannot_access_article_with_inexisting_slug()
    {
        $this->get(route(self::ROUTE_SHOW, ['slug' => 'inexisting-slug', 'lang' => 'fr']))
            ->assertNotFound();
    }

    /** @test */
    public function we_cannot_access_existing_article_if_not_published()
    {
        $this->createArticle([
            'slug' => [
                'fr' => 'non-publie',
            ],
            'status' => Article::_STATUS_DRAFT,
        ]);

        $this->get(route(self::ROUTE_SHOW, ['slug' => 'non-publie', 'lang' => 'fr']))
            ->assertNotFound();
    }

    /** @test */
    public function we_cannot_access_existing_article_if_not_published_yet()
    {
        $this->createArticle([
            'slug' => [
                'fr' => 'pas-encore-publie',
            ],
            'status' => Article::_STATUS_PUBLISHED,
            'publish_at' => Carbon::now()->addYear(),
        ]);

        $this->get(route(self::ROUTE_SHOW, ['slug' => 'pas-encore-publie', 'lang' => 'fr']))
            ->assertNotFound();
    }

    /** @test */
    public function we_cannot_show_an_article_for_another_lang()
    {
        $this->createPublishedArticle(['slug' => ['fr' => 'mon-super-article']]);

        $this->get(route(self::ROUTE_SHOW, ['slug' => 'mon-super-article', 'lang' => 'en']))
            ->assertNotFound();
    }

    /** @test */
    public function route_lang_and_route_articles_root_slug_must_match()
    {
        // On vérifie que ça fonctionne avec la valeur par défaut
        $this->get(route(self::ROUTE_INDEX, ['lang' => 'fr', 'articles_slug' => config('articles.default_paths.articles')]))
            ->assertSuccessful();

        NovaSettings::setSettingValue('articles_root_slug', json_encode([
            'fr' => 'le-blog',
            'en' => 'the-blog',
        ]));

        // On vérifie que ça fonctionne avec la nouvelle valeur en config
        $this->get(route(self::ROUTE_INDEX, ['lang' => 'fr', 'articles_slug' => 'le-blog']))
            ->assertSuccessful();

        // On vérifie qu'on ne peut pas utiliser une langue et le slug d'une autre langue
        $this->get(route(self::ROUTE_INDEX, ['lang' => 'fr', 'articles_slug' => 'the-blog']))
            ->assertNotFound();
    }

    protected function getResourceName(): string
    {
        return 'articles';
    }

    protected function getModel(): Model
    {
        return $this->createArticle();
    }
}
