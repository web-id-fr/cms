<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'cacheable'], function() {
    // Redirect homepage without lang
    Route::get('/', 'Webid\Cms\Src\App\Http\Controllers\TemplateController@rootPage');

    Route::group([
        'namespace' => 'Webid\Cms\Src\App\Http\Controllers',
        'prefix' => '{lang}',
        'middleware' => ['web', 'language', 'check-language-exist'],
    ], function () {
        // Homepage
        Route::get('/', 'TemplateController@index')->name('home');

        // Laisser cette règle en dernier, elle risque "d'attraper" toutes les routes !
        Route::get('{slug}', 'TemplateController@show')->where([
            'slug' => '(?!' . trim(config('nova.path'), '/') . '|ajax|api)(.+)',
        ])->name('pageFromSlug');
    });
});

Route::get('/csrf', 'Webid\Cms\Src\App\Http\Controllers\CsrfController');

Route::group([
    'prefix' => '{lang}/form',
    'namespace' => 'Webid\Cms\Src\App\Http\Controllers\Modules\Ajax\Form',
    'middleware' => ['web', 'anti-spam', 'language', 'check-language-exist']
], function () {
    Route::post('/send', 'FormController@handle')->name('send.form');
});
