<?php

namespace Webid\Cms\Tests;

use App\Providers\NovaServiceProvider;
use DigitalCreative\ConditionalContainer\ConditionalContainerServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Laravel\Nova\NovaCoreServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Sitemap\SitemapServiceProvider;
use Webid\Cms\App\Providers\TestServiceProvider;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\CmsServiceProvider;
use Webid\Cms\Tests\Helpers\Traits\CustomAssertions;
use Webid\Cms\Tests\Helpers\Traits\NovaAssertions;
use Webid\Cms\Tests\Helpers\Traits\PerformsAjaxRequests;

class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions,
        NovaAssertions,
        PerformsAjaxRequests,
        CustomAssertions;

    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            // Charge les factories depuis les nova-components
            if (preg_match('/Webid[\\\]\w+(Field|Tool)/', $modelName, $matches)) {
                return $matches[0] . "\\Database\\Factories\\" . class_basename($modelName) . 'Factory';
            }

            // Charge les factories depuis les modules
            if (Str::contains($modelName, 'Webid\\Cms\\Modules\\')) {
                preg_match('/^Webid\\\Cms\\\Modules\\\([^\\\]+)\\\.*$/', $modelName, $matches);
                $moduleName = $matches[1];

                return "Webid\\Cms\\Modules\\{$moduleName}\\Database\\Factories\\"
                    . class_basename($modelName) . 'Factory';
            }

            // Charge les factories du core
            return "Webid\\Cms\\Database\\Factories\\" . class_basename($modelName) . 'Factory';
        });
    }

    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TestServiceProvider::class,
            CmsServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaServiceProvider::class,
            SitemapServiceProvider::class,
            LaravelModulesServiceProvider::class,
            ConditionalContainerServiceProvider::class,
        ];
    }

    /**
     * @param Application $app
     */
    protected function defineEnvironment($app)
    {
        View::addLocation(package_base_path('src/resources/views'));
        $app->instance('path.public', package_base_path());

        View::share('currentLang', config("translatable.locales." . request()->lang) ?? '');
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale', 'fr');
        $app['config']->set('app.fallback_locale', 'en');
        $app['config']->set('nova.path', 'nova');
        $app['config']->set('modules.scan.enabled', true);
        $app['config']->set('modules.scan.paths', [
            package_base_path('src/Modules'),
        ]);

        $configToLoad = [
            'components',
            'filemanager',
            'translatable',
        ];

        foreach ($configToLoad as $configName) {
            $app['config']->set($configName, require package_base_path("src/config/{$configName}.php"));
        }

        Route::pattern('lang', '(' . app(LanguageService::class)->getAllLanguagesAsRegex() . ')');
    }
}
