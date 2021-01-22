<?php

namespace Webid\Cms\Modules\Galleries\Tests;

use Webid\Cms\Modules\Galleries\Providers\GalleriesServiceProvider;
use Webid\Cms\Tests\TestCase;

class GalleriesTestCase extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, GalleriesServiceProvider::class);

        return $providers;
    }
}
