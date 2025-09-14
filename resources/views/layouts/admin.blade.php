<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

   <style>
    html, body {
        height: 100%;
        margin: 0;
        background-color: #F7F9FC;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 240px;
        height: 100%;
        background: #1A2238; /* Deep navy */
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
        transition: width 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #fff;
        overflow: hidden;
    }

    .sidebar.collapsed {
        width: 80px;
    }

    /* Brand */
    .sidebar .brand {
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar .brand h4 {
        font-weight: bold;
        font-size: 1.2rem;
        color: #E3F6F5;
        margin: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .brand h4 {
        display: none;
    }

    /* Toggle Button */
    .toggle-btn {
        background: transparent;
        border: none;
        color: #E3F6F5;
        font-size: 1.5rem;
        cursor: pointer;
    }

    /* Nav Links */
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.85);
        padding: 0.75rem 1rem;
        margin: 0.25rem 0;
        border-radius: 50px; /* pill style */
        display: flex;
        align-items: center;
        gap: 10px;
        white-space: nowrap;
        transition: background 0.3s, color 0.3s;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #21B6A8; /* teal highlight */
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar.collapsed .nav-link span {
        display: none; /* hide text when collapsed */
    }

    /* Bottom Section */
    .sidebar .bottom-section {
        padding: 1rem;
    }

    .sidebar.collapsed .bottom-section button span {
        display: none;
    }

    /* Main content */
    .main-content {
        margin-left: 260px;
        padding: 2rem;
        min-height: 100vh;
        background: #F7F9FC;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        transition: margin-left 0.3s ease;
    }

    .sidebar.collapsed ~ .main-content {
        margin-left: 100px;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
</style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div>
            <div class="brand">
                <h4>Admin Panel</h4>
                <button class="toggle-btn" id="toggle-btn" title="Toggle Menu"><i class="bi bi-list"></i></button>
            </div>

            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}" data-bs-toggle="tooltip" title="Dashboard">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                       href="{{ route('admin.users') }}" data-bs-toggle="tooltip" title="User Management">
                        <i class="bi bi-people"></i> <span>User Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" 
                       href="{{ route('admin.settings') }}" data-bs-toggle="tooltip" title="System Settings">
                        <i class="bi bi-gear"></i> <span>System Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" 
                       href="{{ route('admin.categories') }}" data-bs-toggle="tooltip" title="Categories">
                        <i class="bi bi-tags"></i> <span>Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" 
                       href="{{ route('admin.reports') }}" data-bs-toggle="tooltip" title="Reports">
                        <i class="bi bi-bar-chart"></i> <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.maintenance*') ? 'active' : '' }}" 
                       href="{{ route('admin.maintenance') }}" data-bs-toggle="tooltip" title="Maintenance">
                        <i class="bi bi-tools"></i> <span>Maintenance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.audit-logs*') ? 'active' : '' }}" 
                       href="{{ route('admin.audit-logs') }}" data-bs-toggle="tooltip" title="Audit Logs">
                        <i class="bi bi-clipboard-data"></i> <span>Audit Logs</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Logout -->
        <div class="p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="btn btn-light w-100 d-flex align-items-center justify-content-center gap-2 nav-link"
                        data-bs-toggle="tooltip" title="Logout">
                    <i class="bi bi-box-arrow-right"></i> 
                    <span class="logout-text">Logout</span>
                </button>
            </form>
        </div>

    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Sidebar toggle
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggle-btn");

    toggleBtn.addEventListener("click", function() {
        sidebar.classList.toggle("collapsed");
    });

    // Enable tooltips only when collapsed
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach(link => {
        new bootstrap.Tooltip(link, {
            trigger: 'hover',
            placement: 'right',
            customClass: 'sidebar-tooltip'
        });
    });

    function updateTooltips() {
        if (sidebar.classList.contains("collapsed")) {
            navLinks.forEach(link => link.setAttribute("data-bs-toggle", "tooltip"));
        } else {
            navLinks.forEach(link => link.removeAttribute("data-bs-toggle"));
        }
    }

    updateTooltips();
    toggleBtn.addEventListener("click", updateTooltips);
</script>
    @stack('scripts')
</body>
</html>
