<?php

namespace Webid\Cms\Modules\Galleries\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webid\Cms\App\Services\DynamicResource;
use Webid\Cms\Modules\Galleries\Http\Services\Contracts\GalleryServiceContract;
use Webid\Cms\Modules\Galleries\Http\Services\GalleryLocalStorageService;
use Webid\Cms\Modules\Galleries\Http\Services\GalleryS3Service;
use Webid\Cms\Modules\Galleries\Nova\Gallery;

class GalleriesServiceProvider extends ServiceProvider
{
    /** @var string  */
    protected $moduleName = 'Galleries';

    /** @var string  */
    protected $moduleNameLower = 'Galleries';

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->booted(function () {
            Nova::resources([
                Gallery::class
            ]);
        });

        DynamicResource::pushSingleModuleResources([
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
    protected function mergeConfig(): void
    {
        $sourcePath = module_path($this->moduleName, 'Config');

        $this->mergeConfigFrom(
            $sourcePath . '/galleries.php',
            $this->moduleNameLower
        );
    }
}
