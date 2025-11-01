<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        if ($request->ajax()) {
            $users = User::with(['roles'])->select(['id', 'name', 'email']);

            return DataTables::of($users)
                ->editColumn('role', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })
                ->addColumn('actions', function ($user) {
                    return '
                    <a href="' . route('admin.users.edit', $user) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="' . route('admin.users.destroy', $user) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['categories', 'tags', 'actions', 'title'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $this->authorize('create', User::class);
        
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->show_edit_button_on_texts = $request->see_edit_button_on_texts ?? false;
        $user->password = bcrypt($request->password);
        $user->save();
        
        // Assign role using Spatie
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $this->authorize('view', $user);
        
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->show_edit_button_on_texts = $request->see_edit_button_on_texts ?? false;
        
        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }

        $user->save();
        
        // Sync role using Spatie
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
