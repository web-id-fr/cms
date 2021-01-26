<?php

namespace Webid\Cms\Modules\Articles\Providers;

use DigitalCreative\CollapsibleResourceManager\Resources\NovaResource;
use Eminiarts\Tabs\Tabs;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use OptimistDigital\NovaSettings\NovaSettings;
use Webid\Cms\App\Rules\TranslatableSlug;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\App\Services\Sitemap\SitemapGenerator;
use Webid\Cms\Modules\Articles\Helpers\SlugHelper;
use Webid\Cms\Modules\Articles\Http\Middleware\CheckSlugsMatch;
use Webid\Cms\Modules\Articles\Http\Middleware\SetDefaultSlugs;
use Webid\Cms\Modules\Articles\Models\Article as ArticleModel;
use Webid\Cms\Modules\Articles\Nova\Article;
use Webid\Cms\Modules\Articles\Nova\ArticleCategory;
use Webid\Cms\Modules\Articles\Observers\ArticleObserver;
use Webid\TranslatableTool\Translatable;

class ArticlesServiceProvider extends ServiceProvider
{
    const MODULE_NAME = 'Articles';
    const MODULE_ALIAS = 'articles';

    public function boot(Router $router)
    {
        $this->registerAndPublishViews();
        $this->registerConfig();

        $this->registerMiddlewares($router);

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

        $this->addSettings();
    }

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(SitemapServiceProvider::class);
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
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            module_path(self::MODULE_NAME, 'Config/config.php'),
            self::MODULE_ALIAS
        );
    }

    /**
     * @param Router $router
     * @return void
     */
    protected function registerMiddlewares(Router $router): void
    {
        $router->aliasMiddleware('set-default-slugs', SetDefaultSlugs::class);
        $router->aliasMiddleware('check-slugs-match', CheckSlugsMatch::class);
    }

    /**
     * @return void
     */
    protected function addSettings(): void
    {
        $lang = app()->getLocale();

        $currentArticlesRoot = SlugHelper::articleSlug($lang);
        $currentCategoriesRoot = SlugHelper::articleCategorySlug($lang);

        NovaSettings::addSettingsFields([
            new Tabs(__('Settings'), [
                __('Articles') => [
                    // Le champ "Slug de base des Articles"
                    Translatable::make(__(':resource root slug', ['resource' => __('Articles')]), 'articles_root_slug')
                        ->singleLine()
                        ->placeholder(
                            __('Default value: :value', ['value' => config('articles.default_paths.articles')])
                        )
                        ->help(
                            __(
                                "This is the root slug that will be used for :resource pages: :url",
                                [
                                    'url' => "/{$lang}/<b>{$currentArticlesRoot}</b>/art-lorem-ipsum",
                                    'resource' => __('Articles'),
                                ]
                            )
                        )
                        ->rules([new TranslatableSlug]),

                    // Le champ "Slug de base des Catégories"
                    Translatable::make(__(':resource root slug', ['resource' => __('Categories')]), 'articles_categories_root_slug')
                        ->singleLine()
                        ->placeholder(
                            __('Default value: :value', ['value' => config('articles.default_paths.categories')])
                        )
                        ->help(
                            __(
                                "This is the root slug that will be used for :resource pages: :url",
                                [
                                    'url' => "/{$lang}/{$currentArticlesRoot}/<b>{$currentCategoriesRoot}</b>/cat-lorem-ipsum",
                                    'resource' => __('Categories'),
                                ]
                            )
                        )
                        ->rules([new TranslatableSlug]),
                ],
            ]),
        ], [
            'articles_root_slug' => 'array',
            'articles_categories_root_slug' => 'array',
        ]);
    }
}
