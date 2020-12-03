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

Route::group([
    'namespace' => 'Webid\Cms\Src\App\Http\Controllers',
    'middleware' => ['web']
], function () {
    Route::get('/csrf', 'CsrfController');
});

Route::group([
    'prefix' => '{lang}/form',
    'namespace' => 'Webid\Cms\Src\App\Http\Controllers\Modules\Ajax\Form',
    'middleware' => ['web', 'anti-spam', 'language', 'check-language-exist']
], function () {
    Route::post('/send', 'FormController@handle')->name('send.form');
});
