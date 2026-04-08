@extends('shared::partials.app')
@section('content-card-title', 'Edit Book Genre')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.book_genres.update', $bookGenre) }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        @include('books::book_genre.form')
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
<br><br>

@include('shared::partials.add-widget-form')

@endsection
