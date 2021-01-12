<?php

use Illuminate\Support\Facades\Route;
use Webid\PreviewItemField\Http\Controllers\PreviewItemFieldController;

/*
|--------------------------------------------------------------------------
| PreviewItemField AJAX Routes
|--------------------------------------------------------------------------
|
*/

Route::post('/store-preview-data', [PreviewItemFieldController::class, 'storeTemplateData'])->name('store.preview');
