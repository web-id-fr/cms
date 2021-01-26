<?php

namespace Webid\Cms\Modules\Articles\Tests;

use Illuminate\Foundation\Application;
use Webid\Cms\Modules\Articles\Providers\ArticlesServiceProvider;
use Webid\Cms\Tests\TestCase;

class ArticlesTestCase extends TestCase
{
    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, ArticlesServiceProvider::class);

        return $providers;
    }
}
