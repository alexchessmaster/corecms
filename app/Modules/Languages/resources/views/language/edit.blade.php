@extends('shared::partials.app')
@section('content-card-title', 'Edit Language')
@section('content-card-body')

    <div class="container">
        <form action="{{ route('admin.languages.update', $language) }}" method="POST">
            @csrf
            @method('PUT')
            @include('languages::language.form')
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>

@endsection
