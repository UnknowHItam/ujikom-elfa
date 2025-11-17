<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\News;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Inisialisasi data default
            $data = [
                'totalGalleries' => 0,
                'totalNews' => 0,
                'unreadMessages' => 0,
                'recentActivity' => 0,
                'recentGalleries' => collect(),
                'galleryCategories' => [
                    ['name' => 'Akademik', 'count' => 0, 'color' => '#4e73df', 'key' => 'academic'],
                    ['name' => 'Ekstrakurikuler', 'count' => 0, 'color' => '#1cc88a', 'key' => 'extracurricular'],
                    ['name' => 'Acara & Event', 'count' => 0, 'color' => '#36b9cc', 'key' => 'event'],
                    ['name' => 'Umum', 'count' => 0, 'color' => '#f6c23e', 'key' => 'general']
                ],
                'latestNews' => collect()
            ];

            // Ambil data dari database dengan optimasi
            try {
                // OPTIMIZATION: Gunakan single query dengan aggregation untuk counts
                // Sebelumnya: 9 queries, Sekarang: 3 queries
                
                // Query 1: Total dan Recent Activity galleries
                $galleryCounts = Gallery::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as recent
                ', [now()->subDays(7)])
                    ->where('is_active', true)
                    ->first();
                
                $data['totalGalleries'] = $galleryCounts->total ?? 0;
                $data['recentActivity'] = $galleryCounts->recent ?? 0;
                
                // Query 2: Category counts menggunakan GROUP BY (single query instead of 4)
                $categoryCounts = Gallery::selectRaw('category, COUNT(*) as count')
                    ->where('is_active', true)
                    ->groupBy('category')
                    ->pluck('count', 'category');
                
                foreach ($data['galleryCategories'] as $index => $category) {
                    $key = $category['key'];
                    $data['galleryCategories'][$index]['count'] = $categoryCounts->get($key) ?? 0;
                }
                
                // Query 3: Total News (bisa dicache)
                $data['totalNews'] = Cache::remember('total_news', 3600, function() {
                    return News::where('is_active', true)->count();
                });
                
                // Query 4: Recent Galleries (dengan select specific columns untuk reduce memory)
                $data['recentGalleries'] = Gallery::where('is_active', true)
                    ->select('id', 'title', 'description', 'image_path', 'category', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(6)
                    ->get();
                
                // Query 5: Latest News (dengan select specific columns)
                $data['latestNews'] = News::where('is_active', true)
                    ->select('id', 'title', 'description', 'category', 'published_at', 'created_at')
                    ->orderBy('published_at', 'desc')
                    ->limit(5)
                    ->get();
                
                Log::info('Data dashboard berhasil diambil dengan optimasi');
                    
            } catch (\Exception $e) {
                Log::error('Gagal mengambil data dashboard: ' . $e->getMessage());
                // Tetap lanjut dengan data default jika terjadi error
            }
            
            return view('user.dashboard', $data);
            
        } catch (\Exception $e) {
            Log::error('Error pada DashboardController@index: ' . $e->getMessage());
            // Redirect ke halaman error atau tampilkan pesan error yang ramah
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi nanti.');
        }
    }
}
