@extends('layouts.dashboard')

@section('title', 'Manage Returns')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="card-title" style="font-weight: bold;">Returns Management</h1>
                <div class="d-flex">
                    @if(in_array('returns.create', $allowedRoutes))                
                        <a href="{{ route('returns.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create a Return
                        </a>
                    @endif
                </div>
            </div>
        </div>

        
        <div class="card-body">
    <div class="row mb-3">
        <div class="col-md-3">
            <form action="{{ route('returns.index') }}" method="GET" id="perPageForm">
                <div class="form-group">
                    <label for="perPageSelect" class="font-weight-bold">Rows per page:</label>
                    <div class="input-group">
                        <select name="perPage" id="perPageSelect" class="form-control" onchange="document.getElementById('perPageForm').submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
            {{-- Live Search Bar --}}
            <div class="mb-3">
                <input type="text" id="liveSearch" class="form-control" placeholder="Search returns by product, SKU, reason, etc...">
            </div>
            
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Sales ID</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>PID</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Returned By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="returnsTableBody">
                    @foreach($returns as $return)
                        <tr>
                            <td class="bg-light">{{ $return->id }}</td>
                            <td class="bg-light">{{ $return->created_at->format('d M Y') }}</td>
                            <td class="bg-light">{{ $return->sales_id }}</td>
                            <td class="bg-light">{{ $return->product->name ?? 'N/A' }}</td>
                            <td class="bg-light">{{ $return->product->sku ?? 'N/A' }}</td>
                            <td class="bg-light">{{ $return->pid }}</td>
                            <td class="bg-light">{{ $return->quantity_returned }}</td>
                            <td class="bg-light">{{ $return->reason }}</td>
                            <td class="bg-light">{{ $return->staff->name }}</td>
                            <td class="@if($return->status == 'accepted') table-success @elseif($return->status == 'pending') table-warning @elseif($return->status == 'declined') table-danger @endif">
                                <span class="badge 
                                    @if($return->status == 'accepted') badge-success
                                    @elseif($return->status == 'pending') badge-warning
                                    @else badge-danger @endif">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td class="@if($return->status == 'accepted') table-success @elseif($return->status == 'pending') table-warning @elseif($return->status == 'declined') table-danger @endif">
                                @if($return->status == 'pending')
                                    @if(in_array('returns.accept', $allowedRoutes))
                                        <form id="accept-form-{{ $return->id }}" action="{{ route('returns.accept', $return) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="button" class="btn btn-success btn-sm" onclick="confirmAction('accept', {{ $return->id }})">
                                                Accept
                                            </button>
                                        </form>
                                    @endif    

                                    @if(in_array('returns.decline', $allowedRoutes))
                                        <form id="decline-form-{{ $return->id }}" action="{{ route('returns.decline', $return) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmAction('decline', {{ $return->id }})">
                                                Decline
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted">No actions available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination links -->
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $returns->firstItem() }} to {{ $returns->lastItem() }} of {{ $returns->total() }} entries
                
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        <li class="page-item {{ $returns->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $returns->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>

                        {{-- Pagination Elements --}}
                        @foreach ($returns->getUrlRange(1, $returns->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $returns->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Next Page Link --}}
                        <li class="page-item {{ !$returns->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $returns->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>    
</div> 
@stop

@section('css')
<style>
    /* Custom table header */
    .table thead.thead-dark th {
        background-color: #343a40;
        color: white;
        font-weight: 600;
    }

    /* Light background for most columns */
    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Status and Actions column colors */
    td.table-success {
        background-color: #d4edda !important;
    }
    td.table-warning {
        background-color: #fff3cd !important;
    }
    td.table-danger {
        background-color: #f8d7da !important;
    }

    /* Hover effects - only on non-colored cells */
    .table tbody tr:hover td.bg-light {
        background-color: rgba(0,0,0,0.03) !important;
    }

    /* Badge styling */
    .badge-success {
        background-color: #28a745;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-danger {
        background-color: #dc3545;
    }

    /* Pagination styles */
    .pagination {
        margin: 0;
    }

    .page-item.active .page-link {
        background-color: #343a40;
        border-color: #343a40;
    }

    .page-link {
        color: #343a40;
    }

    .page-link:hover {
        color: #343a40;
        background-color: #e9ecef;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(action, returnId) {
        const actionText = action.charAt(0).toUpperCase() + action.slice(1);
        const actionColor = action === 'accept' ? '#28a745' : '#dc3545';
        const icon = action === 'accept' ? 'success' : 'warning';
        
        Swal.fire({
            title: `Confirm ${actionText}`,
            text: `Are you sure you want to ${action} this return? This action cannot be undone.`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${actionText} It!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`${action}-form-${returnId}`).submit();
            }
        });
    }
    
    // Pagination links Scripts
    document.querySelectorAll('.pagination a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(this.href);
            url.searchParams.set('search', '{{ request('search') }}');
            url.searchParams.set('perPage', '{{ request('perPage') }}');
            window.location.href = url.toString();
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Live search for returns table
        const searchInput = document.getElementById('liveSearch');
        const tableRows = document.querySelectorAll('#returnsTableBody tr');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();

            tableRows.forEach(row => {
                const rowText = row.textContent.trim().toLowerCase();
                if (rowText.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
