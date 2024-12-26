@extends('admin.partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

    <h2>Create Redirect</h2>

    <!-- Include the form partial, passing a null redirect for a new record -->
    <form action="{{ route('admin.redirects.store') }}" method="POST">
        @csrf
        @include('admin.redirect.form', ['redirect' => null]) <!-- Pass null for create -->
        <button type="submit" class="btn btn-primary">Create Redirect</button>
    </form>

@endsection
