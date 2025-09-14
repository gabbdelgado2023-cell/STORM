<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
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
            background: #4f46e5; /* indigo-700 */
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            transition: width 0.3s ease;
            display: flex; flex-direction: column;
            justify-content: space-between;
            color: #fff;
            overflow: hidden;
        }
        .sidebar.collapsed { width: 80px; }

        /* Brand */
        .brand {
            padding: 1rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .brand h4 { margin: 0; font-size: 1.2rem; font-weight: bold; }
        .sidebar.collapsed .brand h4 { display: none; }

        /* Toggle */
        .toggle-btn {
            background: transparent; border: none; color: #fff; font-size: 1.5rem; cursor: pointer;
        }

        /* Nav Links */
        .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 50px; /* pill look */
            display: flex; align-items: center; gap: 10px;
            white-space: nowrap;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #6366f1; /* indigo-500 */
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .sidebar.collapsed .nav-link span { display: none; }

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
            display: flex; justify-content: flex-end; align-items: center;
            padding: 0 20px;
            border-radius: 10px;
            margin: 10px;   
        }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .user-info span { font-weight: 500; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div>
            <div class="brand">
                <h4>Student Portal</h4>
                <button class="toggle-btn" id="toggle-btn"><i class="bi bi-list"></i></button>
            </div>
            <ul class="nav flex-column px-2">
                <li>
                    <a href="{{ route('student.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
                       data-bs-toggle="tooltip" title="Dashboard">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.organizations') }}" 
                       class="nav-link {{ request()->routeIs('student.organizations*') ? 'active' : '' }}" 
                       data-bs-toggle="tooltip" title="Organizations">
                        <i class="bi bi-people"></i> <span>Organizations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.memberships') }}" 
                       class="nav-link {{ request()->routeIs('student.memberships*') ? 'active' : '' }}" 
                       data-bs-toggle="tooltip" title="My Memberships">
                        <i class="bi bi-person-check"></i> <span>My Memberships</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.events') }}" 
                       class="nav-link {{ request()->routeIs('student.events*') ? 'active' : '' }}" 
                       data-bs-toggle="tooltip" title="Events">
                        <i class="bi bi-calendar-event"></i> <span>Events</span>
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
        <!-- Topbar -->
        <div class="topbar">
            <div class="user-info">
                <i class="bi bi-person-circle fs-4"></i>
                <span>{{ Auth::user()->name ?? 'Student' }}</span>
            </div>
        </div>

        <div class="p-4">
            @yield('content')
            @yield('student-content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.getElementById("toggle-btn");
        const navLinks = document.querySelectorAll(".nav-link");

        toggleBtn.addEventListener("click", function() {
            sidebar.classList.toggle("collapsed");
            updateTooltips();
        });

        // Tooltips (only when collapsed)
        navLinks.forEach(link => {
            new bootstrap.Tooltip(link, {
                trigger: 'hover',
                placement: 'right'
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
    </script>
</body>
</html>
