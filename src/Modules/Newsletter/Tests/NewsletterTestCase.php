<?php

namespace Webid\Cms\Modules\Newsletter\Tests;

use Nwidart\Modules\Facades\Module;
use Webid\Cms\Modules\Newsletter\Providers\NewsletterServiceProvider;
use Webid\Cms\Tests\TestCase;

class NewsletterTestCase extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, NewsletterServiceProvider::class);

        return $providers;
    }
}
