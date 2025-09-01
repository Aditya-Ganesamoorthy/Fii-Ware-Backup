<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('roles.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.create')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        return redirect()->route('roles.create')->with('success', 'Role updated successfully.');
    }

   public function destroy($id)
{
    $role = Role::with('users')->findOrFail($id);

    // Delete associated users first
    $role->users()->delete();

    // Then delete the role itself
    $role->delete();

    return redirect()->route('roles.create')->with('success', 'Role and its users deleted successfully.');
}

}
