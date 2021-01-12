<?php

namespace Webid\PreviewItemField\Tests;

use Webid\Cms\Tests\TestCase;
use Webid\PreviewItemField\FieldServiceProvider;

class PreviewItemFieldTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, FieldServiceProvider::class);

        return $providers;
    }
}
