@extends('shared::partials.app')
@section('content-card-title', 'Edit Tag')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')
        @include('articles::tag.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

@endsection
