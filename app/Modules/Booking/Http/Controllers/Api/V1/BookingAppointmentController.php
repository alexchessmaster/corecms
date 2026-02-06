<?php

namespace App\Modules\Booking\Http\Controllers\Api\V1;

use App\Models\BookingReservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingAppointmentController extends Controller
{
    /**
    * List appointments (reservations) for a guest identified by email or mobile number.
     *
     * Endpoint: GET /api/booking/appointments?email=user@example.com
     *           GET /api/booking/appointments?mobile_number=1234567890
     * Auth: Public - No authentication required.
     *
     * Query Parameters:
     * - email (string, optional): The guest's email address
     * - mobile_number (string, optional): The guest's mobile number
     * - At least one must be provided
     *
     * Behavior:
     * - Searches reservations by email or mobile_number directly (no user table join needed)
     * - Eager loads associated time slot
     * - Returns all matching reservations regardless of status
     *
     * Success Response (200): Array of reservation objects
     * [
     *   {
     *     "id": int,
     *     "user_id": int|null,
     *     "booking_time_slot_id": int,
     *     "name": string,
     *     "email": string,
     *     "mobile_number": string,
     *     "service": string,
     *     "status": string,
     *     "created_at": "ISO8601",
     *     "updated_at": "ISO8601",
     *     "time_slot": { ... time slot fields ... }
     *   }
     * ]
     *
    * When to use:
    * - Guests want to review their existing bookings using email or phone.
    *
    * How to use:
    * - Request: GET /api/booking/appointments?email=user@example.com
    * - Request: GET /api/booking/appointments?mobile_number=+4512345678
    * - Example curl:
    *   curl -X GET "https://<host>/api/booking/appointments?email=user@example.com"
    *
     * Error Response (400): { "error": "Email or mobile number is required" }
     */
    public function index(Request $request)
    {
        $email = $request->query('email');
        $mobileNumber = $request->query('mobile_number');

        if (!$email && !$mobileNumber) {
            return response()->json(['error' => 'Email or mobile number is required'], 400);
        }

        $query = BookingReservation::query();

        if ($email) {
            $query->where('email', $email);
        }

        if ($mobileNumber) {
            $query->orWhere('mobile_number', $mobileNumber);
        }

        $appointments = $query->with('timeSlot')->get();

        return response()->json($appointments);
    }
}
