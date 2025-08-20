@extends('admin.partials.app')
@section('content-card-title', 'Edit Book')
@section('content-body')

    <div class="container">
        <a href="{{ route('admin.books.create') }}" class="btn btn-success"><strong style="">+ </strong>Add a new book</a>
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            @include('admin.book.form')


            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <br>
    <br>

    @include('admin.partials.add-widget-form')

@endsection
