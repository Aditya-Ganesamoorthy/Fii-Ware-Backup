@extends('layouts.dashboard')

@section('title', 'Stock Available Report')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-info shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-boxes mr-2"></i>Stock Available Report
            </h3>
        </div>

        <div class="card-body">
            <!-- Live Search Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Products</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="text" id="liveSearch" class="form-control" placeholder="Search by product name, company, SKU, etc...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Stock Results</h3>
                    <div class="card-tools">
                        <form method="GET" action="{{ route('reports.stock') }}" class="form-inline">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <label for="per_page" class="mr-2">Rows per page</label>
                            <select name="per_page" id="per_page" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>PID</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Category</th>
                                    <th>Style</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->pid }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->company }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->style ?? '-' }}</td>
                                    <td>{{ $product->color ?? '-' }}</td>
                                    <td>{{ $product->size ?? '-' }}</td>
                                    <td>{{ $product->quantity }}</td>
                                   <td>
    <a href="{{ route('reports.stock.product', $product->id) }}" class="btn btn-xs btn-info" title="View">
        <i class="fas fa-eye"></i>
    </a>
</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No products found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
    <div class="float-right">
        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
    </div>
    <div class="pagination-container">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-responsive {
        min-height: 200px;
    }
    .card-tools .input-group {
        width: auto;
    }
    #search-suggestions {
        position: absolute;
        z-index: 1000;
        width: 100%;
    }
    #search-suggestions .list-group-item {
        cursor: pointer;
    }
    #search-suggestions .list-group-item:hover {
        background-color: #f8f9fa;
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
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const searchInput = $('#product-search');
    const suggestionsContainer = $('#search-suggestions');
    
    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Fetch product suggestions
    const fetchSuggestions = debounce(function(query) {
        if (query.length < 2) {
            suggestionsContainer.hide().empty();
            return;
        }
        $.ajax({
            url: '{{ route("reports.stock.suggestions") }}',
            method: 'GET',
            data: { query: query },
            success: function(response) {
                if (response.length > 0) {
                    suggestionsContainer.empty();
                    response.forEach(product => {
                        suggestionsContainer.append(
                            `<a href="#" class="list-group-item list-group-item-action" 
                              data-name="${product.name}">
                                ${product.name}
                            </a>`
                        );
                    });
                    suggestionsContainer.show();
                } else {
                    suggestionsContainer.hide().empty();
                }
            }
        });
    }, 300);
    
    // Handle input changes
    searchInput.on('input', function() {
        fetchSuggestions($(this).val());
    });
    
    // Handle suggestion click
    suggestionsContainer.on('click', '.list-group-item', function(e) {
        e.preventDefault();
        searchInput.val($(this).data('name'));
        suggestionsContainer.hide();
        searchInput.closest('form').submit();
    });
    
    // Hide suggestions when clicking elsewhere
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#product-search, #search-suggestions').length) {
            suggestionsContainer.hide();
        }
    });
});
</script>

@push('scripts')
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