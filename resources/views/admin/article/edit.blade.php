@extends('admin.partials.app')
@section('content-card-title', 'Edit Article')
@section('content-body')

    <div class="container">
        <a href="{{ route('admin.articles.create') }}" class="btn btn-success"><strong style="">+ </strong>Create</a>
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            @include('admin.article.form')


            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <br>
    <br>

    @include('admin.partials.add-widget-form')

@endsection
