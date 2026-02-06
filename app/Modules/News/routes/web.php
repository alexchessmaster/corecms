<?php

use App\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\News\Http\Controllers\Admin\NewsController;
use App\Modules\News\Http\Controllers\Admin\NewsTagController;
use App\Modules\News\Http\Controllers\Admin\NewsAuthorController;
use App\Modules\News\Http\Controllers\Admin\NewsCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('/news', NewsController::class);
    Route::resource('/news-categories', NewsCategoryController::class);
    Route::get('news-tags/select', [NewsTagController::class, 'selectTags'])->name('new.select-tags');
    Route::resource('/news-tags', NewsTagController::class);
    Route::get('news-authors/select', [NewsAuthorController::class, 'selectAuthor'])->name('new.select-author');
    Route::resource('/news-authors', NewsAuthorController::class);
});
