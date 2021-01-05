<?php

namespace Webid\Cms\Tests\Feature;

use Illuminate\Support\Facades\App;
use Webid\Cms\Tests\Helpers\Traits\NewsletterComponentCreator;
use Webid\Cms\Tests\Helpers\Traits\TemplateCreator;
use Webid\Cms\Tests\TestCase;

class TemplateTest extends TestCase
{
    use TemplateCreator,
        NewsletterComponentCreator;

    const _ROUTE_INDEX = 'home';
    const _ROUTE_PAGE = 'pageFromSlug';

    /** @test */
    public function we_can_access_to_home_page()
    {
        $this->createHomepageTemplate();

        $this->get(route(self::_ROUTE_INDEX, ['lang' => 'fr']))
            ->assertSuccessful();
    }

    /** @test */
    public function we_are_redirect_when_no_lang_on_home_page()
    {
        $default_lang = 'fr';

        $this->createHomepageTemplate();

        $this->get('/')
            ->assertRedirect(route(self::_ROUTE_INDEX, ['lang' => $default_lang]));
    }

    /** @test */
    public function we_are_redirected_to_slash_if_we_access_homepage_by_its_slug()
    {
        $this->createHomepageTemplate([
            'slug' => [
                'fr' => 'slug-homepage',
            ],
        ]);

        $this->get(route(self::_ROUTE_PAGE, ['lang' => 'fr', 'slug' => 'slug-homepage']))
            ->assertRedirect(route(self::_ROUTE_INDEX, ['lang' => 'fr']));
    }

    /** @test */
    public function accessing_homepage_with_a_given_lang_changes_app_locale()
    {
        $default_lang = 'fr';
        $other_lang = 'en';

        $this->get('/');
        $this->assertTrue(App::isLocale($default_lang));

        $this->get(route(self::_ROUTE_INDEX, ['lang' => $other_lang]));
        $this->assertTrue(App::isLocale($other_lang));
    }

    /** @test */
    public function we_cant_access_home_page_with_incorrect_lang()
    {
        $this->createHomepageTemplate([
            'slug' => [
                'fr' => 'toto',
            ],
        ]);

        $this->get(route(self::_ROUTE_INDEX, ['lang' => 'en']))
            ->assertNotFound();
    }

    /** @test */
    public function we_can_access_to_the_page_with_correct_slug()
    {
        $this->createHomepageTemplate();
        $component = $this->createNewsletterComponent();
        $template = $this->createTemplate(['homepage' => false]);

        $template->newsletterComponents()->attach($component->getKey(), ['order' => 1]);

        $this->get(route(self::_ROUTE_PAGE, [
            'slug' => $template->slug,
            'lang' => 'fr',
        ]))->assertSuccessful();
    }

    /** @test */
    public function we_cant_access_to_the_page_with_incorrect_slug()
    {
        $this->createHomepageTemplate();
        $this->createTemplate([
            'homepage' => false,
            'slug' => [
                'fr' => 'mon-slug',
            ],
        ]);

        $this->get(route(self::_ROUTE_PAGE, [
            'slug' => 'un-slug-au-hasard',
            'lang' => 'fr',
        ]))->assertNotFound();
    }

    /** @test */
    public function we_cant_access_to_the_page_with_incorrect_lang_parameter()
    {
        $this->createHomepageTemplate();
        $this->createTemplate([
            'homepage' => false,
            'slug' => [
                'fr' => 'fr-slug',
            ],
        ]);

        $this->get(route(self::_ROUTE_PAGE, [
            'slug' => 'fr-slug',
            'lang' => 'en',
        ]))->assertNotFound();
    }
}

