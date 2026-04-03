<?php

use App\Modules\Languages\Http\Middleware\LanguageApiMiddleware;
use App\Modules\Booking\Http\Controllers\Api\V1\BookingAppointmentController;
use App\Modules\Booking\Http\Controllers\Api\V1\BookingAvailabilityController;
use App\Modules\Booking\Http\Controllers\Api\V1\BookingReservationController;
use App\Modules\Booking\Http\Controllers\BookingSlotTemplateController;
use App\Modules\Booking\Http\Middleware\BookingAdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([
    // 'throttle:2000,1'
])->group(function () {

    Route::prefix('booking')->middleware([LanguageApiMiddleware::class, 'throttle:100,1'])->group(function () {
        // Admin routes (require authentication)
        Route::middleware(['auth:sanctum', BookingAdminMiddleware::class])->group(function () {
            Route::get('/reservations', [BookingReservationController::class, 'index']);
            Route::get('/reservations/today', [BookingReservationController::class, 'today']);
            Route::get('/reservations/week', [BookingReservationController::class, 'week']);
            Route::get('/reservations/month', [BookingReservationController::class, 'month']);

            // Slot Template management (CRUD + toggle)
            Route::apiResource('/templates', BookingSlotTemplateController::class);
            Route::post('/templates/{id}/toggle', [BookingSlotTemplateController::class, 'toggleActive']);
        });

        // Public booking routes (no auth required)
        Route::get('/availability', [BookingAvailabilityController::class, 'checkAvailability']); // GET /api/booking/availability?date=YYYY-MM-DD
        Route::get('/appointments', [BookingAppointmentController::class, 'index']); // GET /api/booking/appointments?email=
        Route::post('/reservations', [BookingReservationController::class, 'bookAppointment']); // POST /api/booking/reservations
        // Future endpoints (not yet implemented in controller):
        // Route::patch('/appointments/{id}/cancel', [BookingAppointmentController::class, 'cancel']);
    });

});
