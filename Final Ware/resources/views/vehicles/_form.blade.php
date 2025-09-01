<div class="card-body">
    <!-- Vehicle Type Field -->
    <div class="form-group">
        <label for="vehicle_type">Vehicle Type</label>
        <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" 
               id="vehicle_type" name="vehicle_type" 
               value="{{ old('vehicle_type', $vehicle->vehicle_type ?? '') }}"
               placeholder="e.g., Pickup Truck" required>
        @error('vehicle_type')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Model Year Field -->
    <div class="form-group">
        <label for="model_year">Model Year</label>
        <input type="number" class="form-control @error('model_year') is-invalid @enderror" 
               id="model_year" name="model_year" 
               min="1900" max="{{ now()->addYear()->year }}"
               value="{{ old('model_year', $vehicle->model_year ?? '') }}"
               placeholder="e.g., 2020" required>
        @error('model_year')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Plate Number Field -->
    <div class="form-group">
        <label for="plate_number">Plate Number</label>
        <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
               id="plate_number" name="plate_number" 
               value="{{ old('plate_number', $vehicle->plate_number ?? '') }}"
               placeholder="e.g., TN40 AB 1234" required>
        @error('plate_number')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<style>
    /* Number input spinner styling */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>