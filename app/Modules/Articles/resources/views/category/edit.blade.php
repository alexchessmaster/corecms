@extends('shared::partials.app')
@section('content-card-title', 'Edit Category')
@section('content-card-body')

    <div class="container">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            @include('articles::category.form')
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <br><br>

@endsection
