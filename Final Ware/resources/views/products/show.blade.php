@extends('layouts.dashboard') {{-- AdminLTE layout --}}

@section('title', 'Product Details')

@section('content')
<div class="container">
    <div class="card card-outline card-info shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                Product Details
            </h3>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                            class="img-fluid img-thumbnail rounded" style="max-height: 200px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center border bg-light text-muted"
                             style="height: 200px; border-radius: .375rem;">
                            No Image
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Name:</dt>
                        <dd class="col-sm-8">{{ $product->name }}</dd>

                        <dt class="col-sm-4">Company:</dt>
                        <dd class="col-sm-8">{{ $product->company }}</dd>

                        <dt class="col-sm-4">Category:</dt>
                        <dd class="col-sm-8">{{ $product->category->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Color:</dt>
                        <dd class="col-sm-8">{{ $product->color }}</dd>

                        <dt class="col-sm-4">Style:</dt>
                        <dd class="col-sm-8">{{ $product->style }}</dd>

                        <dt class="col-sm-4">Size:</dt>
                        <dd class="col-sm-8">{{ $product->size }}</dd>

                        <dt class="col-sm-4">Quantity:</dt>
                        <dd class="col-sm-8">{{ $product->quantity }}</dd>

                        <dt class="col-sm-4">SKU:</dt>
                        <dd class="col-sm-8">{{ $product->sku }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
                ⬅ Back to Product List
            </a>
        </div>
    </div>
</div>
@endsection
