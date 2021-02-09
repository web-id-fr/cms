<?php

namespace Webid\Cms\Modules\Slideshow\Tests;

use Webid\Cms\Modules\Slideshow\Providers\SlideshowServiceProvider;
use Webid\Cms\Tests\TestCase;

class SlideshowTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, SlideshowServiceProvider::class);

        return $providers;
    }
}
