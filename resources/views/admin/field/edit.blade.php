@extends('admin.partials.app')
@section('content-card-title', 'Edit Field')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.fields.update', $field) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.field.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

@endsection