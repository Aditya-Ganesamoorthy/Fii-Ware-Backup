@extends('layouts.dashboard')

@section('title', 'Products')



@section('content')

<h1 class="font-weight-bold text-dark">Product List</h1>

    {{-- Add Product Button --}}
    @if(in_array('products.create', $allowedRoutes))
    <div class="mb-3 text-right">
        <a href="{{ route('products.create') }}" class="btn btn-success shadow-sm">
            Add New Product
        </a>
    </div>
    @endif

    {{-- Search Form --}}
    

    {{-- Live Search Bar --}}
    <div class="mb-3">
        <input type="text" id="liveSearch" class="form-control" placeholder="Search by product name, company, SKU, etc...">
    </div>

    {{-- Product Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">All Products</h3>
                <form method="GET" action="{{ route('products.index') }}" class="form-inline">
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
                        <th>Image</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Category</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>style</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail" style="max-width: 70px;" alt="Product">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->company }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->color }}</td>
                            <td>{{ $product->size }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->style }}</td>
                            <td class="text-center">
                                @if(in_array('products.show', $allowedRoutes))
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @endif
                                
                                @if(in_array('products.edit', $allowedRoutes))
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                @endif
                             
                                @if(in_array('products.confirmDelete', $allowedRoutes))
                                <a href="{{ route('products.confirmDelete', $product->id) }}" class="btn btn-sm btn-outline-danger">Delete</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No products available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

    .table img {
        border-radius: 6px;
        transition: transform 0.2s ease-in-out;
    }

    .table img:hover {
        transform: scale(1.05);
    }

    .btn-outline-primary:hover,
    .btn-outline-warning:hover,
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
@stop

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