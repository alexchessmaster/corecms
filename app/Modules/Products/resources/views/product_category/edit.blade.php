@extends('shared::partials.app')
@section('content-card-title', 'Edit Product Category')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.product-categories.update', $productCategory) }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        @include('products::product_category.form')
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>

<br><br>
@include('shared::partials.add-widget-form')

@endsection
