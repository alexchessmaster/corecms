@extends('admin.partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

    <h2>Edit Redirect</h2>

    <!-- Include the form partial, passing the current redirect model -->
    <form action="{{ route('admin.redirects.update', $redirect->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Specify the PUT method for update -->
        @include('admin.redirect.form', ['redirect' => $redirect]) <!-- Pass the existing redirect -->
        <button type="submit" class="btn btn-primary">Update Redirect</button>
    </form>

@endsection
