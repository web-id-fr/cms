<?php

namespace Webid\Cms\Modules\Form\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Nova\Nova;
use Spatie\Honeypot\ProtectAgainstSpam;
use Webid\Cms\App\Http\Middleware\CheckLanguageExist;
use Webid\Cms\App\Http\Middleware\Language;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\Modules\Form\Nova\Field;
use Webid\Cms\Modules\Form\Nova\Form;
use Webid\Cms\Modules\Form\Nova\Recipient;
use Webid\Cms\Modules\Form\Nova\Service;
use Webid\Cms\Modules\Form\Nova\TitleField;

class FormServiceProvider extends ServiceProvider
{
    /**
     * @param Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerAliasMiddleware($router);
        $this->publishSendFormJs();

        $this->app->booted(function () {
            Nova::resources([
                Form::class,
                Field::class,
                TitleField::class,
                Recipient::class,
                Service::class,
            ]);
        });

        View::share('maxFiles', config('dropzone.max-files'));
        View::share('maxTotalSize', config('dropzone.max-file-size'));
    }

    /*
     * Register services
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        Route::pattern('lang', '(' . app(LanguageService::class)->getAllLanguagesAsRegex() . ')');
    }

    /*
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/dropzone.php' => config_path('dropzone.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/fields_type.php', 'form'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/fields_type_validation.php', 'form'
        );
    }

    /**
     * @param Router $router
     *
     * @return void
     */
    protected function registerAliasMiddleware(Router $router)
    {
        $router->aliasMiddleware('anti-spam', ProtectAgainstSpam::class);
        $router->aliasMiddleware('language', Language::class);
        $router->aliasMiddleware('check-language-exist', CheckLanguageExist::class);
    }

    /*
     * @return void
     */
    protected function publishSendFormJs()
    {
        $this->publishes([
            module_path('Form', '/Resources/js/send_form.js') => base_path('/resources/js/send_form.js'),
            module_path('Form', '/Resources/js/send_form_popin.js') => base_path('/resources/js/send_form_popin.js'),
            module_path('Form', '/Resources/js/helpers.js') => base_path('/resources/js/helpers.js'),
        ], 'send-form');
    }

    /*
     * @return void
     */
    protected function registerViews()
    {
        $viewPath = resource_path('resources/views/modules/form');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/form';
        }, Config::get('view.paths')), [$sourcePath]), 'form');
    }

    /**
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/form');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'form');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'form');
        }
    }
}
