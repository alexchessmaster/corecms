@extends('shared::partials.app')
@section('content-card-title', 'Add a News')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.news.store') }}" method="POST" enctype='multipart/form-data'>
        @csrf

        @include('news::news.form')

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>

@endsection
