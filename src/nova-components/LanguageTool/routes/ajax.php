<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool AJAX Routes
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'middleware' => 'nova',
    'namespace' => 'Webid\LanguageTool\Http\Controllers',
], function () {
    Route::prefix('language')->group(function () {
        Route::get('/', 'LanguageController@index');
        Route::post('/', 'LanguageController@store');
        Route::delete('{language}', 'LanguageController@delete');
        Route::get('/available', 'LanguageController@available');
    });
});
