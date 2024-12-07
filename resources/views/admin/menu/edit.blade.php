@extends('admin.partials.app')
@section('content-card-title', 'Menu')
@section('content-card-body')

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
        @method('patch')
        @csrf
        
        @include('admin.menu.form')

        <input type="submit" class="btn btn-success" value="Save">
    </form>

@endsection
