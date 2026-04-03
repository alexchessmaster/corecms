@extends('shared::partials.app')
@section('content-card-title', 'Create Booking Slot Template')
@section('content-body')
    <form action="{{ route('admin.booking-slot-templates.store') }}" method="POST">
        @csrf
        @include('bookings::booking-slot-template.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
