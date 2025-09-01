@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Vendor Details</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-success">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this vendor?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Company Information</h3>
        </div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <strong>Company Name:</strong>
                <p class="text-muted">{{ $vendor->company_name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Authorized Person:</strong>
                <p class="text-muted">{{ $vendor->authorized_person }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>GST Number:</strong>
                <p class="text-muted">{{ $vendor->gst_number }}</p>
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Contact Information</h3>
        </div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <strong>Mobile Number:</strong>
                <p class="text-muted">{{ $vendor->mobile_number }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Address:</strong>
                <p class="text-muted">{{ $vendor->address }}</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('vendors.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to All Vendors
        </a>
    </div>
</div>
@endsection
