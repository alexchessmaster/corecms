<?php

use App\Modules\Insights\Http\Controllers\Admin\UrlLogController;
use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('url-logs/statistics', [UrlLogController::class, 'statistic'])->name('url-logs.statistics');
    Route::resource('url-logs', UrlLogController::class);
});
