<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dean Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')

    <!-- Bootstrap CSS + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #F4F4F9;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100%;
            background: #1e40af; /* Dean color */
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            padding: 0.5rem 0;
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
            margin: 0;
            font-weight: bold;
            font-size: 1.2rem;
            color: #fff;
        }

        /* Toggle button */
        .toggle-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Nav links */
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 50px; /* droplet style */
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: #fff;
        }

        /* Collapse handling */
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .brand h4 {
            display: none;
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 100px;
        }

        /* Logout */
        .sidebar .logout-btn {
            color: #1e40af;
            font-weight: 500;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .sidebar.collapsed .logout-text {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div>
            <div class="brand">
                <h4>Dean Portal</h4>
                <button class="toggle-btn" id="toggle-btn"><i class="bi bi-list"></i></button>
            </div>
            <ul class="nav flex-column px-2">
                <li>
                    <a href="{{ route('dean.dashboard') }}" class="nav-link {{ request()->routeIs('dean.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dean.organizations') }}" class="nav-link {{ request()->routeIs('dean.organizations*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> <span>Organizations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dean.events') }}" class="nav-link {{ request()->routeIs('dean.events*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i> <span>Events Approval</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dean.memberships') }}" class="nav-link {{ request()->routeIs('dean.memberships*') ? 'active' : '' }}">
                        <i class="bi bi-person-check"></i> <span>Memberships</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dean.reports') }}" class="nav-link {{ request()->routeIs('dean.reports*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i> <span>Reports</span>
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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("toggle-btn").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("collapsed");
        });

        // Enable tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el))
    </script>
</body>
</html>
