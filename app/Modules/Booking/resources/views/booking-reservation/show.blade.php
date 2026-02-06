@extends('admin.partials.app')
@section('content-card-title', 'Booking Reservation Details')
@section('content-body')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Booking Reservation #{{ $reservation->id }}</h2>
            <div>
                <a href="{{ route('admin.booking-reservations.edit', $reservation->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.booking-reservations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Reservation Information</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        @if($reservation->status === 'confirmed')
                            <span class="badge bg-success">{{ ucfirst($reservation->status) }}</span>
                        @elseif($reservation->status === 'pending')
                            <span class="badge bg-warning">{{ ucfirst($reservation->status) }}</span>
                        @else
                            <span class="badge bg-danger">{{ ucfirst($reservation->status) }}</span>
                        @endif
                        @if($reservation->isExpired())
                            <span class="badge bg-danger">EXPIRED</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Booking Type:</strong>
                        @if($reservation->isGuestBooking())
                            <span class="badge bg-secondary">Guest Booking</span>
                        @else
                            <span class="badge bg-primary">Registered User</span>
                        @endif
                    </div>
                </div>

                <hr>

                <h6 class="text-muted">Contact Details</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Name:</strong> {{ $reservation->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong> {{ $reservation->email }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mobile Number:</strong> {{ $reservation->mobile_number ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Age:</strong> {{ $reservation->age ?? 'N/A' }}
                    </div>
                </div>

                @if($reservation->user)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Registered User:</strong> {{ $reservation->user->name }} ({{ $reservation->user->email }})
                        </div>
                    </div>
                @endif

                <hr>

                <h6 class="text-muted">Booking Details</h6>
                @if($reservation->timeSlot)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Time Slot:</strong><br>
                            Start: {{ $reservation->timeSlot->start_time->format('Y-m-d H:i') }}<br>
                            End: {{ $reservation->timeSlot->end_time->format('Y-m-d H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Capacity:</strong> {{ $reservation->timeSlot->max_capacity }}<br>
                            <strong>Available:</strong> {{ $reservation->timeSlot->availableCapacity() }}
                        </div>
                    </div>
                @else
                    <p class="text-muted">No time slot assigned</p>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Service:</strong> {{ $reservation->service ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Expires At:</strong> {{ $reservation->expires_at ? $reservation->expires_at->format('Y-m-d H:i') : 'N/A' }}
                    </div>
                </div>

                @if($reservation->comments)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Comments:</strong><br>
                            <p class="border p-2 rounded">{{ $reservation->comments }}</p>
                        </div>
                    </div>
                @endif

                <hr>

                <h6 class="text-muted">Timestamps</h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Created At:</strong> {{ $reservation->created_at->format('Y-m-d H:i:s') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Updated At:</strong> {{ $reservation->updated_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
