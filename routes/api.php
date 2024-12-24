<?php

use App\Http\Controllers\Api\CommonDataController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PageWidgetController;
use App\Http\Controllers\Api\WidgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/content', [ContentController::class, 'fetchContent']);
Route::post('/articles', [ContentController::class, 'fetchArticles']);
// Route::get('/common-data', [CommonDataController::class, 'index']);

Route::apiResource('/pages', PageController::class);
Route::get('/pages/{page}/widget-position/{widgets_position}/field-values/{lang?}', [PageWidgetController::class, 'fieldValue']);
Route::get('/page/{pageId}/widget/{widgetId}/widget-position/{position}/fields-with-values/{lang?}', [WidgetController::class, 'getWidgetFieldsWithValues']);
Route::patch('/pages/widget-position/update-field-value', [PageWidgetController::class, 'updateFieldValue']);
Route::patch('/widgets/detach', [WidgetController::class, 'detach']);
Route::patch('/widgets/attach', [WidgetController::class, 'attach']);
Route::get('/widgets/{id}', [WidgetController::class, 'show']);
Route::apiResource('/fields', FieldController::class);
