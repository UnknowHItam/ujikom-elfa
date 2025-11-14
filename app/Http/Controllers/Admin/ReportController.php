<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryLike;
use App\Models\GalleryComment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function gallery(Request $request)
    {
        // Filter tanggal - default dari awal tahun ini untuk menampilkan data yang lebih relevan
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Tambahkan waktu akhir hari ke endDate agar semua foto di hari tersebut termasuk
        $endDateWithTime = $endDate . ' 23:59:59';
        
        // Get basic statistics (filtered by date)
        $totalPhotos = Gallery::whereBetween('created_at', [$startDate, $endDateWithTime])->count();
        $activePhotos = Gallery::where('is_active', true)
                              ->whereBetween('created_at', [$startDate, $endDateWithTime])
                              ->count();
        $inactivePhotos = Gallery::where('is_active', false)
                                ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                ->count();
        $totalCategories = 4;
        
        // Photos in date range (sama dengan totalPhotos sekarang)
        $photosInRange = $totalPhotos;
        
        // Get gallery IDs in date range untuk filter likes & comments
        $galleryIds = Gallery::whereBetween('created_at', [$startDate, $endDateWithTime])->pluck('id');
        
        // Engagement statistics (filtered by gallery date)
        $totalLikes = GalleryLike::whereIn('gallery_id', $galleryIds)
                                 ->where('type', 'like')
                                 ->count();
        $totalDislikes = GalleryLike::whereIn('gallery_id', $galleryIds)
                                    ->where('type', 'dislike')
                                    ->count();
        $totalComments = GalleryComment::whereIn('gallery_id', $galleryIds)->count();
        $approvedComments = $totalComments; // All comments are auto-approved now
        $pendingComments = 0; // No pending comments anymore

        // Category statistics (filtered by date)
        $categoryStats = [
            'academic' => [
                'name' => 'Akademik',
                'count' => Gallery::where('category', 'academic')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'academic')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ],
            'extracurricular' => [
                'name' => 'Ekstrakurikuler',
                'count' => Gallery::where('category', 'extracurricular')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'extracurricular')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ],
            'event' => [
                'name' => 'Acara & Event',
                'count' => Gallery::where('category', 'event')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'event')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ],
            'common' => [
                'name' => 'Umum',
                'count' => Gallery::where('category', 'common')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'common')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ]
        ];

        // Chart data for category distribution
        $categoryChartData = [
            'labels' => array_values(array_column($categoryStats, 'name')),
            'data' => array_values(array_column($categoryStats, 'count'))
        ];

        // Monthly upload data (dalam range tanggal yang dipilih)
        $monthlyChartData = [
            'labels' => [],
            'data' => []
        ];

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $diffInMonths = $start->diffInMonths($end);
        
        // Jika range lebih dari 12 bulan, tampilkan per bulan dalam range
        // Jika kurang, tampilkan semua bulan dalam range
        $monthsToShow = min($diffInMonths + 1, 12);
        
        for ($i = 0; $i < $monthsToShow; $i++) {
            $date = $start->copy()->addMonths($i);
            if ($date->lte($end)) {
                $monthlyChartData['labels'][] = $date->format('M Y');
                $monthlyChartData['data'][] = Gallery::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        }

        // Top 10 most liked photos (filtered by date)
        $topLikedPhotos = Gallery::withCount(['likes' => function($q) {
            $q->where('type', 'like');
        }])
        ->whereBetween('created_at', [$startDate, $endDateWithTime])
        ->orderBy('likes_count', 'desc')
        ->limit(10)
        ->get();

        // Top 10 most commented photos (filtered by date)
        $topCommentedPhotos = Gallery::withCount('comments')
        ->whereBetween('created_at', [$startDate, $endDateWithTime])
        ->orderBy('comments_count', 'desc')
        ->limit(10)
        ->get();

        // Recent photos (filtered by date)
        $recentPhotos = Gallery::whereBetween('created_at', [$startDate, $endDateWithTime])
                              ->orderBy('created_at', 'desc')
                              ->limit(10)
                              ->get();

        return view('admin.reports.gallery', compact(
            'totalPhotos',
            'activePhotos',
            'inactivePhotos',
            'totalCategories',
            'photosInRange',
            'totalLikes',
            'totalDislikes',
            'totalComments',
            'approvedComments',
            'pendingComments',
            'categoryStats',
            'categoryChartData',
            'monthlyChartData',
            'topLikedPhotos',
            'topCommentedPhotos',
            'recentPhotos',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        // Filter tanggal - default dari awal tahun ini untuk menampilkan data yang lebih relevan
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Tambahkan waktu akhir hari ke endDate
        $endDateWithTime = $endDate . ' 23:59:59';
        
        // Get all data (sama seperti method gallery)
        $totalPhotos = Gallery::whereBetween('created_at', [$startDate, $endDateWithTime])->count();
        $activePhotos = Gallery::where('is_active', true)
                              ->whereBetween('created_at', [$startDate, $endDateWithTime])
                              ->count();
        $inactivePhotos = Gallery::where('is_active', false)
                                ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                ->count();
        
        $galleryIds = Gallery::whereBetween('created_at', [$startDate, $endDateWithTime])->pluck('id');
        
        $totalLikes = GalleryLike::whereIn('gallery_id', $galleryIds)
                                 ->where('type', 'like')
                                 ->count();
        $totalDislikes = GalleryLike::whereIn('gallery_id', $galleryIds)
                                    ->where('type', 'dislike')
                                    ->count();
        $totalComments = GalleryComment::whereIn('gallery_id', $galleryIds)->count();
        $approvedComments = $totalComments; // All comments are auto-approved now
        $pendingComments = 0; // No pending comments anymore

        $categoryStats = [
            'academic' => [
                'name' => 'Akademik',
                'count' => Gallery::where('category', 'academic')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'academic')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ],
            'extracurricular' => [
                'name' => 'Ekstrakurikuler',
                'count' => Gallery::where('category', 'extracurricular')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'extracurricular')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ],
            'event' => [
                'name' => 'Acara & Event',
                'count' => Gallery::where('category', 'event')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'event')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ],
            'common' => [
                'name' => 'Umum',
                'count' => Gallery::where('category', 'common')
                                 ->whereBetween('created_at', [$startDate, $endDateWithTime])
                                 ->count(),
                'likes' => GalleryLike::whereHas('gallery', function($q) use ($startDate, $endDateWithTime) {
                    $q->where('category', 'common')
                      ->whereBetween('created_at', [$startDate, $endDateWithTime]);
                })->where('type', 'like')->count()
            ]
        ];

        $topLikedPhotos = Gallery::withCount(['likes' => function($q) {
            $q->where('type', 'like');
        }])
        ->whereBetween('created_at', [$startDate, $endDateWithTime])
        ->orderBy('likes_count', 'desc')
        ->limit(10)
        ->get();

        $topCommentedPhotos = Gallery::withCount('comments')
        ->whereBetween('created_at', [$startDate, $endDateWithTime])
        ->orderBy('comments_count', 'desc')
        ->limit(10)
        ->get();

        $data = compact(
            'totalPhotos',
            'activePhotos',
            'inactivePhotos',
            'totalLikes',
            'totalDislikes',
            'totalComments',
            'approvedComments',
            'pendingComments',
            'categoryStats',
            'topLikedPhotos',
            'topCommentedPhotos',
            'startDate',
            'endDate'
        );

        $pdf = Pdf::loadView('admin.reports.gallery-pdf', $data);
        
        $filename = 'Laporan-Galeri-' . Carbon::parse($startDate)->format('d-M-Y') . '-sd-' . Carbon::parse($endDate)->format('d-M-Y') . '.pdf';
        
        return $pdf->download($filename);
    }
}