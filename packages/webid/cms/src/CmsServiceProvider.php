<?php

namespace Webid\Cms;

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Webid\Cms\Src\App\Facades\LanguageFacade;
use App\Models\Template as TemplateModel;
use Webid\Cms\Src\App\Http\Controllers\Ajax\Newsletter\NewsletterController;
use Webid\Cms\Src\App\Http\Controllers\Components\ComponentController;
use Webid\Cms\Src\App\Nova\Components\GalleryComponent;
use Webid\Cms\Src\App\Nova\Components\NewsletterComponent;
use Webid\Cms\Src\App\Nova\Modules\Form\Field;
use Webid\Cms\Src\App\Nova\Modules\Form\Form;
use Webid\Cms\Src\App\Nova\Modules\Form\Recipient;
use Webid\Cms\Src\App\Nova\Modules\Form\Service;
use Webid\Cms\Src\App\Nova\Modules\Form\TitleField;
use Webid\Cms\Src\App\Nova\Modules\Galleries\Gallery;
use Webid\Cms\Src\App\Nova\Newsletter\Newsletter;
use Webid\Cms\Src\App\Nova\Popin\Popin;
use Webid\Cms\Src\App\Nova\Slideshow\Slide;
use Webid\Cms\Src\App\Nova\Slideshow\Slideshow;
use Webid\Cms\Src\App\Observers\TemplateObserver;
use Webid\Cms\Src\App\Http\Controllers\TemplateController;
use Webid\Cms\Src\App\Nova\Template;
use Illuminate\Support\Facades\View;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(UrlGenerator $generator)
    {
        // Force https même si l'app est chargée en http
        if (!app()->isLocal()) {
            $generator->forceScheme('https');
        }

        $this->publishConfiguration();
        $this->publishProvider();
        $this->publishViews();
        $this->publishPublicFiles();
        $this->publishPublicFiles();
        $this->publishTemplateModel();
        $this->publishNovaComponents();

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/ajax.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->app->register(CmsServiceProvider::class);

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
                Slide::class
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
        $this->app->make(TemplateController::class);
        $this->app->make(ComponentController::class);
        $this->app->make(NewsletterController::class);

        Route::pattern('id', '[0-9]+');
        Route::pattern('lang', '(' . LanguageFacade::getAllLanguagesAsRegex() . ')');
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
            __DIR__ . '/public/css' => base_path('/public/css'),
            __DIR__ . '/public/js' => base_path('/public/js'),
        ], 'public');
    }

    protected function publishNovaComponents()
    {
        $this->publishes([
            __DIR__ . '/nova-components/ComponentField' => base_path('/nova-components/ComponentField'),
            __DIR__ . '/nova-components/ComponentTool' => base_path('/nova-components/ComponentTool'),
        ], 'nova-components');
    }

    protected function publishTemplateModel()
    {
        $this->publishes([
            __DIR__ . '/app/Models/Template/Template.php' => base_path('/app/Models/Template.php'),
        ], 'template-model');
    }
}
