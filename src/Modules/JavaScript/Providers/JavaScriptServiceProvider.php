<?php

namespace Webid\Cms\Modules\JavaScript\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\JavaScript\Nova\CodeSnippet;

class JavaScriptServiceProvider extends ServiceProvider
{
    /** @var string */
    protected $moduleName = 'JavaScript';

    /** @var string */
    protected $moduleNameLower = 'javascript';

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                CodeSnippet::class,
            ]);
        });

        DynamicResource::pushTemplateModuleGroupResource([
            'label' => __('Code Snippet'),
            'expanded' => false,
            'resources' => [
                CodeSnippet::class,
            ]
        ]);

        $this->registerAndPublishViews();
    }


    /**
     * @return void
     */
    protected function registerAndPublishViews(): void
    {
        $destinationPath = resource_path("views/components");
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $destinationPath,
        ], [
            "{$this->moduleNameLower}-module",
            "{$this->moduleNameLower}-module-views",
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . "/components";
        }, Config::get('view.paths')), [$sourcePath]), $this->moduleName);
    }
}
