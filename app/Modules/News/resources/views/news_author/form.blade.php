<div class="form-group">
    <label for="name" class="required">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', isset($newsAuthor) ? $newsAuthor->getTranslation('name', app()->getLocale(), false) : '') }}"
        placeholder="Enter author name" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Name of the author</small>
</div>

<div class="form-group">
    <label for="date_of_birth">Date of Birth</label>
    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth"
        name="date_of_birth"
        value="{{ old('date_of_birth', isset($newsAuthor) && $newsAuthor->date_of_birth ? $newsAuthor->date_of_birth->format('Y-m-d') : '') }}">
    @error('date_of_birth')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Author's birth date</small>
</div>

<div class="form-group">
    <label for="date_of_death">Date of Death</label>
    <input type="date" class="form-control @error('date_of_death') is-invalid @enderror" id="date_of_death"
        name="date_of_death"
        value="{{ old('date_of_death', isset($newsAuthor) && $newsAuthor->date_of_death ? $newsAuthor->date_of_death->format('Y-m-d') : '') }}">
    @error('date_of_death')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Author's death date (if applicable)</small>
</div>

<div class="form-group">
    <label for="nationality">Nationality</label>
    <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality"
        name="nationality"
        value="{{ old('nationality', isset($newsAuthor) ? $newsAuthor->getTranslation('nationality', app()->getLocale(), false) : '') }}"
        placeholder="Enter nationality">
    @error('nationality')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Author's nationality</small>
</div>

<div class="form-group">
    <label for="biography">Biography</label>
    <textarea class="form-control @error('biography') is-invalid @enderror" id="biography" name="biography" rows="5"
        placeholder="Enter author biography">{{ old('biography', isset($newsAuthor) ? $newsAuthor->getTranslation('biography', app()->getLocale(), false) : '') }}</textarea>
    @error('biography')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Author's biography</small>
</div>

<div class="form-group">
    <label for="image" class="form-label">Author Image</label>
    @if (isset($newsAuthor) && $newsAuthor->image)
        <div class="mb-2">
            <img src="{{ $newsAuthor->image }}" alt="Current Author Image"
                style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;">
        </div>
    @endif
    <div class="mb-2" id="preview-container" style="display: none;">
        <img id="image-preview" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;" />
    </div>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
        accept="image/*">
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Upload author image (JPG, JPEG, PNG, WEBM, GIF - Max: 2MB)</small>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview-container').style.display = 'none';
        }
    });
</script>
