<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\App\Http\Controllers\PreviewController;
use Webid\Cms\App\Http\Controllers\SitemapController;
use Webid\Cms\App\Http\Controllers\TemplateController;

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
        'middleware' => ['web', 'pages', 'language', 'check-language-exist'],
    ], function () {
        // Homepage
        Route::get('/', [TemplateController::class, 'index'])->name('home');

        // Laisser cette règle en dernier, elle risque "d'attraper" toutes les routes !
        Route::get('{slug}', [TemplateController::class, 'show'])
            ->where(['slug' => '(?!' . trim(config('nova.path'), '/') . '|ajax|api)(.+)'])
            ->name('pageFromSlug')
            ->fallback();
    });
});

Route::group([
    'middleware' => ['web'],
], function () {
    Route::get('/preview/{token}', [PreviewController::class, 'preview'])->name('preview');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

# /!\ Cette route doit TOUJOURS être la dernière
Route::middleware(['pages'])->group(function () {
    Route::fallback(function () {
        abort(404);
    });
});
