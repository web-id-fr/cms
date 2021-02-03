<?php

namespace Webid\Cms\Tests\Feature;

use App\Models\Template;
use Carbon\Carbon;
use Webid\Cms\Tests\Helpers\Traits\TemplateCreator;
use Webid\Cms\Tests\TestCase;

class SitemapTest extends TestCase
{
    use TemplateCreator;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow("2020-01-01");
    }

    /** @test */
    public function sitemap_generation_works()
    {
        $response = $this->get(route('sitemap'));

        $response->assertSuccessful();
        $this->assertStringContainsString('text/xml', $response->headers->get('Content-Type'));
    }

    /** @test */
    public function sitemap_generation_handles_only_published_and_indexed_pages()
    {
        $this->createTemplate([
            'slug' => ['fr' => 'je-suis-francais'],
            'homepage' => false,
            'status' => Template::_STATUS_PUBLISHED,
            'publish_at' => now()->subYear(),
            'indexation' => true,
        ]);
        $this->createTemplate([
            'slug' => ['fr' => 'je-ne-suis-pas-indexee'],
            'homepage' => false,
            'status' => Template::_STATUS_PUBLISHED,
            'publish_at' => now()->subYear(),
            'indexation' => false,
        ]);
        $this->createTemplate([
            'slug' => ['fr' => 'je-ne-suis-pas-publiee'],
            'homepage' => false,
            'status' => Template::_STATUS_DRAFT,
            'publish_at' => now()->subYear(),
            'indexation' => true,
        ]);
        $this->createTemplate([
            'slug' => ['fr' => 'je-ne-suis-pas-encore-publiee'],
            'homepage' => false,
            'status' => Template::_STATUS_PUBLISHED,
            'publish_at' => now()->addYear(),
            'indexation' => true,
        ]);
        $this->createTemplate([
            'slug' => ['fr' => 'je-nai-pas-de-date-de-publication'],
            'homepage' => false,
            'status' => Template::_STATUS_PUBLISHED,
            'publish_at' => null,
            'indexation' => true,
        ]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr/je-suis-francais</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/fr/je-nai-pas-de-date-de-publication</loc>', $xml);

        $this->assertStringNotContainsString('<loc>https://localhost/fr/je-ne-suis-pas-indexee</loc>', $xml);
        $this->assertStringNotContainsString('<loc>https://localhost/fr/je-ne-suis-pas-publiee</loc>', $xml);
        $this->assertStringNotContainsString('<loc>https://localhost/fr/je-ne-suis-pas-encore-publiee</loc>', $xml);
    }

    /** @test */
    public function homepage_appears_without_slug()
    {
        $this->createHomepageTemplate([
            'slug' => ['fr' => 'homepage'],
            'indexation' => true,
            'publish_at' => now()->subYear(),
            'status' => Template::_STATUS_PUBLISHED,
        ]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr</loc>', $xml);
        $this->assertStringNotContainsString('<loc>https://localhost/fr/homepage</loc>', $xml);
    }

    /** @test */
    public function sitemap_contains_all_langs_for_each_lang()
    {
        $this->createHomepageTemplate([
            'slug' => ['fr' => 'bonjour', 'en' => 'hello', 'es' => 'hola'],
            'indexation' => true,
            'publish_at' => now()->subYear(),
            'status' => Template::_STATUS_PUBLISHED,
        ]);
        $this->createTemplate([
            'homepage' => false,
            'slug' => ['fr' => 'france', 'en' => 'england', 'es' => 'spain'],
            'indexation' => true,
            'publish_at' => now()->subYear(),
            'status' => Template::_STATUS_PUBLISHED,
        ]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/es</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr" />', 3);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en" />', 3);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="es" href="https://localhost/es" />', 3);

        $this->assertStringContainsString('<loc>https://localhost/fr/france</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/england</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/es/spain</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/france" />', 3);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/england" />', 3);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="es" href="https://localhost/es/spain" />', 3);
    }

    /** @test */
    public function if_a_page_has_an_empty_slug_for_a_lang_it_doesnt_appear_in_sitemap()
    {
        $this->createTemplate([
            'homepage' => false,
            'slug' => ['fr' => 'france', 'en' => 'england', 'it' => ''],
            'indexation' => true,
            'publish_at' => now()->subYear(),
            'status' => Template::_STATUS_PUBLISHED,
        ]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/fr/france</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/en/england</loc>', $xml);
        $this->assertStringNotContainsString('<loc>https://localhost/it/</loc>', $xml);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="fr" href="https://localhost/fr/france" />', 2);
        $this->assertStringContainsStringTimes($xml, '<xhtml:link rel="alternate" hreflang="en" href="https://localhost/en/england" />', 2);
        $this->assertStringNotContainsString('hreflang="it"', $xml);
    }

    /** @test */
    public function sitemap_contains_pages_that_exists_in_multiple_langs_but_not_int_the_default_lang()
    {
        $this->createTemplate([
            'homepage' => false,
            'slug' => ['en' => 'the-slug', 'it' => 'il-slug'],
            'indexation' => true,
            'publish_at' => now()->subYear(),
            'status' => Template::_STATUS_PUBLISHED,
        ]);

        $xml = $this->generateSitemap();

        $this->assertStringContainsString('<loc>https://localhost/en/the-slug</loc>', $xml);
        $this->assertStringContainsString('<loc>https://localhost/it/il-slug</loc>', $xml);
        $this->assertStringNotContainsString('localhost/fr', $xml);
    }

    private function generateSitemap(): string
    {
        return $this->get(route('sitemap'))->content();
    }
}
