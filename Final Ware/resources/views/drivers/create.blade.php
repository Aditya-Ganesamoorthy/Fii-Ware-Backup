@extends('layouts.dashboard')

@section('title', 'Add New Driver')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Driver</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('drivers.store') }}" method="POST" novalidate class="needs-validation">
                        @csrf
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $driver->name ?? '') }}"
                                   placeholder="e.g., Aditya" required minlength="3" maxlength="60" pattern="^[A-Za-z\s]+$">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                
<input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
       id="phone_number" name="phone_number" value="{{ old('phone_number', $driver->phone_number ?? '') }}"
       placeholder="e.g., 9876543210" required pattern="^[6-9]\d{9}$" maxlength="10" minlength="10">
@error('phone_number')
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
                        </div>

                        <div class="form-group">
                            <label for="license_number">License Number</label>
<input type="text" class="form-control @error('license_number') is-invalid @enderror"
       id="license_number" name="license_number" value="{{ old('license_number', $driver->license_number ?? '') }}"
       placeholder="e.g., TN40 12345678901" required maxlength="20">
@error('license_number')
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Full Address</label>
                           <textarea class="form-control @error('address') is-invalid @enderror"
          id="address" name="address" rows="3"
          placeholder="e.g., 123 Main St, Coimbatore, Tamilnadu - 123 456" required maxlength="255">{{ old('address', $driver->address ?? '') }}</textarea>
@error('address')
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
                        </div>

                       <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <div class="input-group">
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                       id="date_of_birth" name="date_of_birth" 
                       value="{{ old('date_of_birth', isset($driver) ? $driver->date_of_birth->format('Y-m-d') : '') }}"
                       max="{{ now()->subYears(18)->format('Y-m-d') }}" required>
                <div class="input-group-append">
                    <span class="input-group-text" onclick="document.getElementById('date_of_birth').showPicker()">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                </div>
                @error('date_of_birth')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="joined_date">Joined Date</label>
            <div class="input-group">
                <input type="date" class="form-control @error('joined_date') is-invalid @enderror" 
                       id="joined_date" name="joined_date" value="{{ old('joined_date') }}"
                       min="{{ old('date_of_birth') }}" required>
                <div class="input-group-append">
                    <span class="input-group-text" onclick="document.getElementById('joined_date').showPicker()">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                </div>
                @error('joined_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('drivers.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Create Driver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Bootstrap 4/5 custom validation
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.getElementsByClassName('needs-validation');
            Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endpush