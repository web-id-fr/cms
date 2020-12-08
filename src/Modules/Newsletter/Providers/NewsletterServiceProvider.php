<?php

namespace Webid\Cms\Modules\Newsletter\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Http\Middleware\IsAjax;
use Webid\Cms\App\Http\Middleware\Language;
use Webid\Cms\Modules\Newsletter\Nova\Newsletter;

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
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
