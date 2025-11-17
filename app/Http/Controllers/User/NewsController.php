<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of news
     */
    public function index(Request $request)
    {
        $query = News::published()
            ->select('id', 'title', 'description', 'category', 'published_at', 'created_at', 'image_url');
        
        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $news = $query->paginate(9);
        
        $categories = [
            'umum' => 'Umum',
            'prestasi' => 'Prestasi',
            'kegiatan' => 'Kegiatan Sekolah',
            'pengumuman' => 'Pengumuman'
        ];
        
        return view('user.news.index', compact('news', 'categories'));
    }

    /**
     * Display the specified news
     */
    public function show($id)
    {
        $news = News::where('is_active', true)->findOrFail($id);
        
        // Get related news (same category, exclude current) - select specific columns
        $relatedNews = News::published()
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->select('id', 'title', 'image_url', 'category', 'published_at')
            ->limit(3)
            ->get();
        
        // Get latest news - select specific columns
        $latestNews = News::published()
            ->where('id', '!=', $news->id)
            ->select('id', 'title', 'image_url', 'category', 'published_at')
            ->limit(5)
            ->get();
        
        return view('user.news.show', compact('news', 'relatedNews', 'latestNews'));
    }
    
    /**
     * Display news by category
     */
    public function category($category)
    {
        $news = News::published()
            ->where('category', $category)
            ->select('id', 'title', 'description', 'category', 'published_at', 'image_url')
            ->paginate(9);
        
        $categories = [
            'umum' => 'Umum',
            'prestasi' => 'Prestasi',
            'kegiatan' => 'Kegiatan Sekolah',
            'pengumuman' => 'Pengumuman'
        ];
        
        $categoryName = $categories[$category] ?? ucfirst($category);
        
        return view('user.news.category', compact('news', 'categories', 'category', 'categoryName'));
    }
}
