<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingTimeSlot;
use Illuminate\Http\Request;

class BookingAvailabilityController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $date = $request->input('date');
        $timeSlots = BookingTimeSlot::where('date', $date)
            ->where('availability', 'available')
            ->get();

        return response()->json($timeSlots);
    }
}