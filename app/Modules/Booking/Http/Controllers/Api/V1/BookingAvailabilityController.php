<?php

namespace App\Modules\Booking\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BookingTimeSlot;
use Illuminate\Http\Request;

class BookingAvailabilityController extends Controller
{
    /**
    * Retrieve available booking time slots for a given date.
     *
     * Endpoint: GET /api/booking/availability?date=YYYY-MM-DD
     * Public: No authentication required.
     *
     * Query Parameters:
     * - date (string, required): Format YYYY-MM-DD. Filters time slots by calendar date.
     *
     * Behavior:
     * - Returns only active time slots that are not manually disabled
     * - Checks capacity against confirmed reservations
     * - Filters by date matching the start_time
     * - Includes time slot details and available capacity
     *
    * When to use:
    * - Visitors selecting an appointment date need to see available time slots.
    *
    * How to use:
    * - Request: GET /api/booking/availability?date=2025-12-01
    * - Example curl:
    *   curl -X GET "https://<host>/api/booking/availability?date=2025-12-01"
    *
     * Success Response (200): Array of available time slot objects
     * [
     *   {
     *     "id": int,
     *     "start_time": "YYYY-MM-DD HH:MM:SS",
     *     "end_time": "YYYY-MM-DD HH:MM:SS",
     *     "max_capacity": int,
     *     "available_capacity": int,
     *     "is_active": boolean,
     *     ... other attributes ...
     *   }
     * ]
     *
     * Error Response (400): { "error": "Date is required" }
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $timeSlots = BookingTimeSlot::whereDate('start_time', $date)
            ->where('is_active', true)
            ->where('is_manually_disabled', false)
            ->with('confirmedReservations')
            ->get()
            ->filter(function ($slot) {
                return $slot->availableCapacity() > 0;
            })
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'max_capacity' => $slot->max_capacity,
                    'available_capacity' => $slot->availableCapacity(),
                    'is_active' => $slot->is_active,
                ];
            })
            ->values();

        return response()->json($timeSlots);
    }
}
