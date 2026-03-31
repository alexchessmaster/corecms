<?php

use App\Modules\Books\Http\Controllers\Api\V1\BookAuthorController;
use App\Modules\Books\Http\Controllers\Api\V1\BookController;
use App\Modules\Books\Http\Controllers\Api\V1\BookGenreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.v1.')->group(function () {
    Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
    Route::put('/{id}/{lang}', [BookController::class, 'removeBookLanguage'])->name('books.removeBookLanguage');
    Route::get('/bookgenres', [BookGenreController::class, 'show'])->name('bookgenres.show');
    Route::get('/book-authors', [BookAuthorController::class, 'index'])->name('book-authors.index');
});
