<?php

namespace Webid\Cms\Tests;

use App\Providers\NovaServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Sitemap\SitemapServiceProvider;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\CmsServiceProvider;
use Webid\Cms\Tests\Helpers\Traits\PerformsAjaxRequests;

class TestCase extends OrchestraTestCase
{
    use
        RefreshDatabase,
        PerformsAjaxRequests;

    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(function (string $modelName) {
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
            CmsServiceProvider::class,
            NovaServiceProvider::class,
            SitemapServiceProvider::class,
        ];
    }

    /**
     * @param Application $app
     */
    protected function defineEnvironment($app)
    {
        View::addLocation(__DIR__ . '/../src/resources/views');
        $app->instance('path.public', __DIR__ . '/../');

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

        $configToLoad = [
            'components',
            'dropzone',
            'fields_type',
            'fields_type_validation',
            'filemanager',
            'translatable',
            'ziggy',
        ];

        foreach ($configToLoad as $configName) {
            $app['config']->set($configName, require __DIR__ . "/../src/config/{$configName}.php");
        }

        Route::pattern('lang', '(' . app(LanguageService::class)->getAllLanguagesAsRegex() . ')');
    }
}
