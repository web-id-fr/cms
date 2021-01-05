<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\Modules\Form\Http\Controllers\FormController;
use Webid\Cms\Modules\Form\Http\Controllers\CsrfController;

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

Route::group(['middleware' => ['web']
], function () {
    Route::get('/csrf', [CsrfController::class], 'index')->name('csrf.index');;
});

Route::group([
    'prefix' => '{lang}/form',
    'middleware' => ['web', 'anti-spam', 'language', 'check-language-exist']
], function () {
    Route::post('/send', [FormController::class, 'handle'])->name('send.form');
});
