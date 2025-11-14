<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Galeri SMK NEGERI 4 BOGOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #224abe;
            --sidebar-width: 280px;
            --header-height: 70px;
            --sidebar-bg: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-anchor: none;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Prevent focus scroll */
        .menu-link:focus {
            outline: none;
        }
        
        .menu-link:focus-visible {
            outline: 2px solid rgba(255,255,255,0.5);
            outline-offset: 2px;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 15px;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.2));
        }

        .sidebar-header h4 {
            margin: 0 0 5px 0;
            font-weight: 700;
            color: white;
            font-size: 1.1rem;
            line-height: 1.3;
            letter-spacing: 0.5px;
        }

        .sidebar-header small {
            color: rgba(255,255,255,0.85);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            margin: 5px 15px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 5px;
        }

        .menu-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .menu-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .menu-link i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
            font-size: 16px;
        }

        .menu-section {
            margin: 20px 0;
        }

        .menu-section-title {
            padding: 10px 20px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .content {
            padding: 30px;
        }

        /* Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .bg-primary-light {
            background-color: rgba(78, 115, 223, 0.15);
        }

        .bg-success-light {
            background-color: rgba(28, 200, 138, 0.15);
        }

        .bg-warning-light {
            background-color: rgba(246, 194, 62, 0.15);
        }

        .bg-info-light {
            background-color: rgba(54, 185, 204, 0.15);
        }

        .stat-text {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-top: 5px;
        }

        /* Tables */
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .table-card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
        }

        .table-card-header h5 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 250px;
            }
            
            .main-content {
                margin-left: 250px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .header {
                padding: 0 15px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .stats-card {
                margin-bottom: 20px;
            }
        }

        /* Loading */
        .loading {
            display: none;
        }

        /* Modal */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #eee;
            padding: 20px 25px;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            border-top: 1px solid #eee;
            padding: 20px 25px;
        }
        
        /* Toggle Button for Mobile */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #333;
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
            margin-right: 15px;
        }
        
        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
        
        /* Menu item spacing */
        .menu-section + .menu-section {
            margin-top: 10px;
        }
        
        /* Badge styling */
        .badge {
            font-size: 0.75em;
            padding: 0.35em 0.65em;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo smkn 4.png') }}" alt="Logo SMK Negeri 4 Bogor" class="sidebar-logo">
            <h4>SMK NEGERI 4<br>BOGOR</h4>
            <small>Admin Dashboard</small>
        </div>
        
        <div class="sidebar-menu">
            <!-- Dashboard -->
            <div class="menu-section">
                <div class="menu-section-title">Dashboard</div>
                <div class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Galeri Management -->
            <div class="menu-section">
                <div class="menu-section-title">Manajemen Galeri</div>
                <div class="menu-item">
                    <a href="{{ route('admin.galleries.index') }}" class="menu-link {{ request()->routeIs('admin.galleries.index') || request()->routeIs('admin.galleries.show') || request()->routeIs('admin.galleries.edit') ? 'active' : '' }}">
                        <i class="fas fa-images"></i>
                        <span>Semua Foto</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.galleries.create') }}" class="menu-link {{ request()->routeIs('admin.galleries.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Foto</span>
                    </a>
                </div>
            </div>

            <!-- Konten Website -->
            <div class="menu-section">
                <div class="menu-section-title">Konten Website</div>
                <div class="menu-item">
                    <a href="{{ route('admin.news.index') }}" class="menu-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Berita</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.about.edit') }}" class="menu-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i>
                        <span>Tentang</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.contact.edit') }}" class="menu-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                        <i class="fas fa-phone"></i>
                        <span>Kontak</span>
                    </a>
                </div>
            </div>

            <!-- Kategori Galeri -->
            <div class="menu-section">
                <div class="menu-section-title">Kategori Galeri</div>
                <div class="menu-item">
                    <a href="{{ route('admin.galleries.category', 'academic') }}" class="menu-link {{ request()->routeIs('admin.galleries.category') && request()->route('category') == 'academic' ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Akademik</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.galleries.category', 'extracurricular') }}" class="menu-link {{ request()->routeIs('admin.galleries.category') && request()->route('category') == 'extracurricular' ? 'active' : '' }}">
                        <i class="fas fa-running"></i>
                        <span>Ekstrakurikuler</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.galleries.category', 'event') }}" class="menu-link {{ request()->routeIs('admin.galleries.category') && request()->route('category') == 'event' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Acara & Event</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.galleries.category', 'common') }}" class="menu-link {{ request()->routeIs('admin.galleries.category') && request()->route('category') == 'common' ? 'active' : '' }}">
                        <i class="fas fa-folder"></i>
                        <span>Umum</span>
                    </a>
                </div>
            </div>

            <!-- Notifikasi -->
            <div class="menu-section">
                <div class="menu-section-title">Notifikasi</div>
                <div class="menu-item">
                    <a href="{{ route('admin.notifications.index') }}" class="menu-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Aktivitas User</span>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="badge bg-danger ms-auto">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Laporan & Statistik -->
            <div class="menu-section">
                <div class="menu-section-title">Laporan</div>
                <div class="menu-item">
                    <a href="{{ route('admin.reports.gallery') }}" class="menu-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistik Galeri</span>
                    </a>
                </div>
            </div>

            <!-- Pengaturan -->
            <div class="menu-section">
                <div class="menu-section-title">Pengaturan</div>
                <div class="menu-item">
                    <a href="{{ route('admin.settings') }}" class="menu-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left d-flex align-items-center">
                <img src="{{ url('images/logo smkn 4.png') }}" alt="Logo SMKN 4 Bogor" style="height: 40px; margin-right: 15px;">
                <h4 class="page-title mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>
            <div class="header-right">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Prevent sidebar scroll jump when clicking menu items
        const sidebar = document.querySelector('.sidebar');
        let savedScrollPos = 0;
        
        document.querySelectorAll('.menu-link').forEach(function(link) {
            link.addEventListener('focus', function(e) {
                // Save scroll position before focus
                savedScrollPos = sidebar.scrollTop;
            });
            
            link.addEventListener('click', function(e) {
                // Save current scroll position
                const currentScroll = sidebar.scrollTop;
                
                // Navigate to href
                const href = this.getAttribute('href');
                if (href && href !== '#') {
                    window.location.href = href;
                }
            });
        });
        
        // Restore scroll position after any focus event in sidebar
        sidebar.addEventListener('focusin', function(e) {
            const currentScroll = this.scrollTop;
            if (currentScroll !== savedScrollPos && savedScrollPos > 0) {
                this.scrollTop = savedScrollPos;
            }
        });
    </script>
    @yield('scripts')
</body>
</html>