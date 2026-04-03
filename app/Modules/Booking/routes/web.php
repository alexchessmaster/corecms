<?php

use App\Modules\Booking\Http\Controllers\BookingReservationController;
use App\Modules\Booking\Http\Controllers\BookingSlotTemplateController;
use App\Modules\Booking\Http\Controllers\BookingTimeSlotController;
use Illuminate\Support\Facades\Route;
use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('booking-slot-templates', BookingSlotTemplateController::class);
    Route::post('booking-slot-templates-toggle', [BookingSlotTemplateController::class, 'toggleActive'])->name('booking-slot-templates.toggle');
    Route::resource('booking-time-slots', BookingTimeSlotController::class);
    Route::post('booking-time-slots/{id}/toggle', [BookingTimeSlotController::class, 'toggleActive'])->name('booking-time-slots.toggle');
    Route::post('booking-time-slots/{id}/toggle-disable', [BookingTimeSlotController::class, 'toggleManualDisable'])->name('booking-time-slots.toggle-disable');
    Route::get('booking-reservations/calendar', [BookingReservationController::class, 'calendar'])->name('booking-reservations.calendar');
    Route::get('booking-reservations/{id}/details', [BookingReservationController::class, 'details'])->name('booking-reservations.details');
    Route::resource('booking-reservations', BookingReservationController::class);
});
