<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookingSlotTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingSlotTemplateController extends Controller
{
    /**
     * Display a listing of slot templates.
     *
     * When to use:
     * - Admin UI or API clients that need to view all booking slot templates.
     *
     * How to use:
     * - Request: GET /api/booking/templates
     * - Auth: Requires `auth:sanctum` and BookingAdmin access (route-level middleware).
     *
     * Example:
     * - curl:
     *   curl -H "Authorization: Bearer <token>" \
     *        -X GET "https://<host>/api/booking/templates"
     */
    public function index()
    {
        $templates = BookingSlotTemplate::orderBy('created_at', 'desc')->get();
        return view('admin.booking-slot-template.index', compact('templates'));
    }

    /**
     * Store a newly created slot template.
     *
     * When to use:
     * - Admins create a reusable schedule template that generates daily time slots.
     *
     * How to use:
     * - Request: POST /api/booking/templates
     * - Auth: Requires `auth:sanctum` and BookingAdmin access (route-level middleware).
     * - Content-Type: application/json
     *
     * Example payload:
     * {
     *   "name": "Weekday Mornings",
     *   "days_of_week": [1,2,3,4,5],
     *   "start_time": "08:00:00",
     *   "end_time": "12:00:00",
     *   "slot_duration_minutes": 30,
     *   "max_capacity": 4,
     *   "valid_from": "2025-12-01",
     *   "valid_until": "2026-03-31",
     *   "is_active": true,
     *   "description": "Morning slots for weekdays"
     * }
     *
     * - curl:
     *   curl -H "Authorization: Bearer <token>" -H "Content-Type: application/json" \
     *        -d '{
     *             "name":"Weekday Mornings",
     *             "days_of_week":[1,2,3,4,5],
     *             "start_time":"08:00:00",
     *             "end_time":"12:00:00",
     *             "slot_duration_minutes":30,
     *             "max_capacity":4,
     *             "valid_from":"2025-12-01",
     *             "valid_until":"2026-03-31",
     *             "is_active":true,
     *             "description":"Morning slots for weekdays"
     *           }' \
     *        -X POST "https://<host>/api/booking/templates"
     */
    public function create()
    {
        return view('admin.booking-slot-template.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'days_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_duration_minutes' => 'required|integer|min:15|max:480',
            'max_capacity' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['days_of_week'] = array_map('intval', explode(',', $validated['days_of_week']));

        $template = BookingSlotTemplate::create($validated);

        return redirect()->route('admin.booking-slot-templates.index')->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified slot template.
     *
     * When to use:
     * - Admins need to fetch one template for editing or inspection.
     *
     * How to use:
     * - Request: GET /api/booking/templates/{id}
     * - Auth: Requires `auth:sanctum` and BookingAdmin access.
     *
     * - curl:
     *   curl -H "Authorization: Bearer <token>" \
     *        -X GET "https://<host>/api/booking/templates/123"
     */
    public function show($id)
    {
        $template = BookingSlotTemplate::findOrFail($id);
        return view('admin.booking-slot-template.show', compact('template'));
    }

    /**
     * Update the specified slot template.
     *
     * When to use:
     * - Admins change parameters like active dates, capacity, or duration.
     *
     * How to use:
     * - Request: PATCH /api/booking/templates/{id}
     * - Auth: Requires `auth:sanctum` and BookingAdmin access.
     * - Content-Type: application/json
     *
     * Example payload (partial update):
     * {
     *   "end_time": "13:00:00",
     *   "slot_duration_minutes": 45,
     *   "description": "Extended into early afternoon"
     * }
     *
     * - curl:
     *   curl -H "Authorization: Bearer <token>" -H "Content-Type: application/json" \
     *        -d '{"end_time":"13:00:00","slot_duration_minutes":45}' \
     *        -X PATCH "https://<host>/api/booking/templates/123"
     */
    public function edit($id)
    {
        $template = BookingSlotTemplate::findOrFail($id);
        return view('admin.booking-slot-template.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = BookingSlotTemplate::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'days_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_duration_minutes' => 'required|integer|min:15|max:480',
            'max_capacity' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string|max:1000',
        ]);
        $validated['days_of_week'] = array_map('intval', explode(',', $validated['days_of_week']));
        $template->update($validated);
        return redirect()->route('admin.booking-slot-templates.index')->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified slot template.
     *
     * When to use:
     * - Admins want to permanently remove a template.
     *
     * How to use:
     * - Request: DELETE /api/booking/templates/{id}
     * - Auth: Requires `auth:sanctum` and BookingAdmin access.
     *
     * - curl:
     *   curl -H "Authorization: Bearer <token>" \
     *        -X DELETE "https://<host>/api/booking/templates/123"
     */
    public function destroy($id)
    {
        $template = BookingSlotTemplate::findOrFail($id);
        $template->delete();
        return redirect()->route('admin.booking-slot-templates.index')->with('success', 'Template deleted successfully.');
    }

    /**
     * Toggle active status of a template.
     *
     * When to use:
     * - Quickly enable/disable a template without deleting it.
     *
     * How to use:
     * - Request: POST /api/booking/templates/{id}/toggle
     * - Auth: Requires `auth:sanctum` and BookingAdmin access.
     *
     * - curl:
     *   curl -H "Authorization: Bearer <token>" \
     *        -X POST "https://<host>/api/booking/templates/123/toggle"
     */
    public function toggleActive($id)
    {
        $template = BookingSlotTemplate::findOrFail($id);
        $template->is_active = !$template->is_active;
        $template->save();
        return redirect()->route('admin.booking-slot-templates.index')->with('success', 'Template status updated.');
    }
}
