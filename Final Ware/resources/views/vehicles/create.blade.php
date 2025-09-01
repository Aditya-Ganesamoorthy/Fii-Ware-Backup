@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Vehicle</h3>
                    <div class="card-tools">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-default">
                            <i class="fas fa-arrow-left mr-1"></i> Back to All Vehicles
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('vehicles.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="vehicle_type">Vehicle Type</label>
                            <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" 
                                   id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" 
                                   placeholder="e.g., Pickup Truck" required>
                            @error('vehicle_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="model_year">Model Year</label>
                            <input type="number" class="form-control @error('model_year') is-invalid @enderror" 
                                id="model_year" name="model_year" 
                                min="1900" max="{{ now()->addYear()->year }}"
                                value="{{ old('model_year') }}"
                                placeholder="e.g., 2020" required>
                            @error('model_year')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="plate_number">Plate Number</label>
                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                   id="plate_number" name="plate_number" value="{{ old('plate_number') }}"
                                   placeholder="e.g., TN40 AB 1234" required>
                            @error('plate_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="card-footer text-right">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-default mr-2">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Create Vehicle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/vendor/adminlte/dist/css/adminlte.min.css">
@stop

@section('js')
    <script src="/vendor/adminlte/dist/js/adminlte.min.js"></script>
    <script>
        // Auto-dismiss the success alert after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@stop