@extends('layouts.dashboard')

@section('title', 'Product Details - Stock Report')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-info shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-box mr-2"></i>Product Details
            </h3>
            <div class="card-tools">
                <a href="{{ route('reports.stock') }}" class="btn btn-sm btn-default">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Stock Report
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Product Image Column -->
                <div class="col-md-4 text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                            class="img-fluid img-thumbnail rounded" style="max-height: 300px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center border bg-light text-muted"
                             style="height: 200px; border-radius: .375rem;">
                            <i class="fas fa-box-open fa-3x"></i>
                        </div>
                    @endif
                </div>

                <!-- Product Details Column -->
                <div class="col-md-8">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-tag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Product Name</span>
                                    <span class="info-box-number">{{ $product->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Company</span>
                                    <span class="info-box-number">{{ $product->company }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-layer-group"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Category</span>
                                    <span class="info-box-number">{{ $product->category->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary"><i class="fas fa-barcode"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">SKU</span>
                                    <span class="info-box-number">{{ $product->sku }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="small-box bg-light p-3 border">
                                <h5 class="text-muted">Color</h5>
                                <p class="lead">{{ $product->color ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-light p-3 border">
                                <h5 class="text-muted">Style</h5>
                                <p class="lead">{{ $product->style ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-light p-3 border">
                                <h5 class="text-muted">Size</h5>
                                <p class="lead">{{ $product->size ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h3 class="card-title">Stock Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h2 class="mb-0">{{ $product->quantity }}</h2>
                                            <p class="text-muted mb-0">Current Stock</p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-box {
        min-height: 80px;
        margin-bottom: 0;
        box-shadow: 0 0 1px rgba(0,0,0,.125);
    }
    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        font-size: 30px;
    }
    .info-box-content {
        padding: 10px;
    }
    .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
</style>
@endpush