@extends('layouts.dashboard')

@section('title', 'Edit Sale')
@php
    $firstSale = $salesGroup->first();
    $saleDate = old('sale_date') 
        ?? ($firstSale->sales_date 
            ? \Carbon\Carbon::parse($firstSale->sales_date)->format('Y-m-d') 
            : $firstSale->created_at->format('Y-m-d'));
@endphp
@section('content')

<div class="container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">✏ Edit Sale — {{ $salesGroup->first()->sales_id }}</h3>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary float-end">⬅ Back</a>
        </div>

        <form action="{{ route('sales.update', $salesGroup->first()->sales_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                {{-- ✅ Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- 🛑 Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 🗓 Sale Date --}}
               <div class="mb-3">
    <label for="sale_date" class="form-label fw-bold">Sale Date</label>
    <div class="input-group" style="max-width: 300px;">
        <input type="date" name="sale_date" id="sale_date" class="form-control" value="{{ $saleDate }}">
        <span class="input-group-text" onclick="document.getElementById('sale_date').showPicker()">
            <i class="fa fa-calendar"></i>
        </span>
    </div>
    @error('sale_date')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

                {{-- 👤 Vendor --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Vendor</label>
                    <input type="text" class="form-control" value="{{ $salesGroup->first()->vendor->company_name }}" disabled>
                    <input type="hidden" name="vendor_id" value="{{ $salesGroup->first()->vendor_id }}">
                </div>

                {{-- 🛒 Products Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Company</th>
                                <th>Color</th>
                                <th>Style</th>
                                <th>Size</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Available Stock</th>
                                <th>Sold Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesGroup as $index => $sale)
                                @php
                                    $product = $sale->product;
                                @endphp

                                @if(!$product)
                                    <tr class="table-danger">
                                        <td colspan="10">
                                            ⚠ Product not found for Sale ID <strong>{{ $sale->id }}</strong> (PID: {{ $sale->pid }})
                                        </td>
                                    </tr>
                                    @continue
                                @endif

                                @php
                                    $availableStock = $product->quantity;
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->company }}</td>
                                    <td>{{ $product->color }}</td>
                                    <td>{{ $product->style }}</td>
                                    <td>{{ $product->size }}</td>
                                    <td><span class="badge bg-dark">{{ $product->sku }}</span></td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge bg-info text-dark">{{ $product->category->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $availableStock }}</td>
                                    <td class="text-center" style="max-width: 120px;">
                                        <input type="number"
                                            name="products[{{ $index }}][sold_quantity]"
                                            value="{{ old("products.$index.sold_quantity", $sale->quantity) }}"
                                            class="form-control form-control-sm text-center"
                                            min="1"
                                           
                                            required>
                                        <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product->id }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- 💾 Submit --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        💾 Update Sale
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection