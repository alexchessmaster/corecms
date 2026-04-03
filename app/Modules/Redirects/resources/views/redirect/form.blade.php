<div class="form-group">
    <label for="from">From</label>
    <input type="text" class="form-control" id="from" name="from" 
        value="{{ old('from', $redirect ? $redirect->from : '') }}" 
        required>
</div>

<div class="form-group">
    <label for="to">To</label>
    <input type="text" class="form-control" id="to" name="to" 
        value="{{ old('to', $redirect ? $redirect->to : '') }}" 
        required>
</div>

<div class="form-group">
    <label for="languages" class="form-label">Language</label>
    <select class="form-control" id="languages" name="language">
        @foreach($languages as $language)
            {{-- @if(!empty($language->getTranslation('language', app()->getLocale(), false))) --}}
                <option value="{{ $language->code }}" 
                        {{ isset($redirect) && ($redirect->language === $language->code) ? 'selected' : '' }}>
                    {{ $language->name }}
                </option>
            {{-- @endif --}}
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="type">Type</label>
    <select class="form-control" id="type" name="type" required>
        <option value="manual" {{ old('type', $redirect ? $redirect->type : '') == 'manual' ? 'selected' : '' }}>Manual</option>
        <option value="import" {{ old('type', $redirect ? $redirect->type : '') == 'import' ? 'selected' : '' }}>Import</option>
        <option value="slug_changed" {{ old('type', $redirect ? $redirect->type : '') == 'slug_changed' ? 'selected' : '' }}>Slug Changed</option>
    </select>
</div>
