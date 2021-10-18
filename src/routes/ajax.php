<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\App\Http\Controllers\Ajax\Menu\MenuConfigurationController;
use Webid\Cms\App\Http\Controllers\Ajax\Menu\MenuController;
use Webid\Cms\App\Http\Controllers\Ajax\Menu\MenuCustomItemController;
use Webid\Cms\App\Http\Controllers\Components\ComponentController;
use Webid\Cms\App\Http\Middleware\IsAjax;

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Ajax routes for your application. Only
| accessible when you are connected to Laravel Nova.
*/

Route::group([
    'middleware' => ['nova', IsAjax::class],
    'prefix' => 'ajax',
    'as' => 'ajax.',
], function () {
    /* *********************************************************************************
     * COMPONENTS AJAX ROUTE
     ********************************************************************************* */
    Route::get('component', [ComponentController::class, 'index']);

    /* *********************************************************************************
     * MENU AJAX ROUTE
     ********************************************************************************* */
    Route::get('menu', [MenuController::class, 'index'])->name('menus.index');
    Route::get('menu-custom-item', [MenuCustomItemController::class, 'index'])->name('menu_custom_items.index');
    Route::get('menu-configuration', [MenuConfigurationController::class, 'index'])->name('menu_configuration.index');
    Route::post('menu-zone', [MenuConfigurationController::class, 'updateZone'])->name('menu_zones.update');
});
