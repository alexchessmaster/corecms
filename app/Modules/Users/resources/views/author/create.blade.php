@extends('admin.partials.app')
@section('content-card-title', 'Create Author')
@section('content-card-body')

    <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('users::author.form')

        <input type="submit" class="btn btn-success" value="Save">
        <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

@endsection
