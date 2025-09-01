<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
{
    $query = Vehicle::query();

    if ($request->filled('table_search')) {
        $search = $request->input('table_search');
        $query->where(function ($q) use ($search) {
            $q->where('plate_number', 'like', "%{$search}%")
              ->orWhere('vehicle_type', 'like', "%{$search}%")
              ->orWhere('model_year', 'like', "%{$search}%");
        });
    }

    $vehicles = $query->latest()->paginate(
        $request->per_page ?? 10
    )->appends([
        'table_search' => $request->table_search,
        'per_page' => $request->per_page
    ]);

    return view('vehicles.index', compact('vehicles'));
}

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'vehicle_type' => 'required|string|max:50',
        'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        'plate_number' => [
            'required',
            'string',
            'max:20',
            'unique:vehicles',
            'regex:/^[a-zA-Z]{2}\d{2} [A-Z]{1,2} \d{4}$/i'
        ]
    ], [
        'plate_number.regex' => 'Plate number must be in the format SS00 AB 1234 or SS00 A 1234, where SS is the state code.'
    ]);

    $validated['plate_number'] = strtoupper($validated['plate_number']);
    Vehicle::create($validated);

    return redirect()->route('vehicles.create')
        ->with('success', 'Vehicle created successfully!');
}

    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:50',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'plate_number' => [
                'required',
                'string',
                'max:20',
                'unique:vehicles,plate_number,' . $vehicle->id,
                'regex:/^[a-zA-Z]{2}\d{2} [A-Z]{1,2} \d{4}$/i'
            ]
        ], [
            'plate_number.regex' => 'Plate number must be in the format SS00 AB 1234 or SS00 A 1234, where SS is the state code.'
        ]);

        // Check if any field has changed
        $changed = false;
        foreach ($validated as $key => $value) {
            if ($vehicle->$key !== $value) {
                $changed = true;
                break;
            }
        }

        if (!$changed) {
            return back()
                ->withErrors(['no_change' => 'No changes detected. Please edit at least one field before updating.'])
                ->withInput();
        }

        $validated['plate_number'] = strtoupper($validated['plate_number']);
        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully!');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully!');
    }
}