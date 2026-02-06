<?php

namespace App\Modules\Booking\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BookingReservation;
use App\Models\BookingTimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingReservationController extends Controller
{
    /**
     * Book an appointment (create a reservation) for a specific time slot.
     *
     * Endpoint: POST /api/booking/reservations
     * Auth: Public - No authentication required.
     *
    * When to use:
    * - Visitors or clients booking a time slot shown by the availability endpoint.
    *
    * How to use:
    * - Request: POST /api/booking/reservations
    * - Content-Type: application/json
    * - Example curl:
    *   curl -H "Content-Type: application/json" \
    *        -d '{
    *              "booking_time_slot_id": 42,
    *              "name": "Jane Doe",
    *              "email": "jane@example.com",
    *              "mobile_number": "+4512345678",
    *              "service": "Consultation",
    *              "age": 34,
    *              "comments": "Prefer a quiet room"
    *            }' \
    *        -X POST "https://<host>/api/booking/reservations"
    *
    * Request JSON Payload:
     * {
     *   "booking_time_slot_id": int,   // Required. Must exist in booking_time_slots.id
     *   "name": string,                 // Required. Customer name
     *   "email": string,                // Required. Customer email
     *   "mobile_number": string,        // Required. Customer phone
     *   "service": string,              // Required. Service being booked
     *   "age": int,                     // Optional. Customer age
     *   "comments": string,             // Optional. Additional notes
     *   "user_id": int                  // Optional. If logged in user
     * }
     *
     * Validation Rules:
     * - booking_time_slot_id: required | exists:booking_time_slots,id
     * - name: required | string | max:255
     * - email: required | email | max:255
     * - mobile_number: required | string | max:255
     * - service: required | string | max:255
     * - age: nullable | integer | min:0 | max:120
     * - comments: nullable | string | max:1000
     * - user_id: nullable | exists:users,id
     *
     * Business Rules:
     * - Time slot must be active and not manually disabled
     * - Time slot cannot exceed max_capacity
     * - Creates pending reservation with 15-minute expiration
     *
     * Success Response (201):
     * {
     *   "id": int,
     *   "booking_time_slot_id": int,
     *   "name": string,
     *   "email": string,
     *   "mobile_number": string,
     *   "service": string,
     *   "status": "pending",
     *   "expires_at": "ISO8601",
     *   "created_at": "ISO8601",
     *   "updated_at": "ISO8601"
     * }
     *
     * Error Responses:
     * - 400: { "message": "Time slot is not available." }
     * - 422: { "email": ["The email field is required."], ... }
     */
    public function bookAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_time_slot_id' => 'required|exists:booking_time_slots,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'comments' => 'nullable|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $timeSlot = BookingTimeSlot::find($request->booking_time_slot_id);

        // Check if slot is available
        if (!$timeSlot->isAvailable()) {
            return response()->json(['message' => 'Time slot is not available.'], 400);
        }

        // Create pending reservation with 15-minute expiration
        $reservation = BookingReservation::create([
            'user_id' => $request->user_id,
            'booking_time_slot_id' => $request->booking_time_slot_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'service' => $request->service,
            'age' => $request->age,
            'comments' => $request->comments,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(15),
        ]);

        return response()->json($reservation, 201);
    }
}
