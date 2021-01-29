<?php

namespace Webid\Cms\Modules\Form\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Form\Nova\Field;
use Webid\Cms\Modules\Form\Nova\Form;
use Webid\Cms\Modules\Form\Nova\Recipient;
use Webid\Cms\Modules\Form\Nova\Service;
use Webid\Cms\Modules\Form\Nova\TitleField;
use Webid\Cms\Modules\Form\Models\Field as FieldModel;
use Webid\Cms\Modules\Form\Observers\FieldObserver;

class FormServiceProvider extends ServiceProvider
{
    /** @var string  */
    protected $moduleName = 'Form';

    /** @var string  */
    protected $moduleNameLower = 'form';

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

        DynamicResource::pushTemplateModuleGroupResource([
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

        Nova::serving(function (ServingNova $event) {
            FieldModel::observe(FieldObserver::class);
        });
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

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
        $this->mergeConfigFrom(
            $sourcePath . '/form.php',
            $this->moduleNameLower
        );
    }

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
