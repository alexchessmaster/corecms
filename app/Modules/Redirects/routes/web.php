<?php

use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Redirects\Http\Controllers\Admin\RedirectController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('redirects', RedirectController::class);
});
