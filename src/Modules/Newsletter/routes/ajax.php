<?php

use Webid\Cms\Src\App\Http\Middleware\IsAjax;
use Webid\Cms\Src\App\Http\Middleware\Language;

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Ajax routes for your application. Only
| accessible when you are connected to Laravel Nova.
*/

/* *********************************************************************************
* NEWSLETTER AJAX ROUTE
********************************************************************************* */
Route::group([
    'namespace' => 'Webid\Cms\Src\App\Http\Controllers\Ajax\Newsletter',
    'middleware' => [IsAjax::class, Language::class],
    'prefix' => '{lang}/ajax'
], function () {
    /**
     * Newsletter
     */
    Route::prefix('/newsletter')->name('newsletter.')->group(function () {
        Route::post('/', 'NewsletterController@store')->name('store');
    });
});
