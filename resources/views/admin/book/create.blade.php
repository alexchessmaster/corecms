@extends('admin.partials.app')
@section('content-card-title', 'Add a Book')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.books.store') }}" method="POST" enctype='multipart/form-data'>
        @csrf
        @include('admin.book.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection