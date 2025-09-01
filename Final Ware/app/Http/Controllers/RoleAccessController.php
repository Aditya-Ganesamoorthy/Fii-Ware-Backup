<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RolePage;

use Illuminate\Support\Facades\DB; // ✅ ADD THIS LINE
use App\Models\Role;

class RoleAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */ 

public function index(Request $request)
{
    $query = RolePage::with('role');

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->whereHas('role', function ($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        })->orWhere('page_name', 'like', "%$search%");
    }

    $accesses = $query->get();

    return view('role_access.index', compact('accesses'));
}

public function create() {
    $roles = Role::all();

    $pages = DB::table('role_pages')->select('page_name')->distinct()->get();


        return view('role_access.create', compact('roles', 'pages')); 
}

public function store(Request $request) {
    $pageName = $request->input('page_name_input') ?? $request->input('page_name_select');

    if (!$pageName) {
        return back()->withErrors(['page_name' => 'Page name is required.']);
    }

    RolePage::create([
        'role_id' => $request->role_id,
        'page_name' => $pageName,
    ]);

    return redirect()->route('role_access.index')->with('success', 'Access added');
}



public function destroy($id) {
    RolePage::destroy($id);
    return back()->with('success', 'Access removed');
}
}