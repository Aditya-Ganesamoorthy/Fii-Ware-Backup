@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Dashboard Overview</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
           <!-- In your dashboard/index.blade.php -->
<div class="col-lg-3 col-6">
    <!--begin::Small Box Widget 1-->
    <div class="small-box text-bg-primary">
        <div class="inner">
            <h3>{{ $totalUsers }}</h3>
            <p>Total Users</p>
        </div>
        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        <a href="{{ route('users.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
            View all <i class="bi bi-link-45deg"></i>
        </a>
    </div>
    <!--end::Small Box Widget 1-->
</div>
               
            <!--end::Col-->
            
            <!--begin::Col-->
            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 3-->
                <div class="small-box text-bg-warning">
                    <div class="inner">

<h3>{{ $totalVendors }}</h3>
            <p>Total Vendors</p>
        </div>
        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 007.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 004.902-5.652l-1.3-1.299a1.875 1.875 0 00-1.325-.549H5.223z"></path>
            <path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 009.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 002.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 010 1.5H2.25a.75.75 0 010-1.5H3zm3-6a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v3a.75.75 0 01-.75.75h-3a.75.75 0 01-.75-.75v-3zm8.25-.75a.75.75 0 00-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 00.75-.75v-5.25a.75.75 0 00-.75-.75h-3z" clip-rule="evenodd"></path>
        </svg>
        <a href="{{ route('vendors.index') }}" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
            View all <i class="bi bi-link-45deg"></i>
        </a>
    </div>
    <!--end::Small Box Widget 3-->
</div>
               
            <!--end::Col-->
            
            <!--begin::Col-->
            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>{{$todayproductTotal}}</h3>
                        <p>Total Products</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                    </svg>
                    <a href="{{route('products.index')}}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        More info <i class="bi bi-link-45deg"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 2-->
            </div>
            <!--end::Col-->
            
            <!--begin::Col-->
            
            <!--end::Col-->
            
            <!--begin::Col-->
            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 4-->
                <div class="small-box text-bg-danger">
                    <div class="inner">

<h3>{{ $todaySalesTotal }}</h3>
                        <p>Today's Sales</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
                    </svg>
                    <a href="{{route('sales.index')}}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        More info <i class="bi bi-link-45deg"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 4-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
        
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-lg-6 connectedSortable">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Recent Activities</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex">
                                <div class="me-3">
                                    <span class="badge bg-primary p-2 rounded-circle">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </span>
                                </div>
                                <div>
        @if ($latestUser)
            <p class="mb-0">New user registered: <strong>{{ $latestUser->name }}</strong></p>
            <small class="text-muted">{{ $userRegisteredAgo }}</small>
        @else
            <p class="mb-0">No users registered yet</p>
        @endif
    </div>
                            </li>
                           
                                <li class="mb-3 d-flex">
    <div class="me-3">
        <span class="badge bg-warning p-2 rounded-circle">
            <i class="bi bi-box-seam-fill"></i>
        </span>
    </div>
    <div>
        @if ($latestProduct)
            <p class="mb-0">
                New product added: <strong>{{ $latestProduct->name }}</strong>
            </p>
            <small class="text-muted">{{ $productAddedAgo }}</small>
        @else
            <p class="mb-0">No products added yet</p>
        @endif
    </div>
</li>

                            
                            
                                
                                    <li class="mb-3 d-flex">
    <div class="me-3">
        <span class="badge bg-success p-2 rounded-circle">
            <i class="bi bi-cart-check-fill"></i>
        </span>
    </div>
    <div>
        @if ($latestSale)
            <p class="mb-0">New sale added</p>
            <small class="text-muted">{{ $saleAddedAgo }}</small>
        @else
            <p class="mb-0">No sales added yet</p>
        @endif
    </div>
</li>

                                
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </div>
            <!--end::Col-->
            
            <!--begin::Col-->
            <div class="col-lg-6 connectedSortable">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            
    @if(in_array('products.create', $allowedRoutes))
                            <div class="col-6 mb-3">
                                <a href="{{ route('products.create') }}" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-plus-lg fs-3 mb-2"></i>
                                    <span>Add Product</span>
                                </a>
                            </div>
                            @endif
                            
    @if(in_array('users.create', $allowedRoutes))
                            <div class="col-6 mb-3">
                                <a href="{{ route('users.create') }}" class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-person-plus fs-3 mb-2"></i>
                                    <span>Add User</span>
                                </a>
                            </div>
                            @endif

                            
    @if(in_array('vendors.create', $allowedRoutes))
                            <div class="col-6">
                                <a href="{{ route('vendors.create') }}" class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-shop fs-3 mb-2"></i>
                                    <span>Add Vendor</span>
                                </a>
                            </div>
                            @endif

                            <div class="col-6">
                                <a href="#" class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-file-earmark-text fs-3 mb-2"></i>
                                    <span>Create Invoice</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
@endsection