<?php

namespace Webid\Cms\Modules\Newsletter\Providers;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Http\Middleware\IsAjax;
use Webid\Cms\App\Http\Middleware\Language;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Newsletter\Nova\Newsletter;

class NewsletterServiceProvider extends ServiceProvider
{
    /** @var string  */
    protected $moduleName = 'Newsletter';

    /** @var string  */
    protected $moduleNameLower = 'newsletter';

    /**
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('is-ajax', IsAjax::class);
        $router->aliasMiddleware('language', Language::class);
        $router->middlewareGroup('ajax', [
            StartSession::class,
            IsAjax::class,
            VerifyCsrfToken::class
        ]);

        $this->publishViews();
        $this->publishJs();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                Newsletter::class
            ]);
        });

        DynamicResource::pushTopLevelResource([
            'label' => __('Newslettter'),
            'badge' => null,
            'linkTo' => Newsletter::class,
        ]);
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /*
     * @return void
     */
    protected function publishViews(): void
    {
        $viewPath = resource_path('views/components');
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], [
            $this->moduleNameLower . '-module',
            $this->moduleNameLower . '-module-views'
        ]);
    }

    /*
    * @return void
    */
    protected function publishJs(): void
    {
        $viewPath = public_path('cms/js');
        $sourcePath = module_path($this->moduleName, 'dist/js');

        $this->publishes([
            $sourcePath => $viewPath
        ], [
            $this->moduleNameLower .'-module',
            $this->moduleNameLower . '-module-js'
        ]);
    }
}
