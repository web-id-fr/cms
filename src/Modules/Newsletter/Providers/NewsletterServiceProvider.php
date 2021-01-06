<?php

namespace Webid\Cms\Modules\Newsletter\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Newsletter\Nova\Newsletter;

class NewsletterServiceProvider extends ServiceProvider
{
    /** @var string  */
    protected $moduleName = 'Newsletter';

    /** @var string  */
    protected $moduleNameLower = 'newsletter';

    public function boot(): void
    {
        $this->publishViews();
        $this->publishJs();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                Newsletter::class
            ]);
        });

        DynamicResource::pushTopLevelResource([
            'label' => __('Newsletter'),
            'badge' => null,
            'linkTo' => Newsletter::class,
        ]);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

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

    protected function publishJs(): void
    {
        $viewPath = public_path('cms/js');
        $sourcePath = module_path($this->moduleName, 'Resources/dist/js');

        $this->publishes([
            $sourcePath => $viewPath
        ], [
            $this->moduleNameLower .'-module',
            $this->moduleNameLower . '-module-js'
        ]);
    }
}
