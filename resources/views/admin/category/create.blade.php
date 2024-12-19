@extends('admin.partials.app')
@section('content-card-title', 'Create Category')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        @include('admin.category.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection