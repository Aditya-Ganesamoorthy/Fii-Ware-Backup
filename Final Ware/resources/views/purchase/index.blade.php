@extends('layouts.dashboard')

@section('title', 'All Purchases')


@section('content')

<h1 class="fw-bold text-dark">📦 All Purchases</h1>
<div class="container-fluid"
     x-data="{
        selectedVendorId: '',
        vendors: {{ $vendors->toJson() }},
        purchases: {{ $purchases->toJson() }}
     }">

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(in_array('purchase.create', $allowedRoutes))
    {{-- ➕ Add New Purchase --}}
    <div class="mb-3 text-end">
        <a href="{{ route('purchase.create') }}" class="btn btn-success">➕ New Purchase</a>
    </div>
    @endif

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

    {{-- 📋 Purchases Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">All Purchases</h3>
                <form method="GET" action="{{ route('purchase.index') }}" class="form-inline">
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
        
        <div class="card-body table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Purchase ID</th>
                        <th>Vendor</th>
                        <th>Products</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(group, purchaseId) in purchases" :key="purchaseId">
                        <template x-if="!selectedVendorId || group[0].vendor.id == selectedVendorId">
                            <tr>
                                <td x-text="group[0].id"></td>
                                <td x-text="group[0].purchase_id"></td>
                                <td x-text="group[0].vendor.company_name"></td>
                                <td>
                                    <ul class="text-start ps-3">
                                        <template x-for="item in group" :key="item.product_id">
                                            <li>
                                                <strong x-text="item.product_name"></strong> (Qty: <span x-text="item.quantity"></span>)
                                            </li>
                                        </template>
                                    </ul>
                                </td>
                                <td x-text="new Date(group[0].purchase_date).toLocaleDateString()"></td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a :href="'/purchase/' + group[0].purchase_id" class="btn btn-sm btn-info">View</a>
                                    <a :href="'/purchase/' + group[0].id + '/edit'" class="btn btn-sm btn-warning">Edit</a>
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

