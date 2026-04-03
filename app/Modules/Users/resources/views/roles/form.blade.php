@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label required">Role Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', isset($role) ? $role->name : '') }}" required
        placeholder="e.g., admin, editor, author, moderator">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">
        Use lowercase letters and descriptive names
    </small>
</div>

<div class="mb-3">
    <label class="form-label">Assign Permissions</label>
    <div class="border rounded p-3" style="height: 400px; overflow-y: auto;">
        @if ($permissions->count() > 0)
            <div class="mb-2">
                <button type="button" class="btn btn-sm btn-secondary" id="select-all">Select All</button>
                <button type="button" class="btn btn-sm btn-secondary" id="deselect-all">Deselect All</button>
            </div>
            <div class="row">
                @foreach ($permissions as $permission)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input permission-checkbox" type="checkbox" 
                                   name="permission_ids[]" 
                                   value="{{ $permission->id }}" 
                                   id="permission_{{ $permission->id }}"
                                   {{ isset($assignedPermissions) && in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">No permissions available. <a href="{{ route('admin.permissions.create') }}">Create permissions first</a>.</p>
        @endif
    </div>
    <small class="form-text text-muted">
        Select the permissions that this role should have
    </small>
</div>

@if (isset($role))
    <div class="mb-3">
        <label class="form-label">Assigned Users</label>
        <div>
            @if ($role->users->count() > 0)
                <span class="badge bg-info">{{ $role->users->count() }} users have this role</span>
            @else
                <span class="text-muted">No users assigned to this role</span>
            @endif
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('select-all');
        const deselectAllBtn = document.getElementById('deselect-all');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(checkbox => checkbox.checked = true);
            });
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(checkbox => checkbox.checked = false);
            });
        }
    });
</script>
