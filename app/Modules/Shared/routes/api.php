<?php

use App\Modules\Insights\Http\Middleware\LogVisitedUrlMiddleware;
use App\Modules\Shared\Http\Controllers\Api\V1\ContentController;
use App\Modules\Shared\Http\Middleware\CacheControlHeaderMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([
    // 'throttle:2000,1'
])->prefix('api')->name('api.')->group(function () {
    Route::get('/fetch-content', [ContentController::class, 'fetchContent'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    
    Route::get('/fetch-menu', [ContentController::class, 'fetchMenu']);
    Route::get('/fetch-languages', [ContentController::class, 'fetchLanguages']);
    Route::get('/fetch-settings', [ContentController::class, 'fetchSettings']);
    Route::get('/fetch-translations', [ContentController::class, 'fetchTranslations']);
    Route::get('/fetch-authors', [ContentController::class, 'fetchAuthors']);
    
    Route::get('/fetch-books', [ContentController::class, 'fetchBooks'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-book-genres', [ContentController::class, 'fetchBookGenres']);
    Route::get('/fetch-book-comments', [ContentController::class, 'fetchBookComments']);
    Route::post('/store-book-comments', [ContentController::class, 'storeBookComments'])->middleware('throttle:1,1');
    
    Route::get('/fetch-articles', [ContentController::class, 'fetchArticles'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-article-categories', [ContentController::class, 'fetchArticleCategories']);
    Route::get('/fetch-article-comments', [ContentController::class, 'fetchArticleComments']);
    Route::post('/store-article-comments', [ContentController::class, 'storeArticleComments'])->middleware('throttle:10,1');

    Route::get('/fetch-news', [ContentController::class, 'fetchNews'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-news-categories', [ContentController::class, 'fetchNewsCategories']);
    Route::get('/fetch-news-comments', [ContentController::class, 'fetchNewsComments']);
    Route::post('/store-news-comments', [ContentController::class, 'storeNewsComments'])->middleware('throttle:10,1');
});

// with auth
Route::middleware(['auth:sanctum'])->prefix('api')->name('api.')->group(function () {
    //
});
