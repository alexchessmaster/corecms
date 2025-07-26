<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\LogVisitedUrlMiddleware;
use App\Http\Controllers\Api\CommonDataController;
use App\Http\Controllers\Api\WidgetableController;
use App\Http\Controllers\Api\FieldWidgetController;
use App\Http\Middleware\CacheControlHeaderMiddleware;
use App\Http\Controllers\Api\WidgetFieldValuesController;
use App\Http\Controllers\NordicStandard\Api\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/fetch-content', [ContentController::class, 'fetchContent'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
// Route::get('/fetch-articles', [ContentController::class, 'fetchArticles']); // later
// Route::get('/fetch-categories', [ContentController::class, 'fetchCategories']); // later

Route::apiResource('/pages', PageController::class);
Route::apiResource('/articles', ArticleController::class);
Route::apiResource('/categories', CategoryController::class);
Route::patch('/widget-field-values', [WidgetFieldValuesController::class, 'update']);
Route::patch('/widgets/attach', [WidgetController::class, 'attach']);
Route::patch('/widgets/detach', [WidgetController::class, 'detach']);
Route::get('/widgets/{id}', [WidgetController::class, 'show']);
Route::apiResource('/widgets/{widget_id}/fields', FieldWidgetController::class);

// custom routes:
Route::post('contact-us', [ContactController::class, 'submitContactForm']);
