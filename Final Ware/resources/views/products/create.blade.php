@extends('layouts.dashboard')

@section('title', 'Add New Product')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Add New Product</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Product Details</h3>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mx-3 mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible mx-3 mt-3">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-check"></i> {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" id="productForm" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-6">

                        {{-- Product Name --}}
                        <div class="form-group">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required pattern="^[A-Za-z\s]+$">
                            <small class="form-text text-muted">Only letters and spaces allowed</small>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Company --}}
                        <div class="form-group">
                            <label for="company">Company <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company') is-invalid @enderror"
                                   id="company" name="company" value="{{ old('company') }}" required pattern="^[A-Za-z\s]+$">
                            <small class="form-text text-muted">Only letters and spaces allowed</small>
                            @error('company')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="form-group">
                            <label for="category_id">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-6">

                        {{-- Color --}}
                        <div class="form-group position-relative">
                            <label for="color">Color <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('color') is-invalid @enderror"
                                   id="color" name="color" value="{{ old('color') }}" required 
                                   placeholder="Start typing to see color suggestions" autocomplete="off">
                            <div class="dropdown-menu w-100" id="colorSuggestions" style="display: none;"></div>
                            <small class="form-text text-muted">Select from suggestions or type your own</small>
                            @error('color')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Style --}}
                        <div class="form-group position-relative">
                            <label for="style">Style <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('style') is-invalid @enderror"
                                   id="style" name="style" value="{{ old('style') }}" required 
                                   placeholder="Start typing to see style suggestions" autocomplete="off">
                            <div class="dropdown-menu w-100" id="styleSuggestions" style="display: none;"></div>
                            <small class="form-text text-muted">Select from suggestions or type your own</small>
                            @error('style')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Size --}}
                        <div class="form-group">
                            <label for="size">Size <span class="text-danger">*</span></label>
                            <select name="size" id="size" class="form-select" required>
                                <option value="">Select Size</option>
                                @foreach(['S','M','L','X','XL','XXL','Meter'] as $size)
                                    <option value="{{ $size }}" {{ old('size') == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SKU --}}
                        <div class="form-group">
                            <label for="sku">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku') }}" required>
                            <small class="form-text text-muted">Enter any SKU for the product</small>
                            @error('sku')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-danger d-block mt-1" id="error-sku"></small>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-default float-right">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Color data
        const colors = [
            'Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Orange', 
            'Purple', 'Pink', 'Brown', 'Gray', 'Silver', 'Gold', 'Beige', 
            'Turquoise', 'Indigo', 'Maroon', 'Olive', 'Teal', 'Navy', 
            'Magenta', 'Coral', 'Lavender', 'Cyan', 'Ruby', 'Mint', 
            'Ivory', 'Charcoal', 'Amber', 'Burgundy', 'Mustard', 'Peach'
        ];

        // Style data
        const styles = [
            'Casual', 'Formal', 'Sporty', 'Business', 'Party', 'Vintage',
            'Bohemian', 'Chic', 'Classic', 'Elegant', 'Grunge', 'Preppy',
            'Rocker', 'Streetwear', 'Trendy', 'Traditional', 'Western', 'Workwear'
        ];

        // Initialize suggestions for color field
        $('#color').on('focus click input', function() {
            const input = $(this).val().toLowerCase();
            const suggestions = $('#colorSuggestions');
            suggestions.empty();
            
            if (input.length === 0) {
                // Show all colors when input is empty
                colors.forEach(color => {
                    suggestions.append(`<a class="dropdown-item" href="#">${color}</a>`);
                });
            } else {
                // Filter colors based on input
                const filteredColors = colors.filter(color => 
                    color.toLowerCase().includes(input)
                );
                
                if (filteredColors.length > 0) {
                    filteredColors.forEach(color => {
                        suggestions.append(`<a class="dropdown-item" href="#">${color}</a>`);
                    });
                } else {
                    suggestions.append('<a class="dropdown-item disabled" href="#">No matches found</a>');
                }
            }
            
            suggestions.show();
        });

        // Initialize suggestions for style field
        $('#style').on('focus click input', function() {
            const input = $(this).val().toLowerCase();
            const suggestions = $('#styleSuggestions');
            suggestions.empty();
            
            if (input.length === 0) {
                // Show all styles when input is empty
                styles.forEach(style => {
                    suggestions.append(`<a class="dropdown-item" href="#">${style}</a>`);
                });
            } else {
                // Filter styles based on input
                const filteredStyles = styles.filter(style => 
                    style.toLowerCase().includes(input)
                );
                
                if (filteredStyles.length > 0) {
                    filteredStyles.forEach(style => {
                        suggestions.append(`<a class="dropdown-item" href="#">${style}</a>`);
                    });
                } else {
                    suggestions.append('<a class="dropdown-item disabled" href="#">No matches found</a>');
                }
            }
            
            suggestions.show();
        });

        // Handle selection from color suggestions
        $('#colorSuggestions').on('click', '.dropdown-item:not(.disabled)', function(e) {
            e.preventDefault();
            $('#color').val($(this).text());
            $('#colorSuggestions').hide();
        });

        // Handle selection from style suggestions
        $('#styleSuggestions').on('click', '.dropdown-item:not(.disabled)', function(e) {
            e.preventDefault();
            $('#style').val($(this).text());
            $('#styleSuggestions').hide();
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#color, #colorSuggestions').length) {
                $('#colorSuggestions').hide();
            }
            if (!$(e.target).closest('#style, #styleSuggestions').length) {
                $('#styleSuggestions').hide();
            }
        });

        // Prevent form submission on Enter key in search fields
        $('#color, #style').keydown(function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .form-group.position-relative {
        position: relative;
    }
    .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        display: none;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 0.25rem;
        left: 0;
        right: 0;
    }
    .dropdown-item {
        cursor: pointer;
        padding: 0.25rem 1.5rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }
    .dropdown-item:hover, .dropdown-item:focus {
        color: #16181b;
        text-decoration: none;
        background-color: #f8f9fa;
    }
    .dropdown-item.disabled {
        color: #6c757d;
        pointer-events: none;
        background-color: transparent;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
</style>
@endsection