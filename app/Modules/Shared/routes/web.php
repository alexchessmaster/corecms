<?php

use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Shared\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    abort(403);
});
Route::get('admin', function () {
    return view('shared::dashboard');
})->middleware(['web', LanguageAdminMiddleware::class, 'auth', 'verified']);
Route::get('dashboard', function () {
    return view('shared::dashboard');
})->middleware(['web', LanguageAdminMiddleware::class, 'auth', 'verified'])->name('dashboard');

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('upload', UploadController::class);
});
