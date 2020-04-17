<?php

namespace Webid\LanguageTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webid\LanguageTool\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'language-tool');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/language-tool')
                ->group(__DIR__.'/../routes/ajax.php');
    }
}
