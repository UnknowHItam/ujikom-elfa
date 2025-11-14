@extends('layouts.user')

@section('title', 'Dashboard Pengguna')

@section('styles')
<style>
    /* Base Styles */
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --secondary: #858796;
        --light: #f8f9fc;
        --dark: #5a5c69;
    }
    
    body {
        color: #4a5568;
        line-height: 1.6;
    }
    /* Base Styles */
    body {
        background-color: #f8f9fc;
    }
    
    /* Card Styles */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        height: 100%;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #4e73df;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Stats Cards */
    .stat-card {
        border-left: 0.25rem solid #4e73df;
        height: 100%;
    }
    
    .stat-card .card-body {
        padding: 1.25rem;
    }
    
    .stat-card.primary {
        border-left-color: #4e73df;
    }
    
    .stat-card.success {
        border-left-color: #1cc88a;
    }
    
    .stat-card.warning {
        border-left-color: #f6c23e;
    }
    
    .stat-card.info {
        border-left-color: #36b9cc;
    }
    
    .stat-card.danger {
        border-left-color: #e74a3b;
    }
    
    /* Category Card Styles */
    .category-card {
        background: white;
        border-radius: 16px;
        padding: 24px 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: height 0.3s ease;
    }
    
    .category-card.category-primary::before {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .category-card.category-success::before {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .category-card.category-info::before {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .category-card.category-warning::before {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    
    .category-card:hover::before {
        height: 100%;
        opacity: 0.05;
    }
    
    .category-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }
    
    .category-primary .category-icon-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .category-success .category-icon-wrapper {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .category-info .category-icon-wrapper {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .category-warning .category-icon-wrapper {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .category-card:hover .category-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    .category-icon {
        font-size: 32px;
        color: white;
    }
    
    .category-info {
        margin-bottom: 12px;
    }
    
    .category-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .category-count {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 6px;
    }
    
    .count-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1;
    }
    
    .count-label {
        font-size: 0.875rem;
        color: #718096;
        font-weight: 500;
    }
    
    .category-arrow {
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        color: #4a5568;
    }
    
    .category-card:hover .category-arrow {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* Dashboard Header Styles */
    .dashboard-header-wrapper {
        padding: 2rem 1rem;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-header-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .wave-emoji {
        display: inline-block;
        animation: wave 2.5s ease-in-out infinite;
        transform-origin: 70% 70%;
        font-size: 2.5rem;
    }
    
    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        10%, 30% { transform: rotate(14deg); }
        20% { transform: rotate(-8deg); }
        40% { transform: rotate(-4deg); }
        50% { transform: rotate(10deg); }
        60% { transform: rotate(0deg); }
    }
    
    .dashboard-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 0;
        position: relative;
        z-index: 1;
        font-weight: 500;
    }
    
    .dashboard-subtitle i {
        animation: bounce 2s ease-in-out infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    /* Gallery Card Styles */
    .gallery-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .gallery-image-wrapper {
        position: relative;
        width: 100%;
        padding-top: 60%; /* 5:3 aspect ratio for landscape */
        overflow: hidden;
        background: #f0f0f0;
    }
    
    .gallery-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-card:hover .gallery-image {
        transform: scale(1.05);
    }
    
    .gallery-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 1;
    }
    
    .gallery-content {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .gallery-title {
        color: #2d3748;
        font-weight: 600;
        font-size: 0.95rem;
        line-height: 1.4;
        margin-bottom: 8px;
    }
    
    .gallery-card:hover .gallery-title {
        color: #4e73df;
    }
    
    .stat-card .stat-text {
        font-size: 0.9rem;
        color: #5a5c69;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .stat-card .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #5a5c69;
    }
    
    .stat-card .stat-icon {
        font-size: 2rem;
        color: #dddfeb;
    }
    
    /* Table Styles */
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #4e73df;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Hover Effects */
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 0.75rem;
        }
        
        .stat-card h3 {
            font-size: 1.75rem;
        }
        
        .stat-card .icon-circle {
            width: 35px;
            height: 35px;
        }
        
        .stat-card .icon-circle i {
            font-size: 1.2rem;
        }
        
        .stat-card .card-body {
            padding: 1rem !important;
        }
        
        .hero-section { 
            min-height: 50vh; 
        }
        
        .hero-content { 
            padding: 2.5rem 1rem; 
        }
    }
    
    @media (max-width: 576px) {
        .stat-card h6 {
            font-size: 0.7rem;
        }
        
        .stat-card h3 {
            font-size: 1.5rem;
        }
        
        .stat-card small {
            font-size: 0.7rem;
        }
    }
    
    /* Chart Container */
    .chart-container {
        position: relative;
        height: 250px;
        min-height: 200px;
    }
    
    /* Recent Items */
    .recent-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eaecf4;
    }
    
    .recent-item:last-child {
        border-bottom: none;
    }
    
    .recent-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 0.35rem;
        margin-right: 1rem;
    }
    
    .recent-item .recent-item-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .recent-item .recent-item-meta {
        font-size: 0.8rem;
        color: #858796;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }
    
    .icon-circle i {
        font-size: 1.5rem;
    }
    
    .bg-primary-light {
        background-color: #c5c9e2;
    }
    
    .bg-success-light {
        background-color: #c6efce;
    }
    
    .bg-warning-light {
        background-color: #fbe4cf;
    }
    
    .bg-info-light {
        background-color: #bee5eb;
    }
    
    .bg-danger-light {
        background-color: #f5c6cb;
    }
    
    /* List Items */
    .list-group-item {
        transition: all 0.2s;
        border-left: 0;
        border-right: 0;
    }
    
    .list-group-item:first-child {
        border-top: 0;
    }
    
    .list-group-item:last-child {
        border-bottom: 0;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fc;
        transform: translateX(5px);
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    
    /* Hero Section */
    .hero-section {
        position: relative;
        width: 100%;
        min-height: 70vh; /* tinggi proporsional layar */
        border-radius: 0.75rem;
        overflow: hidden;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }
    /* Membuat hero full-bleed (melebar sampai tepi layar meski berada di dalam container) */
    .hero-fullbleed {
        position: relative;
        width: 100vw; /* paksa selebar viewport */
        left: 50%;
        transform: translateX(-50%); /* center tanpa sisa gutter */
        margin-left: 0;
        margin-right: 0;
        border-radius: 0; /* full edge */
    }
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient( to bottom, rgba(0,0,0,.25), rgba(0,0,0,.45) );
    }
    .hero-content {
        position: relative;
        z-index: 2;
        color: #fff;
        padding: 4.5rem 2rem; /* lebih lega secara vertikal */
        text-align: center;
    }
    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(255,255,255,.15);
        color: #fff;
        border: 1px solid rgba(255,255,255,.25);
        padding: .5rem .875rem;
        border-radius: 999px;
        backdrop-filter: blur(2px);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .hero-title-intro {
        font-size: clamp(1.4rem, 2.8vw, 2.2rem);
        font-weight: 800;
        line-height: 1.1;
        margin: .5rem 0 .25rem 0;
        text-shadow: 0 2px 10px rgba(0,0,0,.25);
    }
    .hero-title-main {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 1rem;
        text-shadow: 0 2px 10px rgba(0,0,0,.25);
    }
    .hero-subtitle {
        color: #eef2ff;
        max-width: 960px;
        margin: 0 auto 2rem auto;
        font-size: clamp(.95rem, 1.8vw, 1.125rem);
        text-shadow: 0 1px 6px rgba(0,0,0,.2);
    }
    .hero-actions .btn {
        padding: 1rem 1.35rem;
        border-radius: 999px;
        font-weight: 600;
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        .hero-section { min-height: 55vh; }
        .hero-content { padding: 3rem 1.25rem; }
        
        .gallery-image-wrapper {
            padding-top: 65%; /* Slightly taller on mobile */
        }
        
        .col-6 {
            margin-bottom: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .gallery-image-wrapper {
            padding-top: 70%;
        }
        
        .gallery-content {
            padding: 12px;
        }
        
        .gallery-title {
            font-size: 0.875rem;
        }
        
        .category-card {
            padding: 20px 16px;
        }
        
        .category-icon-wrapper {
            width: 60px;
            height: 60px;
        }
        
        .category-icon {
            font-size: 28px;
        }
        
        .count-number {
            font-size: 1.75rem;
        }
        
        .dashboard-title {
            font-size: 1.5rem;
        }
        
        .wave-emoji {
            font-size: 2rem;
        }
        
        .dashboard-subtitle {
            font-size: 0.95rem;
        }
        
        .dashboard-header-wrapper {
            padding: 1.5rem 1rem;
        }
    }
</style>
@endsection

@section('content')

@php
    $heroBackground = asset('images/smk4 bogor 2.jpg');
@endphp

<div class="container-fluid pt-0 pb-4">
    <!-- Hero Section -->
    <div class="hero-section hero-fullbleed mb-4" style="background-image: url('{{ $heroBackground }}');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-kicker">
                <i class="fas fa-graduation-cap"></i>
                <span>Sekolah Unggulan di Bogor</span>
            </div>
            <h1 class="hero-title-intro">Selamat Datang di</h1>
            <h1 class="hero-title-main">GALERI SMK NEGERI 4 BOGOR</h1>
            <p class="hero-subtitle">
                Membangun generasi unggul melalui pendidikan berkualitas, teknologi terdepan, dan
                pengembangan karakter yang kuat untuk masa depan yang gemilang.
            </p>
            <div class="hero-actions d-flex justify-content-center gap-2">
                <a href="{{ route('galleries.index') }}" class="btn btn-primary">
                    <i class="fas fa-images me-2"></i>Jelajahi Galeri
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-light">
                    <i class="fas fa-phone me-2"></i>Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    <!-- Page Header (dipusatkan) -->
    <div class="text-center mb-5">
        <div class="dashboard-header-wrapper">
            <h1 class="dashboard-title mb-3">
                <span class="wave-emoji">👋</span>
                @auth
                    Selamat datang di galeri {{ auth()->user()->name }}!
                @else
                    Selamat datang di Dashboard!
                @endauth
            </h1>
            <p class="dashboard-subtitle">
                <i class="fas fa-chart-line me-2"></i>
                Lihat data dan aktivitas galeri di sini!
            </p>
        </div>
    </div>

    <!-- Kategori Galeri Cards -->
    <div class="row g-3 g-md-4 mb-4">
        @php
            $categories = [
                ['name' => 'Akademik', 'icon' => 'fa-graduation-cap', 'color' => 'primary', 'key' => 'academic'],
                ['name' => 'Ekstrakurikuler', 'icon' => 'fa-users', 'color' => 'success', 'key' => 'extracurricular'],
                ['name' => 'Acara & Event', 'icon' => 'fa-calendar-star', 'color' => 'info', 'key' => 'event'],
                ['name' => 'Umum', 'icon' => 'fa-folder', 'color' => 'warning', 'key' => 'common']
            ];
        @endphp

        @foreach($categories as $category)
            @php
                $categoryData = collect($galleryCategories ?? [])->firstWhere('name', $category['name']);
                $count = $categoryData['count'] ?? 0;
            @endphp
            <div class="col-6 col-md-3">
                <a href="{{ route('galleries.category', $category['key']) }}" class="text-decoration-none">
                    <div class="category-card category-{{ $category['color'] }}">
                        <div class="category-icon-wrapper">
                            <i class="fas {{ $category['icon'] }} category-icon"></i>
                        </div>
                        <div class="category-info">
                            <h6 class="category-name">{{ $category['name'] }}</h6>
                            <div class="category-count">
                                <span class="count-number">{{ $count }}</span>
                                <span class="count-label">Foto</span>
                            </div>
                        </div>
                        <div class="category-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Galeri Terbaru Section -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fas fa-images me-2"></i>Galeri Terbaru
                    </h5>
                    <a href="{{ route('galleries.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-3">
                    @if(isset($recentGalleries) && $recentGalleries->count() > 0)
                        <div class="row g-3">
                            @foreach($recentGalleries->take(6) as $gallery)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <a href="{{ route('galleries.index') }}" class="text-decoration-none">
                                        <div class="gallery-card">
                                            <div class="gallery-image-wrapper">
                                                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="gallery-image">
                                                @php
                                                    $categoryInfo = [
                                                        'academic' => ['name' => 'Akademik', 'color' => '#4e73df'],
                                                        'extracurricular' => ['name' => 'Ekstrakurikuler', 'color' => '#1cc88a'],
                                                        'event' => ['name' => 'Acara', 'color' => '#f6c23e'],
                                                        'common' => ['name' => 'Umum', 'color' => '#36b9cc']
                                                    ][$gallery->category] ?? ['name' => 'Lainnya', 'color' => '#6c757d'];
                                                @endphp
                                                <span class="gallery-badge" style="background-color: {{ $categoryInfo['color'] }};">
                                                    {{ $categoryInfo['name'] }}
                                                </span>
                                            </div>
                                            <div class="gallery-content">
                                                <h6 class="gallery-title mb-2">{{ Str::limit($gallery->title, 50) }}</h6>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i> {{ $gallery->created_at->diffForHumans() }}
                                                    </small>
                                                    <i class="fas fa-arrow-right text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada galeri tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Berita Terbaru Section -->
    <div class="row g-3 g-md-4 mt-1">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-newspaper me-2"></i>Berita Terbaru
                    </h6>
                    <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(isset($latestNews) && $latestNews->count() > 0)
                        <div class="row g-0">
                            @foreach($latestNews->take(5) as $news)
                                <div class="col-md-6 col-lg-4">
                                    <a href="{{ route('news.show', $news->id) }}" class="list-group-item list-group-item-action border-0 py-3 px-4 h-100 d-block">
                                        <div class="d-flex flex-column h-100">
                                            @if($news->image)
                                                <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="rounded mb-3" style="width: 100%; height: 150px; object-fit: cover;">
                                            @endif
                                            <div class="mb-2">
                                                @php
                                                    $categoryColors = [
                                                        'umum' => 'secondary',
                                                        'prestasi' => 'success',
                                                        'kegiatan' => 'primary',
                                                        'pengumuman' => 'warning'
                                                    ];
                                                    $categoryNames = [
                                                        'umum' => 'Umum',
                                                        'prestasi' => 'Prestasi',
                                                        'kegiatan' => 'Kegiatan',
                                                        'pengumuman' => 'Pengumuman'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $categoryColors[$news->category] ?? 'secondary' }}">
                                                    {{ $categoryNames[$news->category] ?? $news->category }}
                                                </span>
                                            </div>
                                            <h6 class="mb-2">{{ Str::limit($news->title, 50) }}</h6>
                                            <p class="text-muted small mb-2">{{ Str::limit($news->description, 80) }}</p>
                                            <div class="mt-auto">
                                                <small class="text-muted d-block">
                                                    <i class="far fa-calendar me-1"></i> {{ $news->published_at->format('d M Y') }}
                                                </small>
                                                @if($news->author)
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-user me-1"></i> {{ $news->author }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">
                                @if(!isset($latestNews))
                                    Data berita tidak tersedia
                                @else
                                    Belum ada berita terbaru
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    const ctx = document.getElementById('galleryPieChart');
    if (ctx) {
        // Pastikan variabel galleryCategories terdefinisi
        const categories = @json(isset($galleryCategories) ? $galleryCategories : []);
        
        // Hanya buat chart jika ada data
        if (categories && categories.length > 0) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: categories.map(item => item.name || ''),
                    datasets: [{
                        data: categories.map(item => item.count || 0),
                        backgroundColor: categories.map(item => item.color || '#cccccc'),
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%',
                borderRadius: 5
            }
            });
        } else {
            // Jika tidak ada data, sembunyikan atau tampilkan pesan
            ctx.closest('.card').innerHTML = `
                <div class="card-body text-center py-4">
                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data kategori galeri yang tersedia</p>
                </div>`;
        }
    }
});
</script>
@endpush

@endsection

