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

    /** @test */
    public function we_can_access_to_home_page()
    {
        $this->createHomepageTemplate();

        $this->get(route(self::_ROUTE_INDEX, ['lang' => 'fr']))
            ->assertSuccessful();

        $this->get(route(self::_ROUTE_INDEX, ['lang' => 'en']))
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
                'fr' => 'homepage-fr',
                'en' => 'homepage-en',
            ],
            'follow' => true,
            'indexation' => true,
        ]);

        $this->get('fr/homepage-fr')
            ->assertRedirect(route(self::_ROUTE_INDEX, ['lang' => 'fr']));

        $this->get('en/homepage-en')
            ->assertRedirect(route(self::_ROUTE_INDEX, ['lang' => 'en']));
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
    public function accessing_a_page_with_a_given_lang_changes_app_locale()
    {
        $this->createPublicTemplate([
            'slug' => [
                'fr' => 'slug-fr',
                'en' => 'slug-en',
            ],
        ]);

        $default_lang = 'fr';
        $other_lang = 'en';

        $this->get('/fr/slug-fr');
        $this->assertTrue(App::isLocale($default_lang));

        $this->get('/en/slug-en');
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
        $template = $this->createPublicTemplate([
            'homepage' => false,
            'follow' => false,
            'indexation' => false,
            'slug' => [
                'fr' => 'mon-slug',
                'en' => 'my-slug',
                'es' => 'mi-slug',
            ],
        ]);

        $template->newsletterComponents()->attach($component->getKey(), ['order' => 1]);

        $this->get('fr/mon-slug')->assertSuccessful();
        $this->get('en/my-slug')->assertSuccessful();
        $this->get('es/mi-slug')->assertSuccessful();
    }

    /** @test */
    public function we_can_access_to_the_page_with_correct_slug_with_parent_page()
    {
        $this->createHomepageTemplate();
        $component = $this->createNewsletterComponent();
        $template_1 = $this->createTemplate([
            'homepage' => false,
            'follow' => false,
            'indexation' => false,
            'slug' => ['fr' => 'mon-slug-1'],
        ]);
        $template_2 = $this->createTemplate([
            'homepage' => false,
            'follow' => false,
            'indexation' => false,
            'slug' => ['fr' => 'mon-slug-2'],
            'parent_page_id' => $template_1->getKey(),
        ]);

        $template_2->newsletterComponents()->attach($component->getKey(), ['order' => 1]);

        $this->get('fr/mon-slug-1/mon-slug-2')->assertSuccessful();
    }

    /** @test */
    public function we_cant_access_to_the_page_with_incorrect_slug_with_parent_page()
    {
        $this->createHomepageTemplate();
        $component = $this->createNewsletterComponent();
        $template_1 = $this->createTemplate([
            'homepage' => false,
            'follow' => false,
            'indexation' => false,
            'slug' => ['fr' => 'mon-slug-1'],
        ]);
        $template_2 = $this->createTemplate([
            'homepage' => false,
            'follow' => false,
            'indexation' => false,
            'slug' => ['fr' => 'mon-slug-2'],
            'parent_page_id' => $template_1->getKey(),
        ]);

        $template_2->newsletterComponents()->attach($component->getKey(), ['order' => 1]);

        $this->get('fr/mon-slug-1/mauvais-slug-2')->assertNotFound();
    }

    /** @test */
    public function we_cant_access_to_the_page_with_incorrect_slug_but_with_correct_last_parameter()
    {
        $this->createHomepageTemplate();
        $template_1 = $this->createTemplate([
            'homepage' => false,
            'slug' => ['fr' => 'mon-slug-1'],
        ]);
        $template_2 = $this->createTemplate([
            'homepage' => false,
            'slug' => ['fr' => 'mon-slug-2'],
            'parent_page_id' => $template_1->getKey(),
        ]);

        $this->get('fr/mauvais-slug/mon-slug-2')
            ->assertRedirect('fr/mon-slug-1/mon-slug-2');
    }

    /** @test */
    public function we_are_redirected_if_we_access_to_child_page_with_only_its_slug()
    {
        $grandParentPage = $this->createPublicTemplate([
            'slug' => ['fr' => 'grand-parent'],
        ]);
        $parentPage = $this->createPublicTemplate([
            'slug' => ['fr' => 'parent'],
            'parent_page_id' => $grandParentPage->id,
        ]);
        $childPage = $this->createPublicTemplate([
            'slug' => ['fr' => 'child'],
            'parent_page_id' => $parentPage->id,
        ]);

        $this->get('/fr/child')
            ->assertRedirect('/fr/grand-parent/parent/child');

        $this->get('/fr/parent/child')
            ->assertRedirect('/fr/grand-parent/parent/child');

        $this->get('/fr/grand-parent/parent/child')
            ->assertOk();
    }

    /** @test */
    public function we_are_not_redirected_when_accessing_to_page_by_its_slug_and_has_homepage_as_parent()
    {
        $homepage = $this->createHomepageTemplate([
            'slug' => ['fr' => 'home'],
        ]);
        $page = $this->createPublicTemplate([
            'slug' => ['fr' => 'ma-page'],
            'parent_page_id' => $homepage->id,
        ]);

        $this->get('/fr/ma-page')
            ->assertOk();

        $this->get('/fr/home/ma-page')
            ->assertRedirect('/fr/ma-page');
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

        $this->get('en/mon-slug')->assertNotFound();
    }

    /** @test */
    public function a_page_with_inexisting_components_is_still_displayed()
    {
        $template = $this->createTemplate([
            'homepage' => false,
            'slug' => [
                'fr' => 'fr-slug',
            ],
        ]);

        $template->related()->create([
            'component_id' => 9999,
            'component_type' => 'Webid\\Cms\\App\\Models\\Dummy\\DummyComponent',
            'order' => 1,
        ]);

        $this->get("/fr/fr-slug")->assertSuccessful();
    }

    /** @test */
    public function we_get_not_found_error_when_trying_to_show_page_in_inexisting_lang()
    {
        $this->createPublicTemplate([
            'slug' => [
                'klingon' => 'ngoj',
                'fr' => 'page',
            ],
            'parent_page_id' => $this->createPublicTemplate()->id,
        ]);

        $this->get('/klingon/ngoj')->assertNotFound();
    }
}

