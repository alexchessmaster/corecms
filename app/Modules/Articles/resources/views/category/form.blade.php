<div class="mb-3">
    <label for="name" class="form-label required">Name</label>
    <input type="text" class="form-control" id="name" name="name"
        value="{{ isset($category) ? $category->getTranslation('name', app()->getLocale(), false) : '' }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label" >Slug</label>
    <input type="text" class="form-control" id="slug" name="slug"
        value="{{ isset($category) ? $category->getTranslation('slug', app()->getLocale(), false) : '' }}"
        @if(isset($category))
            required
        @endif
    >
    <small class="text-muted">Should start with "/"</small>
</div>
<div class="mb-3">
    <label for="parent_id" class="form-label">Parent Category</label>
    <select class="form-control" id="parent_id" name="parent_id">
        <option value="">None</option>
        @foreach ($categories as $parent)
            <option value="{{ $parent->id }}"
                {{ isset($category) && $category->parent_id == $parent->id ? 'selected' : '' }}>
                {{ $parent->getTranslation('name', app()->getLocale(), false) }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="description" class="form-label required">Description</label>
    <textarea class="form-control tinymce" id="description" name="description" rows="5" required>
        {{ isset($category) ? $category->getTranslation('description', app()->getLocale(), false) : '' }}
    </textarea>
</div>
<div class="custom-control custom-checkbox">
    <input class="custom-control-input custom-control-input-danger" type="checkbox"
        name="hide_from_frontend" id="hide_from_frontend" value="1"
        @checked(old('hide_from_frontend', isset($category) ? $category->hide_from_frontend : false))>
    <label for="hide_from_frontend" class="custom-control-label">Hide it from frontend</label>
</div>
<br>
@include('shared::partials.sitemap-form')

<br>

{{-- @include('shared::partials.tinymce-full') --}}

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
