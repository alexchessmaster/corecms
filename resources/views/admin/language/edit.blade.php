@extends('admin.partials.app')
@section('content-card-title', 'Edit Language')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.languages.update', $language) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.language.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

@endsection