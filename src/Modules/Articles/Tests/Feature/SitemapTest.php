<?php

namespace Webid\Cms\Modules\Articles\Tests\Feature;

use Carbon\Carbon;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Tests\ArticlesTestCase;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCategoryCreator;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCreator;
use Webid\Cms\Tests\Helpers\Traits\LanguageCreator;
use Webid\Cms\Tests\Helpers\Traits\TemplateCreator;

class SitemapTest extends ArticlesTestCase
{
    use ArticleCreator,
        ArticleCategoryCreator,
        LanguageCreator,
        TemplateCreator;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow("2020-01-01");

        $this->createLanguage(['name' => 'Français', 'flag' => 'fr']);
        $this->createLanguage(['name' => 'English', 'flag' => 'en']);
        $this->createLanguage(['name' => '日本語', 'flag' => 'ja']);
    }

    /** @test */
    public function sitemap_contains_articles_and_categories_urls()
    {
        $this->createPublicTemplate([
            'contains_articles_list' => true,
            'slug' => ['fr' => 'ma-page', 'en' => 'my-page'],
        ]);
        $this->createPublishedArticle(['slug' => ['fr' => 'titre-article', 'en' => 'article-title']]);
        $this->createArticleCategory(['name' => ['fr' => 'nom-categorie', 'en' => 'category-name']]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr/articles/titre-article</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/articles/article-title</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/articles/titre-article" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/article-title" />', 2);

        $this->assertStringContainsString('<loc>https://localhost/fr/ma-page?category=nom-categorie</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/my-page?category=category-name</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/ma-page?category=nom-categorie" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/my-page?category=category-name" />', 2);
    }

    /** @test */
    public function sitemap_contains_articles_and_categories_urls_when_page_is_homepage()
    {
        $this->createPublicTemplate([
            'homepage' => true,
            'contains_articles_list' => true,
            'slug' => ['fr' => 'ma-page', 'en' => 'my-page'],
        ]);
        $this->createPublishedArticle(['slug' => ['fr' => 'titre-article', 'en' => 'article-title']]);
        $this->createArticleCategory(['name' => ['fr' => 'nom-categorie', 'en' => 'category-name']]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr/articles/titre-article</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/articles/article-title</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/articles/titre-article" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/article-title" />', 2);

        $this->assertStringContainsString('<loc>https://localhost/fr?category=nom-categorie</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en?category=category-name</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr?category=nom-categorie" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en?category=category-name" />', 2);
    }

    /** @test */
    public function sitemap_does_not_contain_unpublished_articles_and_categories()
    {
        $this->createArticle([
            'slug' => ['fr' => 'non-publie'],
            'status' => Article::_STATUS_DRAFT,
        ]);
        $this->createArticle([
            'slug' => ['fr' => 'pas-encore-publie'],
            'status' => Article::_STATUS_PUBLISHED,
            'publish_at' => Carbon::now()->addYear(),
        ]);
        $this->createArticleCategory(['name' => ['fr' => 'nom-categorie']]);

        $xml = $this->generateSitemap();

        $this->assertStringNotContainsString('<loc>https://localhost/fr/articles/pas-encore-publie</loc>', $xml);
        $this->assertStringNotContainsString('<loc>https://localhost/fr/articles/non-publie</loc>', $xml);
        $this->assertStringNotContainsString('<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/articles/pas-encore-publie" />', $xml);
        $this->assertStringNotContainsString('<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/articles/non-publie" />', $xml);

        $this->assertStringNotContainsString('<loc>https://localhost/en/articles/tags/nom-categorie</loc>', $xml);
        $this->assertStringNotContainsString('<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/tags/nom-categorie" />', $xml);
    }

    /** @test */
    public function alternates_are_well_handled_for_multiple_articles_in_sitemap()
    {
        $this->createPublishedArticle(['slug' => ['fr' => 'article-1-fr']]);
        $this->createPublishedArticle(['slug' => ['fr' => 'article-2-fr', 'en' => 'article-2-en']]);
        $this->createPublishedArticle(['slug' => ['en' => 'article-3-en']]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr/articles/article-1-fr</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/fr/articles/article-2-fr</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/articles/article-2-en</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/articles/article-3-en</loc>', $xml);

        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/articles/article-1-fr" />', 1);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/articles/article-2-fr" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/article-2-en" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/article-3-en" />', 1);
    }

    private function generateSitemap(): string
    {
        return $this->get(route('sitemap'))->content();
    }
}
