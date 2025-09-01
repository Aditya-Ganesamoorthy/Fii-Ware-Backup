@extends('layouts.dashboard')

@section('title', 'Create Purchase')

@section('content')
<div class="container-fluid">

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops!</strong> Please fix the following issues:
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="purchaseForm" action="{{ route('purchase.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm">
            <div class="card-body">

                <!-- Vendor Selection -->
                <div class="mb-3">
                    <label for="vendor_id" class="form-label fw-semibold">Select Vendor</label>
                    <select id="vendor_id" name="vendor_id" class="form-select" onchange="showVendorDetails()">
                        <option value="">-- Select Vendor --</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->company_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Vendor Details --}}
                @foreach ($vendors as $vendor)
                    <div id="vendor-{{ $vendor->id }}" class="vendor-detail d-none border rounded p-3 mb-3">
                        <h5 class="mb-2">{{ $vendor->company_name }}</h5>
                        <p><strong>Authorized Person:</strong> {{ $vendor->authorized_person }}</p>
                        <p><strong>Address:</strong> {{ $vendor->address }}</p>
                        <p><strong>GST Number:</strong> {{ $vendor->gst_number }}</p>
                        <p><strong>Mobile:</strong> {{ $vendor->mobile_number }}</p>
                    </div>
                @endforeach

                <!-- Dynamic Product Rows -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Products</label>
                    <div id="product-rows">
                        <!-- Initial product row -->
                        <div class="row g-2 mb-2 product-row">
                            <div class="col-md-5 position-relative">
                                <input type="text" class="form-control product-search-input" 
                                       placeholder="Click to select product..." 
                                       autocomplete="off" required>
                                <input type="hidden" name="products[0][product_id]" class="product-id-field">
                                <div class="dropdown-menu w-100 product-suggestions" style="display: none;"></div>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="products[0][quantity]" class="form-control" 
                                       min="1" placeholder="Enter quantity" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-danger remove-product">Delete</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-product" class="btn btn-outline-primary mt-3">Add Product</button>
                </div>

                <!-- Purchase Date -->
                <div class="mb-3">
                    <label for="purchase_date" class="form-label fw-semibold">Purchase Date</label>
                    <div class="input-group" style="max-width: 300px;">
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
                        <span class="input-group-text" onclick="document.getElementById('purchase_date').showPicker()" style="cursor: pointer;">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Submit Purchase
                    </button>
                </div>
            </div>
        </div>

        <a href="{{ route('purchase.index') }}" class="btn btn-secondary my-3">
            ← Back to Purchase List
        </a>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vendor details display
        window.showVendorDetails = function() {
            const selectedId = document.getElementById('vendor_id').value;
            document.querySelectorAll('.vendor-detail').forEach(el => el.classList.add('d-none'));
            if (selectedId) {
                const target = document.getElementById('vendor-' + selectedId);
                if (target) target.classList.remove('d-none');
            }
        };
        showVendorDetails(); // Show vendor details if pre-selected

        // Prepare product data for JS (only name and SKU)
        const products = [
            @foreach($products as $product)
                {
                    id: {{ $product->id }},
                    name: "{{ addslashes($product->name) }}",
                    sku: "{{ addslashes($product->sku) }}"
                },
            @endforeach
        ];

        // Initialize product search functionality
        function initProductSearch(inputElement) {
            // Show dropdown when input is clicked/focused
            inputElement.addEventListener('focus', function() {
                const suggestions = this.parentElement.querySelector('.product-suggestions');
                if (suggestions.innerHTML === '') {
                    // Show all products when first focused
                    products.forEach(product => {
                        suggestions.appendChild(createProductSuggestion(product));
                    });
                }
                suggestions.style.display = 'block';
            });

            // Filter products as user types
            inputElement.addEventListener('input', function() {
                const input = this.value.toLowerCase();
                const suggestions = this.parentElement.querySelector('.product-suggestions');
                suggestions.innerHTML = '';

                if (input.length === 0) {
                    // Show all products when input is empty
                    products.forEach(product => {
                        suggestions.appendChild(createProductSuggestion(product));
                    });
                } else {
                    // Filter products based on input (search in both name and SKU)
                    const filteredProducts = products.filter(product => 
                        product.name.toLowerCase().includes(input) || 
                        product.sku.toLowerCase().includes(input)
                    );
                    
                    if (filteredProducts.length > 0) {
                        filteredProducts.forEach(product => {
                            suggestions.appendChild(createProductSuggestion(product));
                        });
                    } else {
                        suggestions.innerHTML = '<a class="dropdown-item disabled" href="#">No matches found</a>';
                    }
                }
                
                suggestions.style.display = 'block';
            });
        }

        // Create product suggestion item (simplified to show only name and SKU)
        function createProductSuggestion(product) {
            const item = document.createElement('a');
            item.className = 'dropdown-item';
            item.href = '#';
            item.innerHTML = `
                <div class="d-flex justify-content-between">
                    <span><strong>${product.name}</strong></span>
                    <span class="text-muted">${product.sku}</span>
                </div>
            `;
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.position-relative');
                parent.querySelector('.product-search-input').value = product.name;
                parent.querySelector('.product-id-field').value = product.id;
                parent.querySelector('.product-suggestions').style.display = 'none';
            });
            return item;
        }

        // Initialize existing product search inputs
        document.querySelectorAll('.product-search-input').forEach(input => {
            initProductSearch(input);
        });

        // Add new product row
        let rowCounter = 1;
        document.getElementById('add-product').addEventListener('click', function() {
            const productRows = document.getElementById('product-rows');
            const newRow = document.createElement('div');
            newRow.className = 'row g-2 mb-2 product-row';
            newRow.innerHTML = `
                <div class="col-md-5 position-relative">
                    <input type="text" class="form-control product-search-input" 
                           placeholder="Click to select product..." 
                           autocomplete="off" required>
                    <input type="hidden" name="products[${rowCounter}][product_id]" class="product-id-field">
                    <div class="dropdown-menu w-100 product-suggestions" style="display: none;"></div>
                </div>
                <div class="col-md-4">
                    <input type="number" name="products[${rowCounter}][quantity]" class="form-control" 
                           min="1" placeholder="Enter quantity" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger remove-product">Delete</button>
                </div>
            `;
            
            productRows.appendChild(newRow);
            
            // Initialize search for the new row
            initProductSearch(newRow.querySelector('.product-search-input'));
            
            // Add event listener to the new delete button
            newRow.querySelector('.remove-product').addEventListener('click', function() {
                productRows.removeChild(newRow);
            });
            
            rowCounter++;
        });

        // Remove buttons on initial rows
        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.product-row').remove();
            });
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.product-search-input, .product-suggestions')) {
                document.querySelectorAll('.product-suggestions').forEach(el => {
                    el.style.display = 'none';
                });
            }
        });

        // Prevent form submission on Enter key in search fields
        document.querySelectorAll('.product-search-input').forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // Form validation
        document.getElementById('purchaseForm').addEventListener('submit', function(e) {
            const productRows = document.querySelectorAll('.product-row');
            for (let row of productRows) {
                const productId = row.querySelector('.product-id-field').value;
                const quantityInput = row.querySelector('input[type="number"]');
                if (!productId || !quantityInput.value || quantityInput.value <= 0) {
                    alert('Please select a product and enter a valid quantity for all rows.');
                    e.preventDefault();
                    return;
                }
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .form-group.position-relative {
        position: relative;
    }
    .dropdown-menu {
        max-height: 300px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        display: none;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 0.25rem;
        left: 0;
        right: 0;
    }
    .dropdown-item {
        cursor: pointer;
        padding: 0.5rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: normal;
        background-color: transparent;
        border: 0;
    }
    .dropdown-item:hover, .dropdown-item:focus {
        color: #16181b;
        text-decoration: none;
        background-color: #f8f9fa;
    }
    .dropdown-item.disabled {
        color: #6c757d;
        pointer-events: none;
        background-color: transparent;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .product-row {
        align-items: center;
    }
    .vendor-detail {
        background-color: #f8f9fa;
    }
    .product-search-input {
        cursor: pointer;
    }
</style>
@endsection