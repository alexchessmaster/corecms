<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" 
           value="{{ isset($tag) ? $tag->getTranslation('name', app()->getLocale(), false) : '' }}" required>
</div>
