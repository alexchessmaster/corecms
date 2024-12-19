@extends('admin.partials.app')
@section('content-card-title', 'Create Tag')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.tags.store') }}" method="POST">
        @csrf
        @include('admin.tag.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection