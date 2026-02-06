@extends('admin.partials.app')
@section('content-card-title', 'View Booking Time Slot')
@section('content-body')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-4">Time Slot Details</h3>
                
                <div class="row mb-3">
                    <div class="col-md-3"><strong>ID:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->id }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Template:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->template->name ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Start Time:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->start_time->format('Y-m-d H:i:s') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>End Time:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->end_time->format('Y-m-d H:i:s') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Max Capacity:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->max_capacity }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Available Capacity:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->availableCapacity() }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Is Active:</strong></div>
                    <div class="col-md-9">
                        <span class="badge badge-{{ $timeSlot->is_active ? 'success' : 'secondary' }}">
                            {{ $timeSlot->is_active ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Manually Disabled:</strong></div>
                    <div class="col-md-9">
                        <span class="badge badge-{{ $timeSlot->is_manually_disabled ? 'danger' : 'success' }}">
                            {{ $timeSlot->is_manually_disabled ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Is Available:</strong></div>
                    <div class="col-md-9">
                        <span class="badge badge-{{ $timeSlot->isAvailable() ? 'success' : 'warning' }}">
                            {{ $timeSlot->isAvailable() ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Admin Notes:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->admin_notes ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Created At:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->created_at->format('Y-m-d H:i:s') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Updated At:</strong></div>
                    <div class="col-md-9">{{ $timeSlot->updated_at->format('Y-m-d H:i:s') }}</div>
                </div>

                <hr>

                <h4 class="mt-4 mb-3">Reservations ({{ $timeSlot->reservations->count() }})</h4>
                
                @if($timeSlot->reservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeSlot->reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->id }}</td>
                                        <td>{{ $reservation->customer_name }}</td>
                                        <td>{{ $reservation->customer_email }}</td>
                                        <td>
                                            <span class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $reservation->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No reservations for this time slot.</p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('admin.booking-time-slots.edit', $timeSlot->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('admin.booking-time-slots.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
