@extends('admin.partials.app')
@section('content-card-title', 'Create Booking Time Slot')
@section('content-body')
    <form action="{{ route('admin.booking-time-slots.store') }}" method="POST">
        @csrf
        @include('admin.booking-time-slot.form')
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.booking-time-slots.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
