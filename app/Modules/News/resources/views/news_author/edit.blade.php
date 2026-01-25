@extends('admin.partials.app')
@section('content-card-title', 'Edit News Author')
@section('content-card-body')

    <form action="{{ route('admin.news-authors.update', $newsAuthor->id) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf

        @include('news::news_author.form')

        <input type="submit" class="btn btn-success" value="Update">
        <a href="{{ route('admin.news-authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    {{-- <br><br>
    @include('admin.partials.add-widget-form') --}}

@endsection
