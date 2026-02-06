@extends('admin.partials.app')
@section('content-card-title', 'Edit Booking Slot Template')
@section('content-body')
    <form action="{{ route('admin.booking-slot-templates.update', $template->id) }}" method="POST">
        @csrf
        @method('PATCH')
        @include('admin.booking-slot-template.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
