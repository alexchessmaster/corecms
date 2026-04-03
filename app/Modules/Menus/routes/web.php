<?php

use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Menus\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('menus', MenuController::class);
});
