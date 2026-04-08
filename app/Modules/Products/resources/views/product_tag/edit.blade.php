@extends('shared::partials.app')
@section('content-card-title', 'Edit Product Tag')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.product-tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')
        @include('products::product_tag.form')
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>

@endsection
