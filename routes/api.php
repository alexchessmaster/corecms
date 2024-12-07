<?php

use App\Http\Controllers\Api\CommonDataController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\WidgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/content', [ContentController::class, 'fetchContent']);
// Route::get('/common-data', [CommonDataController::class, 'index']);

Route::apiResource('/pages', PageController::class);
Route::patch('/widgets/detach', [WidgetController::class, 'detach']);
Route::patch('/widgets/attach', [WidgetController::class, 'attach']);
Route::get('/widgets/{id}', [WidgetController::class, 'show']);
// Route::apiResource('/widgets', WidgetController::class);
Route::apiResource('/fields', FieldController::class);
