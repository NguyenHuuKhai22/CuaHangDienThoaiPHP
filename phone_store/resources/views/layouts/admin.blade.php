<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - HK')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/admin/layoutadmin.css') }}">
</head>
<body>
    <a href="#" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <i class="fas fa-chart-line"></i>
            <span>Admin Panel</span>
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
     
        <nav class="nav flex-column mt-3">
            <!-- dashboad -->
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <!-- nguoi dung -->
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Người dùng</span>
            </a>
            <!-- san pham -->
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-mobile-alt"></i>
                <span>Quản lý sản phẩm</span>
            </a>
            <!-- danh muc -->
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span>Quản lý danh mục</span>
            </a>
            <!-- don hang -->
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Quản lý đơn hàng</span>
            </a>
            <!-- khuyen mai -->
            <a href="{{ route('admin.promotions.index') }}" class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Quản lý khuyến mãi</span>
            </a>
            <!-- cai dat -->
            <a href="#" class="nav-link ">
                <i class="fas fa-cog"></i>
                <span>Cài đặt</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="navbar">
            <button class="navbar-toggler d-lg-none" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="welcome-section mb-7">
        <h1 class="h4 fw-bold text-dark mb-2">Chào mừng trở lại, Admin! 👋</h1>
        <p class="text-muted fs-6">Xem tổng quan về hoạt động kinh doanh của bạn.</p>
    </div>
            <div class="ms-auto user-info">
                <div class="avatar">A</div>
                <span class="me-2">{{Auth::user()->name}}</span>
                <span class="badge bg-primary">ADMIN</span>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');

            // Check localStorage for saved state
            const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isSidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 