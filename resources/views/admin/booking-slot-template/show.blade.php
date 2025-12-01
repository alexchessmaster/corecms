@extends('admin.partials.app')
@section('content-card-title', 'Booking Slot Template Details')
@section('content-body')
    <div class="container">
        <h2>{{ $template->name }}</h2>
        <ul>
            <li><strong>Days of Week:</strong> {{ implode(',', $template->days_of_week ?? []) }}</li>
            <li><strong>Start Time:</strong> {{ $template->start_time }}</li>
            <li><strong>End Time:</strong> {{ $template->end_time }}</li>
            <li><strong>Slot Duration:</strong> {{ $template->slot_duration_minutes }}</li>
            <li><strong>Max Capacity:</strong> {{ $template->max_capacity }}</li>
            <li><strong>Valid From:</strong> {{ $template->valid_from }}</li>
            <li><strong>Valid Until:</strong> {{ $template->valid_until }}</li>
            <li><strong>Active:</strong> {{ $template->is_active ? 'Yes' : 'No' }}</li>
            <li><strong>Description:</strong> {{ $template->description }}</li>
        </ul>
        <a href="{{ route('admin.booking-slot-templates.edit', $template->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('admin.booking-slot-templates.index') }}" class="btn btn-secondary">Back</a>
    </div>
@endsection
