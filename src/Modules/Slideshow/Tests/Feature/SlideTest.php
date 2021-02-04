<?php

namespace Webid\Cms\Modules\Slideshow\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Slideshow\Tests\Helpers\SlideCreator;
use Webid\Cms\Modules\Slideshow\Tests\SlideshowTestCase;
use Webid\Cms\Tests\Helpers\Traits\DummyUserCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class SlideTest extends SlideshowTestCase
{
    use SlideCreator, DummyUserCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'slides';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createSlide();
    }
}
