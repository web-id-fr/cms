<?php

namespace Webid\Cms\Modules\Redirections301\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Redirections301\Console\ImportRedirections;
use Webid\Cms\Modules\Redirections301\Http\Middleware\RedirectOldLinks;
use Webid\Cms\Modules\Redirections301\Nova\Redirection;

class Redirections301ServiceProvider extends ServiceProvider
{
    const MODULE_NAME = 'Redirections301';
    const MODULE_ALIAS = 'redirections-301';

    public function boot(Router $router)
    {
        $this->mergeConfigFrom(
            module_path(self::MODULE_NAME, 'Config/config.php'),
            self::MODULE_ALIAS
        );

        $router->aliasMiddleware('redirect-old-links', RedirectOldLinks::class);
        $router->prependMiddlewareToGroup('pages', RedirectOldLinks::class);

        $this->loadMigrationsFrom(module_path(self::MODULE_NAME, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([Redirection::class]);
        });

        DynamicResource::pushTopLevelResource([
            'label' => __('Redirections'),
            'badge' => null,
            'linkTo' => Redirection::class,
        ]);

        $this->commands([
            ImportRedirections::class,
        ]);
    }
}
