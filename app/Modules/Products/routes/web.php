<?php

use App\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Products\Http\Controllers\Admin\ProductController;
use App\Modules\Products\Http\Controllers\Admin\ProductTagController;
use App\Modules\Products\Http\Controllers\Admin\ProductAuthorController;
use App\Modules\Products\Http\Controllers\Admin\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('product-categories', ProductCategoryController::class);
    Route::get('product-tags/select', [ProductTagController::class, 'selectTags'])->name('product-tags.select-tags');
    Route::resource('product-tags', ProductTagController::class);
    Route::get('product-authors/select', [ProductAuthorController::class, 'selectAuthor'])->name('product-authors.select-author');
    Route::resource('product-authors', ProductAuthorController::class);
});
