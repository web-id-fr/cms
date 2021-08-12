<?php

namespace Webid\Cms\Modules\JavaScript\Tests;

use Webid\Cms\Modules\JavaScript\Providers\JavaScriptServiceProvider;
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
        array_push($providers, JavaScriptServiceProvider::class);

        return $providers;
    }
}
