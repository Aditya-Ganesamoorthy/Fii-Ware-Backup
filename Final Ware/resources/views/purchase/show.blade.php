@extends('layouts.dashboard')

@section('title', 'Purchase Details')

@section('content')
<div class="container">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">📦 Purchase Details — {{ $purchaseId }}</h3>
            <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-secondary">
                ⬅ Back to Purchases
            </a>
        </div>

        <div class="card-body">
            @if($purchases->isEmpty())
                <div class="alert alert-info">
                    No purchase records found for ID <strong>{{ $purchaseId }}</strong>.
                </div>
            @else
                @php
                    $vendor = $purchases->first()->vendor ?? null;
                    $purchaseDate = \Carbon\Carbon::parse($purchases->first()->created_at)->format('d M, Y h:i A');
                @endphp

                <div class="mb-4">
                    <h5><strong>Purchase ID:</strong> {{ $purchaseId }}</h5>
                    <h5><strong>Vendor:</strong> {{ $vendor->company_name ?? 'N/A' }}</h5>
                    <h5><strong>Date:</strong> {{ $purchaseDate }}</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Company</th>
                                <th>Color</th>
                                <th>Style</th>
                                <th>Size</th>
                                <th>SKU</th>
                                <th>Quantity Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $index => $purchase)
                                @php
                                    $product = $purchase->product;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $product->name ?? $purchase->product_name }}</td>
                                    <td>{{ $product->company ?? 'N/A' }}</td>
                                    <td>{{ $product->color ?? 'N/A' }}</td>
                                    <td>{{ $product->style ?? 'N/A' }}</td>
                                    <td>{{ $product->size ?? 'N/A' }}</td>
                                    <td>{{ $product->sku ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $purchase->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
