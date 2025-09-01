@extends('layouts.dashboard')

@section('title', 'Edit Role')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Edit Role: {{ $role->name }}</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Role Information</h3>
        </div>

        
        <div class="card-body">
            
    @if(in_array('roles.update', $allowedRoutes))
            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="font-weight-bold">Role Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $role->name) }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           required
                    >
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('roles.create') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update Role
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
@stop

@section('css')
<style>
    .card {
        border-radius: 8px;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
        color: #fff;
        font-weight: 600;
    }

    .form-control {
        border-radius: 4px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@stop

@section('js')
<script>
    // Add any necessary JavaScript here
</script>
@stop