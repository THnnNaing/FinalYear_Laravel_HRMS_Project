<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HR Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .hr-primary-bg {
            background-color: #2563eb;
        }
        .hr-secondary-bg {
            background-color: #1e40af;
        }
        .hr-accent {
            color: #3b82f6;
        }
        
        /* Dark mode styles */
        .dark-mode {
            background-color: #0f172a;
            color: #f8fafc;
        }
        .dark-mode .card {
            background-color: #1e293b;
            border-color: #334155;
        }
        .dark-mode .hr-primary-bg {
            background-color: #1e40af;
        }
        .dark-mode .hr-secondary-bg {
            background-color: #1e3a8a;
        }
        .dark-mode .hr-accent {
            color: #60a5fa;
        }
        .dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        .hero-section {
            min-height: 80vh;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        }
        .dark-mode .hero-section {
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.2) 0%, rgba(15, 23, 42, 1) 100%);
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg hr-primary-bg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-people-fill me-2"></i>HR Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="user-avatar me-2">
                                @else
                                    <div class="user-avatar me-2 bg-light text-dark d-flex align-items-center justify-content-center">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" id="darkModeToggle">
                                        <i class="bi bi-moon-stars me-2"></i> Dark Mode
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                        <!-- @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link">
                                    <i class="bi bi-person-plus me-1"></i> Register
                                </a>
                            </li>
                        @endif -->
                    @endauth
                    <li class="nav-item">
                        <a href="#" class="nav-link" id="darkModeToggle">
                            <i class="bi bi-moon-stars me-1"></i> Dark Mode
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4 hr-accent">
                        Modern HR Management <span class="text-dark dark-mode:text-white">Solution</span>
                    </h1>
                    <p class="lead mb-4 text-muted">
                        Streamline your HR processes with our comprehensive management system. 
                        Manage employees, attendance, payroll, and more in one place.
                    </p>
                    <div class="d-flex gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg px-4">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </a>
                            <!-- @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="bi bi-person-plus me-2"></i> Register
                                </a>
                            @endif -->
                        @endauth
                    </div>
                    @auth
                        <div class="mt-4 p-3 bg-light dark-mode:bg-gray-800 rounded">
                            <p class="mb-1">Logged in as: <strong>{{ Auth::user()->email }}</strong></p>
                            <p class="mb-0">Account type: <span class="badge bg-primary">{{ Auth::user()->user_type ?? 'Employee' }}</span></p>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg overflow-hidden">
                        <div class="card-body p-0">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                                 alt="HR Management" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <!-- <section class="py-5 bg-white dark-mode:bg-gray-900">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold hr-accent">Key Features</h2>
                <p class="lead text-muted">Everything you need to manage your workforce effectively</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-people-fill hr-accent fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Employee Management</h4>
                            <p class="text-muted">
                                Centralize all employee data, track employment history, and manage personal information securely.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-calendar-check hr-accent fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Attendance Tracking</h4>
                            <p class="text-muted">
                                Monitor employee attendance, leaves, and time-off requests with our intuitive system.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-cash-stack hr-accent fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Payroll Processing</h4>
                            <p class="text-muted">
                                Automate payroll calculations, tax deductions, and generate payslips effortlessly.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Call to Action -->
    <section class="py-5 hr-primary-bg text-white">
        <div class="container py-4 text-center">
            <h2 class="fw-bold mb-4">Ready to transform your HR operations?</h2>
            <p class="lead mb-4 opacity-75">
                Join hundreds of companies who trust our HR Management System
            </p>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg px-4 me-2">
                    <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-2">
                    <i class="bi bi-person-plus me-2"></i> Get Started
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <!-- <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-3">
                        <i class="bi bi-people-fill me-2 hr-accent"></i>HR Management System
                    </h5>
                    <p class="text-muted mb-0">
                        Streamlining your HR processes for better workforce management.
                    </p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Home</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Features</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Contact</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> hr@company.com</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> (123) 456-7890</li>
                        <li class="mb-2"><i class="bi bi-building me-2"></i> 123 HR Street</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">&copy; {{ date('Y') }} HR Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Terms</a>
                    <a href="#" class="text-decoration-none text-muted">Help</a>
                </div>
            </div>
        </div>
    </footer> -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Dark Mode Toggle -->
    <script>
        document.getElementById('darkModeToggle').addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('dark-mode');
            document.querySelector('nav').classList.toggle('hr-secondary-bg');
            
            // Save preference to localStorage
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        });
        
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            document.querySelector('nav').classList.add('hr-secondary-bg');
        }
    </script>
</body>
</html>