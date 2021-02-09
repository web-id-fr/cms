<?php

namespace Webid\Cms\Modules\Slideshow\Tests\Helpers;

use Webid\Cms\Modules\Slideshow\Models\Slideshow;

trait SlideshowCreator
{
    private function createSlideshow(array $params = []): Slideshow
    {
        return Slideshow::factory($params)->create();
    }
}
