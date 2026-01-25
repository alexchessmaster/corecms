@extends('admin.partials.app')
@section('content-card-title', 'Edit News Category')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.news-categories.update', $newsCategory) }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        @include('news::news_category.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<br><br>
@include('admin.partials.add-widget-form')

@endsection