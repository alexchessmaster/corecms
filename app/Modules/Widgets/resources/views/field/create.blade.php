@extends('shared::partials.app')
@section('content-card-title', 'Create Field')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.fields.store') }}" method="POST">
        @csrf
        @include('widgets::field.form')
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>

@endsection
