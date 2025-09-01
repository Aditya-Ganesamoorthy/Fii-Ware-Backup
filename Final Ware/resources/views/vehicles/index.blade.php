@extends('layouts.dashboard')

@section('title', 'Vehicle Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="font-weight-bold text-dark">Vehicle Management</h1>
        </div>
        <div class="col-sm-6">
            @if(in_array('vehicles.create', $allowedRoutes))
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Add New Vehicle
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

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">All Vehicles</h3>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="liveSearch" class="form-control float-right" placeholder="Search by plate, type, etc...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plate Number</th>
                        <th>Vehicle Type</th>
                        <th>Model Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="vehicleTableBody">
                    @forelse ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->id }}</td>
                        <td>{{ $vehicle->plate_number }}</td>
                        <td>{{ $vehicle->vehicle_type }}</td>
                        <td>{{ $vehicle->model_year }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if(in_array('vehicles.edit', $allowedRoutes))
                                <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endif
                                
                                @if(in_array('vehicles.destroy', $allowedRoutes))
                                <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No vehicles found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            <div class="float-right">
                <form method="GET" action="{{ route('vehicles.index') }}" class="form-inline float-right">
                    <label for="per_page" class="mr-2">Rows per page:</label>
                    <select name="per_page" id="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
            {{ $vehicles->appends(request()->query())->links('pagination::bootstrap-4') }}
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
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .d-flex.gap-2 > * {
        margin-right: 0.5rem;
    }
    .d-flex.gap-2 > *:last-child {
        margin-right: 0;
    }
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    #liveSearch {
        transition: all 0.3s ease;
    }
    #liveSearch:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearch');
    const tableRows = document.querySelectorAll('#vehicleTableBody tr');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();

        tableRows.forEach(row => {
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