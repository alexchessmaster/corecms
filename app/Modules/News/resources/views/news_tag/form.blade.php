<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" 
           value="{{ isset($tag) ? $tag->getTranslation('name', app()->getLocale(), false) : '' }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label">slug</label>
    <input type="text" class="form-control" id="slug" name="slug" 
           value="{{ isset($tag) ? $tag->getTranslation('slug', app()->getLocale(), false) : '' }}" disabled>
</div>
