<?php

use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Widgets\Http\Controllers\Admin\FieldController;
use App\Modules\Widgets\Http\Controllers\Admin\WidgetController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('widgets', WidgetController::class);
    Route::post('widgets/sort', [WidgetController::class, 'sort'])->name('widgets.sort');
    Route::resource('fields', FieldController::class);
});
