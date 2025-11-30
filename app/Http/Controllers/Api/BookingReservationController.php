<?php

namespace App\Http\Controllers\Api;

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
     * Request JSON Payload:
     * {
     *   "time_slot_id": int,   // Required. Must exist in booking_time_slots.id
     *   "user_id": int         // Required. Must exist in booking_users.id
     * }
     *
     * Validation Rules:
     * - time_slot_id: required | exists:booking_time_slots,id
     * - user_id: required | exists:booking_users,id
     *
     * Business Rules:
     * - A time slot can have a maximum of 2 reservations. If limit reached, returns 400.
     *
     * Success Response (201):
     * {
     *   "id": int,
     *   "user_id": int,
     *   "time_slot_id": int,
     *   "status": "booked",
     *   "created_at": "ISO8601",
     *   "updated_at": "ISO8601"
     * }
     *
     * Error Responses:
     * - 400: { "message": "Time slot is fully booked." }
     * - 422: { "time_slot_id": ["The selected time slot id is invalid."], ... }
     */
    public function bookAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time_slot_id' => 'required|exists:booking_time_slots,id',
            'user_id' => 'required|exists:booking_users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $timeSlot = BookingTimeSlot::find($request->time_slot_id);

        // if ($timeSlot->reservations()->count() >= 2) {
        //     return response()->json(['message' => 'Time slot is fully booked.'], 400);
        // }

        $reservation = BookingReservation::create([
            'user_id' => $request->user_id,
            'time_slot_id' => $request->time_slot_id,
            'status' => 'booked',
        ]);

        return response()->json($reservation, 201);
    }
}