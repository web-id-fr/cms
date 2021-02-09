<?php

namespace Webid\Cms\Modules\Slideshow\Tests\Helpers;

use Webid\Cms\Modules\Slideshow\Models\Slide;

trait SlideCreator
{
    private function createSlide(array $params = []): Slide
    {
        return Slide::factory($params)->create();
    }
}
