<?php

namespace Webid\Cms\Modules\JavaScript\Tests;

use Webid\Cms\Modules\JavaScript\Providers\CodeSnippetServiceProvider;
use Webid\Cms\Tests\TestCase;

class CodeSnippetTestCase extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, CodeSnippetServiceProvider::class);

        return $providers;
    }
}
