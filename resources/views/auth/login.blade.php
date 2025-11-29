<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - HR Management System</title>
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
        .dark-mode .form-control {
            background-color: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .dark-mode .form-control:focus {
            background-color: #1e293b;
            border-color: #3b82f6;
            color: #f8fafc;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        .dark-mode .form-check-input {
            background-color: #1e293b;
            border-color: #475569;
        }
        .dark-mode .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .dark-mode .btn-outline-primary {
            color: #60a5fa;
            border-color: #60a5fa;
        }
        .dark-mode .btn-outline-primary:hover {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .login-section {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
            display: flex;
            align-items: center;
        }
        .dark-mode .login-section {
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.2) 0%, rgba(15, 23, 42, 1) 100%);
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        .login-card {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .hr-logo {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg hr-primary-bg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-people-fill me-2"></i>HR Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">
                            <i class="bi bi-house me-1"></i> Home
                        </a>
                    </li>
                    <!-- @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">
                                <i class="bi bi-person-plus me-1"></i> Register
                            </a>
                        </li>
                    @endif -->
                    <li class="nav-item">
                        <a href="#" class="nav-link" id="darkModeToggle">
                            <i class="bi bi-moon-stars me-1"></i> Dark Mode
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card login-card">
                        <div class="login-header">
                            <div class="hr-logo">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <h2 class="mb-0">Welcome Back</h2>
                            <p class="mb-0 opacity-75">Sign in to your HR Management account</p>
                        </div>
                        <div class="card-body p-5">
                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success mb-4" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <input id="email" class="form-control form-control-lg" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus 
                                           autocomplete="username"
                                           placeholder="Enter your email">
                                    @if ($errors->has('email'))
                                        <div class="text-danger mt-2">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="password" class="form-label fw-semibold">Password</label>
                                        @if (Route::has('password.request'))
                                            <a class="text-decoration-none hr-accent" href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                    </div>
                                    <input id="password" class="form-control form-control-lg"
                                           type="password"
                                           name="password"
                                           required 
                                           autocomplete="current-password"
                                           placeholder="Enter your password">
                                    @if ($errors->has('password'))
                                        <div class="text-danger mt-2">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Remember Me -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                        <label for="remember_me" class="form-check-label">
                                            {{ __('Remember me') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('Log in') }}
                                    </button>
                                </div>

                                <!-- @if (Route::has('register'))
                                    <div class="text-center mt-4">
                                        <p class="text-muted">Don't have an account? 
                                            <a href="{{ route('register') }}" class="text-decoration-none hr-accent fw-semibold">Register here</a>
                                        </p>
                                    </div>
                                @endif -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <!-- <footer class="hr-primary-bg text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>HR Management System
                    </h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 opacity-75">&copy; {{ date('Y') }} HR Management System. All rights reserved.</p>
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
            document.querySelector('footer').classList.toggle('hr-secondary-bg');
            
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
            document.querySelector('footer').classList.add('hr-secondary-bg');
        }
    </script>
</body>
</html>