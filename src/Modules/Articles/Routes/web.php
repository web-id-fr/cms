<?php

use Illuminate\Support\Facades\Route;
use Webid\Cms\Modules\Articles\Http\Controllers\ArticleController;
use Webid\Cms\Modules\Articles\Http\Controllers\ArticleCategoryController;

Route::group([
    'prefix' => '{lang}/' . config('articles.path'),
    'middleware' => ['web', 'language', 'check-language-exist'],
    'as' => 'articles.',
], function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/cat/{category}', [ArticleCategoryController::class, 'show'])->name('categories.show');
    Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
});
