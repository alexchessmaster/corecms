@extends('admin.partials.app')
@section('content-card-title', 'Edit Article')
@section('content-body')

    <div class="container">
        <a href="{{ route('admin.articles.create') }}" class="btn btn-success"><strong style="">+ </strong>Create</a>
        <span class="float-right">
            <button class="btn btn-default" id="translate" type="button">Translate to
                {{ strtoupper(app()->getLocale()) }}</button>
            <button class="btn btn-danger" id="remove-language"
                {{ isset($article) && !empty($article->getTranslation('slug', app()->getLocale(), false)) ?: 'hidden' }}>Remove
                {{ strtoupper(app()->getLocale()) }} <i class="fa fa-trash"></i></button>
        </span>
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            @include('admin.article.form')


            <button type="submit" class="btn btn-primary" onclick="clickSaveAll(event)">Update</button>
        </form>
    </div>

    <br>
    <br>

    @include('admin.partials.add-widget-form')

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
            fetch("{{ route('api.v1.articles.removeArticleLanguage', [$article->id, app()->getLocale()]) }}", {
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
