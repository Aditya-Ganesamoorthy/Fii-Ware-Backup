<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
   public function index(Request $request)
{
    $query = Driver::query();

    if ($request->filled('search')) {  // Changed from 'table_search' to 'search'
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone_number', 'like', "%{$search}%")
              ->orWhere('license_number', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%");
        });
    }

    $drivers = $query->latest()->paginate($request->per_page ?? 10)->appends([
        'search' => $request->input('search'),  // Make sure this matches
        'per_page' => $request->input('per_page')
    ]);
    
    return view('drivers.index', compact('drivers'));
}

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:10|unique:drivers,phone_number',
            'license_number' => [
                'required',
                'string',
                'unique:drivers,license_number',
                'regex:/^[a-zA-Z]{2}\d{2} \d{11}$/'
            ],
            'address' => 'required|string',
            'date_of_birth' => 'required|date|before:-18 years',
            'joined_date' => 'required|date|after_or_equal:date_of_birth'
        ], [
            'phone_number.unique' => 'This phone number is already registered.',
            'license_number.unique' => 'This license number is already registered.',
            'license_number.regex' => 'License number must be in the format SS00 12345678901, where SS is the state code.'
        ]);

        $validated['license_number'] = strtoupper($validated['license_number']);
        Driver::create($validated);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver created successfully!');
    }

    public function show(Driver $driver)
    {
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:10|unique:drivers,phone_number,'.$driver->id,
            'license_number' => [
                'required',
                'string',
                'unique:drivers,license_number,'.$driver->id,
                'regex:/^[a-zA-Z]{2}\d{2} \d{11}$/'
            ],
            'address' => 'required|string',
            'date_of_birth' => 'required|date|before:-18 years',
            'joined_date' => 'required|date|after_or_equal:date_of_birth'
        ], [
            'phone_number.unique' => 'This phone number is already registered.',
            'license_number.unique' => 'This license number is already registered.',
            'license_number.regex' => 'License number must be in the format SS00 12345678901, where SS is the state code.'
        ]);

        // Check if any field has changed
        $changed = false;
        foreach ($validated as $key => $value) {
            if ($driver->$key !== $value) {
                $changed = true;
                break;
            }
        }

        if (!$changed) {
            return back()
                ->withErrors(['no_change' => 'No changes detected. Please edit at least one field before updating.'])
                ->withInput();
        }

        $validated['license_number'] = strtoupper($validated['license_number']);
        $driver->update($validated);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver updated successfully!');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver deleted successfully!');
    }
}