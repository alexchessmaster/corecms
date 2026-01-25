@extends('admin.partials.app')
@section('content-card-title', 'Create News Author')
@section('content-card-body')

    <form action="{{ route('admin.news-authors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('news::news_author.form')

        <input type="submit" class="btn btn-success" value="Save">
        <a href="{{ route('admin.news-authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

@endsection
