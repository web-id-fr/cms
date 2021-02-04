<?php

namespace Webid\Cms\Modules\Faq\Tests;

use Webid\Cms\Modules\Faq\Providers\FaqServiceProvider;
use Webid\Cms\Tests\TestCase;

class FaqTestCase extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, FaqServiceProvider::class);

        return $providers;
    }
}
