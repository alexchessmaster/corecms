@extends('shared::partials.app')
@section('content-card-title', 'Add a Product')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype='multipart/form-data'>
        @csrf

        @include('products::product.form')

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>

@endsection
