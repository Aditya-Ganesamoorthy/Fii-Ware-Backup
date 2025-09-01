<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="menu">
                <ul>
                    <li class="menu-item">
                        <button class="menu-btn" onclick="toggleSubmenu(this)">Users</button>
                        <ul class="submenu">
                            <li><a href="{{ route('users.index') }}">All Users</a></li>
                            <li><a href="#">Roles</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <button class="menu-btn" onclick="toggleSubmenu(this)">Products</button>
                        <ul class="submenu">
                            <li><a href="#">All Products</a></li>
                            <li><a href="#">Categories</a></li>
                        </ul>
                    </li>
                    <li><a href="{{route('vendors.index')}}" class="menu-link">Vendors</a></li>
                    <li><a href="#" class="menu-link">Purchase</a></li>
                    <li><a href="#" class="menu-link">Sales</a></li>
                    <li><a href="#" class="menu-link">Returns</a></li>
                    <li><a href="#" class="menu-link">Reports</a></li>
                    <li class="menu-item">
                        <button class="menu-btn" onclick="toggleSubmenu(this)">Transport</button>
                        <ul class="submenu">
                            <li><a href="#">Drivers</a></li>
                            <li><a href="#">Vehicles</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <section class="main-content">
            <div class="content-header">
                <div class="breadcrumb">
                    <span>Home</span>
                    <span class="divider">/</span>
                    <span class="active">Vendors</span>
                </div>
                <h2 class="content-title">Vendors</h2>
            </div>
            <div class="content-body">
                <p>This is the Vendors section content.</p>
            </div>
        </section>
    </div>

    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #1f2937;
            color: #f9fafb;
            padding: 20px;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-btn, .menu-link {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px;
            margin-bottom: 5px;
            background: none;
            border: none;
            color: #f9fafb;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }

        .menu-btn:hover, .menu-link:hover {
            background-color: #374151;
        }

        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .menu-item.active .submenu {
            max-height: 500px;
        }

        .submenu a {
            display: block;
            padding: 8px 10px;
            color: #d1d5db;
            text-decoration: none;
            border-radius: 4px;
        }

        .submenu a:hover {
            background-color: #4b5563;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f3f4f6;
        }

        .content-header {
            margin-bottom: 20px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            color: #6b7280;
            font-size: 14px;
        }

        .divider {
            margin: 0 8px;
        }

        .content-title {
            font-size: 24px;
            font-weight: 600;
            margin: 10px 0;
        }
    </style>

    <script>
        function toggleSubmenu(button) {
            const menuItem = button.parentElement;
            menuItem.classList.toggle('active');
            
            // Close other open menus
            document.querySelectorAll('.menu-item').forEach(item => {
                if (item !== menuItem) {
                    item.classList.remove('active');
                }
            });
        }
    </script>
</x-app-layout>