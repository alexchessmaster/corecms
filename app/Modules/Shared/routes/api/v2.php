<?php

// API v1
use App\Http\Middleware\CacheControlHeaderMiddleware;
use App\Http\Middleware\LogVisitedUrlMiddleware;
use App\Modules\Shared\Http\Controllers\Api\V2\ContentController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    // 'throttle:2000,1'
])->prefix('/v2')->name('.v2')->group(function () {
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
