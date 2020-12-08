<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\App\Http\Middleware\IsAjax;
use Webid\Cms\App\Http\Middleware\Language;
use Webid\Cms\App\Http\Controllers\Components\ComponentController;
use Webid\Cms\App\Http\Controllers\Ajax\Newsletter\NewsletterController;
use Webid\Cms\App\Http\Controllers\Ajax\Menu\MenuController;
use Webid\Cms\App\Http\Controllers\Ajax\Menu\MenuCustomItemController;
use Webid\Cms\App\Http\Controllers\Ajax\Menu\MenuConfigurationController;

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
