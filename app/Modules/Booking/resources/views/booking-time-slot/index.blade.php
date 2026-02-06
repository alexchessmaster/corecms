@extends('admin.partials.app')
@section('content-card-title', 'Booking Time Slots')
@section('content-body')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Booking Time Slots</h2>
            <a href="{{ route('admin.booking-time-slots.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Template</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Max Capacity</th>
                        <th>Available</th>
                        <th>Active</th>
                        <th>Manually Disabled</th>
                        <th>Admin Notes</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $slot)
                        <tr>
                            <td>{{ $slot->id }}</td>
                            <td>{{ $slot->template->name ?? 'N/A' }}</td>
                            <td>{{ $slot->start_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $slot->end_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $slot->max_capacity }}</td>
                            <td>{{ $slot->availableCapacity() }}</td>
                            <td>
                                <span class="badge badge-{{ $slot->is_active ? 'success' : 'secondary' }}">
                                    {{ $slot->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $slot->is_manually_disabled ? 'danger' : 'success' }}">
                                    {{ $slot->is_manually_disabled ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ Str::limit($slot->admin_notes, 30) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.booking-time-slots.show', $slot->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.booking-time-slots.edit', $slot->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.booking-time-slots.destroy', $slot->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <form action="{{ route('admin.booking-time-slots.toggle', $slot->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">{{ $slot->is_active ? 'Deactivate' : 'Activate' }}</button>
                                </form>
                                <form action="{{ route('admin.booking-time-slots.toggle-disable', $slot->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-{{ $slot->is_manually_disabled ? 'success' : 'secondary' }}">
                                        {{ $slot->is_manually_disabled ? 'Enable' : 'Disable' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
