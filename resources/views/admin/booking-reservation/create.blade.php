@extends('admin.partials.app')
@section('content-card-title', 'Create Booking Reservation')
@section('content-body')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Create New Booking Reservation</h2>
            <a href="{{ route('admin.booking-reservations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        <form action="{{ route('admin.booking-reservations.store') }}" method="POST">
            @csrf
            @include('admin.booking-reservation.form')
            <button type="submit" class="btn btn-primary">Create Reservation</button>
        </form>
    </div>
@endsection
