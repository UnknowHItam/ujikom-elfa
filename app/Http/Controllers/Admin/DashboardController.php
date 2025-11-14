<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Agenda;
use App\Models\Informasi;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah admin sudah login
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        try {
            // Initialize variables with default values
            $totalPhotos = 0;
            $activePhotos = 0;
            $inactivePhotos = 0;
            $photosByCategory = collect();
            $recentPhotos = collect();
            $thisMonthPhotos = 0;
            $thisWeekPhotos = 0;
            $todayPhotos = 0;
            $totalAgenda = 0;
            $activeAgenda = 0;
            $totalInformasi = 0;
            $activeInformasi = 0;
            
            // Category names mapping
            $categoryNames = [
                'academic' => 'Akademik',
                'extracurricular' => 'Ekstrakurikuler',
                'event' => 'Acara & Event',
                'common' => 'Umum'
            ];
            
            // Category colors for chart
            $categoryColors = [
                'academic' => '#4e73df',
                'extracurricular' => '#1cc88a',
                'event' => '#36b9cc',
                'common' => '#f6c23e'
            ];
            
            // Check if galleries table exists
            if (Schema::hasTable('galleries')) {
                $totalPhotos = Gallery::count();
                $activePhotos = Gallery::where('is_active', true)->count();
                $inactivePhotos = Gallery::where('is_active', false)->count();
                
                // Get photos by category with proper structure for chart
                $categoryData = Gallery::select('category', DB::raw('count(*) as count'))
                    ->groupBy('category')
                    ->get();
                
                // Transform data for chart
                $photosByCategory = $categoryData->map(function($item) use ($categoryNames, $categoryColors) {
                    return [
                        'name' => $categoryNames[$item->category] ?? ucfirst($item->category),
                        'count' => $item->count,
                        'color' => $categoryColors[$item->category] ?? '#cccccc'
                    ];
                });
                
                // Get recent photos
                $recentPhotos = Gallery::orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                // Get photos added this month
                $thisMonthPhotos = Gallery::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                
                // Get photos added this week
                $thisWeekPhotos = Gallery::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count();
                
                // Get photos added today
                $todayPhotos = Gallery::whereDate('created_at', today())->count();
            }

            // Check if agendas table exists
            if (Schema::hasTable('agendas')) {
                $totalAgenda = Agenda::count();
                $activeAgenda = Agenda::where('is_active', true)->count();
            }

            // Check if informasis table exists
            if (Schema::hasTable('informasis')) {
                $totalInformasi = Informasi::count();
                $activeInformasi = Informasi::where('is_active', true)->count();
            }
            
            return view('admin.dashboard', compact(
                'totalPhotos',
                'activePhotos',
                'inactivePhotos',
                'photosByCategory',
                'recentPhotos',
                'thisMonthPhotos',
                'thisWeekPhotos',
                'todayPhotos',
                'totalAgenda',
                'activeAgenda',
                'totalInformasi',
                'activeInformasi',
                'categoryNames'
            ));
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('DashboardController error: ' . $e->getMessage());
            
            // Return a simple view with error message
            // Jika terjadi error, tampilkan halaman error atau redirect ke halaman dashboard dengan pesan error
            return redirect()->route('admin.dashboard')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}