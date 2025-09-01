<div class="space-y-4">
    <!-- Name Field -->
    <div class="form-group">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-input @error('name') invalid-input @enderror" 
               id="name" name="name" 
               value="{{ old('name', $driver->name ?? '') }}"
               placeholder="Aditya" required>
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone Number Field -->
    <div class="form-group">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="tel" class="form-input @error('phone_number') invalid-input @enderror" 
               id="phone_number" name="phone_number" 
               value="{{ old('phone_number', $driver->phone_number ?? '') }}"
               placeholder="9876543210" required>
        @error('phone_number')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <!-- License Number Field -->
    <div class="form-group">
        <label for="license_number" class="form-label">License Number</label>
        <input type="text" class="form-input @error('license_number') invalid-input @enderror" 
               id="license_number" name="license_number" 
               value="{{ old('license_number', $driver->license_number ?? '') }}"
               placeholder="TN40 12345678901" required>
        @error('license_number')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <!-- Address Field -->
    <div class="form-group">
        <label for="address" class="form-label">Full Address</label>
        <textarea class="form-input @error('address') invalid-input @enderror" 
                  id="address" name="address" rows="3"
                  placeholder="123 Main St, Coimbatore, Tamilnadu - 123 456" required>{{ old('address', $driver->address ?? '') }}</textarea>
        @error('address')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <!-- Date of Birth Field -->
    <div class="form-group">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" class="form-input @error('date_of_birth') invalid-input @enderror" 
               id="date_of_birth" name="date_of_birth" 
               max="{{ now()->subYears(18)->format('Y-m-d') }}"
               value="{{ old('date_of_birth', isset($driver->date_of_birth) ? $driver->date_of_birth->format('Y-m-d') : '') }}" required>
        @error('date_of_birth')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <!-- Joined Date Field -->
    <div class="form-group">
        <label for="joined_date" class="form-label">Joined Date</label>
        <input type="date" class="form-input @error('joined_date') invalid-input @enderror" 
               id="joined_date" name="joined_date" 
               max="{{ now()->format('Y-m-d') }}"
               value="{{ old('joined_date', isset($driver->joined_date) ? $driver->joined_date->format('Y-m-d') : '') }}" required>
        @error('joined_date')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>
</div>