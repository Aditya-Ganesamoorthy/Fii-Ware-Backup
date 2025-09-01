<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form with roles from DB.
     */
    public function create(): View
    {
        $roles = Role::all(); // Fetch all roles from MySQL
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle new user registration and store selected role.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
    'name'     => ['required', 'string', 'max:255'],
    'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'role_id'  => ['required', 'exists:roles,id'], // 👈 validate foreign key
]);

$user = User::create([
    'name'     => $request->name,
    'email'    => $request->email,
    'password' => Hash::make($request->password),
    'role_id'  => $request->role_id, // 👈 insert role_id
]);


        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
