<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Dashboard CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
     <!-- <link href="{{ asset('css/vendors.css') }}" rel="stylesheet"> -->
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Dashboard Container -->
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <h3 class="logo">Admin<span>Panel</span></h3>
                </div>
                <nav class="menu">
                    <ul>
                        <li class="menu-item">
                            <button class="menu-btn">
                                <span class="flex items-center">
                                    <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <span>Users</span>
                                </span>
                                <svg class="chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <ul class="submenu">
                                <li><a href="#" class="submenu-link">All Users</a></li>
                                <li><a href="#" class="submenu-link">Roles</a></li>
                            </ul>
                        </li>
                        <!-- Other menu items -->
                        <li><a href="{{ route('vendors.index') }}" class="menu-link active">Vendors</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Content Header -->
                <div class="content-header">
                    <div class="breadcrumb">
                        <span>Home</span>
                        <span class="divider">/</span>
                        <span class="active">Vendors</span>
                    </div>
                    <h2 class="content-title">Vendors</h2>
                </div>

                <!-- Dynamic Content -->
                <div class="content-body">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Dashboard JavaScript -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>