<?php

use App\Modules\Pages\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api')->name('api.')->group(function () {
    Route::apiResource('pages', PageController::class);
});
