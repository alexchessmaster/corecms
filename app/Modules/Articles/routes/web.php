<?php

use App\Modules\Languages\Http\Controllers\Admin\LanguageController;
use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Articles\Http\Controllers\Admin\ArticleController;
use App\Modules\Articles\Http\Controllers\Admin\CategoryController;
use App\Modules\Articles\Http\Controllers\Admin\TagController;
use App\Modules\Users\Http\Controllers\Admin\AuthorController;
use App\Modules\Users\Http\Controllers\Admin\PermissionController;
use App\Modules\Users\Http\Controllers\Admin\ProfileController;
use App\Modules\Users\Http\Controllers\Admin\RoleController;
use App\Modules\Users\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('articles', ArticleController::class);
    Route::resource('tags', TagController::class);
    Route::get('article-tags/select', [TagController::class, 'selectTags'])->name('article-tags.select-tags');
});
