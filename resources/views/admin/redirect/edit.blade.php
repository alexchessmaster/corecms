@extends('admin.partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

    <h2>Edit Redirect</h2>

    <form action="{{ route('admin.redirects.update', $redirect->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.redirect.form', ['redirect' => $redirect])
        <button type="submit" class="btn btn-primary">Update Redirect</button>
    </form>

@endsection
