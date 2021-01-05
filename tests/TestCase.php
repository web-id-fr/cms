<?php

namespace Webid\Cms\Tests;

use App\Providers\NovaServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
use Webid\Cms\Tests\Helpers\Traits\NovaAssertions;
use Webid\Cms\Tests\Helpers\Traits\PerformsAjaxRequests;

class TestCase extends OrchestraTestCase
{
    use RefreshDatabase,
        NovaAssertions,
        PerformsAjaxRequests;

    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            if (Str::contains($modelName, 'Webid\\Cms\\Modules\\')) {
                preg_match('/^Webid\\\Cms\\\Modules\\\([^\\\]+)\\\.*$/', $modelName, $matches);
                $moduleName = $matches[1];

                return "Webid\\Cms\\Modules\\{$moduleName}\\Database\\Factories\\"
                    . class_basename($modelName) . 'Factory';
            }

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

    /**
     * @var array
     */
    protected $ajaxHeaders = [
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ];

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxPost($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('POST', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxGet($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('GET', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxPut($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('PUT', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxDelete($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('DELETE', $uri, $data, $headers);
    }
}
