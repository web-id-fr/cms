<?php

namespace Webid\Cms\Modules\Redirections301\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Redirections301\Models\Redirection;
use Webid\Cms\Modules\Redirections301\Tests\Helpers\RedirectionCreator;
use Webid\Cms\Modules\Redirections301\Tests\Redirections301TestCase;
use Webid\Cms\Tests\Helpers\Traits\DummyUserCreator;
use Webid\Cms\Tests\Helpers\Traits\TemplateCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class RedirectionTest extends Redirections301TestCase
{
    use RedirectionCreator, DummyUserCreator, TemplateCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'redirections';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createRedirection();
    }

    /** @test */
    public function we_cannot_create_two_redirections_with_same_source_url()
    {
        $this->beDummyUser();

        $data = [
            'source_url' => '/old-path',
            'destination_url' => '/new-path',
        ];

        $this->createNovaResource('redirections', $data)->originalResponse()->assertSuccessful();

        $this->assertDatabaseHas('redirections', $data);

        $this->createNovaResource('redirections', $data)->originalResponse()->assertStatus(422);
    }

    /** @test */
    public function we_are_redirected_if_we_hit_a_path_defined_in_redirections()
    {
        $sourcesToTest = [
            '/en/old-path',
            '/another-old-path/',
        ];

        foreach ($sourcesToTest as $source) {
            $data = [
                'source_url' => $source,
                'destination_url' => '/fr/new-path',
            ];

            $this->createRedirection($data);
        }

        $this->get('/en/old-path')->assertRedirect('/fr/new-path');
        $this->get('/en/old-path/')->assertRedirect('/fr/new-path');
        $this->get('/another-old-path')->assertRedirect('/fr/new-path');
        $this->get('/another-old-path/')->assertRedirect('/fr/new-path');
    }

    /** @test */
    public function current_slug_is_well_handled()
    {
        $this->createHomepageTemplate();
        $this->createPublicTemplate(['slug' => ['fr' => 'my-page-with-data-on-fr']]);

        $this->createRedirection([
            'source_url' => '/fr/my-page-with-data-on-fr',
            'destination_url' => '/fr/anywhere',
        ]);

        // On vérifie que le middleware ne match pas "/fr" avec "...-fr"
        $this->get('/fr')->assertSuccessful();
    }
}
