<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dashboard</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="Admin Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{asset('admin/css/adminlte.css')}}" as="style" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <script src="//unpkg.com/alpinejs" defer></script>
    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{asset('admin/css/adminlte.css')}}" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />

    <!-- AdminLTE CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE JS -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

<!-- Date Range Picker (optional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1/daterangepicker.min.js"></script>

    <!-- Select2 CSS -->
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Select2 CSS purchase-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- In head section -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


<!-- In the head section -->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Before closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Toastr CSS (optional) -->
<link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
  </head>
  <!--end::Head-->

 <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">

 <!-- Loading Overlay -->
    <div id="loading-overlay" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100vw; height:100vh; background:rgba(255,255,255,0.85); justify-content:center; align-items:center;">
        <video src="{{ asset('admin/images/loading.mp4') }}" autoplay loop muted style="width:120px; height:120px; border-radius:16px; box-shadow:0 0 16px #007bff;"></video>
    </div>


    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <!-- Commenting out these links as they're not needed
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
            -->
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li>
            <!--end::Navbar Search-->
            
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4361ee&color=fff&size=160"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4361ee&color=fff&size=160"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    {{ Auth::user()->name }}
                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Footer-->
                <li class="user-footer d-flex flex-column gap-2 align-items-stretch">
  <a href="{{ route('profile.edit') }}" class="btn btn-dark btn-block mb-2" style="background:#222; color:#fff; border:none;">
    Profile
  </a>
  <form method="POST" action="{{ route('logout') }}" class="w-100">
    @csrf
    <button type="submit" class="btn btn-dark btn-block" style="background:#222; color:#fff; border:none;">
      Sign out
    </button>
  </form>
</li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('admin/images/temlogo.jpg') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">
    {{ auth()->user()->role->name ?? 'Dashboard' }} Dashboard
</span>

            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">

            <!--begin::Sidebar Menu-->
            <ul
  class="nav sidebar-menu flex-column"
  data-lte-toggle="treeview"
  role="navigation"
  aria-label="Main navigation"
  data-accordion="false"
  id="navigation"
>
  <li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link">

      <i class="nav-icon bi bi-speedometer"></i>
      <p>Dashboard</p>
    </a>
  </li>
       

<!-- Users Menu -->
@if(in_array('users.index', $allowedRoutes) || in_array('roles.create', $allowedRoutes) || in_array('role_access.index', $allowedRoutes))
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon bi bi-people-fill"></i>
    <p>
      Users
      <i class="nav-arrow bi bi-chevron-right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">

    @if(in_array('users.index', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('users.index') }}" class="nav-link">
        <i class="nav-icon bi bi-list"></i>
        <p>All Users</p>
      </a>
    </li>
    @endif

    @if(in_array('roles.create', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('roles.create') }}" class="nav-link">
        <i class="nav-icon bi bi-person-badge"></i>
        <p>Roles</p>
      </a>
    </li>
    @endif

    @if(in_array('role_access.index', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('role_access.index') }}" class="nav-link">
        <i class="nav-icon bi bi-shield-lock"></i>
        <p>Role Access</p>
      </a>
    </li>
    @endif

  </ul>
</li>
@endif


<!-- Products Menu -->
@if(in_array('products.index', $allowedRoutes) || in_array('categories.index', $allowedRoutes))
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon bi bi-box-seam"></i>
    <p>
      Products
      <i class="nav-arrow bi bi-chevron-right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">

    @if(in_array('products.index', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('products.index') }}" class="nav-link">
        <i class="nav-icon bi bi-list"></i>
        <p>All Products</p>
      </a>
    </li>
    @endif

    @if(in_array('categories.index', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('categories.index') }}" class="nav-link">
        <i class="nav-icon bi bi-tags"></i>
        <p>Categories</p>
      </a>
    </li>
    @endif

  </ul>
</li>
@endif


              
              <!-- Vendors Menu -->
              
              <!-- Purchase Menu -->
            <!-- Purchase Menu -->
@if(in_array('purchase.index', $allowedRoutes))
<li class="nav-item">
  <a href="{{ route('purchase.index')}}" class="nav-link">
    <i class="nav-icon bi bi-cart-plus"></i>
    <p>Purchase</p>
  </a>
</li>
@endif

<!-- Sales Menu -->
@if(in_array('sales.index', $allowedRoutes))
<li class="nav-item">
  <a href="{{ route('sales.index')}}" class="nav-link">
    <i class="nav-icon bi bi-graph-up"></i>
    <p>Sales</p>
  </a>
</li>
@endif

<!-- Returns Menu -->
@if(in_array('returns.index', $allowedRoutes))
<li class="nav-item">
  <a href="{{ route('returns.index')}}" class="nav-link">
    <i class="nav-icon bi bi-arrow-left-right"></i>
    <p>Returns</p>
  </a>
</li>
@endif
 @if(in_array('vendors.index', $allowedRoutes))
<li class="nav-item">
  <a href="{{ route('vendors.index') }}" class="nav-link">
    <i class="nav-icon bi bi-shop"></i>
    <p>Vendors</p>
  </a>
</li>
@endif
              
 <!-- Reports Menu -->
@if(in_array('reports.stock', $allowedRoutes) || in_array('reports.purchase', $allowedRoutes) || in_array('reports.sales', $allowedRoutes))
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon bi bi-file-earmark-text"></i>
    <p>
      Reports
      <i class="nav-arrow bi bi-chevron-right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">

    @if(in_array('reports.stock', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('reports.stock') }}" class="nav-link">
        <i class="nav-icon bi bi-box-seam"></i>
        <p>Stock Available</p>
      </a>
    </li>
    @endif

    @if(in_array('reports.purchase', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('reports.purchase') }}" class="nav-link">
        <i class="nav-icon bi bi-cart-plus"></i>
        <p>Purchase Report</p>
      </a>
    </li>
    @endif

   


    @if(in_array('reports.sales', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('reports.sales') }}" class="nav-link">
        <i class="nav-icon bi bi-graph-up"></i>
        <p>Sales Report</p>
      </a>
    </li>
    @endif

  </ul>
</li>
@endif
 
              <!-- Transport Menu -->
             <!-- Transport Menu -->
             {{-- Transport Menu --}}
@if(in_array('drivers.index', $allowedRoutes) || in_array('vehicles.index', $allowedRoutes))
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon bi bi-truck"></i>
    <p>
      Transport
      <i class="nav-arrow bi bi-chevron-right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">

    @if(in_array('drivers.index', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('drivers.index') }}" class="nav-link">
        <i class="nav-icon bi bi-person-badge"></i>
        <p>Drivers</p>
      </a>
    </li>
    @endif

    @if(in_array('vehicles.index', $allowedRoutes))
    <li class="nav-item">
      <a href="{{ route('vehicles.index') }}" class="nav-link">
        <i class="nav-icon bi bi-car-front"></i>
        <p>Vehicles</p>
      </a>
    </li>
    @endif

  </ul>
</li>
@endif

              
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

      <!-- Main Content -->
      <main class="app-main">
        @yield('content')
      </main>

      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Created by Sunface Technologies</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2025&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">Jurrasic world</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{asset('admin/js/adminlte.js')}}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script
      src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
      crossorigin="anonymous"
    ></script>
    <!-- sortablejs -->
    <script>
      new Sortable(document.querySelector('.connectedSortable'), {
        group: 'shared',
        handle: '.card-header',
      });

      const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
      cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = 'move';
      });
    </script>
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>

    <!--Loading overlay-->
<!--Loading overlay-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (
            link &&
            link.href &&
            link.target !== '_blank' &&
            !link.hasAttribute('data-lte-toggle') &&
            !link.classList.contains('nav-arrow') &&
            link.href !== window.location.href &&
            link.href.indexOf('javascript:') !== 0 &&
            link.getAttribute('href') !== '#' &&
            !link.classList.contains('dropdown-toggle')
        ) {
            document.getElementById('loading-overlay').style.display = 'flex';
        }
    });

    // Hide loader when the new page is loaded
    window.addEventListener('pageshow', function () {
        document.getElementById('loading-overlay').style.display = 'none';
    });
    window.addEventListener('load', function () {
        document.getElementById('loading-overlay').style.display = 'none';
    });
});
</script>
    <!-- ChartJS -->
    <script>
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++

      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#revenue-chart'),
        sales_chart_options,
      );
      sales_chart.render();
    </script>
    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>
    <!-- jsvectormap -->
    <script>
      // World map by jsVectorMap
      new jsVectorMap({
        selector: '#world-map',
        map: 'world',
      });

      // Sparkline charts
      const option_sparkline1 = {
        series: [
          {
            data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
      sparkline1.render();

      const option_sparkline2 = {
        series: [
          {
            data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
      sparkline2.render();

      const option_sparkline3 = {
        series: [
          {
            data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
      sparkline3.render();
    </script>
    <!--end::Script-->
    <!-- Select2 JS -->
<script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>

<!-- Toastr JS (optional) -->
<script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>

<!-- Initialize Select2 -->
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Before closing body tag -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@yield('scripts')  {{-- Add this line here --}}
  </body>
  <!--end::Body-->
</html>