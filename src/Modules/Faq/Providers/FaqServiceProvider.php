<?php

namespace Webid\Cms\Modules\Faq\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Faq\Nova\Faq;
use Webid\Cms\Modules\Faq\Nova\FaqTheme;

class FaqServiceProvider extends ServiceProvider
{
    /** @var string */
    protected $moduleName = 'Faq';

    /** @var string */
    protected $moduleNameLower = 'faq';

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                Faq::class,
                FaqTheme::class
            ]);
        });

        DynamicResource::pushTemplateModuleGroupResource([
            'label' => __('Faq'),
            'expanded' => false,
            'resources' => [
                Faq::class,
                FaqTheme::class
            ]
        ]);
    }
}
