@extends('admin.partials.app')
@section('content-card-title', 'Menu')
@section('content-card-body')

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('admin.menu.form')

        <input type="submit" class="btn btn-success" value="Save">
    </form>

@endsection
