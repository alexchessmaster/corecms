<div class="mb-3">
    <label for="user_id" class="form-label">Registered User (Optional)</label>
    <select name="user_id" id="user_id" class="form-control">
        <option value="">-- Guest Booking --</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('user_id', $reservation->user_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">Leave empty for guest bookings</small>
</div>

<div class="mb-3">
    <label for="booking_time_slot_id" class="form-label">Time Slot <span class="text-danger">*</span></label>
    <select name="booking_time_slot_id" id="booking_time_slot_id" class="form-control" required>
        <option value="">-- Select Time Slot --</option>
        @foreach($timeSlots as $slot)
            <option value="{{ $slot->id }}" {{ old('booking_time_slot_id', $reservation->booking_time_slot_id ?? '') == $slot->id ? 'selected' : '' }}>
                {{ $slot->start_time->format('Y-m-d H:i') }} - {{ $slot->end_time->format('H:i') }} 
                (Available: {{ $slot->availableCapacity() }}/{{ $slot->max_capacity }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control" required>
        <option value="pending" {{ old('status', $reservation->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="confirmed" {{ old('status', $reservation->status ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
        <option value="cancelled" {{ old('status', $reservation->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
</div>

<div class="mb-3">
    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $reservation->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $reservation->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="mobile_number" class="form-label">Mobile Number</label>
    <input type="text" name="mobile_number" id="mobile_number" class="form-control" value="{{ old('mobile_number', $reservation->mobile_number ?? '') }}">
</div>

<div class="mb-3">
    <label for="age" class="form-label">Age</label>
    <input type="number" name="age" id="age" class="form-control" min="1" max="150" value="{{ old('age', $reservation->age ?? '') }}">
</div>

<div class="mb-3">
    <label for="service" class="form-label">Service</label>
    <input type="text" name="service" id="service" class="form-control" value="{{ old('service', $reservation->service ?? '') }}">
</div>

<div class="mb-3">
    <label for="expires_at" class="form-label">Expires At</label>
    <input type="datetime-local" name="expires_at" id="expires_at" class="form-control" 
           value="{{ old('expires_at', isset($reservation->expires_at) ? $reservation->expires_at->format('Y-m-d\TH:i') : '') }}">
    <small class="form-text text-muted">Leave empty for no expiration</small>
</div>

<div class="mb-3">
    <label for="comments" class="form-label">Comments</label>
    <textarea name="comments" id="comments" class="form-control" rows="4">{{ old('comments', $reservation->comments ?? '') }}</textarea>
</div>
