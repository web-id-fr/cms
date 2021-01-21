<?php

namespace Webid\Cms\Modules\Articles\Providers;

use DigitalCreative\CollapsibleResourceManager\Resources\NovaResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Articles\Models\Article as ArticleModel;
use Webid\Cms\Modules\Articles\Nova\Article;
use Webid\Cms\Modules\Articles\Nova\ArticleCategory;
use Webid\Cms\Modules\Articles\Observers\ArticleObserver;

class ArticlesServiceProvider extends ServiceProvider
{
    const MODULE_NAME = 'Articles';
    const MODULE_ALIAS = 'articles';

    public function boot()
    {
        $this->registerAndPublishViews();
        $this->registerAndPublishConfig();

        $this->app->register(RouteServiceProvider::class);

        $this->loadMigrationsFrom(module_path(self::MODULE_NAME, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                Article::class,
                ArticleCategory::class,
            ]);
        });

        Nova::serving(function (ServingNova $event) {
            ArticleModel::observe(ArticleObserver::class);
        });

        DynamicResource::pushTopLevelResource([
            'label' => __('Articles'),
            'badge' => null,
            'linkTo' => Article::class,
            'resources' => [
                NovaResource::make(ArticleCategory::class),
            ],
        ]);
    }

    /**
     * @return void
     */
    protected function registerAndPublishViews(): void
    {
        $moduleAlias = self::MODULE_ALIAS;
        $destinationPath = resource_path("views/modules/{$moduleAlias}");
        $sourcePath = module_path(self::MODULE_NAME, 'Resources/views');

        $this->publishes([
            $sourcePath => $destinationPath,
        ], [
            "{$moduleAlias}-module",
            "{$moduleAlias}-module-views",
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) use ($moduleAlias) {
            return $path . "/modules/{$moduleAlias}";
        }, Config::get('view.paths')), [$sourcePath]), $moduleAlias);
    }

    /**
     * @return void
     */
    protected function registerAndPublishConfig(): void
    {
        $sourcePath = module_path(self::MODULE_NAME, 'Config');

        $this->mergeConfigFrom(
            "{$sourcePath}/config.php",
            self::MODULE_ALIAS
        );

        $this->publishes([
            "{$sourcePath}/config.php" => config_path('articles.php'),
        ], [
            self::MODULE_ALIAS . '-module',
            self::MODULE_ALIAS . '-module-config',
        ]);
    }
}
