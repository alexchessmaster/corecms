@extends('shared::partials.app')
@section('content-card-title', 'Create Article')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype='multipart/form-data'>
        @csrf
        @include('articles::article.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection
