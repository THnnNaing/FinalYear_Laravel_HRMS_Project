<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HR System') }} | @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Select2 (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --primary-light: #e6ecfe;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .wrapper { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            box-shadow: 4px 0 12px rgba(0,0,0,.05);
            position: fixed;
            top: 0; left: 0; height: 100vh;
            z-index: 1050;
            transition: transform .3s ease;
            overflow-y: auto;
        }

        .sidebar.active { transform: translateX(0); }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1.5rem;
            color: var(--text-light);
            font-weight: 500;
            transition: .2s;
            border-radius: .5rem;
            margin: 0 .5rem;
        }

        .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link i { width: 1.5rem; text-align: center; }

        .sidebar-divider {
            height: 1px;
            background: var(--border);
            margin: 1.5rem 1rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: margin-left .3s ease;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding-top: 5rem; }

            .navbar-toggler {
                position: fixed;
                top: 1rem; left: 1rem;
                z-index: 1100;
                background: var(--primary);
                border: none;
                color: white;
                padding: .5rem;
                border-radius: .5rem;
                font-size: 1.5rem;
            }
        }

        @media (min-width: 769px) {
            .navbar-toggler { display: none !important; }
        }

        /* Cards */
        .card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            transition: .2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,.1);
        }
    </style>
</head>

<body>
<div class="wrapper">

    <!-- Sidebar -->
    <x-sidebar />

    <div class="main-content">

        <!-- Mobile Toggle -->
        <button class="navbar-toggler d-lg-none" type="button">
            <i class="bi bi-list"></i>
        </button>

        <div class="container">

            <!-- Page Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-semibold text-primary">@yield('title', 'HR System')</h1>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.querySelector('.sidebar');
        const toggler = document.querySelector('.navbar-toggler');
        const breakpoint = 768;

        // Toggle sidebar
        toggler?.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close on link click (mobile)
        sidebar?.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= breakpoint) {
                    sidebar.classList.remove('active');
                }
            });
        });

        // Resize: remove active on desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > breakpoint) {
                sidebar.classList.remove('active');
            }
        });
    });
</script>

@yield('scripts')
</body>
</html>