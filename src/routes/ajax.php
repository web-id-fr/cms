<?php

use Webid\Cms\Src\App\Http\Middleware\IsAjax;
use Webid\Cms\Src\App\Http\Middleware\Language;
use Webid\Cms\Src\App\Http\Controllers\Components\ComponentController;
use Webid\Cms\Src\App\Http\Controllers\Ajax\Newsletter\NewsletterController;
use Webid\Cms\Src\App\Http\Controllers\Ajax\Menu\MenuController;
use Webid\Cms\Src\App\Http\Controllers\Ajax\Menu\MenuCustomItemController;
use Webid\Cms\Src\App\Http\Controllers\Ajax\Menu\MenuConfigurationController;

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Ajax routes for your application. Only
| accessible when you are connected to Laravel Nova.
*/

/* *********************************************************************************
 * COMPONENTS AJAX ROUTE
 ********************************************************************************* */
Route::group([
    'middleware' => ['nova', IsAjax::class],
    'prefix' => 'ajax',
], function () {
    Route::get('component', [ComponentController::class, 'index']);
});

/* *********************************************************************************
* NEWSLETTER AJAX ROUTE
********************************************************************************* */
Route::group([
    'middleware' => [IsAjax::class, Language::class],
    'prefix' => '{lang}/ajax'
], function () {
    /**
     * Newsletter
     */
    Route::prefix('/newsletter')->name('newsletter.')->group(function () {
        Route::post('/', [NewsletterController::class, 'store'])->name('store');
    });
});

/* *********************************************************************************
 * MENU AJAX ROUTE
 ********************************************************************************* */
Route::group([
    'middleware' => ['nova', IsAjax::class],
    'prefix' => 'ajax',
], function () {
    Route::get('menu', [MenuController::class, 'index']);
    Route::get('menu-custom-item', [MenuCustomItemController::class, 'index']);
    Route::get('menu-configuration', [MenuConfigurationController::class, 'index']);
    Route::get('menu-zone', [MenuConfigurationController::class, 'updateZone']);
});
