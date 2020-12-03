<?php

namespace Webid\Cms\Src\App\Modules\Newsletter\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\Src\App\Http\Middleware\IsAjax;
use Webid\Cms\Src\App\Http\Middleware\Language;
use Webid\Cms\Src\App\Modules\Newsletter\Http\Controllers\Newsletter\NewsletterController;
use Webid\Cms\Src\App\Modules\Newsletter\Nova\Newsletter;

class NewsletterServiceProvider extends ServiceProvider
{
    /**
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('is-ajax', IsAjax::class);
        $router->aliasMiddleware('language', Language::class);

        $this->app->booted(function () {
            Nova::resources([
                Newsletter::class
            ]);
        });
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->make(NewsletterController::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
