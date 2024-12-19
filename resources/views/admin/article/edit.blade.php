@extends('admin.partials.app')
@section('content-card-title', 'Edit Article')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        @include('admin.article.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

@endsection