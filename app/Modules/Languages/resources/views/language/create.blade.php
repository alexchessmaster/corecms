@extends('shared::partials.app')
@section('content-card-title', 'Add a Language')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.languages.store') }}" method="POST">
        @csrf
        @include('languages::language.form')
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>

@endsection
