<?php

namespace Webid\Cms\Tests\Unit;

use Webid\Cms\App\Models\Dummy\DummyComponent;
use Webid\Cms\Tests\Helpers\Traits\TemplateCreator;
use Webid\Cms\Tests\TestCase;

class ComponentTest extends TestCase
{
    use TemplateCreator;

    /** @test */
    public function relationships_are_deleted_on_component_deletion()
    {
        // We create the template and component
        $template = $this->createTemplate([
            'homepage' => false,
            'slug' => [
                'fr' => 'fr-slug',
            ],
        ]);

        $dummyComponent = DummyComponent::create([
            'id' => 1,
        ]);

        $template->related()->create([
            'component_id' => $dummyComponent->getKey(),
            'component_type' => 'Webid\\Cms\\App\\Models\\Dummy\\DummyComponent',
            'order' => 1,
        ]);

        // We check the component and the relationship are in database
        $this->assertDatabaseCount('dummy_components', 1);
        $this->assertDatabaseCount('components', 1);

        // We delete the component
        $dummyComponent->delete();

        // We check the component AND the relationship are deleted from database
        $this->assertDatabaseCount('dummy_components', 0);
        $this->assertDatabaseCount('components', 0);
    }
}
