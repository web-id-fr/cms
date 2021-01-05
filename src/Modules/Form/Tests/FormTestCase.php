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

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $configToLoad = [
            'dropzone',
            'fields_type',
            'fields_type_validation',
            'ziggy',
        ];

        foreach ($configToLoad as $configName) {
            $app['config']->set($configName, require package_base_path("src/Modules/Form/Config/{$configName}.php"));
        }
    }
}
