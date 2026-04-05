<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" 
           value="{{ isset($tag) ? $tag->getTranslation('name', app()->getLocale(), false) : '' }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label">slug</label>
    <input type="text" class="form-control" id="slug" name="slug" 
           value="{{ isset($tag) ? $tag->getTranslation('slug', app()->getLocale(), false) : '' }}" required>
</div>

{{-- auto-fill slug from title --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (mainInput && slugInput && !slugInput.disabled) {
            mainInput.addEventListener('blur', function () {
                if (slugInput.value.trim() === '') {
                    slugInput.value = mainInput.value
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
