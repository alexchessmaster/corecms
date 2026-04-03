<?php

use App\Modules\Comments\Http\Controllers\Admin\CommentableController;
use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('comments', CommentableController::class);

});
