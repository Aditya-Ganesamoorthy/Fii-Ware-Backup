@extends('layouts.dashboard')

@section('title', 'Create Return')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Create New Return</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('returns.store') }}" method="POST" id="return-form">
                @csrf


                {{-- Sales ID --}}
                <div class="form-group">
                    <label>Sales ID</label>
                    <select name="sales_id" id="sales_id" class="form-control" required>
                        <option value="">Select Sales ID</option>
                        @foreach($sales as $saleId)
                            <option value="{{ $saleId }}">{{ $saleId }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Products Table --}}
                <div id="products_table" style="display: none; margin-top: 20px;">
                    <h4>Select Products to Return</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product Name</th>
                                <th>PID</th>
                                <th>Sold Quantity</th>
                                <th>Quantity to Return</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody id="products_table_body">
                            <!-- Products will be added here dynamically -->
                        </tbody>
                    </table>
                </div>

                {{-- Returned By --}}
                <div class="form-group">
                    <label>Returned By</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    <small class="form-text text-muted">(Automatically filled with current user)</small>
                </div>

                {{-- Submit --}}
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                        <i class="fas fa-check"></i> Submit Return
                    </button>
                    <a href="{{ route('returns.index') }}" class="btn btn-default">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Dynamic JS --}}
    <script>
        document.getElementById('sales_id').addEventListener('change', function() {
            const salesId = this.value;
            const productsTable = document.getElementById('products_table');
            const productsBody = document.getElementById('products_table_body');
            const submitBtn = document.getElementById('submit-btn');

            if (!salesId) {
                productsTable.style.display = 'none';
                submitBtn.disabled = true;
                productsBody.innerHTML = '';
                return;
            }

            fetch(`/returns/get-products/${salesId}`)
                .then(response => response.json())
                .then(products => {
                    productsBody.innerHTML = '';
                    
                    if (products.length === 0) {
                        productsTable.style.display = 'none';
                        submitBtn.disabled = true;
                        return;
                    }

                    products.forEach((product, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>
                                <input type="checkbox" class="product-check" data-index="${index}">
                            </td>
                            <td>${product.name}</td>
                            <td>${product.pid}</td>
                            <td>${product.sold_quantity}</td>
                            <td>
                                <input type="number" name="products[${index}][quantity_returned]" 
                                       class="form-control quantity-input" min="1" max="${product.sold_quantity}" 
                                       disabled required>
                            </td>
                            <td>
                                <textarea name="products[${index}][reason]" 
                                       class="form-control reason-input" disabled required></textarea>
                            </td>
                            <input type="hidden" name="products[${index}][pid]" value="${product.pid}" disabled>
                        `;
                        productsBody.appendChild(row);
                    });

                    productsTable.style.display = 'block';
                    submitBtn.disabled = false;

                    // Add event listeners to checkboxes
                    document.querySelectorAll('.product-check').forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const index = this.getAttribute('data-index');
                            const quantityInput = document.querySelector(`input[name="products[${index}][quantity_returned]"]`);
                            const reasonInput = document.querySelector(`textarea[name="products[${index}][reason]"]`);
                            const pidInput = document.querySelector(`input[name="products[${index}][pid]"]`);
                            
                            if (this.checked) {
                                pidInput.disabled = false;
                                quantityInput.disabled = false;
                                reasonInput.disabled = false;
                            } else {
                                pidInput.disabled = true;
                                quantityInput.disabled = true;
                                quantityInput.value = '';
                                reasonInput.disabled = true;
                                reasonInput.value = '';
                            }
                        });
                    });
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    productsTable.style.display = 'none';
                    submitBtn.disabled = true;
                });
        });

        // Form submission validation
        document.getElementById('return-form').addEventListener('submit', function(e) {
            const selectedProducts = document.querySelectorAll('.product-check:checked');
            if (selectedProducts.length === 0) {
                e.preventDefault();
                alert('Please select at least one product to return');
                return false;
            }
            return true;
        });
    </script>
@endsection