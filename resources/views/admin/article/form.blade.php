<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($article) && $article->image)
        <div class="mb-2">
            <img src="{{ $article->image }}" alt="Current Image" 
                 style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;" />
    </div>
    <input type="file" class="form-control" id="image" name="image" 
    @if(!(isset($article) && $article->image))
    required
    @endif
    >
</div>
<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" id="title" name="title" 
           value="{{ isset($article) ? $article->getTranslation('title', app()->getLocale(), false) : '' }}" required>
</div>
@if(isset($article))
<div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" 
           value="{{ isset($article) ? $article->getTranslation('slug', app()->getLocale(), false) : '' }}" required>
</div>
@endif
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" rows="2">{{ isset($article) ? $article->getTranslation('description', app()->getLocale(), false) : '' }}</textarea>
</div>
<div class="mb-3">
    <label for="content" class="form-label">Content</label>
    <textarea class="form-control tinymce" id="content" name="content" rows="5" required>{{ isset($article) ? $article->getTranslation('content', app()->getLocale(), false) : '' }}</textarea>
</div>
<div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select class="form-control" id="category_id" name="category_id" required>
        @foreach($categories as $category)
            @if(!empty($category->getTranslation('name', app()->getLocale(), false)))
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
        @foreach($tags as $tag)
            @if(!empty($tag->getTranslation('name', app()->getLocale(), false)))
                <option value="{{ $tag->id }}" 
                        {{ isset($article) && $article->tags->contains($tag->id) ? 'selected' : '' }}>
                    {{ $tag->getTranslation('name', app()->getLocale()) }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="template_page_id" class="form-label">Template page</label>
    <select class="form-control" id="template_page_id" name="template_page_id" required>
        @foreach($pages as $page)
            <option value="{{ $page->id }}" 
                    {{ isset($article) && $article->template_page_id == $page->id ? 'selected' : '' }}
                    {{ !isset($article) && $page->getTranslation('title', app()->getLocale()) === 'article' ? 'selected' : '' }}
                    >
                {{ $page->getTranslation('title', app()->getLocale()) }}
            </option>
        @endforeach
    </select>
    <small id="name" class="form-text text-muted">Do not change it if you don't know what is this.</small>
</div>

@include('admin.partials.tinymce-full')


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