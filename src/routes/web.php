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
use Webid\Cms\Src\App\Http\Controllers\TemplateController;
use Webid\Cms\Src\App\Http\Controllers\CsrfController;
use Webid\Cms\Src\App\Http\Controllers\Modules\Ajax\Form\FormController;

Route::group(['middleware' => 'cacheable'], function() {
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
