@extends('shared::partials.app')
@section('content-card-title', 'Edit Book')
@section('content-body')

    <div class="container">
        <a href="{{ route('admin.books.create') }}" class="btn btn-info"><strong style="">+ </strong>Add a new
            book</a>
        <span class="float-right">
            <button class="btn btn-default" id="translate" type="button">Translate to
                {{ strtoupper(app()->getLocale()) }}</button>
            <button class="btn btn-danger" id="remove-language"
            {{ isset($book) && !empty($book->getTranslation('slug', app()->getLocale(), false)) ?: 'hidden' }}
            >Remove {{ strtoupper(app()->getLocale()) }} <i
                    class="fa fa-trash"></i></button>
        </span>
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            @include('books::book.form')


            <button type="submit" class="btn btn-success" onclick="clickSaveAll(event)">Update</button>
        </form>
    </div>

    <br>
    <br>

    @include('shared::partials.add-widget-form')

@endsection

@push('scripts')
    {{-- Start handle click Save All button --}}
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
    {{-- End handle click Save All button --}}
    {{-- Start remove-language-button --}}
    <script>
        const removeLanguage = document.getElementById('remove-language');
        removeLanguage.addEventListener('click', () => {
            console.log('remove language clicked {{ app()->getLocale() }}');
            fetch("{{ route('api.v1.books.removeBookLanguage', [$book->id, app()->getLocale()]) }}", {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer {{ $authToken ?? '' }}'
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || 'Request failed');
                    }

                    return data;
                })
                .then(data => {
                    toastr.success(data.message || 'Success');
                    window.location.reload();
                })
                .catch(err => {
                    toastr.error(err.message || 'Unexpected error');
                    console.error(err);
                });
        });
    </script>
    {{-- End remove-language-button --}}
@endpush
