<?php

use App\Http\Controllers\LanguageController;
use App\Http\Middleware\LanguageAdminMiddleware;
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
});
