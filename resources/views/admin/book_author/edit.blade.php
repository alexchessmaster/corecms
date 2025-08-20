@extends('admin.partials.app')
@section('content-card-title', 'Edit Book Author')
@section('content-card-body')

    <form action="{{ route('admin.book-authors.update', $bookAuthor->id) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf

        @include('admin.book_author.form')

        <input type="submit" class="btn btn-success" value="Update">
        <a href="{{ route('admin.book-authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

@endsection
