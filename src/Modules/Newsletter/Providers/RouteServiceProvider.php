<?php

namespace Webid\Cms\Modules\Newsletter\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Webid\Cms\Modules\Newsletter\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAjaxRoutes();
    }

    /**
     * Define the "ajax" routes for the application.
     *
     * These routes are for nova only.
     *
     * @return void
     */
    protected function mapAjaxRoutes()
    {
        Route::middleware('ajax')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Newsletter', 'Routes/ajax.php'));
    }
}
