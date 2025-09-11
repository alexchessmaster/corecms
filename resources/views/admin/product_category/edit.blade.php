@extends('admin.partials.app')
@section('content-card-title', 'Edit Product Category')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.product-categories.update', $productCategory) }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        @include('admin.product_category.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<br><br>
@include('admin.partials.add-widget-form')

@endsection