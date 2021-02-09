<?php

namespace Webid\Cms\Modules\Slideshow\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Slideshow\Tests\Helpers\SlideshowCreator;
use Webid\Cms\Modules\Slideshow\Tests\SlideshowTestCase;
use Webid\Cms\Tests\Helpers\Traits\DummyUserCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class SlideshowTest extends SlideshowTestCase
{
    use SlideshowCreator, DummyUserCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'slideshows';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createSlideshow();
    }
}
