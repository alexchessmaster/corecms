@extends('admin.partials.app')
@section('content-card-title', 'Create News Tag')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.news-tags.store') }}" method="POST">
        @csrf
        @include('news::product_tag.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection