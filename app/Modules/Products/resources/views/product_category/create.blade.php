@extends('admin.partials.app')
@section('content-card-title', 'Create Product Category')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.product-categories.store') }}" method="POST"  enctype='multipart/form-data'>
        @csrf
        @include('products::product_category.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection
