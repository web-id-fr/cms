<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\App\Http\Controllers\TemplateController;
use Webid\Cms\App\Http\Controllers\SitemapController;

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

Route::group(['middleware' => 'cacheable'], function () {
    // Redirect homepage without lang
    Route::get('/', [TemplateController::class, 'rootPage']);

    Route::group([
        'prefix' => '{lang}',
        'middleware' => ['web', 'language', 'check-language-exist'],
    ], function () {
        // Homepage
        Route::get('/', [TemplateController::class, 'index'])->name('home');

        // Laisser cette règle en dernier, elle risque "d'attraper" toutes les routes !
        Route::get('{slug}', [TemplateController::class, 'show'])->where([
            'slug' => '(?!' . trim(config('nova.path'), '/') . '|ajax|api)(.+)',
        ])->name('pageFromSlug');
    });
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
