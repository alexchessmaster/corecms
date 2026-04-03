<?php

use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Books\Http\Controllers\Admin\BookGenreController;
use App\Modules\Books\Http\Controllers\Admin\BookAuthorController;
use Illuminate\Support\Facades\Route;
use App\Modules\Books\Http\Controllers\Admin\BookController;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('book_genres', BookGenreController::class);
    Route::resource('book-authors', BookAuthorController::class);
    Route::resource('books', BookController::class);
});
