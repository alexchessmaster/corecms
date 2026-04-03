@extends('shared::partials.app')
@section('content-card-title', 'Menu')
@section('content-card-body')

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @method('patch')
        @csrf

        @include('menus::menu.form')

        <input type="submit" class="btn btn-success" value="Save">
    </form>

@endsection
