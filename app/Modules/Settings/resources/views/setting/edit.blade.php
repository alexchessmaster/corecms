@extends('shared::partials.app')
@section('content-card-title', 'Menu')
@section('content-card-body')

    <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST">
        @method('patch')
        @csrf

        <div class="form-group">
            <label for="exampleInputEmail1">Key</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name" name="key" value="{{ isset($setting) ? $setting->key : '' }}" disabled>
            <small id="emailHelp" class="form-text text-muted">Key of the setting</small>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_translatable" name="is_translatable" value="1"
                {{ isset($setting) && $setting->is_translatable ? 'checked' : '' }}>
            <label class="form-check-label" for="is_translatable">The value should be translatable for each languages</label>
        </div>
        <div class="form-group" id="value-container">
            <label for="exampleInputEmail12">Value</label>
            <input type="text" class="form-control" id="value" aria-describedby="emailHelp" placeholder="Enter value"
                name="value" value="{{ isset($setting) ? $setting->value : '' }}">
            <small id="emailHelp" class="form-text text-muted">Value of the setting</small>
        </div>
        <div class="form-group" id="values-container">
            @foreach ($languages as $language)
                <label for="{{ $language->name }}">Value for {{ $language->name }} Language</label>
                <input type="text" class="form-control" id="{{ $language->name }}"
                    aria-describedby="{{ $language->name }}" placeholder="Value for {{ $language->name }}"
                    name="{{ $language->code }}" value="{{ isset($values[$language->code]) ? $values[$language->code] : '' }}">
                <small id="emailHelp" class="form-text text-muted">Value of the setting for {{ $language->name }}</small>
            @endforeach
        </div>
        <div class="form-group">
            <label for="exampleInputEmail12">Description</label>
            <input type="text" class="form-control" id="description" aria-describedby="emailHelp"
                placeholder="Enter value" name="description" value="{{ isset($setting) ? $setting->description : '' }}">
            <small id="emailHelp" class="form-text text-muted">Description of the record</small>
        </div>

        <input type="submit" class="btn btn-success" value="Save">
    </form>

    <script>
        const isTranslatableInput = document.getElementById('is_translatable');
        const handleCheckboxToggle = () => {
            const valueContainer = document.getElementById('value-container');
            const valuesContainer = document.getElementById('values-container');
            if (isTranslatableInput.checked) {
                valueContainer.style.display = 'none';
                valuesContainer.style.display = 'block';
            } else {
                if(document.getElementById('value').value.includes(':{')){
                    document.getElementById('value').value = '';
                }
                valuesContainer.style.display = 'none';
                valueContainer.style.display = 'block';
            }
        }
        isTranslatableInput.addEventListener('click', handleCheckboxToggle);
        handleCheckboxToggle()
    </script>

@endsection
