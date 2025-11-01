<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        $roles = Role::with(['permissions', 'users'])->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);

        $role = Role::create(['name' => $request->name]);
        
        // Sync permissions to the role
        if ($request->permission_ids) {
            $permissions = Permission::whereIn('id', $request->permission_ids)->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role has been created successfully');
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);
        $permissions = Permission::orderBy('name')->get();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'assignedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);

        $role->update(['name' => $request->name]);
        
        // Sync permissions to the role
        if ($request->permission_ids) {
            $permissions = Permission::whereIn('id', $request->permission_ids)->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role has been updated successfully');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users');
        }
        
        $role->delete();
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role has been deleted successfully');
    }
}