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
    <label for="description" class="form-label required">Description</label>
    <textarea class="form-control" id="description" name="description" rows="2">{{ isset($book) ? $book->getTranslation('description', app()->getLocale(), false) : '' }}</textarea>
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

{{-- <div class="mb-3">
    <label for="template_page_id" class="form-label">Template page</label>
    <select class="form-control" id="template_page_id" name="template_page_id" required>
        @foreach ($pages as $page)
            <option value="{{ $page->id }}"
                {{ isset($book) && $book->template_page_id == $page->id ? 'selected' : '' }}
                {{ !isset($book) && $page->getTranslation('title', app()->getLocale()) === 'book' ? 'selected' : '' }}>
                {{ $page->getTranslation('title', app()->getLocale()) }}
            </option>
        @endforeach
    </select>
    <small id="name" class="form-text text-muted">Do not change it if you don't know what is this.</small>
</div> --}}

@include('admin.partials.sitemap-form')

<br>


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
