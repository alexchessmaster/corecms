{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> --}}

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    {{-- <div class="mb-3">
        <label for="key" class="form-label">Key</label>
        <input type="text" name="key" id="key" class="form-control"
            value="{{ old('key', $widget->key ?? '') }}" required>
    </div> --}}

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-control"
            value="{{ old('name', $widget->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="key" class="form-label">User Note</label>
        <textarea name="key" id="key" class="form-control">{{ old('key', $widget->key ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" id="image" class="form-control">
        @if (!empty($widget->image))
            <img src="{{ $widget->image }}" alt="Widget Image" class="mt-2" style="max-width: 100px;">
        @endif
    </div>

    <div class="form-group">
        <label for="exampleFormControlSelect12 required">Position</label>
        <select class="form-control" name="order" id="exampleFormControlSelect12">
            @if (isset($widget))
                <option value="">- Keep current position</option>
            @endif
            <option value="0">- First item</option>
            @foreach ($widgets as $item)
                @if (isset($widget))
                    @if ($item->id !== $widget->id)
                        <option value="{{ $item->order }}">AFTER: {{ $item->name }}</option>
                    @endif
                @else
                    <option value="{{ $item->order }}">AFTER: {{ $item->name }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="active" class="form-label">Is Active</label>
        <select name="active" id="active" class="form-control" required>
            <option value="1" {{ old('active', $widget->active ?? true) ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !old('active', $widget->active ?? true) ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="locked_fields_value" class="form-label">The field values are always locked in different
            places</label>
        <select name="locked_fields_value" id="locked_fields_value" class="form-control" required>
            <option value="0" {{ isset($widget) && $widget->locked_fields_value == false ? 'selected' : '' }}>
                Normal</option>
            <option value="1" {{ isset($widget) && $widget->locked_fields_value == true ? 'selected' : '' }}>
                Locked
            </option>
        </select>
        <small id="" class="form-text text-muted">For example for the "Footer widget" or "Big Header widget" we
            always need same values in every page.</small>
    </div>

    {{-- <hr>

     <div class="mb-3">
        <label for="fields" class="form-label">Fields</label>
        <select name="fields[]" id="fields" class="form-control select2" multiple="multiple">
            @foreach ($fields as $field)
                <option value="{{ $field->id }}"
                    {{ in_array($field->id, isset($widget) ? $widget->fields()->pluck('id')->toArray() : []) ? 'selected' : '' }}>
                    {{ $field->type }}
                </option>
            @endforeach
        </select>
    </div> 

    <hr> --}}

    <button type="submit" class="btn btn-success">Save</button>
</form>

{{-- @push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select Fields',
                allowClear: true,
                theme: 'bootstrap-5',
            });
        });
    </script>
@endpush --}}
