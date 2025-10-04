<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($article) && $article->image)
        <div class="mb-2">
            <img src="{{ $article->image }}" alt="Current Image" style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;" />
    </div>
    <input type="file" class="form-control" id="image" name="image"
        @if (!(isset($article) && $article->image)) required @endif>
</div>
<div class="mb-3">
    <label for="title" class="form-label required">Title</label>
    <input type="text" class="form-control" id="title" name="title"
        value="{{ isset($article) ? $article->getTranslation('title', app()->getLocale(), false) : '' }}" required>
</div>
@if (isset($article))
    <div class="mb-3">
        <label for="slug" class="form-label required">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug"
            value="{{ isset($article) ? $article->getTranslation('slug', app()->getLocale(), false) : '' }}" required>
            <small><a href="{{ isset($article) ? App\Helpers\UrlHelper::getFrontendUrl($article->getTranslation('slug', app()->getLocale(), false)) : '' }}">{{ isset($article) ? App\Helpers\UrlHelper::getFrontendUrl($article->getTranslation('slug', app()->getLocale(), false)) : '' }}</a></small>
    </div>
@endif
<div class="mb-3">
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="draft"
                {{ old('status', $page->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft
            </option>
            <option value="published"
                {{ old('status', $page->status ?? '') == 'published' ? 'selected' : '' }}>Published
            </option>
            <option value="scheduled"
                {{ old('status', $page->status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled
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
    <label for="description" class="form-label required">Description</label>
    <textarea class="form-control" id="description" name="description" rows="2">{{ isset($article) ? $article->getTranslation('description', app()->getLocale(), false) : '' }}</textarea>
</div>
<div class="mb-3">
    <label for="category_id" class="form-label required">Category</label>
    <select class="form-control" id="category_id" name="category_id" required>
        @foreach ($categories as $category)
            @if (!empty($category->getTranslation('name', app()->getLocale(), false)))
                <option value="{{ $category->id }}"
                    {{ isset($article) && $article->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->getTranslation('name', app()->getLocale()) }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="tags" class="form-label">Tags</label>
    <select class="form-control" id="tags" name="tags[]" multiple>
        @foreach ($tags as $tag)
            @if (!empty($tag->getTranslation('name', app()->getLocale(), false)))
                <option value="{{ $tag->id }}"
                    {{ isset($article) && $article->tags->contains($tag->id) ? 'selected' : '' }}>
                    {{ $tag->getTranslation('name', app()->getLocale()) }}
                </option>
            @endif
        @endforeach
    </select>
</div>

@include('admin.partials.sitemap-form')

<br>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Run on load to handle edit forms and validation error repopulation
        toggleScheduledInput();
    });
</script>
<script>
    // Get references to the DOM elements
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('image-preview');
    const currentImageContainer = document.getElementById('current-image-container');

    // Event listener for image input
    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file

        if (file) {
            // Create a URL for the selected file
            const fileURL = URL.createObjectURL(file);

            // Hide the current image container if it exists
            if (currentImageContainer) {
                currentImageContainer.style.display = 'none';
            }

            // Display the new image preview
            previewContainer.style.display = 'block';
            previewImage.src = fileURL;
        } else {
            // If no file is selected, reset the preview and show the current image
            previewContainer.style.display = 'none';
            if (currentImageContainer) {
                currentImageContainer.style.display = 'block';
            }
        }
    });
</script>
