@extends('resources.views.admin.partials.app')
@section('content-card-title', 'Edit Booking Reservation')
@section('content-body')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Edit Booking Reservation #{{ $reservation->id }}</h2>
            <a href="{{ route('admin.booking-reservations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        <form action="{{ route('admin.booking-reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('booking-reservation.form')
            <button type="submit" class="btn btn-primary">Update Reservation</button>
        </form>
    </div>
@endsection
