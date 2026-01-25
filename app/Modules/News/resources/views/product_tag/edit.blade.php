@extends('admin.partials.app')
@section('content-card-title', 'Edit News Tag')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.news-tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')
        @include('news::product_tag.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

@endsection