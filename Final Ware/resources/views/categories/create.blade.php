@extends('layouts.dashboard')

@section('title', 'Add New Category')

@section('content_header')
    <h1 class="font-weight-bold text-dark">➕ Add New Category</h1>
@endsection

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ❌ Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Category Name</label>
                        <input type="text" name="name" id="name" required
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Enter category name"
                               pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"
                               value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description (optional)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Enter a short description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ✅ Buttons Row --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            ⬅ Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            ➕ Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
