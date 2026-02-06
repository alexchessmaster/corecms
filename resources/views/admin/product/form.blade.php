<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($product) && $product->image)
        <div class="mb-2">
            <img src="{{ $product->image }}" alt="Current Image" style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;"/>
    </div>
    <input type="file" class="form-control" id="image" name="image"
           @if (!(isset($product) && $product->image)) required @endif>
</div>
<div class="mb-3">
    <label for="title" class="form-label required">Title</label>
    <input type="text" class="form-control" id="title" name="title"
           value="{{ isset($product) ? $product->getTranslation('title', app()->getLocale(), false) : '' }}" required>
</div>
@if (isset($product))
    <div class="mb-3">
        <label for="slug" class="form-label required">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug"
               value="{{ isset($product) ? $product->getTranslation('slug', app()->getLocale(), false) : '' }}"
               disabled>
        <small><a href="{{ isset($product) ? \App\Modules\Shared\Helpers\UrlHelper::getFrontendUrl($product->getTranslation('slug', app()->getLocale(), false)) : '' }}">{{ isset($product) ? \App\Modules\Shared\Helpers\UrlHelper::getFrontendUrl($product->getTranslation('slug', app()->getLocale(), false)) : '' }}</a></small>
    </div>
@endif
<div class="mb-3">
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="draft" {{ isset($product) && $product->status === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ isset($product) && $product->status === 'published' ? 'selected' : '' }}>
                Published
            </option>
            <option value="scheduled" {{ isset($product) && $product->status === 'scheduled' ? 'selected' : '' }}>
                Scheduled
            </option>
        </select>
    </div>
    <div class="form-group mt-2" id="scheduled_at_group" style="display: none;">
        <label for="scheduled_at">Scheduled At</label>
        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at"
               value="{{ old('scheduled_at', isset($product->scheduled_at) ? $product->scheduled_at->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description (Short)</label>
    <textarea class="form-control" id="description" name="description"
              rows="2">{{ isset($product) ? $product->getTranslation('description', app()->getLocale(), false) : '' }}</textarea>
</div>
<div class="mb-3">
    <label for="category_id" class="form-label required">Category</label>
    <select class="form-control" id="category_id" name="category_id" required>
        @foreach ($categories as $category)
            @if (!empty($category->getTranslation('name', app()->getLocale(), false)))
                <option value="{{ $category->id }}"
                        {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->getTranslation('name', app()->getLocale()) }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="price" class="form-label required">Price</label>
    <input type="number" class="form-control" id="price" name="price" min="0" step="0.01"
           value="{{ isset($product) ? $product->price : '' }}" required>
</div>
<div class="mb-3">
    <label for="stock" class="form-label">Stock</label>
    <input type="number" class="form-control" id="stock" name="stock" min="0"
           value="{{ isset($product) ? $product->stock : '' }}">
</div>

@include('admin.partials.sitemap-form')

<br>

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
