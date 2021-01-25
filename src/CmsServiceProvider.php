<?php

namespace Webid\Cms;

use App\Models\Template as TemplateModel;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Spatie\Honeypot\ProtectAgainstSpam;
use Spatie\Varnish\Middleware\CacheWithVarnish;
use Webid\Cms\App\Http\Middleware\CheckLanguageExist;
use Webid\Cms\App\Http\Middleware\IsAjax;
use Webid\Cms\App\Http\Middleware\Language;
use Webid\Cms\App\Nova\Components\GalleryComponent;
use Webid\Cms\App\Nova\Components\NewsletterComponent;
use Webid\Cms\App\Nova\Menu\Menu;
use Webid\Cms\App\Nova\Menu\MenuCustomItem;
use Webid\Cms\App\Nova\Modules\Galleries\Gallery;
use Webid\Cms\App\Nova\Modules\Slideshow\Slide;
use Webid\Cms\App\Nova\Modules\Slideshow\Slideshow;
use Webid\Cms\App\Nova\Popin\Popin;
use Webid\Cms\App\Nova\Template;
use Webid\Cms\App\Observers\TemplateObserver;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\App\Services\Galleries\Contracts\GalleryServiceContract;
use Webid\Cms\App\Services\Galleries\GalleryLocalStorageService;
use Webid\Cms\App\Services\Galleries\GalleryS3Service;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\App\Services\MenuService;
use Webid\Cms\App\Services\Sitemap\SitemapGenerator;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * @param UrlGenerator $generator
     * @param Router $router
     *
     * @return void
     */
    public function boot(UrlGenerator $generator, Router $router): void
    {
        if (!app()->isLocal()) {
            $generator->forceScheme('https');
        }

        $this->app->singleton(DynamicResource::class);
        $this->app->singleton(SitemapGenerator::class);

        $this->registerMenuDirective();

        $this->publishConfiguration();
        $this->publishProvider();
        $this->publishViews();
        $this->publishPublicFiles();
        $this->publishTemplateModel();
        $this->publishNovaComponents();
        $this->publishTranslations();
        $this->publishServices();

        $this->registerAliasMiddleware($router);

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/ajax.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        Nova::serving(function (ServingNova $event) {
            // Model Observers
            TemplateModel::observe(TemplateObserver::class);
        });

        $this->app->booted(function () {
            Nova::resources([
                Template::class,
                Gallery::class,
                GalleryComponent::class,
                NewsletterComponent::class,
                Popin::class,
                Slideshow::class,
                Slide::class,
                Menu::class,
                MenuCustomItem::class,
            ]);
        });

        JsonResource::withoutWrapping();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/cms.php', 'cms');

        $this->bindGalleryServiceContract();

        Route::pattern('id', '[0-9]+');
        Route::pattern('lang', '(' . app(LanguageService::class)->getAllLanguagesAsRegex() . ')');
    }

    /**
     * @return void
     */
    protected function registerMenuDirective(): void
    {
        Blade::directive('menu', function ($expression) {
            $expression = str_replace("'", "\'", $expression);
            return "<?php echo app('" . MenuService::class . "')->showMenu('{$expression}'); ?>";
        });
    }

    /**
     * @return void
     */
    protected function publishConfiguration(): void
    {
        $this->publishes([
            __DIR__ . '/config/translatable.php' => config_path('translatable.php'),
            __DIR__ . '/config/filemanager.php' => config_path('filemanager.php'),
            __DIR__ . '/config/components.php' => config_path('components.php'),
            __DIR__ . '/config/phpcs.xml' => base_path('phpcs.xml'),
            __DIR__ . '/config/psalm.xml' => base_path('psalm.xml'),
            __DIR__ . '/config/Makefile' => base_path('Makefile'),
            __DIR__ . '/config/cms.php' => config_path('cms.php'),
            __DIR__ . '/config/varnish.php' => config_path('varnish.php'),
        ], 'config');
    }

    /**
     * @return void
     */
    protected function publishViews(): void
    {
        $this->publishes([
            __DIR__ . '/resources/views' => base_path('/resources/views'),
        ], 'views');
    }

    /**
     * @return void
     */
    protected function publishProvider(): void
    {
        $this->publishes([
            __DIR__ . '/app/Providers/NovaServiceProvider.php' => base_path('/app/Providers/NovaServiceProvider.php'),
        ], 'providers');
    }

    /**
     * @return void
     */
    protected function publishPublicFiles(): void
    {
        $this->publishes([
            __DIR__ . '/public/cms' => base_path('/public/cms'),
        ], 'public');
    }

    /**
     * @return void
     */
    protected function publishNovaComponents(): void
    {
        $this->publishes([
            __DIR__ . '/nova-components/ComponentItemField' => base_path('/nova-components/ComponentItemField'),
            __DIR__ . '/nova-components/ComponentTool' => base_path('/nova-components/ComponentTool'),
        ], 'nova-components');
    }

    /**
     * @return void
     */
    protected function publishTemplateModel(): void
    {
        $this->publishes([
            __DIR__ . '/app/Models/Template.php' => base_path('/app/Models/Template.php'),
        ], 'template-model');
    }

    /**
     * @return void
     */
    protected function publishTranslations(): void
    {
        $this->publishes([
            __DIR__ . '/resources/lang' => base_path('/resources/lang'),
        ], 'translations');
    }

    /**
     * @return void
     */
    protected function publishServices(): void
    {
        $this->publishes([
            __DIR__ . '/app/Services/ExtraElementsForPageService.php' =>
                base_path('/app/Services/ExtraElementsForPageService.php'),
        ], 'services');
    }

    /**
     * @param Router $router
     *
     * @return void
     */
    protected function registerAliasMiddleware(Router $router): void
    {
        // Alias middlewares
        $router->aliasMiddleware('anti-spam', ProtectAgainstSpam::class);
        $router->aliasMiddleware('language', Language::class);
        $router->aliasMiddleware('check-language-exist', CheckLanguageExist::class);
        $router->aliasMiddleware('cacheable', CacheWithVarnish::class);
        $router->aliasMiddleware('is-ajax', IsAjax::class);

        // Create middleware groups
        $router->middlewareGroup('pages', []);
        $router->middlewareGroup('ajax', [
            StartSession::class,
            'is-ajax',
            VerifyCsrfToken::class
        ]);
    }

    /**
     * @return void
     */
    protected function bindGalleryServiceContract(): void
    {
        if ('s3' == config('cms.filesystem_driver')) {
            $galleryService = GalleryS3Service::class;
        } else {
            $galleryService = GalleryLocalStorageService::class;
        }

        $this->app->bind(
            GalleryServiceContract::class,
            $galleryService
        );
    }
}
