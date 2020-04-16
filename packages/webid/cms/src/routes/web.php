<?php

use Webid\Cms\Src\App\Http\Middleware\CheckLanguageExist;
use Webid\Cms\Src\App\Http\Middleware\Language;

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

// Redirect homepage without lang
Route::get('/', 'Webid\Cms\Src\App\Http\Controllers\TemplateController@rootPage');

Route::group([
    'namespace' => 'Webid\Cms\Src\App\Http\Controllers',
    'prefix' => '{lang}',
    'middleware' => [Language::class, CheckLanguageExist::class],
], function () {
    // Homepage
    Route::get('/', 'TemplateController@index')->name('home');

    // Laisser cette règle en dernier, elle risque "d'attraper" toutes les routes !
    Route::get('{slug}', 'TemplateController@show')->where([
        'slug' => '(?!' . trim(config('nova.path'), '/') . '|ajax|api)(.+)',
    ])->name('pageFromSlug');
});

Route::group([
    'prefix' => 'form',
    'namespace' => 'Modules\Ajax\Form',
    'middleware' => ['web', 'antispam']
], function () {
    Route::post('/send', 'FormController@sendForm')->name('send.form');
});
