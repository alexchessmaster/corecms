@extends('shared::partials.app')
@section('content-card-title', 'Edit Tag')
@section('content-card-body')

    <form action="{{ route('admin.translation-texts.update', $translationText->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('translation-texts::translation-text.form')

        <button type="submit" class="btn btn-success">Update</button>
    </form>

@endsection
