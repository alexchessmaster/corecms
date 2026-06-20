<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($book) && $book->image)
        <div class="mb-2">
            <img src="{{ $book->image }}" alt="Current Image" style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;"/>
    </div>
    <input type="file" class="form-control" id="image" name="image"
           @if (!(isset($book) && $book->image)) required @endif>
</div>
<div class="mb-3">
    <label for="pdf" class="form-label">PDF</label>
    @if (isset($book) && $book->getTranslation('pdf', app()->getLocale(), false))
        <div class="mb-2">
            <a href="{{ $book->getTranslation('pdf', app()->getLocale(), false) }}" target="_blank" rel="noopener">View current PDF</a>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="1" id="remove_pdf" name="remove_pdf">
            <label class="form-check-label" for="remove_pdf">Remove current PDF</label>
        </div>
    @endif
    <input type="file" class="form-control" id="pdf" name="pdf" accept="application/pdf">
    <small class="form-text text-muted">Upload a PDF version of the book.</small>
</div>
<div class="mb-3">
    <label for="title" class="form-label required">Title</label>
    <input type="text" class="form-control" id="title" name="title"
           style="{{ isset($book) && !empty($book->getTranslation('slug', app()->getLocale(), false)) ?: 'background-color:lightgreen' }}"
           value="{{ isset($book) ? App\Modules\Shared\Helpers\TranslationHelper::firstAvailableValue($book, 'title', true) : '' }}"
           required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label required">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug"
           value="{{ isset($book) ? $book->getTranslation('slug', app()->getLocale(), false) : '' }}" required>
    @if (isset($book))
        <small>
            @php
                $settingRepository = new \App\Modules\Settings\Repositories\SettingRepository();
                // get the prefix from settings
                $prefix = $settingRepository->findByKey(App\Modules\Shared\Enums\SettingKeyEnum::BOOK_PREFIX);
            @endphp
            <a href="{{ \App\Modules\Shared\Helpers\UrlHelper::getFrontendUrl(
                        $book->getTranslation('slug', app()->getLocale(), false),
                        session('lang'),
                        $prefix,
                    )
                }}">
                {{ \App\Modules\Shared\Helpers\UrlHelper::getFrontendUrl(
                        $book->getTranslation('slug', app()->getLocale(), false),
                        session('lang'),
                        $prefix,
                    )
                }}
            </a>
        </small>
    @endif
</div>

<div class="mb-3">
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="draft" {{ isset($book) && $book->status === 'draft' ? 'selected' : '' }}>Draft
            </option>
            <option value="published" {{ isset($book) && $book->status === 'published' ? 'selected' : '' }}>Published
            </option>
            <option value="scheduled" {{ isset($book) && $book->status === 'scheduled' ? 'selected' : '' }}>Scheduled
            </option>
        </select>
    </div>

    <div class="form-group mt-2" id="scheduled_at_group" style="display: none;">
        <label for="scheduled_at">Scheduled At</label>
        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at"
               value="{{ old('scheduled_at', isset($page->scheduled_at) ? $page->scheduled_at->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>
<div class="mb-3">
    <label for="description" class="form-label required">Description (Short)</label>
    <textarea class="form-control" id="description" name="description"
              rows="2">{{ isset($book) ? $book->getTranslation('description', app()->getLocale(), false) : '' }}</textarea>
</div>
{{-- <div class="mb-3">
    <label for="author" class="form-label">Author</label>
    <input type="text" class="form-control" id="author" name="author"
        value="{{ isset($book) ? $book->getTranslation('author', app()->getLocale(), false) : '' }}">
</div> --}}
<div class="mb-3">
    <label for="book_author_id" class="form-label">Book Author</label>
    <select class="form-control" id="book_author_id" name="book_author_id" style="width: 100%;">
        @if (isset($book) && $book->author_id && $book->author)
            <option value="{{ $book->author_id }}" selected>
                {{ $book->author->getTranslation('name', app()->getLocale(), false) ?: $book->author->getTranslation('name', 'en', true) }}
            </option>
        @endif
    </select>
</div>
<div class="mb-3">
    <label for="total_pages" class="form-label">Total Pages</label>
    <input type="text" class="form-control" id="total_pages" name="total_pages"
           value="{{ isset($book) ? $book->total_pages : '' }}">
</div>

<div class="form-group mt-2" id="published_at_group" style="">
    <label for="published_year">Published Year</label>
    <input type="number" class="form-control" id="published_year" name="published_year"
           value="{{ old('published_year', isset($book->published_year) ? $book->published_year : '') }}">
</div>
<div class="mb-3">
    <label for="book_genre_id" class="form-label required">Book genre</label>
    <select class="form-control" id="book_genre_id" name="book_genre_id" required>
        @foreach ($bookGenres as $bookGenre)
            @if (!empty($bookGenre->getTranslation('name', app()->getLocale(), false)))
                <option value="{{ $bookGenre->id }}"
                        {{ isset($book) && $book->book_genre_id == $bookGenre->id ? 'selected' : '' }}>
                    {{ $bookGenre->getTranslation('name', app()->getLocale()) }}
                </option>
            @endif
        @endforeach
    </select>
</div>


@include('shared::partials.sitemap-form')

<br>

{{-- auto-fill slug from title --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        if (titleInput && slugInput && !slugInput.disabled) {
            titleInput.addEventListener('blur', function() {
                if (slugInput.value.trim() === '') {
                    slugInput.value = titleInput.value
                        .toLowerCase()
                        .trim()
                        .replace(/\s+/g, '-')
                        .replace(/[^\u0600-\u06FF\w-]/g, '')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }
            });
        }
    });
</script> --}}
{{-- end auto-fill slug from title --}}

<script>
    jQuery(document).ready(function ($) {
        $('#book_author_id').select2({
            ajax: {
                url: '/api/v1/book-authors?lang={!! App::currentLocale() !!}',
                dataType: 'json',
                delay: 300,
                headers: {
                    'Authorization': 'Bearer {{ $authToken ?? '' }}',
                    'Accept': 'application/json'
                },
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    const results = data.data.map(function (author) {
                        return {
                            id: author.id,
                            text: author.name
                        };
                    });

                    return {
                        results: results
                    };
                },
                cache: true
            },
            placeholder: 'Type to search for an author...',
            minimumInputLength: 0,
            allowClear: true,
            width: '100%'
        });

        // Trigger change event to ensure the pre-selected option is properly loaded
        @if (isset($book) && $book->book_author_id && $book->bookAuthor)
        $('#book_author_id').trigger('change');
        @endif
    });
</script>

{{-- status --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status');
        const scheduledGroup = document.getElementById('scheduled_at_group');

        function toggleScheduledInput() {
            if (statusSelect.value === 'scheduled') {
                scheduledGroup.style.display = 'block';
            } else {
                scheduledGroup.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', toggleScheduledInput);
        toggleScheduledInput();
    });
</script>
{{-- end status --}}

{{-- show image --}}
<script>
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('image-preview');
    const currentImageContainer = document.getElementById('current-image-container');

    imageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (file) {
            const fileURL = URL.createObjectURL(file);

            if (currentImageContainer) {
                currentImageContainer.style.display = 'none';
            }

            previewContainer.style.display = 'block';
            previewImage.src = fileURL;
        } else {
            previewContainer.style.display = 'none';
            if (currentImageContainer) {
                currentImageContainer.style.display = 'block';
            }
        }
    });
</script>
{{-- end show image --}}
