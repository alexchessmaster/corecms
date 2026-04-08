@extends('shared::partials.app')
@section('content-card-title', 'Edit Field')
@section('content-card-body')

    <div class="container">
        <form action="{{ route('admin.fields.update', $field) }}" method="POST">
            @csrf
            @method('PUT')
            @include('widgets::field.form')
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>

@endsection
