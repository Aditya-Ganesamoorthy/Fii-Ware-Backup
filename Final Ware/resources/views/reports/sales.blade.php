@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Sales Report</h3>
                        <form method="GET" action="{{ route('reports.sales') }}" class="form-inline">
                            <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                            <input type="hidden" name="to_date" value="{{ request('to_date') }}">
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
                <div class="card-body">
                    <form action="{{ route('reports.sales.filter') }}" method="GET" class="form-inline mb-4">
                        <div class="form-group mr-2">
                            <label for="from_date" class="mr-2">From Date:</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="from_date" name="from_date" 
                                       value="{{ $fromDate ?? '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="document.getElementById('from_date').showPicker()">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mr-2">
                            <label for="to_date" class="mr-2">To Date:</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="to_date" name="to_date" 
                                       value="{{ $toDate ?? '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="document.getElementById('to_date').showPicker()">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('reports.sales') }}" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Sales ID</th>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $index => $sale)
                                    <tr>
                                        <td>{{ $index + $sales->firstItem() }}</td>
                                        <td>{{ $sale->sales_id }}</td>
                                        <td>{{ $sale->created_at->format('d M Y') }}</td>
                                        <td>{{ $sale->vendor->company_name ?? 'N/A' }}</td>
                                        <td>{{ $sale->product->name ?? 'N/A' }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No sales found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer clearfix">
                        <div class="float-right">
                            Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} entries
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $sales->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-right">
                        <button class="btn btn-success" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Report
                        </button>
                        <button class="btn btn-info" id="exportBtn">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Export button functionality
        $('#exportBtn').click(function() {
            let fromDate = $('#from_date').val() || 'all';
            let toDate = $('#to_date').val() || 'all';
            let perPage = $('#per_page').val() || 'all';
            
            window.location.href = "{{ url('reports/sales/export') }}?from_date=" + fromDate + "&to_date=" + toDate + "&per_page=" + perPage;
        });
    });
</script>
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