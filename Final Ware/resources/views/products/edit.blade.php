@extends('layouts.dashboard')

@section('title', 'Edit Product')

@section('content')
<div class="container">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">✏️ Edit Product</h3>
        </div>

        <div class="card-body">
            {{-- Success --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Error --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the following issues:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                               class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="company">Company</label>
                        <input type="text" name="company" value="{{ old('company', $product->company) }}"
                               class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="style">Style</label>
                        <input type="text" name="style" value="{{ old('style', $product->style) }}"
                               class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="color">Color</label>
                        <input type="text" name="color" value="{{ old('color', $product->color) }}"
                               class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="size">Size</label>
                        <select name="size" class="form-control" required>
                            @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <option value="{{ $size }}" {{ old('size', $product->size) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="category_id">Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="sku">SKU <small class="text-muted">(Format: COMP-CAT-NAME-COLR-SIZE-STYL)</small></label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}"
                           class="form-control" min="0" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">💾 Update Product</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">↩ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
