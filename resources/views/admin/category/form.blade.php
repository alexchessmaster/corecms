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
        disabled
    >
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
<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="exclude_from_sitemap" name="exclude_from_sitemap">
        <label for="exclude_from_sitemap" class="form-check-label">Exclude from sitemap</label>
    </div>
</div>

@include('admin.partials.tinymce-full')
