<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingReservation;
use App\Models\BookingTimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingReservationController extends Controller
{
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

        if ($timeSlot->reservations()->count() >= 2) {
            return response()->json(['message' => 'Time slot is fully booked.'], 400);
        }

        $reservation = BookingReservation::create([
            'user_id' => $request->user_id,
            'time_slot_id' => $request->time_slot_id,
            'status' => 'booked',
        ]);

        return response()->json($reservation, 201);
    }
}