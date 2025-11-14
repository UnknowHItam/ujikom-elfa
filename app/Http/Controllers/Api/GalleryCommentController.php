<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryComment;
use App\Models\Setting;
use Illuminate\Http\Request;

class GalleryCommentController extends Controller
{
    public function index(Gallery $gallery)
    {
        $comments = $gallery->comments()
                           ->with('user:id,name,profile_photo')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    public function store(Request $request, Gallery $gallery)
    {
        // Cek pengaturan apakah login diperlukan
        $requireLogin = Setting::get('gallery_require_login', true);
        
        // Jika login diperlukan, cek apakah user sudah login
        if ($requireLogin && !auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk menambahkan komentar'
            ], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $commentData = [
            'gallery_id' => $gallery->id,
            'comment' => $request->comment,
            'ip_address' => $request->ip()
        ];
        
        // Tambahkan user_id jika user login
        if (auth()->check()) {
            $commentData['user_id'] = auth()->id();
        }

        $comment = GalleryComment::create($commentData);

        $comment->load('user:id,name,profile_photo');

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $comment
        ]);
    }

    public function update(Request $request, Gallery $gallery, GalleryComment $comment)
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk mengedit komentar'
            ], 401);
        }

        // Cek apakah user adalah pemilik komentar
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit komentar ini'
            ], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $comment->update([
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil diupdate',
            'data' => $comment
        ]);
    }

    public function destroy(Gallery $gallery, GalleryComment $comment)
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk menghapus komentar'
            ], 401);
        }

        // Cek apakah user adalah pemilik komentar
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus'
        ]);
    }
}