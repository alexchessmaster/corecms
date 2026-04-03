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
    <label for="name" class="form-label required">Permission Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', isset($permission) ? $permission->name : '') }}" required
        placeholder="e.g., create-articles, edit-users, delete-posts">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">
        Use lowercase letters, hyphens, and descriptive names (e.g., view-articles, manage-users)
    </small>
</div>

<div class="mb-3">
    <label class="form-label">Assign to Roles</label>
    <div class="border rounded p-3">
        @if ($roles->count() > 0)
            <div class="mb-2">
                <button type="button" class="btn btn-sm btn-secondary" id="select-all-roles">Select All</button>
                <button type="button" class="btn btn-sm btn-secondary" id="deselect-all-roles">Deselect All</button>
            </div>
            <div class="row">
                @foreach ($roles as $role)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input role-checkbox" type="checkbox" 
                                   name="role_ids[]" 
                                   value="{{ $role->id }}" 
                                   id="role_{{ $role->id }}"
                                   {{ isset($assignedRoles) && in_array($role->id, $assignedRoles) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->name }}
                                @if ($role->users->count() > 0)
                                    <span class="badge bg-info badge-sm">{{ $role->users->count() }} users</span>
                                @endif
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">No roles available. <a href="{{ route('admin.roles.create') }}">Create roles first</a>.</p>
        @endif
    </div>
    <small class="form-text text-muted">
        Select the roles that should have this permission
    </small>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('select-all-roles');
        const deselectAllBtn = document.getElementById('deselect-all-roles');
        const checkboxes = document.querySelectorAll('.role-checkbox');

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

