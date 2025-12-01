<div class="mb-3">
    <label for="template_id" class="form-label">Template</label>
    <select name="template_id" id="template_id" class="form-control" required>
        <option value="">Select Template</option>
        @foreach($templates as $template)
            <option value="{{ $template->id }}" {{ old('template_id', $timeSlot->template_id ?? '') == $template->id ? 'selected' : '' }}>
                {{ $template->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="start_time" class="form-label">Start Time</label>
    <input type="datetime-local" name="start_time" id="start_time" class="form-control" 
           value="{{ old('start_time', isset($timeSlot) ? $timeSlot->start_time->format('Y-m-d\TH:i') : '') }}" required>
</div>

<div class="mb-3">
    <label for="end_time" class="form-label">End Time</label>
    <input type="datetime-local" name="end_time" id="end_time" class="form-control" 
           value="{{ old('end_time', isset($timeSlot) ? $timeSlot->end_time->format('Y-m-d\TH:i') : '') }}" required>
</div>

<div class="mb-3">
    <label for="max_capacity" class="form-label">Max Capacity</label>
    <input type="number" name="max_capacity" id="max_capacity" class="form-control" 
           value="{{ old('max_capacity', $timeSlot->max_capacity ?? '') }}" min="1" required>
</div>

<div class="mb-3">
    <label for="is_active" class="form-label">Active</label>
    <select name="is_active" id="is_active" class="form-control">
        <option value="1" {{ old('is_active', $timeSlot->is_active ?? 1) == 1 ? 'selected' : '' }}>Yes</option>
        <option value="0" {{ old('is_active', $timeSlot->is_active ?? 1) == 0 ? 'selected' : '' }}>No</option>
    </select>
</div>

<div class="mb-3">
    <label for="is_manually_disabled" class="form-label">Manually Disabled</label>
    <select name="is_manually_disabled" id="is_manually_disabled" class="form-control">
        <option value="0" {{ old('is_manually_disabled', $timeSlot->is_manually_disabled ?? 0) == 0 ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('is_manually_disabled', $timeSlot->is_manually_disabled ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
    </select>
</div>

<div class="mb-3">
    <label for="admin_notes" class="form-label">Admin Notes</label>
    <textarea name="admin_notes" id="admin_notes" class="form-control" rows="4">{{ old('admin_notes', $timeSlot->admin_notes ?? '') }}</textarea>
</div>
