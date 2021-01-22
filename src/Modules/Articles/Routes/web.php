<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\Modules\Articles\Http\Controllers\ArticleCategoryController;
use Webid\Cms\Modules\Articles\Http\Controllers\ArticleController;

Route::group([
    'prefix' => '{lang}/{articles_slug}',
    'middleware' => [
        'web',
        'language',
        'check-language-exist',
        'set-default-slugs',
        'check-slugs-match',
    ],
    'as' => 'articles.',
], function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/{categories_slug}/{category}', [ArticleCategoryController::class, 'show'])->name('categories.show');
    Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
});
