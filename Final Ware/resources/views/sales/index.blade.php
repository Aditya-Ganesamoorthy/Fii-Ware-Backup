@extends('layouts.dashboard')

@section('title', 'Sales List')


@section('content')
<div class="container-fluid"
     x-data="{
        selectedVendorId: '',
        vendors: {{ $vendors->toJson() }},
        sales: {{ $sales->toJson() }},
        formatDate(dateStr) {
            const date = new Date(dateStr);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
     }">

    {{-- ✅ Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ➕ New Sale --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark">📦 All Sales</h3>
        @if(in_array('sales.create', $allowedRoutes))
            <a href="{{ route('sales.create') }}" class="btn btn-success btn-sm">➕ New Sale</a>
        @endif
    </div>

    {{-- 🔍 Vendor Filter --}}
    <div class="mb-4">
        <label for="vendor_filter" class="form-label fw-semibold">Filter by Vendor</label>
        <select id="vendor_filter" class="form-select" x-model="selectedVendorId">
            <option value="">-- Show All Vendors --</option>
            <template x-for="vendor in vendors" :key="vendor.id">
                <option :value="vendor.id" x-text="vendor.company_name"></option>
            </template>
        </select>
    </div>

    {{-- 📋 Sales Table --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Sales Records</h3>
                <form method="GET" action="{{ route('sales.index') }}" class="form-inline">
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
        
        <div class="card-body table-responsive">
            <template x-if="Object.keys(sales).length === 0">
                <p class="text-muted text-center my-4">No sales recorded yet.</p>
            </template>

            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Sales ID</th>
                        <th>Vendor</th>
                        <th>Contact</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(group, index) in sales" :key="group[0].sales_id">
                        <template x-if="!selectedVendorId || group[0].vendor.id == selectedVendorId">
                            <tr>
                                <td class="fw-semibold" x-text="group[0].sales_id"></td>
                                <td x-text="group[0].vendor.company_name || 'N/A'"></td>
                                <td x-text="group[0].vendor.authorized_person || 'N/A'"></td>
                                <td x-text="formatDate(group[0].sales_date ?? group[0].created_at)"></td>
                                <td x-text="group.length"></td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a :href="'/sales/' + group[0].sales_id" class="btn btn-sm btn-info">🔍 View</a>
                                    <a :href="'/sales/' + group[0].sales_id + '/edit'" class="btn btn-sm btn-warning">✏️ Edit</a>
                                    
                                </td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="card-footer clearfix">
            <div class="float-right">
                Showing {{ $pagination->firstItem() }} to {{ $pagination->lastItem() }} of {{ $pagination->total() }} entries
            </div>
            <div class="pagination-container">
                {{ $pagination->appends(request()->query())->links('pagination::bootstrap-4') }}
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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
        color: #fff;
        font-weight: 600;
    }
    
    .table th {
        background-color: #343a40;
        color: white;
        font-weight: 500;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
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
    
    .form-select, .form-control-sm {
        max-width: 300px;
    }
    
    .alert {
        border-left: 4px solid;
    }
    
    .form-inline {
        display: flex;
        align-items: center;
    }
    
    .form-inline label {
        margin-right: 0.5rem;
        margin-bottom: 0;
    }
</style>
@endsection