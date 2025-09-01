@extends('layouts.dashboard')

@section('title', 'View Sale')

@section('content')
<div class="container">
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">🧾 Sale Details — {{ $salesId }}</h3>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary">
                ⬅ Back to Sales
            </a>
        </div>
        

        <div class="card-body">
            {{-- ✅ Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ❌ Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ⚠️ No Sales --}}
            @if($sales->isEmpty())
                <div class="alert alert-warning">
                    No sale found for ID: <strong>{{ $salesId }}</strong>
                </div>
            @else
                @php
                    $firstSale = $sales->first();
                    $vendor = $firstSale->vendor ?? null;
                    $saleDate = \Carbon\Carbon::parse($firstSale->sale_date ?? $firstSale->created_at)->format('d M, Y h:i A');
                @endphp

                <div class="mb-4">
                    <h5><strong>Sale ID:</strong> {{ $salesId }}</h5>
                    <h5><strong>Vendor:</strong> {{ $vendor->company_name ?? 'N/A' }}</h5>
                    <h5><strong>Date:</strong> {{ $saleDate }}</h5>
                </div>

                {{-- 📊 Product Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Quantity Sold</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach($sales as $index => $sale)
                                @php
                                    $product = $sale->product;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->name ?? $sale->name ?? 'N/A' }}</td>
                    
                                    <td>{{ $sale->quantity }}</td>
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
