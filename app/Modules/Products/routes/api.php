<?php

use App\Modules\Products\Http\Controllers\Api\V1\ProductCategoryController;
use App\Modules\Products\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.v1.')->group(function () {
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/product-categories', ProductCategoryController::class);
});
