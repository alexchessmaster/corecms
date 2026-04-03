<?php

use App\Modules\Forms\Http\Controllers\Api\FormContactUsController;
use App\Modules\Forms\Http\Controllers\Api\FormNewsletterController;
use App\Modules\Languages\Http\Middleware\LanguageApiMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.v1.')->group(function () {
    Route::prefix('contact-us')->middleware([LanguageApiMiddleware::class, 'throttle:10,1'])->group(function () {
        // Contact us - Public
        Route::post('', [FormContactUsController::class, 'store']);
        // Admin only
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/', [FormContactUsController::class, 'index']);
            Route::get('/statistics', [FormContactUsController::class, 'statistics']);
            Route::get('/{uuid}', [FormContactUsController::class, 'show']);
            Route::patch('/{uuid}/status', [FormContactUsController::class, 'updateStatus']);
            Route::delete('/{uuid}', [FormContactUsController::class, 'destroy']);
        });
    });
});

Route::prefix('newsletter')->middleware([LanguageApiMiddleware::class, 'throttle:10,1'])->group(function () {
    // Newsletter - Public
    Route::post('/subscribe', [FormNewsletterController::class, 'store']);
    Route::get('/verify/{token}', [FormNewsletterController::class, 'verify']);
    Route::post('/unsubscribe/{email}', [FormNewsletterController::class, 'unsubscribe']);
    Route::get('/status/{email}', [FormNewsletterController::class, 'status']);
});
