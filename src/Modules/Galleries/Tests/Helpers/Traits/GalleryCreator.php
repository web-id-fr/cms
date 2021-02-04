<?php

namespace Webid\Cms\Modules\Galleries\Tests\Helpers\Traits;

use Webid\Cms\Modules\Galleries\Models\Gallery;

trait GalleryCreator
{
    /**
     * @param array $params
     *
     * @return Gallery
     */
    private function createGallery(array $params = []): Gallery
    {
        return Gallery::factory()->create($params);
    }
}
