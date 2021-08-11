<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\App\Http\Controllers\PreviewController;
use Webid\Cms\App\Http\Controllers\SitemapController;
use Webid\Cms\App\Http\Controllers\TemplateController;
use Webid\Cms\App\Repositories\TemplateRepository;

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
    });
});

Route::group([
    'middleware' => ['web'],
], function () {
    Route::get('/preview/{token}', [PreviewController::class, 'preview'])->name('preview');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

# /!\ Cette route doit TOUJOURS être la dernière
Route::prefix('{lang}')
    ->middleware([
        'web',
        'pages',
        'language',
        'check-language-exist',
        'redirect-to-homepage',
        'cacheable'
    ])->group(function () {
        Route::fallback([TemplateController::class, 'pages']);
    });
