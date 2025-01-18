@extends('admin.partials.app')
@section('content-card-title', 'Create Tag')
@section('content-card-body')

    <form action="{{ route('admin.translation-texts.update', $translationText->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="key" class="form-label">Key</label>
            <input type="text" name="key" id="key" class="form-control"
                value="{{ old('key', $translationText->key) }}" required>
        </div>

        @foreach ($languages as $language)
            <div class="mb-3">
                <label for="{{ $language->code }}" class="form-label">Translation in {{ $language->code }} language</label>
                <input name="lang-{{ $language->code }}" id="{{ $language->code }}" class="form-control"
                    value="{{ old('lang-' . $language->code, $translations[$language->code] ?? '') }}" required />
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Update</button>
    </form>

@endsection
