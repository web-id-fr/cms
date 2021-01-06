<?php

namespace Webid\Cms\Modules\Form\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
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
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->publishViews();
        $this->publishJs();
        $this->publishTranslations();
        $this->registerConfig();
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
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /*
     * @return void
     */
    protected function registerConfig(): void
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
    protected function publishConfig(): void
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

    /*
     * @return void
     */
    protected function publishJs(): void
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
    protected function publishViews(): void
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
    public function publishTranslations(): void
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
