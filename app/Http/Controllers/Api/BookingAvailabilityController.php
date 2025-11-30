<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingTimeSlot;
use Illuminate\Http\Request;

class BookingAvailabilityController extends Controller
{
    /**
     * Retrieve available booking time slots for a given date.
     *
     * Endpoint: GET /api/booking/availability?date=YYYY-MM-DD
     * Public: No authentication required (declared under public booking routes).
     *
     * Query Parameters:
     * - date (string, required): Format YYYY-MM-DD. Filters time slots by calendar date.
     *
     * Behavior:
     * - Returns only time slots where `availability` column equals 'available'.
     * - If date is missing, currently returns empty collection (no explicit validation). Consider adding validation if needed.
     *
     * Success Response (200): Array of time slot objects
     * [
     *   {
     *     "id": int,
     *     "date": "YYYY-MM-DD",
     *     "start_time": "HH:MM:SS",
     *     "end_time": "HH:MM:SS",
     *     "availability": "available",
     *     ... other attributes ...
     *   }
     * ]
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->input('date');
        $timeSlots = BookingTimeSlot::where('date', $date)
            ->where('availability', 'available')
            ->get();

        return response()->json($timeSlots);
    }
}