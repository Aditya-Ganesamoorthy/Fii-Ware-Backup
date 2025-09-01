@extends('layouts.dashboard')

@section('title', 'Edit Purchase')

@section('content')
<div class="container">
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">✏️ Edit Purchase</h3>
            <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-secondary">⬅ Back to Purchases</a>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('purchase.updateGroup', $purchase_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                {{-- 📅 Purchase Date --}}
                <div class="mb-3">
                    <label for="purchase_date" class="form-label">📅 Purchase Date</label>
                    <input
                        type="date"
                        name="purchase_date"
                        id="purchase_date"
                        class="form-control"
                        value="{{ old('purchase_date', $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') : '') }}">
                </div>

                {{-- 🏢 Vendor --}}
                <div class="mb-3">
                    <label class="form-label">🏢 Vendor</label>
                    <select name="vendor_id" class="form-select" disabled>
                        <option value="">-- Select Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ $vendor_id == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->company_name }} - {{ $vendor->authorized_person }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="vendor_id" value="{{ $vendor_id }}">
                </div>

                {{-- 🧾 Products Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                                <tr class="text-center">
                                    <td>
                                        {{ $purchase->product->name ?? 'Unknown Product' }}
                                        <input type="hidden" name="products[{{ $purchase->id }}][product_id]" value="{{ $purchase->product_id }}">
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="products[{{ $purchase->id }}][quantity]"
                                            class="form-control text-center"
                                            min="1"
                                            value="{{ $purchase->quantity }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Submit Button --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Purchase
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
