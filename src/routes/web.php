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

use Illuminate\Support\Facades\Route;
use Webid\Cms\App\Http\Controllers\CsrfController;
use Webid\Cms\App\Http\Controllers\Modules\Ajax\Form\FormController;
use Webid\Cms\App\Http\Controllers\SitemapController;
use Webid\Cms\App\Http\Controllers\TemplateController;

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
        Route::get('{slug}', [TemplateController::class, 'show'])->where([
            'slug' => '(?!' . trim(config('nova.path'), '/') . '|ajax|api)(.+)',
        ])->name('pageFromSlug');
    });
});

Route::group([
    'middleware' => ['web'],
], function () {
    Route::get('/csrf', [CsrfController::class, 'index'])->name('csrf.index');
});

Route::group([
    'prefix' => '{lang}/form',
    'middleware' => ['web', 'anti-spam', 'language', 'check-language-exist'],
], function () {
    Route::get('/send', [FormController::class, 'handle'])->name('send.form');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

# /!\ Cette route doit TOUJOURS être la dernière
Route::middleware(['pages'])->group(function () {
    Route::fallback(function () {
        abort(404);
    });
});
