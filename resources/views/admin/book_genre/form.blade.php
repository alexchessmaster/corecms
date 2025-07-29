<div class="mb-3">
    <label for="name" class="form-label required">Name</label>
    <input type="text" class="form-control" id="name" name="name"
        value="{{ isset($bookGenre) ? $bookGenre->getTranslation('name', app()->getLocale(), false) : '' }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label" >Slug</label>
    <input type="text" class="form-control" id="slug" name="slug"
        value="{{ isset($bookGenre) ? $bookGenre->getTranslation('slug', app()->getLocale(), false) : '' }}" 
        @if(isset($bookGenre))
            required
        @endif
        disabled
    >
</div>
<div class="mb-3">
    <label for="parent_id" class="form-label">Parent bookGenre</label>
    <select class="form-control" id="parent_id" name="parent_id">
        <option value="">None</option>
        @foreach ($bookGenres as $parent)
            <option value="{{ $parent->id }}"
                {{ isset($bookGenre) && $bookGenre->parent_id == $parent->id ? 'selected' : '' }}>
                {{ $parent->getTranslation('name', app()->getLocale(), false) }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="description" class="form-label required">Description</label>
    <textarea class="form-control tinymce" id="description" name="description" rows="5" required>
        {{ isset($bookGenre) ? $bookGenre->getTranslation('description', app()->getLocale(), false) : '' }}
    </textarea>
</div>

@include('admin.partials.sitemap-form')

<br>

{{-- @include('admin.partials.tinymce-full') --}}
