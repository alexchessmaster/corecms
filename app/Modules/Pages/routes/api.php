<?php

use App\Modules\Pages\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.v1.')->group(function () {
    Route::apiResource('pages', PageController::class);
});
