<?php

namespace Webid\Cms\Modules\Redirections301\Tests;

use Webid\Cms\Modules\Redirections301\Providers\Redirections301ServiceProvider;
use Webid\Cms\Tests\NovaResourceTestCase;

class Redirections301TestCase extends NovaResourceTestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, Redirections301ServiceProvider::class);

        return $providers;
    }
}
