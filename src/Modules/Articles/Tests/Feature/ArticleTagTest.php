<?php

namespace Webid\Cms\Modules\Articles\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Tests\ArticlesTestCase;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCreator;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleTagCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class ArticleTagTest extends ArticlesTestCase
{
    use TestsNovaResource, ArticleTagCreator, ArticleCreator;

    const ROUTE_SHOW = 'articles.tags.show';

    /** @test */
    public function we_can_get_articles_list_for_a_given_tag()
    {
        $tag = $this->createArticleTag(['name' => ['fr' => 'categorie']]);

        $this->createPublishedArticle(['slug' => ['fr' => 'mon-article']])->tags()->attach($tag);
        $this->createPublishedArticle(['slug' => ['en' => 'my-other-article']])->tags()->attach($tag);
        $this->createArticle([
            'slug' => [
                'fr' => 'article-non-publie',
            ],
            'status' => Article::_STATUS_DRAFT,
        ])->tags()->attach($tag);

        $response = $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'tag' => 'categorie']))
            ->assertSuccessful()
            ->assertViewIs('articles::article-tag.show');

        $this->assertEquals('categorie', $response->viewData('tag'));
        $this->assertCount(1, $response->viewData('articles'));
        $this->assertEquals('mon-article', $response->viewData('articles')[0]['slug']);

        // On vérifie que le tag n'est pas sensible à la casse
        $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'tag' => 'CATEGORIE']))
            ->assertSuccessful()
            ->assertViewIs('articles::article-tag.show');

        $this->assertEquals('categorie', $response->viewData('tag'));
        $this->assertCount(1, $response->viewData('articles'));
        $this->assertEquals('mon-article', $response->viewData('articles')[0]['slug']);
    }

    /** @test */
    public function we_cannot_show_articles_for_inexisting_tag()
    {
        $this->get(route(self::ROUTE_SHOW, ['lang' => 'fr', 'tag' => 'inexisting']))
            ->assertNotFound();
    }

    /** @test */
    public function we_cannot_show_articles_for_a_tag_in_another_lang()
    {
        $this->createArticleTag(['name' => ['fr' => 'le-tag', 'en' => 'the-tag']]);

        $this->get(route(self::ROUTE_SHOW, ['lang' => 'en', 'tag' => 'le-tag']))
            ->assertNotFound();
    }

    protected function getResourceName(): string
    {
        return 'article-tags';
    }

    protected function getModel(): Model
    {
        return $this->createArticleTag();
    }
}
