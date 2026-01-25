<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        $permissions = Permission::with(['roles'])->orderBy('name')->paginate(300);

        if (request()->ajax()) {
            $data = Permission::with(['roles'])->select(['id', 'name']);
            // Define a set of colors for up to 10 roles
            $roleColors = [
                'bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info',
                'bg-secondary', 'bg-dark', 'bg-light text-dark', 'bg-teal', 'bg-purple'
            ];
            // Assign color by hashing role name for consistency
            $getRoleColor = function ($role) use ($roleColors) {
                $hash = crc32($role);
                $index = $hash % count($roleColors);
                return $roleColors[$index];
            };
            return datatables()
                ->of($data)
                ->addColumn('roles', function ($item) use ($getRoleColor) {
                    $roles = $item->roles->pluck('name')->toArray();
                    $allRoles = array_map(function ($role) use ($getRoleColor) {
                        $color = $getRoleColor($role);
                        return '<span class="badge ' . $color . ' me-1 mb-1">' . $role . '</span>';
                    }, $roles);
                    return $roles ? implode(' ', $allRoles) : '';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.permissions.edit', $row->id);
                    $deleteUrl = route('admin.permissions.destroy', $row->id);
                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>
                    <form action="' . $deleteUrl . '" method="POST" style="display: inline-block;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
                })
                ->rawColumns(['roles', 'actions'])
                ->make(true);
        }

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $this->authorize('create', Permission::class);
        $roles = \Spatie\Permission\Models\Role::with(['users'])->orderBy('name')->get();
        return view('admin.permissions.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        $permission = Permission::create(['name' => $request->name]);
        
        // Sync roles to the permission
        if ($request->role_ids) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $request->role_ids)->pluck('name')->toArray();
            $permission->syncRoles($roles);
        } else {
            $permission->syncRoles([]);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission has been created successfully');
    }

    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);
        $roles = \Spatie\Permission\Models\Role::with('users')->orderBy('name')->get();
        $assignedRoles = $permission->roles->pluck('id')->toArray();
        return view('admin.permissions.edit', compact('permission', 'roles', 'assignedRoles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        $permission->update(['name' => $request->name]);
        
        // Sync roles to the permission
        if ($request->role_ids) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $request->role_ids)->pluck('name')->toArray();
            $permission->syncRoles($roles);
        } else {
            $permission->syncRoles([]);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission has been updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete permission assigned to roles');
        }
        
        $permission->delete();
        
        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission has been deleted successfully');
    }
}