@extends('admin.partials.app')
@section('content-card-title', 'Create Book Author')
@section('content-card-body')

    <form action="{{ route('admin.book-authors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('books::book_author.form')

        <input type="submit" class="btn btn-success" value="Save">
        <a href="{{ route('admin.book-authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

@endsection
