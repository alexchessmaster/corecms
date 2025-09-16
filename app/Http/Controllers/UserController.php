<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'role']);

            return DataTables::of($users)
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
        $user = new User();
        $roles = $user->roles;

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $role = $request->role;
        $password = $request->password;
        $repeat_password = $request->repeat_password;
        $see_edit_button_on_texts = $request->see_edit_button_on_texts;

        if($password !== $repeat_password){

            return redirect()->back();
        }
        
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->role = $role;
        $user->show_edit_button_on_texts = $see_edit_button_on_texts;
        if(!empty($password)){
            $user->password = bcrypt($password);
        }
        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        $roles = $user->roles;

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $name = $request->name;
        $email = $request->email;
        $role = $request->role;
        $password = $request->password;
        $repeat_password = $request->repeat_password;
        $see_edit_button_on_texts = $request->see_edit_button_on_texts;

        if($password !== $repeat_password){

            return redirect()->back();
        }
        $user->name = $name;
        $user->email = $email;
        $user->role = $role;
        $user->show_edit_button_on_texts = $see_edit_button_on_texts;
        if(!empty($password)){
            $user->password = bcrypt($password);
        }

        $user->save();

        return redirect()->route('admin.users.edit', $user->id);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
