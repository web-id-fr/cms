<?php

namespace Webid\Cms\Modules\Slideshow\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Slideshow\Nova\Slide;
use Webid\Cms\Modules\Slideshow\Nova\Slideshow;

class SlideshowServiceProvider extends ServiceProvider
{
    /** @var string */
    protected $moduleName = 'Slideshow';

    /** @var string */
    protected $moduleNameLower = 'slideshow';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                Slideshow::class,
                Slide::class
            ]);
        });

        DynamicResource::pushTemplateModuleGroupResource([
            'label' => __('Slideshow'),
            'expanded' => false,
            'resources' => [
                Slideshow::class,
                Slide::class
            ]
        ]);
    }
}
