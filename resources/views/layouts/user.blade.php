<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Galeri SMK Negeri 4 Bogor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            padding-top: 56px;
        }

        /* Navbar Styles */
        .navbar-custom {
            background: #4e73df;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0;
            min-height: 56px;
            transition: all 0.3s ease;
        }

        .navbar-custom.scrolled {
            background: #4e73df;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1rem;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: rgba(255,255,255,0.9) !important;
        }

        .navbar-brand .logo {
            width: 26px;
            height: 26px;
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover .logo {
            transform: scale(1.05);
        }

        .navbar-nav {
            margin-left: auto;
        }

        .nav-link {
            color: rgba(255,255,255,0.95) !important;
            font-weight: 500;
            padding: 10px 12px !important;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.85rem;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.1);
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            width: 100%;
        }

        .nav-link i {
            margin-right: 5px;
            font-size: 0.8rem;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border-radius: 15px;
            margin-top: 15px;
            padding: 10px;
            animation: dropdownFadeIn 0.3s ease;
            overflow: hidden;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 12px 20px;
            color: #2c3e50;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 10px;
            margin: 2px 5px;
            position: relative;
            overflow: hidden;
        }

        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, #4e73df, #224abe);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .dropdown-item:hover {
            background: #4e73df;
            color: white;
        }

        .dropdown-item:hover::before {
            width: 100%;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .dropdown-item:hover i {
            transform: scale(1.2);
        }

        /* Mobile Navbar Toggle */
        .navbar-toggler {
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-toggler:hover {
            background: rgba(255,255,255,0.1);
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 80px);
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .stats-label {
            color: #7f8c8d;
            font-weight: 500;
            margin-bottom: 0;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
        }

        .table-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
        }

        .table-card-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-body {
                padding: 0 15px 15px;
            }
        }

        /* Toggle Button */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }

        .footer {
            background: white;
            border-top: 1px solid #eee;
            padding: 20px 25px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                <img src="{{ asset('images/logo smkn 4.png') }}" alt="Logo" class="logo">
                SMK Negeri 4 Bogor
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-home"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="fas fa-info-circle"></i>Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.*') || request()->routeIs('berita*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                            <i class="fas fa-newspaper"></i>Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('galleries.*') || request()->routeIs('galeri*') ? 'active' : '' }}" href="{{ route('galleries.index') }}">
                            <i class="fas fa-images"></i>Galeri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="fas fa-phone"></i>Kontak
                        </a>
                    </li>
                    @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.login') ? 'active' : '' }}" href="{{ route('user.login') }}">
                            <i class="fas fa-sign-in-alt"></i>Login
                        </a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset(Auth::user()->profile_photo) }}" alt="Profile" 
                                     class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle"></i>
                            @endif
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('user.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Glassmorphism effect on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add ripple effect to nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    </script>
    
    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
    
    @yield('scripts')

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">
                        <img src="{{ asset('images/logo smkn 4.png') }}" alt="Logo" style="width: 30px; height: 30px;" class="me-2">
                        SMK Negeri 4 Bogor
                    </h5>
                    <p class="footer-text">
                        Lembaga pendidikan kejuruan yang menghasilkan lulusan kompeten dan siap kerja di bidang teknologi dan industri.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">Link Cepat</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('user.dashboard') }}"><i class="fas fa-chevron-right me-2"></i>Beranda</a></li>
                        <li><a href="{{ route('about') }}"><i class="fas fa-chevron-right me-2"></i>Tentang</a></li>
                        <li><a href="{{ route('news.index') }}"><i class="fas fa-chevron-right me-2"></i>Berita</a></li>
                        <li><a href="{{ route('galleries.index') }}"><i class="fas fa-chevron-right me-2"></i>Galeri</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right me-2"></i>Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">Kontak Kami</h5>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Jl. Raya Tajur, Kp. Buntar, Bogor Selatan</li>
                        <li><i class="fas fa-phone me-2"></i>(0251) 7547381</li>
                        <li><i class="fas fa-envelope me-2"></i>smkn4@smkn4bogor.sch.id</li>
                        <li><i class="fas fa-globe me-2"></i>www.smkn4bogor.sch.id</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: #ecf0f1;
            padding: 50px 0 20px;
            margin-top: auto;
        }

        .footer-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .footer-text {
            color: #bdc3c7;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .social-link {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-link:hover {
            background: #4e73df;
            color: #fff;
            transform: translateY(-3px);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #bdc3c7;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 5px;
        }

        .footer-contact {
            list-style: none;
            padding: 0;
        }

        .footer-contact li {
            color: #bdc3c7;
            font-size: 0.9rem;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .footer-contact i {
            color: #4e73df;
        }

        .footer-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 30px 0 20px;
        }

        .copyright-text {
            color: #95a5a6;
            font-size: 0.85rem;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .footer {
            margin-top: auto;
        }
    </style>
</body>
</html>
