<?php

use App\Modules\Languages\Http\Controllers\Admin\LanguageController;
use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('languages', LanguageController::class);
});
