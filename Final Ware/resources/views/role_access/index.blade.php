@extends('layouts.dashboard')

@section('title', 'Role Page Access')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2 class="mb-0">Role Page Access Management</h2>
        
    @if(in_array('role_access.create', $allowedRoutes))
        <a href="{{ route('role_access.create') }}" class="btn btn-success btn-sm shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Add Role Page Access
        </a>@endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 🔍 Search Bar --}}
    <form method="GET" action="{{ route('role_access.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Role or Page Name..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Page Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accesses as $access)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $access->role->name }}</td>
                            <td>{{ $access->page_name }}</td>
                            <td class="text-center">
                                
    @if(in_array('role_access.destroy', $allowedRoutes))
                                <form action="{{ route('role_access.destroy', $access->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this access?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty 
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No role page access entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
