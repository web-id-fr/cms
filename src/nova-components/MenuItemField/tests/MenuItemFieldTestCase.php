<?php

namespace Webid\MenuItemField\Tests;

use Webid\Cms\Tests\TestCase;
use Webid\MenuItemField\FieldServiceProvider;

class MenuItemFieldTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, FieldServiceProvider::class);

        return $providers;
    }
}
