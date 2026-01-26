<?php

use App\Modules\News\Http\Controllers\Api\V1\NewsController;

Route::middleware(['auth:sanctum'])->prefix('api/v1/news')->name('api.v1.news.')->group(function () {
    Route::get('/{id}', [NewsController::class, 'show'])->name('show');
});
