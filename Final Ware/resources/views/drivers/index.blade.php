@extends('layouts.dashboard')

@section('title', 'Driver Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="font-weight-bold text-dark">Driver Management</h1>
        </div>
        <div class="col-sm-6">
            @if(in_array('drivers.create', $allowedRoutes))
            <a href="{{ route('drivers.create') }}" class="btn btn-success float-right shadow-sm">
                <i class="fas fa-plus-circle mr-2"></i> Add New Driver
            </a>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

   {{-- Live Search Bar --}}
<div class="mb-3">
    <input type="text" id="liveSearch" class="form-control" placeholder="Search Drivers...">
</div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">All Drivers</h3>
                <form method="GET" action="{{ route('drivers.index') }}" class="form-inline">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label for="per_page" class="mr-2 text-white">Rows per page</label>
                    <select name="per_page" id="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-hover table-striped mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone No</th>
                        <th>License No</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($drivers as $driver)
                    <tr>
                        <td>{{ $driver->id }}</td>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->phone_number }}</td>
                        <td>{{ $driver->license_number }}</td>
                        <td>{{ $driver->address }}</td>
                        <td>{{ $driver->date_of_birth->format('M d, Y') }}</td>
                        <td>{{ $driver->joined_date->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if(in_array('drivers.edit', $allowedRoutes))
                                <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">Edit
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(in_array('drivers.destroy', $allowedRoutes))
                                <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">Delete
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No drivers found. Please add a new driver.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            <div class="float-right">
                Showing {{ $drivers->firstItem() }} to {{ $drivers->lastItem() }} of {{ $drivers->total() }} entries
            </div>
            <div class="pagination-container">
                {{ $drivers->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

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

    .btn-outline-primary:hover,
    .btn-outline-danger:hover {
        color: #fff !important;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }
    
    /* Pagination styling */
    .pagination-container .pagination {
        margin: 0;
        flex-wrap: wrap;
    }
    
    .pagination-container .page-item {
        margin: 2px;
    }
    
    .pagination-container .page-link {
        padding: 0.3rem 0.6rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #3490dc;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
    
    .pagination-container .page-item.active .page-link {
        color: #fff;
        background-color: #3490dc;
        border-color: #3490dc;
    }
    
    .pagination-container .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        cursor: auto;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    .pagination-container .page-link:hover {
        color: #1d68a7;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.025);
    }
    
    .d-flex.gap-2 > * {
        margin-right: 0.5rem;
    }
    .d-flex.gap-2 > *:last-child {
        margin-right: 0;
    }
</style>

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearch');
    const tableRows = document.querySelectorAll('table tbody tr');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();

        tableRows.forEach(row => {
            // Combine all cell text for searching
            const rowText = row.textContent.trim().toLowerCase();
            if (rowText.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>