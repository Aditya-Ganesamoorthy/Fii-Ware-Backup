<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index(Request $request)
{
    $query = Vendor::whereRaw('flag != ?', [pack('C', 1)]); // Only non-deleted vendors

    if ($request->filled('table_search')) {
        $search = $request->input('table_search');
        $query->where(function ($q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
              ->orWhere('authorized_person', 'like', "%{$search}%")
              ->orWhere('gst_number', 'like', "%{$search}%")
              ->orWhere('mobile_number', 'like', "%{$search}%");
        });
    }

    $vendors = $query->latest()->paginate($request->per_page ?? 10)->appends([
        'table_search' => $request->input('table_search'),
        'per_page' => $request->input('per_page'),
    ]);

    return view('vendors.index', compact('vendors'));
}


    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
        'company_name' => [
            'required',
            'string',
            'max:100',
            'regex:/^[A-Za-z0-9\s\.\-&]+$/',
            'unique:vendors,company_name'
        ],
        'authorized_person' => 'required|string|max:60|regex:/^[A-Za-z\s\.]+$/',
        'address' => 'required|string|max:255',
        'gst_number' => [
            'required',
            'string',
            'size:15',
            'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            'unique:vendors,gst_number'
        ],
        'mobile_number' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
    ]);

    Vendor::create($validated);

    return redirect()->route('vendors.create')
        ->with('success', 'Vendor created successfully. You can now add another vendor.');
}

   public function show(Vendor $vendor)
{
    if ($vendor->flag === pack('C', 1)) {
        abort(404); // Prevent access to deleted vendor
    }

    return view('vendors.show', compact('vendor'));
}


    public function edit(Vendor $vendor)
    {
        if ($vendor->flag === pack('C', 1)) {
    abort(404);
}

        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
         $validated = $request->validate([
        'company_name' => [
            'required',
            'string',
            'max:100',
            'regex:/^[A-Za-z0-9\s\.\-&]+$/',
            Rule::unique('vendors')->ignore($vendor->id)
        ],
        'authorized_person' => 'required|string|max:60|regex:/^[A-Za-z\s\.]+$/',
        'address' => 'required|string|max:255',
        'gst_number' => [
            'required',
            'string',
            'size:15',
            'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            Rule::unique('vendors')->ignore($vendor->id)
        ],
        'mobile_number' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
    ]);

    // Check if any field has changed
    $changed = false;
    foreach ($validated as $key => $value) {
        if ($vendor->$key !== $value) {
            $changed = true;
            break;
        }
    }

    if (!$changed) {
        return back()
            ->withErrors(['no_change' => 'No changes detected. Please edit at least one field before updating.'])
            ->withInput();
    }

    $vendor->update($validated);

    if ($vendor->flag === pack('C', 1)) {
    abort(404);
}


    return redirect()->route('vendors.index')
        ->with('success', 'Vendor updated successfully');
}


   public function destroy(Vendor $vendor)
{
    // Instead of deleting, set the flag to 1
    $vendor->flag = pack('C', 1); // For binary(1), use pack to store the byte
    $vendor->save();

    return redirect()->route('vendors.index')
        ->with('success', 'Vendor marked as deleted successfully');
}


    public function checkCompany(Request $request)
{
    $query = Vendor::where('company_name', $request->company_name);
    
    if ($request->has('exclude_id')) {
        $query->where('id', '!=', $request->exclude_id);
    }
    
    return response()->json(['exists' => $query->exists()]);
}

public function checkGst(Request $request)
{
    $query = Vendor::where('gst_number', $request->gst_number);
    
    if ($request->has('exclude_id')) {
        $query->where('id', '!=', $request->exclude_id);
    }
    
    return response()->json(['exists' => $query->exists()]);
}
}