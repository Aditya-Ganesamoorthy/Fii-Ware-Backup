@extends('layouts.dashboard')

@section('title', 'Create Sale')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">🛒 Create New Sale</h3>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary float-end">⬅ Back</a>
        </div>
        <!-- form start -->

        <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
            @csrf
            <div class="card-body">

                {{-- Show Success/Error --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Sale Date -->
                <div class="mb-3">
                    <label for="sale_date" class="form-label fw-bold">Sale Date</label>
                    <div class="input-group" style="max-width: 300px;">
                        <input type="date" name="sale_date" id="sale_date" class="form-control" value="{{ old('sale_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        <span class="input-group-text" onclick="document.getElementById('sale_date').showPicker()" style="cursor: pointer;">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                </div>

                {{-- Vendor Dropdown --}}
                <div class="mb-3">
                    <label for="vendor_id" class="form-label fw-bold">Select Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="vendor_id" class="form-select" required>
                        <option value="">-- Choose Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->company_name }} ({{ $vendor->authorized_person }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Product Selection Card --}}
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Products</h5>
                    </div>
                    <div class="card-body">
                        <div id="product-rows">
                            <!-- Initial product row -->
                            <div class="row g-3 mb-3 product-row align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Product</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control product-search-input {{ $errors->has('products.0.product_id') ? 'is-invalid' : '' }}" 
                                               placeholder="Click to select product..." 
                                               autocomplete="off" required>
                                        <input type="hidden" name="products[0][product_id]" class="product-id-field" value="{{ old('products.0.product_id') }}">
                                        <div class="dropdown-menu w-100 product-suggestions" style="display: none;"></div>
                                        @error('products.0.product_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Quantity</label>
                                    <input type="number" name="products[0][quantity]" class="form-control {{ $errors->has('products.0.quantity') ? 'is-invalid' : '' }}"
                                           min="1" value="{{ old('products.0.quantity', 1) }}" required>
                                    @error('products.0.quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-product mt-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-product" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        💾 Submit Sale
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let productIndex = 1; // Start from 1 for product rows
        
        // Prepare product data for JS
        const products = [
            @foreach($products as $product)
                {
                    id: {{ $product->id }},
                    name: "{{ addslashes($product->name) }}",
                    sku: "{{ addslashes($product->sku) }}",
                    quantity: {{ $product->quantity }}
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

        // Create product suggestion item
        function createProductSuggestion(product) {
            const item = document.createElement('a');
            item.className = 'dropdown-item';
            item.href = '#';
            item.innerHTML = `
                <div class="d-flex justify-content-between">
                    <span><strong>${product.name}</strong></span>
                    <span class="text-muted">${product.sku}</span>
                </div>
                <div class="text-muted small">Stock: ${product.quantity}</div>
            `;
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.position-relative');
                parent.querySelector('.product-search-input').value = product.name;
                parent.querySelector('.product-id-field').value = product.id;
                parent.querySelector('.product-suggestions').style.display = 'none';
                
                // Auto-focus quantity field after selection
                const quantityInput = parent.closest('.product-row').querySelector('input[type="number"]');
                if (quantityInput) {
                    quantityInput.focus();
                }
            });
            return item;
        }

        // Initialize existing product search inputs
        document.querySelectorAll('.product-search-input').forEach(input => {
            // Set initial value if there's old input
            const productId = input.nextElementSibling.value;
            if (productId) {
                const product = products.find(p => p.id == productId);
                if (product) {
                    input.value = product.name;
                }
            }
            
            initProductSearch(input);
        });

        // Add new product row
        document.getElementById('add-product').addEventListener('click', function () {
            const productRows = document.getElementById('product-rows');
            const newRow = document.createElement('div');
            newRow.className = 'row g-3 mb-3 product-row align-items-center';
            newRow.innerHTML = `
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product</label>
                    <div class="position-relative">
                        <input type="text" class="form-control product-search-input" 
                               placeholder="Click to select product..." 
                               autocomplete="off" required>
                        <input type="hidden" name="products[${productIndex}][product_id]" class="product-id-field">
                        <div class="dropdown-menu w-100 product-suggestions" style="display: none;"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Quantity</label>
                    <input type="number" name="products[${productIndex}][quantity]" class="form-control"
                           min="1" value="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-product mt-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            productRows.appendChild(newRow);
            
            // Initialize search for the new row
            initProductSearch(newRow.querySelector('.product-search-input'));
            
            // Add event listener to the new delete button
            newRow.querySelector('.remove-product').addEventListener('click', function() {
                newRow.remove();
            });
            
            productIndex++;
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
    });
</script>
@endsection

@section('styles')
<style>
    .position-relative {
        position: relative;
    }
    .dropdown-menu {
        max-height: 300px;
        overflow-y: auto;
        position: absolute;
        z-index: 1050;
        display: none;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 0.25rem;
        left: 0;
        right: 0;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
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
        transition: background-color 0.2s;
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
    .product-search-input {
        cursor: pointer;
    }
    .product-row {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .remove-product {
        height: 38px;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875em;
    }
</style>
@endsection