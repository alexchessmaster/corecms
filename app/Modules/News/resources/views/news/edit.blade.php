@extends('admin.partials.app')
@section('content-card-title', 'Edit News')
@section('content-body')

    <div class="container">
        <a href="{{ route('admin.news.create') }}" class="btn btn-success"><strong style="">+ </strong>Add a new news</a>
        <button class="btn btn-default float-right" id="translate">Translate to {{ strtoupper(app()->getLocale()) }}</button>
        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            @include('news::news.form')


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
