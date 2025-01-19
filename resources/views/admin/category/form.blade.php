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
<div style="background: rgb(202, 202, 202);padding:5px">
    <h5>Sitemap</h5>
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <label for="sitemap_priority" class="form-label">Sitemap priority</label>
            <select class="form-control" id="sitemap_priority" name="sitemap_priority">
                <option value="">Default</option>
                <option value="0.1" {{ isset($category) && $category->sitemap_priority == '0.1' ? 'selected' : '' }}>
                    0.1</option>
                <option value="0.2" {{ isset($category) && $category->sitemap_priority == '0.2' ? 'selected' : '' }}>
                    0.2</option>
                <option value="0.3" {{ isset($category) && $category->sitemap_priority == '0.3' ? 'selected' : '' }}>
                    0.3</option>
                <option value="0.4" {{ isset($category) && $category->sitemap_priority == '0.4' ? 'selected' : '' }}>
                    0.4</option>
                <option value="0.5" {{ isset($category) && $category->sitemap_priority == '0.5' ? 'selected' : '' }}>
                    0.5</option>
                <option value="0.6" {{ isset($category) && $category->sitemap_priority == '0.6' ? 'selected' : '' }}>
                    0.6</option>
                <option value="0.7" {{ isset($category) && $category->sitemap_priority == '0.7' ? 'selected' : '' }}>
                    0.7</option>
                <option value="0.8" {{ isset($category) && $category->sitemap_priority == '0.8' ? 'selected' : '' }}>
                    0.8</option>
                <option value="0.9" {{ isset($category) && $category->sitemap_priority == '0.9' ? 'selected' : '' }}>
                    0.9</option>
                <option value="1.0" {{ isset($category) && $category->sitemap_priority == '1.0' ? 'selected' : '' }}>
                    1.0</option>
            </select>
            <small id="name" class="form-text text-muted">Do not change it if you don't know what is this.</small>
        </div>
        <div class="col-sm-4 col-xs-12">
            <label for="sitemap_change_frequency" class="form-label">Sitemap change frequency</label>
            <select class="form-control" id="sitemap_change_frequency" name="sitemap_change_frequency">
                <option value="">Default</option>
                <option value="always"
                    {{ isset($category) && $category->sitemap_change_frequency == 'always' ? 'selected' : '' }}>always
                </option>
                <option value="hourly"
                    {{ isset($category) && $category->sitemap_change_frequency == 'hourly' ? 'selected' : '' }}>hourly
                </option>
                <option value="daily"
                    {{ isset($category) && $category->sitemap_change_frequency == 'daily' ? 'selected' : '' }}>daily
                </option>
                <option value="weekly"
                    {{ isset($category) && $category->sitemap_change_frequency == 'weekly' ? 'selected' : '' }}>weekly
                </option>
                <option value="monthly"
                    {{ isset($category) && $category->sitemap_change_frequency == 'monthly' ? 'selected' : '' }}>monthly
                </option>
                <option value="yearly"
                    {{ isset($category) && $category->sitemap_change_frequency == 'yearly' ? 'selected' : '' }}>yearly
                </option>
            </select>
            <small id="name" class="form-text text-muted">Do not change it if you don't know what is
                this.</small>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="sitemap_exclude" id="sitemap_exclude"
                    {{ isset($category) && !empty($category->sitemap_exclude) ? 'checked' : '' }}>
                <label for="sitemap_exclude" class="form-check-label">Exclude from sitemap</label>
            </div>
            <small id="name" class="form-text text-muted">Do not change it if you don't know what is
                this.</small>
        </div>
    </div>
</div>
<br>

@include('admin.partials.tinymce-full')
