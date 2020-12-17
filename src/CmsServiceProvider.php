<?php

namespace Webid\Cms;

use App\Models\Template as TemplateModel;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Spatie\Honeypot\ProtectAgainstSpam;
use Spatie\Varnish\Middleware\CacheWithVarnish;
use Webid\Cms\App\Http\Middleware\CheckLanguageExist;
use Webid\Cms\App\Http\Middleware\Language;
use Webid\Cms\App\Nova\Components\GalleryComponent;
use Webid\Cms\App\Nova\Components\NewsletterComponent;
use Webid\Cms\App\Nova\Menu\Menu;
use Webid\Cms\App\Nova\Menu\MenuCustomItem;
use Webid\Cms\App\Nova\Modules\Form\Field;
use Webid\Cms\App\Nova\Modules\Form\Form;
use Webid\Cms\App\Nova\Modules\Form\Recipient;
use Webid\Cms\App\Nova\Modules\Form\Service;
use Webid\Cms\App\Nova\Modules\Form\TitleField;
use Webid\Cms\App\Nova\Modules\Galleries\Gallery;
use Webid\Cms\App\Nova\Newsletter\Newsletter;
use Webid\Cms\App\Nova\Popin\Popin;
use Webid\Cms\App\Nova\Slideshow\Slide;
use Webid\Cms\App\Nova\Slideshow\Slideshow;
use Webid\Cms\App\Nova\Template;
use Webid\Cms\App\Observers\TemplateObserver;
use Webid\Cms\App\Services\Galleries\Contracts\GalleryServiceContract;
use Webid\Cms\App\Services\Galleries\GalleryLocalStorageService;
use Webid\Cms\App\Services\Galleries\GalleryS3Service;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\App\Services\MenuService;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * @param UrlGenerator $generator
     * @param Router $router
     */
    public function boot(UrlGenerator $generator, Router $router)
    {
        // Force https même si l'app est chargée en http
        if (!app()->isLocal()) {
            $generator->forceScheme('https');
        }

        $this->registerMenuDirective();

        $this->publishConfiguration();
        $this->publishProvider();
        $this->publishViews();
        $this->publishPublicFiles();
        $this->publishPublicFiles();
        $this->publishTemplateModel();
        $this->publishNovaComponents();
        $this->publishTranslations();
        $this->publishSendFormJs();
        $this->publishServices();
        $this->publishEmailTemplate();
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
                Newsletter::class,
                NewsletterComponent::class,
                Popin::class,
                Form::class,
                Field::class,
                TitleField::class,
                Recipient::class,
                Service::class,
                Slideshow::class,
                Slide::class,
                Menu::class,
                MenuCustomItem::class,
            ]);
        });

        View::share('maxFiles', config('dropzone.max-files'));
        View::share('maxTotalSize', config('dropzone.max-file-size'));
    }

    /**
     * Register services.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/cms.php', 'cms');
        $this->bindGalleryServiceContract();

        Route::pattern('id', '[0-9]+');
        Route::pattern('lang', '(' . app(LanguageService::class)->getAllLanguagesAsRegex() . ')');
    }

    protected function registerMenuDirective()
    {
        Blade::directive('menu', function ($expression) {
            $expression = str_replace("'", "\'", $expression);
            return "<?php echo app('" . MenuService::class . "')->showMenu('{$expression}'); ?>";
        });
    }

    protected function publishConfiguration()
    {
        $this->publishes([
            __DIR__ . '/config/translatable.php' => config_path('translatable.php'),
            __DIR__ . '/config/filemanager.php' => config_path('filemanager.php'),
            __DIR__ . '/config/components.php' => config_path('components.php'),
            __DIR__ . '/config/phpcs.xml' => base_path('phpcs.xml'),
            __DIR__ . '/config/psalm.xml' => base_path('psalm.xml'),
            __DIR__ . '/config/Makefile' => base_path('Makefile'),
            __DIR__ . '/config/dropzone.php' => config_path('dropzone.php'),
            __DIR__ . '/config/fields_type.php' => config_path('fields_type.php'),
            __DIR__ . '/config/fields_type_validation.php' => config_path('fields_type_validation.php'),
            __DIR__ . '/config/ziggy.php' => config_path('ziggy.php'),
            __DIR__ . '/config/cms.php' => config_path('cms.php'),
            __DIR__ . '/config/varnish.php' => config_path('varnish.php'),
        ], 'config');
    }

    protected function publishViews()
    {
        $this->publishes([
            __DIR__ . '/resources/views' => base_path('/resources/views'),
        ], 'views');
    }

    protected function publishProvider()
    {
        $this->publishes([
            __DIR__ . '/app/Providers/NovaServiceProvider.php' => base_path('/app/Providers/NovaServiceProvider.php'),
        ], 'providers');
    }

    protected function publishPublicFiles()
    {
        $this->publishes([
            __DIR__ . '/public/cms' => base_path('/public/cms'),
        ], 'public');
    }

    protected function publishNovaComponents()
    {
        $this->publishes([
            __DIR__ . '/nova-components/ComponentItemField' => base_path('/nova-components/ComponentItemField'),
            __DIR__ . '/nova-components/ComponentTool' => base_path('/nova-components/ComponentTool'),
        ], 'nova-components');
    }

    protected function publishTemplateModel()
    {
        $this->publishes([
            __DIR__ . '/app/Models/Template.php' => base_path('/app/Models/Template.php'),
        ], 'template-model');
    }

    protected function publishTranslations()
    {
        $this->publishes([
            __DIR__ . '/resources/lang' => base_path('/resources/lang'),
        ], 'translations');
    }

    protected function publishSendFormJs()
    {
        $this->publishes([
            __DIR__ . '/resources/js/send_form.js' => base_path('/resources/js/send_form.js'),
            __DIR__ . '/resources/js/send_form_popin.js' => base_path('/resources/js/send_form_popin.js'),
            __DIR__ . '/resources/js/helpers.js' => base_path('/resources/js/helpers.js'),
        ], 'send-form');
    }

    protected function publishServices()
    {
        $this->publishes([
            __DIR__ . '/app/Services/Publish/ExtraElementsForPageService.php' => base_path('/app/Services/ExtraElementsForPageService.php'),
        ], 'services');
    }

    protected function publishEmailTemplate()
    {
        $this->publishes([
            __DIR__ . '/resources/views/mail/form.blade.php' => base_path('/resources/views/mail/form.blade.php'),
        ], 'email-template');
    }

    /**
     * @param Router $router
     */
    protected function registerAliasMiddleware(Router $router)
    {
        $router->aliasMiddleware('anti-spam', ProtectAgainstSpam::class);
        $router->aliasMiddleware('language', Language::class);
        $router->aliasMiddleware('check-language-exist', CheckLanguageExist::class);
        $router->aliasMiddleware('cacheable', CacheWithVarnish::class);
    }

    protected function bindGalleryServiceContract()
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
