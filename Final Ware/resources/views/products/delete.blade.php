@extends('layouts.dashboard')

@section('title', 'Delete Product')

@section('content_header')
    <h1 class="font-weight-bold text-danger">Confirm Deletion</h1>
@stop

@section('content')
    <div class="card shadow-sm border-danger">
        <div class="card-header bg-danger text-white">
            <h3 class="mb-0">Are you sure you want to delete this product?</h3>
        </div>

        <div class="card-body">
            <p class="mb-3">You are about to delete the product:</p>

            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Name:</strong> {{ $product->name }}</li>
                <li class="list-group-item"><strong>Company:</strong> {{ $product->company }}</li>
                <li class="list-group-item"><strong>Category:</strong> {{ $product->category->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Quantity:</strong> {{ $product->quantity }}</li>
            </ul>

            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-danger">
                        Yes, Delete Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
<style>
    .card {
        border-radius: 8px;
    }
</style>
@stop
