@extends('layouts.dashboard')

@section('title', 'Role Management')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Role Management</h1>
@stop

@section('content')
    <!-- Create Role Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Add New Role</h3>
        </div>
        
        <div class="card-body">
            
    @if(in_array('roles.store', $allowedRoutes))
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="font-weight-bold">Role Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           required
                           value="{{ old('name') }}"
                    >
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary mt-3">
                    Create Role
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Existing Roles Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Existing Roles</h3>
        </div>
        
        <div class="card-body p-0">
            @if($roles->isEmpty())
                <div class="p-4 text-center text-muted">
                    No roles available.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Role Name</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            
    @if(in_array('roles.edit', $allowedRoutes))
                                            <a href="{{ route('roles.edit', $role->id) }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                Edit
                                            </a>@endif
                                            
    @if(in_array('roles.destroy', $allowedRoutes))<form action="{{ route('roles.destroy', $role->id) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>@endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

    .table th, .table td {
        vertical-align: middle;
    }

    .btn-outline-warning:hover,
    .btn-outline-danger:hover {
        color: #fff !important;
    }

    .form-control {
        border-radius: 4px;
        transition: all 0.2s ease-in-out;
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