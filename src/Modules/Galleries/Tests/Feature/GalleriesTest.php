<?php

namespace Webid\Cms\Modules\Galleries\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Galleries\Tests\GalleriesTestCase;
use Webid\Cms\Modules\Galleries\Tests\Helpers\Traits\GalleryCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class GalleriesTest extends GalleriesTestCase
{
    use GalleryCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'galleries';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createGallery();
    }
}
