<?php

namespace App\Http\Controllers\Api;

use App\Models\BookingReservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingAppointmentController extends Controller
{
    /**
     * List appointments (reservations) for a user identified by email.
     *
     * Endpoint: GET /api/booking/appointments?email=user@example.com
     * Auth: Public - No authentication required.
     *
     * Query Parameters:
     * - email (string, required): The user's email address. Used to filter reservations via related user model.
     *
     * Behavior:
     * - If `email` is missing, returns 400 with { "error": "Email is required" }.
     * - Eager loads associated time slot via `with('timeSlot')`.
     *
     * Success Response (200): Array of reservation objects
     * [
     *   {
     *     "id": int,
     *     "user_id": int,
     *     "time_slot_id": int,
     *     "status": string,
     *     "created_at": "ISO8601",
     *     "updated_at": "ISO8601",
     *     "time_slot": { ... time slot fields ... }
     *   }
     * ]
     *
     * Error Response (400): { "error": "Email is required" }
     */
    public function index(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return response()->json(['error' => 'Email is required'], 400);
        }

        $appointments = BookingReservation::whereHas('user', function ($query) use ($email) {
            $query->where('email', $email);
        })->with('timeSlot')->get();

        return response()->json($appointments);
    }
}