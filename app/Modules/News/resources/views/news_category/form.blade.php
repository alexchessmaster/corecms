<div class="mb-3">
    <label for="name" class="form-label required">Name</label>
    <input type="text" class="form-control" id="name" name="name"
        value="{{ isset($newsCategory) ? $newsCategory->getTranslation('name', app()->getLocale(), false) : '' }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label" >Slug</label>
    <input type="text" class="form-control" id="slug" name="slug"
        value="{{ isset($newsCategory) ? $newsCategory->getTranslation('slug', app()->getLocale(), false) : '' }}" 
        @if(isset($newsCategory))
            required
        @endif
    >
</div>
<div class="mb-3">
    <label for="parent_id" class="form-label">Parent News Category</label>
    <select class="form-control" id="parent_id" name="parent_id">
        <option value="">None</option>
        @foreach ($newsCategories as $parent)
            <option value="{{ $parent->id }}"
                {{ isset($newsCategory) && $newsCategory->parent_id == $parent->id ? 'selected' : '' }}>
                {{ $parent->getTranslation('name', app()->getLocale(), false) }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control tinymce" id="description" name="description" rows="5">
        {{ isset($newsCategory) ? $newsCategory->getTranslation('description', app()->getLocale(), false) : '' }}
    </textarea>
</div>

<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($newsCategory) && $newsCategory->image)
        <div class="mb-2">
            <img src="{{ $newsCategory->image }}" alt="Current Image" style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;" />
    </div>
    <input type="file" class="form-control" id="image" name="image"
        @if (!(isset($newsCategory) && $newsCategory->image)) required @endif>
</div>

@include('admin.partials.sitemap-form')

<br>

{{-- @include('admin.partials.tinymce-full') --}}

{{-- auto-fill slug from title --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('name');
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
</script>
{{-- end auto-fill slug from title --}}
