<?php

namespace Webid\Cms\Tests\Helpers\Traits;

use Webid\Cms\App\Models\Components\GalleryComponent;

trait GalleryComponentCreator
{
    private function createGalleryComponent(array $params = []): GalleryComponent
    {
        return GalleryComponent::factory()->create($params);
    }
}
