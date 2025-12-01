<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $template->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="days_of_week" class="form-label">Days of Week (comma separated, 1=Mon ... 7=Sun)</label>
    <input type="text" name="days_of_week" id="days_of_week" class="form-control" value="{{ old('days_of_week', isset($template) ? implode(',', $template->days_of_week ?? []) : '') }}" required>
</div>
<div class="mb-3">
    <label for="start_time" class="form-label">Start Time</label>
    <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $template->start_time ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="end_time" class="form-label">End Time</label>
    <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $template->end_time ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="slot_duration_minutes" class="form-label">Slot Duration (minutes)</label>
    <input type="number" name="slot_duration_minutes" id="slot_duration_minutes" class="form-control" value="{{ old('slot_duration_minutes', $template->slot_duration_minutes ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="max_capacity" class="form-label">Max Capacity</label>
    <input type="number" name="max_capacity" id="max_capacity" class="form-control" value="{{ old('max_capacity', $template->max_capacity ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="valid_from" class="form-label">Valid From</label>
    <input type="date" name="valid_from" id="valid_from" class="form-control" value="{{ old('valid_from', $template->valid_from ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="valid_until" class="form-label">Valid Until</label>
    <input type="date" name="valid_until" id="valid_until" class="form-control" value="{{ old('valid_until', $template->valid_until ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="is_active" class="form-label">Active</label>
    <select name="is_active" id="is_active" class="form-control">
        <option value="1" {{ old('is_active', $template->is_active ?? 1) == 1 ? 'selected' : '' }}>Yes</option>
        <option value="0" {{ old('is_active', $template->is_active ?? 1) == 0 ? 'selected' : '' }}>No</option>
    </select>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control">{{ old('description', $template->description ?? '') }}</textarea>
</div>
