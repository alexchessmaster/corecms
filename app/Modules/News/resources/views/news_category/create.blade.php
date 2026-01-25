@extends('admin.partials.app')
@section('content-card-title', 'Create News Category')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.news-categories.store') }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @include('news::news_category.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection