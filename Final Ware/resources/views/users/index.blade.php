@extends('layouts.dashboard')

@section('title', 'User Management')

@section('content_header')
    <h1 class="font-weight-bold text-dark">User List</h1>
@stop

@section('content')

    {{-- Back & Add User Buttons --}}
    <div class="card-tools mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
            ⬅ Back
        </a>

        @if(in_array('users.create', $allowedRoutes))
            <a href="{{ route('users.create') }}" class="btn btn-success shadow-sm">
                + Add User
            </a>
        @endif
    </div>

    {{-- Search Bar --}}
    {{-- Search Bar --}}
<form method="GET" action="{{ route('users.index') }}" class="mb-3">
    <div class="input-group" style="max-width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or role"
               value="{{ request('search') }}">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>


    {{-- User Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">All Users</h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-hover table-striped mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                {{ $user->name }}<br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('d / m / Y') }}
                                </small>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-{{ $user->role ? 'primary' : 'secondary' }}" style="color: black;">
                                    {{ $user->role->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @if(in_array('users.edit', $allowedRoutes))
                                        <a href="{{ route('users.edit', $user->id) }}" 
                                           class="btn btn-sm btn-outline-warning">Edit</a>
                                    @endif
                                    @if(in_array('users.confirmDelete', $allowedRoutes))
                                        <a href="{{ route('users.confirmDelete', $user->id) }}" 
                                           class="btn btn-sm btn-outline-danger">Delete</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No users available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

    table th, table td {
        vertical-align: middle !important;
    }

    .btn-outline-warning:hover,
    .btn-outline-danger:hover {
        color: #fff !important;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
</style>
@stop

@section('js')
{{-- No JS needed --}}
@stop
