@extends('layouts.dashboard')

@section('title', 'Create Sale')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Create New Sale</h3>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary float-end" style="background-color: #FFEB00; color: #555555;">Back</a>
        </div>

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
                    <label for="sale_date" style=" color: #555555;">Sale Date</label>
                    <div class="input-group" style="max-width: 300px;">
                        <input type="date" name="sale_date" id="sale_date" class="form-control" value="{{ old('sale_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        <span class="input-group-text" onclick="document.getElementById('sale_date').showPicker()" style="cursor: pointer;">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                </div>

                {{-- Vendor Dropdown --}}
                <div class="mb-3">
                    <label for="vendor_id" style="color: #555555;">Select Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="vendor_id" class="form-select" required>
                        <option value="">-- Choose Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->company_name }} ({{ $vendor->authorized_person }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Product Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity to Sell</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="product-rows">
                            <tr class="product-row">
                                <td>
                                    <select name="products[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('products.0.product_id') == $product->id ? 'selected' : '' }}
                                                    data-stock="{{ $product->quantity }}">
                                                {{ $product->name }} ({{ $product->sku }}) - Stock: {{ $product->quantity }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="products[0][quantity]" class="form-control form-control-sm"
                                           min="1" value="1" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger remove-product">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <button type="button" id="add-product" class="btn btn-outline-primary" style="color: #555555;">Add Product</button>
                </div>

                {{-- Submit Button --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary" style="background-color: #FFEB00; color: #555555;">
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
        const products = @json($products); // Passing all products data to JavaScript

        // Add new product row
        document.getElementById('add-product').addEventListener('click', function () {
            const productRows = document.getElementById('product-rows');
            const newRow = document.createElement('tr');
            newRow.className = 'product-row';
            newRow.innerHTML = `
                <td>
                    <select name="products[${productIndex}][product_id]" class="form-select product-select" required>
                        <option value="">-- Select Product --</option>
                        ${products.map(product => `
                            <option value="${product.id}" data-stock="${product.quantity}">
                                ${product.name} (${product.sku}) - Stock: ${product.quantity}
                            </option>
                        `).join('')}
                    </select>
                </td>
                <td class="text-center">
                    <input type="number" name="products[${productIndex}][quantity]" class="form-control form-control-sm"
                           min="1" value="1" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger remove-product">Delete</button>
                </td>
            `;
            productRows.appendChild(newRow);
            productIndex++;

            // Add event listener to the new delete button
            newRow.querySelector('.remove-product').addEventListener('click', function () {
                newRow.remove();
            });
        });

        // Add event listener to existing delete buttons
        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', function () {
                this.closest('.product-row').remove();
            });
        });

        // Dynamically update stock when product selection changes
        document.querySelectorAll('.product-select').forEach(select => {
            select.addEventListener('change', function () {
                const selectedProductId = this.value;
                const selectedOption = this.selectedOptions[0];
                const stockQuantity = selectedOption ? selectedOption.getAttribute('data-stock') : 0;

                // Find the stock cell in the row and update it
                const stockCell = this.closest('tr').querySelector('td[id^="stock-"]');
                if (stockCell) {
                    stockCell.textContent = stockQuantity;
                }
            });
        });
    });

    // Optional: validate the form before submission
    document.getElementById('salesForm').addEventListener('submit', function (e) {
        const productRows = document.querySelectorAll('.product-row');
        let valid = true;

        productRows.forEach(row => {
            const productSelect = row.querySelector('select');
            const quantityInput = row.querySelector('input');

            if (!productSelect.value || !quantityInput.value || quantityInput.value <= 0) {
                valid = false;
            }
        });

        if (!valid) {
            alert('Please ensure that all products have a valid quantity.');
            e.preventDefault();
        }
    });
</script>
@endsection