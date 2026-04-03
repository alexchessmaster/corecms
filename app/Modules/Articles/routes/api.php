<?php

use App\Modules\Articles\Http\Controllers\Api\ArticleController;
use App\Modules\Articles\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.v1.')->group(function () {
    Route::apiResource('/articles', ArticleController::class);
    Route::put('/{id}/{lang}', [ArticleController::class, 'removeArticleLanguage'])->name('articles.removeArticleLanguage');
    Route::apiResource('/categories', CategoryController::class);
});
