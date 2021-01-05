<?php

namespace Webid\Cms\Modules\Form\Tests;

use Webid\Cms\Modules\Form\Providers\FormServiceProvider;
use Webid\Cms\Tests\TestCase;

class FormTestCase extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, FormServiceProvider::class);

        return $providers;
    }
}
