<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $is_admin = $request->is_admin;
        $password = $request->password;
        $repeat_password = $request->repeat_password;
        $see_edit_button_on_texts = $request->see_edit_button_on_texts;

        if($password !== $repeat_password){

            return redirect()->back();
        }
        $user = [
            'name' => $name,
            'email' => $email,
            'is_admin' => true,
        ];
        if(!empty($password)){
            $user['password'] = bcrypt($password);
        }
        User::create($user);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $name = $request->name;
        $email = $request->email;
        $is_admin = $request->is_admin;
        $password = $request->password;
        $repeat_password = $request->repeat_password;
        $see_edit_button_on_texts = $request->see_edit_button_on_texts;

        if($password !== $repeat_password){

            return redirect()->back();
        }
        $user->name = $name;
        $user->email = $email;
        $user->is_admin = $is_admin;
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
