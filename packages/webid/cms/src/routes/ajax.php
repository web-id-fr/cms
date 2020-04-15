<?php

use Webid\Cms\Src\App\Http\Middleware\IsAjax;
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
    'namespace' => 'Webid\Cms\Src\App\Http\Controllers\Components',
    'middleware' => ['nova', IsAjax::class],
    'prefix' => 'ajax',
], function () {
    Route::get('component', 'ComponentController@index');
});
