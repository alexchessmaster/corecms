@extends('shared::partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

    <h2>Create Redirect</h2>

    <form action="{{ route('admin.redirects.store') }}" method="POST">
        @csrf
        @include('redirects::redirect.form', ['redirect' => null])
        <button type="submit" class="btn btn-primary">Create Redirect</button>
    </form>

@endsection
