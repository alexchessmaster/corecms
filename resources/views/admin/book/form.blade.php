<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($book) && $book->image)
        <div class="mb-2">
            <img src="{{ $book->image }}" alt="Current Image" style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;" />
    </div>
    <input type="file" class="form-control" id="image" name="image"
        @if (!(isset($book) && $book->image)) required @endif>
</div>
<div class="mb-3">
    <label for="title" class="form-label required">Title</label>
    <input type="text" class="form-control" id="title" name="title"
        value="{{ isset($book) ? $book->getTranslation('title', app()->getLocale(), false) : '' }}" required>
</div>
@if (isset($book))
    <div class="mb-3">
        <label for="slug" class="form-label required">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug"
            value="{{ isset($book) ? $book->getTranslation('slug', app()->getLocale(), false) : '' }}" required>
    </div>
@endif
<div class="mb-3">
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="draft"
                {{ isset($book) && $book->status === 'draft' ? 'selected' : '' }}>Draft
            </option>
            <option value="published"
                {{ isset($book) && $book->status === 'published' ? 'selected' : '' }}>Published
            </option>
            <option value="scheduled"
                {{ isset($book) && $book->status === 'scheduled' ? 'selected' : '' }}>Scheduled
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
    <textarea class="form-control" id="description" name="description" rows="2">{{ isset($book) ? $book->getTranslation('description', app()->getLocale(), false) : '' }}</textarea>
</div>
<div class="mb-3">
    <label for="author" class="form-label">Author</label>
    <input type="text" class="form-control" id="author" name="author"
        value="{{ isset($book) ? $book->getTranslation('author', app()->getLocale(), false) : '' }}">
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
