<?php

use App\Http\Controllers\LanguageController;
use App\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Users\Http\Controllers\Admin\AuthorController;
use App\Modules\Users\Http\Controllers\Admin\PermissionController;
use App\Modules\Users\Http\Controllers\Admin\ProfileController;
use App\Modules\Users\Http\Controllers\Admin\RoleController;
use App\Modules\Users\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('authors/select', [AuthorController::class, 'selectAuthor'])->name('authors.select-author');
    Route::resource('authors', AuthorController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::post('user-locale', [LanguageController::class, 'setUserLocale'])->name('user-locale');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
