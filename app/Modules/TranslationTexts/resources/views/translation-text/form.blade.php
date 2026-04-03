<div class="mb-3">
    <label for="key" class="form-label">Key</label>
    <input type="text" name="key" id="key" class="form-control" value="{{ isset($translationText) ? old('key', $translationText->key) : '' }}"
        required>
</div>

@foreach ($languages as $language)
    <div class="mb-3">
        <label for="{{ $language->code }}" class="form-label">Translation in {{ $language->code }} language</label>
        <input name="lang-{{ $language->code }}" id="{{ $language->code }}" class="form-control"
            value="{{ old('lang-' . $language->code, $translations[$language->code] ?? '') }}" required />
    </div>
@endforeach
<script>
document.addEventListener('DOMContentLoaded', function() {
    const keyInput = document.getElementById('key');
    
    if (keyInput) {
        keyInput.addEventListener('input', function() {
            // Convert to lowercase and replace dashes and spaces with underscores
            this.value = this.value
                .toLowerCase()
                .replace(/[-\s]/g, '_');
        });
    }
});
</script>
