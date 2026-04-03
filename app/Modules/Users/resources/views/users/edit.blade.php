@extends('admin.partials.app')
@section('content-card-title', 'User')
@section('content-card-body')

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @method('patch')
        @csrf

        @include('users::users.form')

        <input type="submit" class="btn btn-success" value="Save">
    </form>

@endsection
