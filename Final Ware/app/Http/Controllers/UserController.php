<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

public function index(Request $request)
{
    $query = User::with('role');

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhereHas('role', function ($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%'); // ✅ FIXED: 'name' is the correct column
              });
        });
    }

    $users = $query->orderBy('created_at', 'desc')->get();

    return view('users.index', compact('users'));
}



    // Show form to create user
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $plainPassword = $request->password;

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($plainPassword),
            'role_id'  => $request->role_id,
        ]);

        $roleName = Role::find($request->role_id)->name ?? 'User';

        // Send email with credentials
        Mail::to($user->email)->send(new UserCreatedMail($user->email, $plainPassword, $roleName));

        return redirect()->route('dashboard')->with('status', 'User created and email sent successfully.');
    }

    // Show edit user form
    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    // Show delete confirmation
    public function confirmDelete(User $user)
    {
        return view('users.confirm-delete', compact('user'));
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('status', 'User deleted successfully.');
    }

    
}
