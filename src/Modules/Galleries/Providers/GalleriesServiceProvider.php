<?php

namespace Webid\Cms\Modules\Galleries\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Galleries\Services\Contracts\GalleryServiceContract;
use Webid\Cms\Modules\Galleries\Services\GalleryLocalStorageService;
use Webid\Cms\Modules\Galleries\Services\GalleryS3Service;
use Webid\Cms\Modules\Galleries\Nova\Gallery;

class GalleriesServiceProvider extends ServiceProvider
{
    /** @var string  */
    protected $moduleName = 'Galleries';

    /** @var string  */
    protected $moduleNameLower = 'galleries';

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                Gallery::class
            ]);
        });

        DynamicResource::pushSingleModuleResource([
            'resource' => Gallery::class
        ]);
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->bindGalleryServiceContract();
    }

    /**
     * @return void
     */
    protected function bindGalleryServiceContract(): void
    {
        if ('s3' == config('galleries.filesystem_driver')) {
            $galleryService = GalleryS3Service::class;
        } else {
            $galleryService = GalleryLocalStorageService::class;
        }

        $this->app->bind(
            GalleryServiceContract::class,
            $galleryService
        );
    }

    /**
     * @return void
     */
    protected function publishConfig(): void
    {
        $sourcePath = module_path($this->moduleName, 'Config');

        $this->publishes([
            $sourcePath . '/galleries.php' => config_path('galleries.php'),
        ], [
            $this->moduleNameLower . '-module',
            $this->moduleNameLower . '-module-config'
        ]);
    }
}
