@extends('admin.partials.app')
@section('content-card-title', 'Edit Product Author')
@section('content-card-body')

    <form action="{{ route('admin.product-authors.update', $productAuthor->id) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf

        @include('products::product_author.form')

        <input type="submit" class="btn btn-success" value="Update">
        <a href="{{ route('admin.product-authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    {{-- <br><br>
    @include('admin.partials.add-widget-form') --}}

@endsection
