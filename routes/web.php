<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Kontroller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\ContactPageController;

// Kontroller User
use App\Http\Controllers\User\NewsController;
use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\AboutController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Auth\RegisterController;

// =========================
// Rute Halaman Depan (Publik)
// =========================

// Halaman Utama
Route::get('/', function () {
    return view('gallery.index');
})->name('home');

// Rute Publik (Tidak Perlu Login)
Route::get('/tentang', [AboutController::class, 'index'])->name('tentang');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
// Berita Publik
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/news', [NewsController::class, 'index'])->name('berita');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('berita.show');

// Rute User (Dashboard publik, yang lain perlu login)
Route::prefix('user')->name('user.')->group(function() {
    // Dashboard - Bisa diakses tanpa login
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Berita - Publik
    Route::get('/berita', [NewsController::class, 'index'])->name('news');
    Route::get('/berita/{id}', [NewsController::class, 'show'])->name('news.show');
    
    // Galeri - Publik
    Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri');
    Route::get('/galeri/kategori/{category}', [GalleryController::class, 'category'])->name('galeri.kategori');
});

// Alias untuk kompatibilitas
Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/galleries/category/{category}', [GalleryController::class, 'category'])->name('galleries.category');

// =========================
// Admin Authentication
// =========================
Route::get('/admin/login', function () {
    return view('auth.login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');

    // Login sederhana (sementara)
    if ($username === 'admin' && $password === 'admin123') {
        // Simpan session login admin
        session(['admin_logged_in' => true]);
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors(['username' => 'Username atau password salah.']);
})->name('admin.login.submit');

// =========================
// User Authentication
// =========================

// Guest routes (belum login)
Route::middleware('guest')->group(function() {
    // Default /login route untuk user
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    Route::get('/user/login', [AuthController::class, 'showLoginForm'])->name('user.login');
    Route::post('/user/login', [AuthController::class, 'login'])->name('user.login.submit');
    Route::get('/user/register', [RegisterController::class, 'showRegistrationForm'])->name('user.register');
    Route::post('/user/register', [RegisterController::class, 'register'])->name('user.register.submit');
});

// Authenticated user routes
Route::middleware('auth')->group(function() {
    Route::post('/user/logout', [AuthController::class, 'logout'])->name('user.logout');
    
    // Profile routes
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::put('/user/profile', [ProfileController::class, 'update'])->name('user.profile.update');
});

// =========================
// Admin Routes (Lindungi dengan middleware auth nanti)
// =========================
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect root admin ke login jika belum login, atau ke dashboard jika sudah login
    Route::get('/', function () {
        // Cek apakah sudah login (ini adalah pengecekan sederhana untuk contoh)
        // Dalam implementasi sebenarnya, Anda akan menggunakan middleware auth
        if (session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('index');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gallery Management
    Route::resource('galleries', AdminGalleryController::class);
    Route::patch('/galleries/{gallery}/toggle-status', [AdminGalleryController::class, 'toggleStatus'])->name('galleries.toggle-status');
    Route::get('/galleries/category/{category}', [AdminGalleryController::class, 'category'])->name('galleries.category');
    Route::delete('/galleries/comments/{comment}', [AdminGalleryController::class, 'deleteComment'])->name('galleries.comments.delete');
    
    // News Management
    Route::resource('news', AdminNewsController::class);
    Route::patch('/news/{news}/toggle-status', [AdminNewsController::class, 'toggleStatus'])->name('news.toggle-status');
    
    // Reports
    Route::get('/reports/gallery', [ReportController::class, 'gallery'])->name('reports.gallery');
    Route::get('/reports/gallery/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.gallery.export-pdf');
    
    // Notifications
    Route::get('/notifications', [AdminGalleryController::class, 'notifications'])->name('notifications.index');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // About Page Management
    Route::get('/about/edit', [AboutPageController::class, 'edit'])->name('about.edit');
    Route::put('/about/update', [AboutPageController::class, 'update'])->name('about.update');
    
    // Contact Page Management
    Route::get('/contact/edit', [ContactPageController::class, 'edit'])->name('contact.edit');
    Route::put('/contact/update', [ContactPageController::class, 'update'])->name('contact.update');
});

// Admin Logout Route (outside of group to avoid naming conflicts)
Route::post('/admin/logout', function () {
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    // Hapus session admin login
    session()->forget('admin_logged_in');
    return redirect()->route('admin.login');
})->name('admin.logout');