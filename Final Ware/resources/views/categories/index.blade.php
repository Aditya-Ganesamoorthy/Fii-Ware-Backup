@extends('layouts.dashboard')

@section('title', 'Categories & Products')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Categories</h1>
@endsection

@section('content')

    {{-- Add Category Button --}}
    @if(in_array('categories.create', $allowedRoutes))
    <div class="mb-3 text-right">
        <a href="{{ route('categories.create') }}" class="btn btn-success shadow-sm">
            ➕ Add New Category
        </a>
    </div>
    @endif

    <div class="container-fluid py-4"
         x-data="{
            categories: {{ $categories->toJson() }},
            pagination: {{ json_encode([
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ]) }}
         }">

        <!-- Category List Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>🏷 Category Name</th>
                        <th>📦 Total Items</th>
                        <th>⚙ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="category in categories.data" :key="category.id">
                        <tr>
                            <td x-text="category.name"></td>
                            <td x-text="category.products_count"></td>
                            <td>
                                <div class="btn-group">
                                    <a :href="'/categories/' + category.id + '/edit'" class="btn btn-sm btn-warning"> Edit</a>

                                    <form :action="'/categories/' + category.id" method="POST" class="d-inline"
                                          @submit.prevent="if(confirm('Are you sure you want to delete this category?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="text-muted" x-text="`Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} entries`"></div>
            </div>
            <div class="col-md-6">
                <nav class="float-right">
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        <li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
                            <a class="page-link" href="{{ $categories->url(1) }}" aria-label="First">
                                <span aria-hidden="true">&laquo;&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
                            <a class="page-link" href="{{ $categories->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <!-- Page Numbers -->
                        <template x-for="page in pagination.last_page" :key="page">
                            <li class="page-item" :class="{ 'active': pagination.current_page === page }">
                                <a class="page-link" :href="'{{ $categories->url(1) }}'.replace(/page=\d+/, 'page=' + page)" x-text="page"></a>
                            </li>
                        </template>

                        <!-- Next Page Link -->
                        <li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
                            <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
                            <a class="page-link" href="{{ $categories->url($categories->lastPage()) }}" aria-label="Last">
                                <span aria-hidden="true">&raquo;&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection