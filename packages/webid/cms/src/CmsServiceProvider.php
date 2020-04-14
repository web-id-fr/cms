<?php

namespace Webid\Cms;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(UrlGenerator $generator)
    {
        // Force https même si l'app est chargée en http
        if (!app()->isLocal()) {
            $generator->forceScheme('https');
        }

        $this->publishConfiguration();
        $this->publishProvider();
        $this->publishViews();
        $this->publishPublicFiles();

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->app->register(CmsServiceProvider::class);

        Nova::serving(function (ServingNova $event) {
            //
        });

        $this->app->booted(function () {
            Nova::resources([
                //
            ]);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    protected function publishConfiguration()
    {
        $this->publishes([
            __DIR__ . '/config/translatable.php' => config_path('translatable.php'),
            __DIR__ . '/config/filemanager.php' => config_path('filemanager.php'),
            __DIR__ . '/config/phpcs.xml' => base_path('phpcs.xml'),
            __DIR__ . '/config/psalm.xml' => base_path('psalm.xml'),
            __DIR__ . '/config/Makefile' => base_path('Makefile'),
        ], 'config');
    }

    protected function publishViews()
    {
        $this->publishes([
            __DIR__ . '/resources/views' => base_path('/resources/views'),
        ], 'views');
    }

    protected function publishProvider()
    {
        $this->publishes([
            __DIR__ . '/app/Providers/NovaServiceProvider.php' => base_path('/app/Providers/NovaServiceProvider.php'),
        ], 'providers');
    }

    protected function publishPublicFiles()
    {
        $this->publishes([
            __DIR__ . '/public/css' => base_path('/public/css'),
        ], 'public');
    }
}
