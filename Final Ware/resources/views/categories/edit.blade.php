@extends('layouts.dashboard') {{-- AdminLTE layout --}}

@section('title', 'Edit Category')

@section('content')
<div class="container mt-4">
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header">
            <h3 class="card-title">✏️ Edit Category</h3>
            <div class="card-tools">
              
            </div>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" name="name" id="name"
                           class="form-control form-control-sm @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">
                    🔙 Cancel
                </a>
                <button type="submit" class="btn btn-sm btn-primary">
                    💾 Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
