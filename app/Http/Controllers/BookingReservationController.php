<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookingReservation;
use App\Models\BookingTimeSlot;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingReservationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of booking reservations.
     */
    public function index()
    {
        $this->authorize('viewAny', BookingReservation::class);
        
        $reservations = BookingReservation::with(['user', 'timeSlot'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.booking-reservation.index', compact('reservations'));
    }

    /**
     * Display calendar view of booking reservations.
     */
    public function calendar(Request $request)
    {
        $this->authorize('viewAny', BookingReservation::class);
        
        $view = $request->get('view', 'today'); // today, tomorrow, week, month
        $date = $request->get('date', now()->format('Y-m-d'));
        
        $startDate = Carbon::parse($date)->startOfDay();
        
        switch ($view) {
            case 'tomorrow':
                $startDate = Carbon::parse($date)->addDay()->startOfDay();
                $endDate = $startDate->copy()->endOfDay();
                break;
            case 'today_tomorrow':
                $endDate = $startDate->copy()->addDay()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::parse($date)->startOfWeek();
                $endDate = $startDate->copy()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::parse($date)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                break;
            default: // today
                $endDate = $startDate->copy()->endOfDay();
                break;
        }
        
        $reservations = BookingReservation::with(['user', 'timeSlot'])
            ->whereHas('timeSlot', function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_time', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy(function($reservation) {
                return $reservation->timeSlot->start_time->format('Y-m-d');
            });
        
        return view('admin.booking-reservation.calendar', compact('reservations', 'view', 'startDate', 'endDate'));
    }

    /**
     * Get reservation details for modal (AJAX).
     */
    public function details($id)
    {
        $reservation = BookingReservation::with(['user', 'timeSlot'])->findOrFail($id);
        $this->authorize('view', $reservation);
        
        return response()->json($reservation);
    }

    /**
     * Show the form for creating a new booking reservation.
     */
    public function create()
    {
        $this->authorize('create', BookingReservation::class);
        
        $timeSlots = BookingTimeSlot::where('is_active', true)
            ->orderBy('start_time', 'asc')
            ->get();
        $users = User::orderBy('name', 'asc')->get();
        return view('admin.booking-reservation.create', compact('timeSlots', 'users'));
    }

    /**
     * Store a newly created booking reservation.
     */
    public function store(Request $request)
    {
        $this->authorize('create', BookingReservation::class);
        
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'booking_time_slot_id' => 'required|exists:booking_time_slots,id',
            'status' => 'required|in:pending,confirmed,cancelled',
            'expires_at' => 'nullable|date',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1|max:150',
            'service' => 'nullable|string|max:255',
            'comments' => 'nullable|string|max:1000',
        ]);

        $reservation = BookingReservation::create($validated);

        return redirect()->route('admin.booking-reservations.index')
            ->with('success', 'Booking reservation created successfully.');
    }

    /**
     * Display the specified booking reservation.
     */
    public function show($id)
    {
        $reservation = BookingReservation::with(['user', 'timeSlot'])->findOrFail($id);
        $this->authorize('view', $reservation);
        
        return view('admin.booking-reservation.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified booking reservation.
     */
    public function edit($id)
    {
        $reservation = BookingReservation::findOrFail($id);
        $this->authorize('view', $reservation);
        
        $timeSlots = BookingTimeSlot::where('is_active', true)
            ->orderBy('start_time', 'asc')
            ->get();
        $users = User::orderBy('name', 'asc')->get();
        return view('admin.booking-reservation.edit', compact('reservation', 'timeSlots', 'users'));
    }

    /**
     * Update the specified booking reservation.
     */
    public function update(Request $request, $id)
    {
        $reservation = BookingReservation::findOrFail($id);
        $this->authorize('update', $reservation);
        
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'booking_time_slot_id' => 'required|exists:booking_time_slots,id',
            'status' => 'required|in:pending,confirmed,cancelled',
            'expires_at' => 'nullable|date',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1|max:150',
            'service' => 'nullable|string|max:255',
            'comments' => 'nullable|string|max:1000',
        ]);

        $reservation->update($validated);

        return redirect()->route('admin.booking-reservations.index')
            ->with('success', 'Booking reservation updated successfully.');
    }

    /**
     * Remove the specified booking reservation.
     */
    public function destroy($id)
    {
        $reservation = BookingReservation::findOrFail($id);
        $this->authorize('delete', $reservation);
        
        $reservation->delete();
        
        return redirect()->route('admin.booking-reservations.index')
            ->with('success', 'Booking reservation deleted successfully.');
    }
}
