@extends('admin.partials.app')
@section('content-card-title', 'Edit Author')
@section('content-card-body')

    <form action="{{ route('admin.authors.update', $author->id) }}" method="POST"
          enctype="multipart/form-data">
        @method('PATCH')
        @csrf

        @include('users::author.form')

        <input type="submit" class="btn btn-success" value="Update">
        <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    {{-- <br><br>
    @include('admin.partials.add-widget-form') --}}

@endsection
