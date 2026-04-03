@extends('shared::partials.app')
@section('content-card-title', 'Booking Reservations')
@section('content-body')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Booking Reservations</h2>
            <div>
                <a href="{{ route('admin.booking-reservations.calendar') }}" class="btn btn-info">
                    <i class="fas fa-calendar"></i> Calendar View
                </a>
                <a href="{{ route('admin.booking-reservations.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Create
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>User</th>
                        <th>Time Slot</th>
                        <th>Status</th>
                        <th>Service</th>
                        <th>Age</th>
                        <th>Expires At</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr class="{{ $reservation->isExpired() ? 'table-danger' : '' }}">
                            <td>{{ $reservation->id }}</td>
                            <td>{{ $reservation->name }}</td>
                            <td>{{ $reservation->email }}</td>
                            <td>{{ $reservation->mobile_number }}</td>
                            <td>
                                @if($reservation->user)
                                    {{ $reservation->user->name }}
                                @else
                                    <span class="badge bg-secondary">Guest</span>
                                @endif
                            </td>
                            <td>
                                @if($reservation->timeSlot)
                                    {{ $reservation->timeSlot->start_time->format('Y-m-d H:i') }} -
                                    {{ $reservation->timeSlot->end_time->format('H:i') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($reservation->status === 'confirmed')
                                    <span class="badge bg-success">{{ ucfirst($reservation->status) }}</span>
                                @elseif($reservation->status === 'pending')
                                    <span class="badge bg-warning">{{ ucfirst($reservation->status) }}</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($reservation->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $reservation->service }}</td>
                            <td>{{ $reservation->age }}</td>
                            <td>{{ $reservation->expires_at ? $reservation->expires_at->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>{{ $reservation->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.booking-reservations.show', $reservation->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.booking-reservations.edit', $reservation->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.booking-reservations.destroy', $reservation->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
