<?php

namespace App\Http\Controllers\Api;

use App\Models\BookingReservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingAppointmentController extends Controller
{
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