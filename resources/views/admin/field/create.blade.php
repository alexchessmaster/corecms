@extends('admin.partials.app')
@section('content-card-title', 'Create Field')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.fields.store') }}" method="POST">
        @csrf
        @include('admin.field.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection