<?php

namespace App\Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Booking\Models\BookingTimeSlot;
use App\Modules\Booking\Models\BookingSlotTemplate;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingTimeSlotController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of time slots.
     */
    public function index()
    {
        $this->authorize('viewAny', BookingTimeSlot::class);

        $timeSlots = BookingTimeSlot::with('template')->orderBy('start_time', 'desc')->get();
        return view('bookings::booking-time-slot.index', compact('timeSlots'));
    }

    /**
     * Show the form for creating a new time slot.
     */
    public function create()
    {
        $this->authorize('create', BookingTimeSlot::class);

        $templates = BookingSlotTemplate::where('is_active', true)->get();
        return view('bookings::booking-time-slot.create', compact('templates'));
    }

    /**
     * Store a newly created time slot.
     */
    public function store(Request $request)
    {
        $this->authorize('create', BookingTimeSlot::class);

        $validated = $request->validate([
            'template_id' => 'required|exists:booking_slot_templates,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_capacity' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'is_manually_disabled' => 'required|boolean',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $timeSlot = BookingTimeSlot::create($validated);

        return redirect()->route('bookings::booking-time-slots.index')->with('success', 'Time slot created successfully.');
    }

    /**
     * Display the specified time slot.
     */
    public function show($id)
    {
        $timeSlot = BookingTimeSlot::with(['template', 'reservations'])->findOrFail($id);
        $this->authorize('view', $timeSlot);

        return view('bookings::booking-time-slot.show', compact('timeSlot'));
    }

    /**
     * Show the form for editing the specified time slot.
     */
    public function edit($id)
    {
        $timeSlot = BookingTimeSlot::findOrFail($id);
        $this->authorize('view', $timeSlot);

        $templates = BookingSlotTemplate::where('is_active', true)->get();
        return view('bookings::booking-time-slot.edit', compact('timeSlot', 'templates'));
    }

    /**
     * Update the specified time slot.
     */
    public function update(Request $request, $id)
    {
        $timeSlot = BookingTimeSlot::findOrFail($id);
        $this->authorize('update', $timeSlot);

        $validated = $request->validate([
            'template_id' => 'required|exists:booking_slot_templates,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_capacity' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'is_manually_disabled' => 'required|boolean',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $timeSlot->update($validated);

        return redirect()->route('bookings::booking-time-slots.index')->with('success', 'Time slot updated successfully.');
    }

    /**
     * Remove the specified time slot.
     */
    public function destroy($id)
    {
        $timeSlot = BookingTimeSlot::findOrFail($id);
        $this->authorize('delete', $timeSlot);

        $timeSlot->delete();

        return redirect()->route('bookings::booking-time-slots.index')->with('success', 'Time slot deleted successfully.');
    }

    /**
     * Toggle active status of a time slot.
     */
    public function toggleActive($id)
    {
        $timeSlot = BookingTimeSlot::findOrFail($id);
        $this->authorize('update', $timeSlot);

        $timeSlot->is_active = !$timeSlot->is_active;
        $timeSlot->save();

        return redirect()->route('bookings::booking-time-slots.index')->with('success', 'Time slot status updated.');
    }

    /**
     * Toggle manual disable status of a time slot.
     */
    public function toggleManualDisable($id)
    {
        $timeSlot = BookingTimeSlot::findOrFail($id);
        $this->authorize('update', $timeSlot);

        $timeSlot->is_manually_disabled = !$timeSlot->is_manually_disabled;
        $timeSlot->save();

        return redirect()->route('bookings::booking-time-slots.index')->with('success', 'Time slot manual disable status updated.');
    }
}
