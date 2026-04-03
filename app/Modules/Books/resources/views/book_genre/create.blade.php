@extends('shared::partials.app')
@section('content-card-title', 'Create Book Genre')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.book_genres.store') }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @include('books::book_genre.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection
