@extends('shared::partials.app')
@section('content-card-title', 'Booking Slot Templates')
@section('content-body')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Booking Slot Templates</h2>
            <a href="{{ route('admin.booking-slot-templates.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Days of Week</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Slot Duration</th>
                        <th>Max Capacity</th>
                        <th>Valid From</th>
                        <th>Valid Until</th>
                        <th>Active</th>
                        <th>Description</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>{{ implode(',', $template->days_of_week ?? []) }}</td>
                            <td>{{ $template->start_time }}</td>
                            <td>{{ $template->end_time }}</td>
                            <td>{{ $template->slot_duration_minutes }}</td>
                            <td>{{ $template->max_capacity }}</td>
                            <td>{{ $template->valid_from }}</td>
                            <td>{{ $template->valid_until }}</td>
                            <td>{{ $template->is_active ? 'Yes' : 'No' }}</td>
                            <td>{{ $template->description }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.booking-slot-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.booking-slot-templates.destroy', $template->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <form action="{{ route('admin.booking-slot-templates.toggle', $template->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">{{ $template->is_active ? 'Disable' : 'Enable' }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
