@extends('shared::partials.app')
@section('content-card-title', 'Edit Booking Time Slot')
@section('content-body')
    <form action="{{ route('admin.booking-time-slots.update', $timeSlot->id) }}" method="POST">
        @csrf
        @method('PATCH')
        @include('bookings::booking-time-slot.form')
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.booking-time-slots.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
