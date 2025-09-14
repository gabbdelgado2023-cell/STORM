<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Officer Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')

    <!-- Bootstrap CSS + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body { background-color: #F4F4F9; }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 240px; height: 100%;
            background:rgb(1, 67, 158); /* Officer color */
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            transition: width 0.3s ease;
            display: flex; flex-direction: column;
            justify-content: space-between;
            color: #fff;
            padding: 0.5rem 0;
        }
        .sidebar.collapsed { width: 80px; }

        /* Brand */
        .brand {
            padding: 1rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .brand h4 { margin: 0; font-size: 1.2rem; font-weight: bold; color: #fff; }

        /* Toggle */
        .toggle-btn {
            background: transparent; border: none; color: #fff; font-size: 1.5rem; cursor: pointer;
        }

        /* Nav Links */
        .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 50px; /* droplet style */
            display: flex; align-items: center; gap: 10px;
            transition: background 0.3s, color 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.2); color: #fff;
        }

        /* Collapsed */
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .brand h4 { display: none; }

        /* Main Content */
        .main-content {
            margin-left: 260px; transition: margin-left 0.3s ease;
        }
        .sidebar.collapsed ~ .main-content { margin-left: 100px; }

        /* Topbar */
        .topbar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            border-radius: 10px;
            margin: 10px;
            display: flex; justify-content: flex-end; align-items: center;
            padding: 0 20px;
        }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .user-info span { font-weight: 500; }

        /* Logout */
        .sidebar .logout-btn {
            color: #334155;
            font-weight: 500;
            border-radius: 50px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .sidebar.collapsed .logout-text { display: none; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div>
            <div class="brand">
                <h4>Officer Portal</h4>
                <button class="toggle-btn" id="toggle-btn"><i class="bi bi-list"></i></button>
            </div>
            <ul class="nav flex-column px-2">
                <li><a href="{{ route('officer.dashboard') }}" class="nav-link {{ request()->routeIs('officer.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a></li>
                <li><a href="{{ route('officer.members') }}" class="nav-link {{ request()->routeIs('officer.members*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span>Manage Members</span>
                </a></li>
                <li><a href="{{ route('officer.events') }}" class="nav-link {{ request()->routeIs('officer.events*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i> <span>Events</span>
                </a></li>
                <li><a href="{{ route('officer.profile') }}" class="nav-link {{ request()->routeIs('officer.profile*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> <span>Organization Profile</span>
                </a></li>
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
        <!-- Topbar -->
        <div class="topbar">
            <div class="user-info">
                <i class="bi bi-person-circle fs-4"></i>
                <span>{{ Auth::user()->name ?? 'Officer' }}</span>
            </div>
        </div>

        <div class="p-4">
            @yield('content')
        </div>
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
