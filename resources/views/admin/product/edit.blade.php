@extends('admin.partials.app')
@section('content-card-title', 'Edit Product')
@section('content-body')

    <div class="container">
        <a href="{{ route('admin.products.create') }}" class="btn btn-success"><strong style="">+ </strong>Add a new
            product</a>
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            @include('admin.product.form')


            <button type="submit" class="btn btn-primary" onclick="clickSaveAll(event)">Update</button>
        </form>
    </div>

    <br>
    <br>

    @include('admin.partials.add-widget-form')

@endsection

@push('scripts')
    <script>
        function clickSaveAll(event) {
            event.preventDefault(); // Prevent the default form submission

            // Find and click the save-all button
            const saveAllButton = document.getElementById('save-all');
            if (saveAllButton) {
                saveAllButton.click();
            }

            // Then submit the form
            event.target.closest('form').submit();
        }
    </script>
@endpush
