@extends('admin.partials.app')
@section('content-card-title', 'Create Tag')
@section('content-card-body')

    <form action="{{ route('admin.translation-texts.store') }}" method="POST">
        @csrf

        @include('admin.translation-text.form')

        <button type="submit" class="btn btn-primary">Save</button>
    </form>

@endsection
