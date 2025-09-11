<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\LogVisitedUrlMiddleware;
use App\Http\Controllers\Api\BookGenreController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\BookAuthorController;
use App\Http\Controllers\Api\CommonDataController;
use App\Http\Controllers\Api\WidgetableController;
use App\Http\Controllers\Api\FieldWidgetController;
use App\Http\Middleware\CacheControlHeaderMiddleware;
use App\Http\Controllers\Api\WidgetFieldValuesController;
use App\Http\Controllers\NordicStandard\Api\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('throttle:240,1')->group(function () {
    Route::get('/fetch-menu', [ContentController::class, 'fetchMenu']);
    Route::get('/fetch-languages', [ContentController::class, 'fetchLanguages']);
    Route::get('/fetch-settings', [ContentController::class, 'fetchSettings']);
    Route::get('/fetch-translations', [ContentController::class, 'fetchTranslations']);
    Route::get('/fetch-content', [ContentController::class, 'fetchContent'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-books', [ContentController::class, 'fetchBooks'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-book-genres', [ContentController::class, 'fetchBookGenres']);
    Route::get('/fetch-authors', [ContentController::class, 'fetchAuthors']);
    Route::get('/fetch-book-comments', [ContentController::class, 'fetchBookComments']);
    // Route::get('/fetch-articles', [ContentController::class, 'fetchArticles']); // later
    // Route::get('/fetch-categories', [ContentController::class, 'fetchCategories']); // later
});

Route::post('/store-book-comments', [ContentController::class, 'storeBookComments'])->middleware('throttle:1,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/pages', PageController::class);
    Route::apiResource('/articles', ArticleController::class);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/books', BookController::class);
    Route::apiResource('/bookgenres', BookGenreController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/productcategories', ProductCategoryController::class);
    Route::patch('/widget-field-values', [WidgetFieldValuesController::class, 'update']);
    Route::patch('/widgets/attach', [WidgetController::class, 'attach']);
    Route::patch('/widgets/detach', [WidgetController::class, 'detach']);
    Route::get('/widgets/{id}', [WidgetController::class, 'show']);
    Route::apiResource('/widgets/{widget_id}/fields', FieldWidgetController::class);
    Route::get('/book-authors', [BookAuthorController::class, 'index']);
});
// custom routes:
Route::post('contact-us', [ContactController::class, 'submitContactForm'])->middleware('throttle:5,1');
