<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($news) && App\Modules\Shared\Helpers\TranslationHelper::firstAvailableValue($news, 'image'))
        <div class="mb-2">
            <img src="{{ App\Modules\Shared\Helpers\TranslationHelper::firstAvailableValue($news, 'image') }}" alt="Current Image" style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;"/>
    </div>
    <input type="file" class="form-control" id="image"
           name="image" {{-- @if (!(isset($news) && $news->image)) required @endif --}}>
</div>
<div class="mb-3">
    <label for="title" class="form-label required">Title</label>
    <input type="text" class="form-control" id="title" name="title"
           value="{{ isset($news) ? $news->getTranslation('title', app()->getLocale(), false) : '' }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug"
        value="{{ isset($news) ? $news->getTranslation('slug', app()->getLocale(), false) : '' }}">
    @if (isset($news))
        <small>
            @php
                $settingStore = new App\Stores\SettingStore;
                // get the prefix from settings
                $prefix = $settingStore->findByKey(App\Modules\Shared\Enums\SettingKeyEnum::NEWS_PREFIX)
            @endphp
            <a href="{{ \App\Modules\Shared\Helpers\UrlHelper::getFrontendUrl(
                        $news->getTranslation('slug', app()->getLocale(), false),
                        session('lang'),
                        $prefix
                    )
                }}">
                {{ \App\Modules\Shared\Helpers\UrlHelper::getFrontendUrl(
                        $news->getTranslation('slug', app()->getLocale(), false),
                        session('lang'),
                        $prefix
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
            <option value="draft" {{ isset($news) && $news->status === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ isset($news) && $news->status === 'published' ? 'selected' : '' }}>Published
            </option>
            <option value="scheduled" {{ isset($news) && $news->status === 'scheduled' ? 'selected' : '' }}>Scheduled
            </option>
        </select>
    </div>
    <div class="form-group mt-2" id="scheduled_at_group" style="display: none;">
        <label for="scheduled_at">Scheduled At</label>
        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at"
               value="{{ old('scheduled_at', isset($news->scheduled_at) ? $news->scheduled_at->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>
<div class="mb-3">
    <label for="news_date">News date</label>
    <input type="datetime-local" class="form-control" id="news_date" name="news_date"
        value="{{ old('news_date', isset($news->news_date) ? $news->news_date->format('Y-m-d\TH:i') : '') }}">
</div>
{{-- Translatable inputs needed. Description is not translatable right now. <div class="mb-3">
    <label for="description" class="form-label">Description (Short)</label>
    <textarea class="form-control" id="description" name="description"
              rows="2">{{ isset($news) ? $news->getTranslation('description', app()->getLocale(), false) : '' }}</textarea>
</div> --}}
<div class="mb-3">
    <label for="category_id" class="form-label required">Category</label>
    <select class="form-control" id="category_id" name="category_id" required>
        @foreach ($categories as $category)
            <!-- The category must be translated before creating a new item to generate the correct slug -->
            @if (!empty($category->getTranslation('name', app()->getLocale(), false)))
                <option value="{{ $category->id }}"
                        {{ isset($news) && $news->news_category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->getTranslation('name', app()->getLocale()) }}
                </option>
            @endif
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="tag_ids" class="form-label">News Tags</label>
    <select class="form-control" id="tag_ids" name="tag_ids[]" multiple style="width: 100%;">
        @if (isset($news) && $news->tags)
            @foreach ($news->tags as $tag)
                <option value="{{ $tag->id }}" selected>
                    {{ $tag->getTranslation('name', app()->getLocale(), false) ?: $tag->getTranslation('name', 'en', true) }}
                </option>
            @endforeach
        @endif
    </select>
</div>

<div class="mb-3">
    <label for="author_id" class="form-label">News Author</label>
    <select class="form-control" id="author_id" name="author_id" style="width: 100%;">
        @if (isset($news) && $news->author_id && $news->author)
            <option value="{{ $news->author_id }}" selected>
                {{ $news->author->getTranslation('name', app()->getLocale(), false) ?: $news->author->getTranslation('name', 'en', true) }}
            </option>
        @endif
    </select>
</div>

@include('admin.partials.sitemap-form')

<br>

{{-- select2 for author --}}
<script>
    jQuery(document).ready(function ($) {
        $('#author_id').select2({
            ajax: {
                url: '/admin/news-authors/select?lang={!! App::currentLocale() !!}',
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
        @if (isset($news) && $news->author_id && $news->author)
            $('#author_id').trigger('change');
        @endif
    });
</script>
{{-- end select2 for author --}}

{{-- select2 for tags --}}
<script>
    jQuery(document).ready(function ($) {
        $('#tag_ids').select2({
            multiple: true,
            ajax: {
                url: '/admin/news-tags/select?lang={!! App::currentLocale() !!}',
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
                    const results = data.data.map(function (tag) {
                        return {
                            id: tag.id,
                            text: tag.name
                        };
                    });
                    return {
                        results: results
                    };
                },
                cache: true
            },
            placeholder: 'Type to search and select tags...',
            minimumInputLength: 0,
            allowClear: true,
            width: '100%'
        });
    });
</script>
{{-- end select2 for tags --}}

{{-- auto-fill slug from title --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        if (titleInput && slugInput && !slugInput.disabled) {
            titleInput.addEventListener('blur', function () {
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
{{-- end status --}}
