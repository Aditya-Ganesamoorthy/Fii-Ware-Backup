@extends('layouts.dashboard')

@section('title', 'Vendors')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="font-weight-bold text-dark">Vendors Management</h1>
        </div>
        <div class="col-sm-6">
            @if(in_array('vendors.create', $allowedRoutes))
            <a href="{{ route('vendors.create') }}" class="btn btn-success float-right shadow-sm">
                <i class="fas fa-plus"></i> Add New Vendor
            </a>
            @endif
        </div>
    </div>

    
{{-- Live Search Bar --}}
<div class="mb-3">
    <input type="text" id="liveSearch" class="form-control" placeholder="Search Vendors by Comapany Name , Authorized Person , Gst ...">
</div>

    {{-- Vendors Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">All Vendors</h3>
                <form method="GET" action="{{ route('vendors.index') }}" class="form-inline">
                    <input type="hidden" name="table_search" value="{{ request('table_search') }}">
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
                        <th width="5%">ID</th>
                        <th width="25%">Company</th>
                        <th width="20%">Authorized Person</th>
                        <th width="15%">Mobile</th>
                        <th width="15%">GST</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->id }}</td>
                        <td>{{ $vendor->company_name }}</td>
                        <td>{{ $vendor->authorized_person }}</td>
                        <td>{{ $vendor->mobile_number }}</td>
                        <td>{{ $vendor->gst_number }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if(in_array('vendors.show', $allowedRoutes))
                                <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-sm btn-outline-info" title="View">View
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                
                                @if(in_array('vendors.edit', $allowedRoutes))
                                <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">Edit
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(in_array('vendors.destroy', $allowedRoutes))
                                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">Delete
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No vendors found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            <div class="float-right">
                Showing {{ $vendors->firstItem() }} to {{ $vendors->lastItem() }} of {{ $vendors->total() }} entries
            </div>
            <div class="pagination-container">
                {{ $vendors->appends(request()->query())->links('pagination::bootstrap-4') }}
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
    .btn-outline-info:hover,
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
