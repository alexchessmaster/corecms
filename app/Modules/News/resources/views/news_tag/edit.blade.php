@extends('shared::partials.app')
@section('content-card-title', 'Edit News Tag')
@section('content-card-body')

    <div class="container">
        <form action="{{ route('admin.news-tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')
            @include('news::news_tag.form')
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>

@endsection
