@extends('admin.partials.app')
@section('content-card-title', 'Create Product Author')
@section('content-card-body')

    <form action="{{ route('admin.product-authors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('admin.product_author.form')

        <input type="submit" class="btn btn-success" value="Save">
        <a href="{{ route('admin.product-authors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

@endsection
