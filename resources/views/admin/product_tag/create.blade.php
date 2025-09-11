@extends('admin.partials.app')
@section('content-card-title', 'Create Product Tag')
@section('content-card-body')

<div class="container">
    <form action="{{ route('admin.product-tags.store') }}" method="POST">
        @csrf
        @include('admin.product_tag.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@endsection