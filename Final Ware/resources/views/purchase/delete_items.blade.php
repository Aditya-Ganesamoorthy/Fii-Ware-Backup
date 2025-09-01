@extends('layouts.dashboard')

@section('title', 'Delete Purchase Items')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">🗑️ Delete Items from Purchase #{{ $purchaseId }}</h3>

    <form method="POST" action="{{ route('purchase.deleteItems.post', $purchaseId) }}">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Select</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Vendor</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $item)
                            <tr>
                                <td><input type="checkbox" name="items[]" value="{{ $item->id }}"></td>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->vendor->company_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the selected items?')">
                        🗑️ Delete Selected
                    </button>
                    <a href="{{ route('purchase.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
