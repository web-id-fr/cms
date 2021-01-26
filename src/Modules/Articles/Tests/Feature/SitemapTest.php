<?php

namespace Webid\Cms\Modules\Articles\Tests\Feature;

use Carbon\Carbon;
use OptimistDigital\NovaSettings\NovaSettings;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Tests\ArticlesTestCase;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCategoryCreator;
use Webid\Cms\Modules\Articles\Tests\Helpers\ArticleCreator;
use Webid\Cms\Tests\Helpers\Traits\LanguageCreator;

class SitemapTest extends ArticlesTestCase
{
    use ArticleCreator,
        ArticleCategoryCreator,
        LanguageCreator;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow("2020-01-01");

        NovaSettings::setSettingValue(
            'articles_root_slug',
            json_encode(['fr' => 'blog', 'en' => 'articles'])
        );
        NovaSettings::setSettingValue(
            'articles_categories_root_slug',
            json_encode(['fr' => 'cat', 'en' => 'tags'])
        );

        $this->createLanguage(['name' => 'Français', 'flag' => 'fr']);
        $this->createLanguage(['name' => 'English', 'flag' => 'en']);
    }

    /** @test */
    public function sitemap_contains_articles_index_in_all_langs()
    {
        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr/blog</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/articles</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/blog" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles" />', 2);
    }

    /** @test */
    public function sitemap_contains_articles_and_categories_urls()
    {
        $this->createPublishedArticle(['slug' => ['fr' => 'titre-article', 'en' => 'article-title']]);
        $this->createArticleCategory(['name' => ['fr' => 'nom-categorie', 'en' => 'category-name']]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr/blog/titre-article</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/articles/article-title</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/blog/titre-article" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/article-title" />', 2);

        $this->assertStringContainsString('<loc>https://localhost/fr/blog/cat/nom-categorie</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/articles/tags/category-name</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/blog/cat/nom-categorie" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/tags/category-name" />', 2);
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

        $this->assertStringNotContainsString('<loc>https://localhost/fr/blog/pas-encore-publie</loc>', $xml);
        $this->assertStringNotContainsString('<loc>https://localhost/fr/blog/non-publie</loc>', $xml);
        $this->assertStringNotContainsString('<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/blog/pas-encore-publie" />', $xml);
        $this->assertStringNotContainsString('<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/blog/non-publie" />', $xml);

        $this->assertStringNotContainsString('<loc>https://localhost/en/articles/tags/nom-categorie</loc>', $xml);
        $this->assertStringNotContainsString('<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/articles/tags/nom-categorie" />', $xml);
    }

    private function generateSitemap(): string
    {
        return $this->get(route('sitemap'))->content();
    }
}
