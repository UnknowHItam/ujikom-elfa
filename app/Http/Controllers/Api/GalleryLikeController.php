<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryLike;
use App\Models\Setting;
use Illuminate\Http\Request;

class GalleryLikeController extends Controller
{
    public function toggle(Request $request, Gallery $gallery)
    {
        // Cek pengaturan apakah login diperlukan
        $requireLogin = Setting::get('gallery_require_login', true);
        
        // Jika login diperlukan, cek apakah user sudah login
        if ($requireLogin && !auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk memberikan like/dislike'
            ], 401);
        }
        
        // Jika login tidak diperlukan tapi user tidak login, tolak
        if ($requireLogin && !auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk memberikan like/dislike'
            ], 401);
        }

        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $userId = auth()->check() ? auth()->id() : null;
        $ipAddress = $request->ip();
        
        // Cek apakah user sudah like/dislike
        $query = GalleryLike::where('gallery_id', $gallery->id);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('ip_address', $ipAddress);
        }
        
        $existingLike = $query->first();

        if ($existingLike) {
            // Jika type sama, hapus (toggle off)
            if ($existingLike->type === $request->type) {
                $existingLike->delete();
                $action = 'removed';
            } else {
                // Jika type beda, update
                $existingLike->update(['type' => $request->type]);
                $action = 'updated';
            }
        } else {
            // Buat baru
            GalleryLike::create([
                'gallery_id' => $gallery->id,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'type' => $request->type
            ]);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'likes_count' => $gallery->likes()->where('type', 'like')->count(),
            'dislikes_count' => $gallery->likes()->where('type', 'dislike')->count()
        ]);
    }

    public function getStatus(Request $request, Gallery $gallery)
    {
        $userLike = null;
        
        // Cek status like hanya jika user sudah login
        if (auth()->check()) {
            $userLike = GalleryLike::where('gallery_id', $gallery->id)
                                   ->where('user_id', auth()->id())
                                   ->first();
        }
        
        // Jika tidak login tapi pengaturan tidak memerlukan login, cek berdasarkan IP
        $requireLogin = Setting::get('gallery_require_login', true);
        if (!$requireLogin && !auth()->check()) {
            $ipAddress = $request->ip();
            $userLike = GalleryLike::where('gallery_id', $gallery->id)
                                   ->where('ip_address', $ipAddress)
                                   ->first();
        }

        return response()->json([
            'success' => true,
            'user_type' => $userLike ? $userLike->type : null,
            'likes_count' => $gallery->likes()->where('type', 'like')->count(),
            'dislikes_count' => $gallery->likes()->where('type', 'dislike')->count(),
            'is_authenticated' => auth()->check()
        ]);
    }
}