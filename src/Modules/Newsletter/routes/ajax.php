<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\Modules\Newsletter\Http\Controllers\NewsletterController;


/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Ajax routes for your application. Only
| accessible when you are connected to Laravel Nova.
*/

Route::group([
    'middleware' => ['is-ajax', 'language'],
    'prefix' => '{lang}/ajax'
], function () {
    Route::prefix('/newsletter')->name('newsletter.')->group(function () {
        Route::post('/', [NewsletterController::class, 'store'])->name('store');
    });
});
