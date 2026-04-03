<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Middleware\LanguageApiMiddleware;
use App\Modules\Articles\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContentController;
use App\Modules\Shared\Http\Controllers\Api\V2\ContentController as ContentControllerV1;
use App\Modules\Articles\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\LogVisitedUrlMiddleware;
use App\Http\Controllers\Api\CommonDataController;
use App\Http\Controllers\Api\WidgetableController;
use App\Http\Controllers\Api\FieldWidgetController;
use App\Http\Controllers\Api\FormContactUsController;
use App\Http\Middleware\CacheControlHeaderMiddleware;
use App\Http\Controllers\Api\FormNewsletterController;
use App\Http\Controllers\Api\WidgetFieldValuesController;
use App\Modules\Shared\Http\Middleware\IncreaseMemoryLimitMiddleware;

// Used in the frontend
// Deprecated. These routes are for the existing frontend apps. For new apps use: App/Modules/Shared/routes/api.php

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([
    // 'throttle:2000,1'
])->group(function () {
    Route::get('/fetch-menu', [ContentController::class, 'fetchMenu']);
    Route::get('/fetch-languages', [ContentController::class, 'fetchLanguages']);
    Route::get('/fetch-settings', [ContentController::class, 'fetchSettings']);
    Route::get('/fetch-translations', [ContentController::class, 'fetchTranslations']);
    Route::get('/fetch-content', [ContentController::class, 'fetchContent'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-books', [ContentController::class, 'fetchBooks'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-book-genres', [ContentController::class, 'fetchBookGenres']);
    Route::get('/fetch-authors', [ContentController::class, 'fetchAuthors']);
    Route::get('/fetch-book-comments', [ContentController::class, 'fetchBookComments']);
    Route::post('/store-book-comments', [ContentController::class, 'storeBookComments'])->middleware('throttle:1,1');

    Route::get('/fetch-news', [ContentController::class, 'fetchNews'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-news-categories', [ContentController::class, 'fetchNewsCategories']);
    Route::get('/fetch-news-comments', [ContentController::class, 'fetchNewsComments']);
    Route::post('/store-news-comments', [ContentController::class, 'storeNewsComments'])->middleware('throttle:10,1');

    // Route::get('/fetch-articles', [ContentController::class, 'fetchArticles']); // later
    // Route::get('/fetch-categories', [ContentController::class, 'fetchCategories']); // later
});


// Used in admin-panel
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/pages', PageController::class);

    Route::apiResource('/categories', CategoryController::class);
    Route::patch('/widget-field-values', [WidgetFieldValuesController::class, 'update'])->middleware([IncreaseMemoryLimitMiddleware::class]);
    Route::patch('/widgets/attach', [WidgetController::class, 'attach']);
    Route::patch('/widgets/detach', [WidgetController::class, 'detach']);
    Route::get('/widgets/{id}', [WidgetController::class, 'show']);
    Route::apiResource('/widgets/{widget_id}/fields', FieldWidgetController::class);
//    Route::get('/book-authors', [BookAuthorController::class, 'index']);
});

// custom routes:
Route::prefix('contact-us')->middleware([LanguageApiMiddleware::class, 'throttle:10,1'])->group(function () {
    // Contact us - Public
    Route::post('', [FormContactUsController::class, 'store']);
    // Admin only
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('', [FormContactUsController::class, 'index']);
        Route::get('/statistics', [FormContactUsController::class, 'statistics']);
        Route::get('/{uuid}', [FormContactUsController::class, 'show']);
        Route::patch('/{uuid}/status', [FormContactUsController::class, 'updateStatus']);
        Route::delete('/{uuid}', [FormContactUsController::class, 'destroy']);
    });
});
Route::prefix('newsletter')->middleware([LanguageApiMiddleware::class, 'throttle:10,1'])->group(function () {
    // Newsletter - Public
    Route::post('/subscribe', [FormNewsletterController::class, 'store']);
    Route::get('/verify/{token}', [FormNewsletterController::class, 'verify']);
    Route::post('/unsubscribe/{email}', [FormNewsletterController::class, 'unsubscribe']);
    Route::get('/status/{email}', [FormNewsletterController::class, 'status']);
});


// Booking System Routes
