@php
    if (isset($page)) {
        $model = $page;
    } elseif (isset($article)) {
        $model = $article;
    } elseif (isset($article)) {
        $model = $article;
    } elseif (isset($bookGenre)) {
        $model = $bookGenre;
    } elseif (isset($category)) {
        $model = $category;
    }
@endphp

<div style="background: rgb(202, 202, 202);padding:5px">
    <h5>Sitemap</h5>
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <label for="sitemap_priority" class="form-label">Sitemap priority</label>
            <select class="form-control" id="sitemap_priority" name="sitemap_priority">
                <option value="">Default</option>
                <option value="0.1" {{ isset($model) && $model->sitemap_priority == '0.1' ? 'selected' : '' }}>
                    0.1</option>
                <option value="0.2" {{ isset($model) && $model->sitemap_priority == '0.2' ? 'selected' : '' }}>
                    0.2</option>
                <option value="0.3" {{ isset($model) && $model->sitemap_priority == '0.3' ? 'selected' : '' }}>
                    0.3</option>
                <option value="0.4" {{ isset($model) && $model->sitemap_priority == '0.4' ? 'selected' : '' }}>
                    0.4</option>
                <option value="0.5" {{ isset($model) && $model->sitemap_priority == '0.5' ? 'selected' : '' }}>
                    0.5</option>
                <option value="0.6" {{ isset($model) && $model->sitemap_priority == '0.6' ? 'selected' : '' }}>
                    0.6</option>
                <option value="0.7" {{ isset($model) && $model->sitemap_priority == '0.7' ? 'selected' : '' }}>
                    0.7</option>
                <option value="0.8" {{ isset($model) && $model->sitemap_priority == '0.8' ? 'selected' : '' }}>
                    0.8</option>
                <option value="0.9" {{ isset($model) && $model->sitemap_priority == '0.9' ? 'selected' : '' }}>
                    0.9</option>
                <option value="1.0" {{ isset($model) && $model->sitemap_priority == '1.0' ? 'selected' : '' }}>
                    1.0</option>
            </select>
            <small id="name" class="form-text text-muted">Do not change it if you don't know
                what is this.</small>
        </div>
        <div class="col-sm-4 col-xs-12">
            <label for="sitemap_change_frequency" class="form-label">Sitemap change
                frequency</label>
            <select class="form-control" id="sitemap_change_frequency" name="sitemap_change_frequency">
                <option value="">Default</option>
                <option value="always"
                    {{ isset($model) && $model->sitemap_change_frequency == 'always' ? 'selected' : '' }}>
                    always
                </option>
                <option value="hourly"
                    {{ isset($model) && $model->sitemap_change_frequency == 'hourly' ? 'selected' : '' }}>
                    hourly
                </option>
                <option value="daily"
                    {{ isset($model) && $model->sitemap_change_frequency == 'daily' ? 'selected' : '' }}>
                    daily
                </option>
                <option value="weekly"
                    {{ isset($model) && $model->sitemap_change_frequency == 'weekly' ? 'selected' : '' }}>
                    weekly
                </option>
                <option value="monthly"
                    {{ isset($model) && $model->sitemap_change_frequency == 'monthly' ? 'selected' : '' }}>
                    monthly
                </option>
                <option value="yearly"
                    {{ isset($model) && $model->sitemap_change_frequency == 'yearly' ? 'selected' : '' }}>
                    yearly
                </option>
            </select>
            <small id="name" class="form-text text-muted">Do not change it if you don't know
                what is
                this.</small>
        </div>
        <div class="col-sm-4 col-xs-12">
            <label for="primary_language" class="form-label">Primary Language</label>
            <select class="form-control" id="primary_language" name="primary_language">
                <option value="default">Default</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->code }}"
                        {{ isset($model) && $model->primary_language === $language->code ? 'selected' : '' }}>
                        {{ $language->name }}</option>
                @endforeach
            </select>
            <small id="name" class="form-text text-muted">If it's default, the default website
                language is the
                value.</small>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="sitemap_exclude" id="sitemap_exclude"
                    {{ isset($model) && !empty($model->sitemap_exclude) ? 'checked' : '' }}>
                <label for="sitemap_exclude" class="form-check-label">Exclude from sitemap</label>
            </div>
            <small id="name" class="form-text text-muted">Do not change it if you don't know
                what is
                this.</small>
        </div>
    </div>
</div>
