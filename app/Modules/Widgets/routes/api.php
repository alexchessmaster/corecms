<?php

use App\Modules\Shared\Http\Middleware\IncreaseMemoryLimitMiddleware;
use App\Modules\Widgets\Http\Controllers\Api\FieldWidgetController;
use App\Modules\Widgets\Http\Controllers\Api\WidgetController;
use App\Modules\Widgets\Http\Controllers\Api\WidgetFieldValuesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api')->name('api.')->group(function () {

    Route::patch('widget-field-values', [WidgetFieldValuesController::class, 'update'])->middleware([IncreaseMemoryLimitMiddleware::class]);
    Route::patch('widgets/attach', [WidgetController::class, 'attach']);
    Route::patch('widgets/detach', [WidgetController::class, 'detach']);
    Route::get('widgets/{id}', [WidgetController::class, 'show']);
    Route::apiResource('widgets/{widget_id}/fields', FieldWidgetController::class);
});
