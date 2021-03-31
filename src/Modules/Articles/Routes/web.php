<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\Modules\Articles\Http\Controllers\ArticleController;

Route::group([
    'prefix' => '{lang}/articles',
    'middleware' => [
        'language',
        'check-language-exist',
    ],
    'as' => 'articles.',
], function () {
    Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
});
