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
<div class="custom-control custom-checkbox">
    <input class="custom-control-input custom-control-input-danger" type="checkbox"
        name="hide_from_frontend" id="hide_from_frontend" value="1"
        @checked(old('hide_from_frontend', isset($bookGenre) ? $bookGenre->hide_from_frontend : false))>
    <label for="hide_from_frontend" class="custom-control-label">Hide it from frontend</label>
</div>
<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    @if (isset($bookGenre) && $bookGenre->image)
        <div class="mb-2">
            <img src="{{ $bookGenre->image }}" alt="Current Image" style="max-width: 150px; height: auto;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto;" />
    </div>
    <input type="file" class="form-control" id="image" name="image"
        @if (!(isset($bookGenre) && $bookGenre->image)) required @endif>
</div>

@include('shared::partials.sitemap-form')

<br>

{{-- @include('shared::partials.tinymce-full') --}}
