@extends('admin.partials.app')
@section('content-card-title', 'Edit Book Genre')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.book_genres.update', $bookGenre) }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        @include('admin.book_genre.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<br><br>

@include('admin.partials.add-widget-form')

@endsection