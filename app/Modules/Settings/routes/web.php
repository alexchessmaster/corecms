<?php

use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Settings\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('settings', SettingController::class);
});
