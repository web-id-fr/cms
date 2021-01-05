<?php

namespace Webid\Cms\Modules\Form\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Nova\Nova;
use Spatie\Honeypot\ProtectAgainstSpam;
use Webid\Cms\App\Http\Middleware\CheckLanguageExist;
use Webid\Cms\App\Http\Middleware\Language;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\Modules\Form\Nova\Field;
use Webid\Cms\Modules\Form\Nova\Form;
use Webid\Cms\Modules\Form\Nova\Recipient;
use Webid\Cms\Modules\Form\Nova\Service;
use Webid\Cms\Modules\Form\Nova\TitleField;

class FormServiceProvider extends ServiceProvider
{
    /** @var string  */
    protected $moduleName = 'Form';

    /** @var string  */
    protected $moduleNameLower = 'form';

    /**
     * @param Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publishConfig();
        $this->publishViews();
        $this->publishJs();
        $this->publishTranslations();
        $this->registerAliasMiddleware($router);
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

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

        DynamicResource::pushGroupModuleResource([
            'label' => __('Form'),
            'expanded' => false,
            'resources' => [
                Form::class,
                Field::class,
                TitleField::class,
                Service::class,
                Recipient::class,
            ]
        ]);
    }

    /*
     * Register services
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerConfig();
        Route::pattern('lang', '(' . app(LanguageService::class)->getAllLanguagesAsRegex() . ')');
    }

    /*
     * @return void
     */
    protected function registerConfig()
    {
        $sourcePath = module_path($this->moduleName, 'Config');

        $this->mergeConfigFrom(
            $sourcePath . '/fields_type.php',
            $this->moduleNameLower
        );
        $this->mergeConfigFrom(
            $sourcePath . '/fields_type_validation.php',
            $this->moduleNameLower
        );
    }

    /*
    * @return void
    */
    protected function publishConfig()
    {
        $sourcePath = module_path($this->moduleName, 'Config');

        $this->publishes([
            $sourcePath . '/dropzone.php' => config_path('dropzone.php'),
            $sourcePath . '/fields_type.php' => config_path('fields_type.php'),
            $sourcePath . '/fields_type_validation.php' => config_path('fields_type_validation.php'),
        ], [
            $this->moduleNameLower . '-module',
            $this->moduleNameLower . '-module-config'
        ]);
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
    protected function publishJs()
    {
        $jsPath = resource_path('js');
        $sourcePath = module_path($this->moduleName, 'Resources/js');

        $this->publishes([
            $sourcePath . '/send_form.js' => $jsPath . '/send_form.js',
            $sourcePath . '/send_form_popin.js' => $jsPath . '/send_form_popin.js',
            $sourcePath . '/helpers.js' => $jsPath . '/helpers.js',
        ], [
            $this->moduleNameLower . '-module',
            $this->moduleNameLower . '-module-js'
        ]);
    }

    /*
     * @return void
     */
    protected function publishViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], [
            $this->moduleNameLower . '-module',
            $this->moduleNameLower . '-module-views'
        ]);
    }

    /**
     * @return void
     */
    public function publishTranslations()
    {
        $langPath = resource_path('lang');
        $sourcePath = module_path($this->moduleName, 'Resources/lang');

        $this->publishes([
            $sourcePath => $langPath,
        ], [
            $this->moduleNameLower . '-module',
            $this->moduleNameLower . '-module-lang'
        ]);
    }
}
