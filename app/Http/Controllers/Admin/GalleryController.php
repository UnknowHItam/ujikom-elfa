<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    // Middleware sederhana untuk memeriksa login admin
    public function __construct()
    {
        // Tambahkan pengecekan login di setiap method
    }
    
    private function checkAdminLogin()
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    public function index(Request $request)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        $query = Gallery::query();

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $galleries = $query->orderBy('created_at', 'desc')->paginate(12);
        
        $categoryNames = [
            'academic' => 'Akademik',
            'extracurricular' => 'Ekstrakurikuler',
            'event' => 'Acara & Event',
            'common' => 'Umum'
        ];

        return view('admin.galleries.index', compact('galleries', 'categoryNames'));
    }

    public function create()
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        $categories = [
            'academic' => 'Akademik',
            'extracurricular' => 'Ekstrakurikuler',
            'event' => 'Acara & Event',
            'common' => 'Umum'
        ];

        // Get settings from database
        $maxFileSizeKB = Setting::get('upload_max_file_size', 5120); // Default 5120KB (5MB)
        $maxFileSize = round($maxFileSizeKB / 1024, 2);
        $allowedFormats = Setting::get('upload_allowed_extensions', 'jpg, jpeg, png, gif');
        
        // Convert string to array if needed
        if (is_string($allowedFormats)) {
            $allowedFormats = array_map('trim', explode(',', $allowedFormats));
        }

        return view('admin.galleries.create', compact('categories', 'maxFileSize', 'allowedFormats'));
    }

    public function store(Request $request)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        // Get settings from database
        $maxFileSizeKB = Setting::get('upload_max_file_size', 5120); // Default 5120KB (5MB)
        $allowedFormats = Setting::get('upload_allowed_extensions', 'jpg, jpeg, png, gif');
        
        // Convert string to array if needed
        if (is_string($allowedFormats)) {
            $allowedFormats = array_map('trim', explode(',', $allowedFormats));
        }
        
        // Calculate MB for display
        $maxFileSize = round($maxFileSizeKB / 1024, 2);
        
        // Convert array to string for validation
        $mimesString = implode(',', $allowedFormats);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:' . $mimesString . '|max:' . $maxFileSizeKB,
            'category' => 'required|string|in:academic,extracurricular,event,common',
            'is_active' => 'nullable|boolean'
        ], [
            'image.max' => "Ukuran file gambar tidak boleh lebih dari {$maxFileSize}MB ({$maxFileSizeKB} KB).",
            'image.image' => 'File yang diupload harus berupa gambar.',
            'image.mimes' => 'Format gambar yang didukung: ' . strtoupper(implode(', ', $allowedFormats)) . '.',
            'title.required' => 'Judul foto harus diisi.',
            'title.max' => 'Judul foto tidak boleh lebih dari 255 karakter.',
            'category.required' => 'Kategori harus dipilih.',
            'category.in' => 'Kategori yang dipilih tidak valid.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = $request->file('image')->store('gallery', 'public');

        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'category' => $request->category,
            'is_active' => $request->input('is_active', 0) == 1,
        ]);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto berhasil ditambahkan!');
    }

    public function show(Gallery $gallery)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        return view('admin.galleries.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        $categories = [
            'academic' => 'Akademik',
            'extracurricular' => 'Ekstrakurikuler',
            'event' => 'Acara & Event',
            'common' => 'Umum'
        ];

        // Get settings from database
        $maxFileSizeKB = Setting::get('upload_max_file_size', 5120); // Default 5120KB (5MB)
        $maxFileSize = round($maxFileSizeKB / 1024, 2);
        $allowedFormats = Setting::get('upload_allowed_extensions', 'jpg, jpeg, png, gif');
        
        // Convert string to array if needed
        if (is_string($allowedFormats)) {
            $allowedFormats = array_map('trim', explode(',', $allowedFormats));
        }

        return view('admin.galleries.edit', compact('gallery', 'categories', 'maxFileSize', 'allowedFormats'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        // Debug: Log the request data
        \Log::info('Update request data:', $request->all());
        \Log::info('Has file: ' . ($request->hasFile('image') ? 'Yes' : 'No'));
        
        // Get settings from database
        $maxFileSizeKB = Setting::get('upload_max_file_size', 5120); // Default 5120KB (5MB)
        $allowedFormats = Setting::get('upload_allowed_extensions', 'jpg, jpeg, png, gif');
        
        // Convert string to array if needed
        if (is_string($allowedFormats)) {
            $allowedFormats = array_map('trim', explode(',', $allowedFormats));
        }
        
        // Calculate MB for display
        $maxFileSize = round($maxFileSizeKB / 1024, 2);
        
        // Convert array to string for validation
        $mimesString = implode(',', $allowedFormats);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:' . $mimesString . '|max:' . $maxFileSizeKB,
            'category' => 'required|string|in:academic,extracurricular,event,common',
            'is_active' => 'nullable|boolean'
        ], [
            'image.max' => "Ukuran file gambar tidak boleh lebih dari {$maxFileSize}MB ({$maxFileSizeKB} KB).",
            'image.image' => 'File yang diupload harus berupa gambar.',
            'image.mimes' => 'Format gambar yang didukung: ' . strtoupper(implode(', ', $allowedFormats)) . '.',
            'title.required' => 'Judul foto harus diisi.',
            'title.max' => 'Judul foto tidak boleh lebih dari 255 karakter.',
            'category.required' => 'Kategori harus dipilih.',
            'category.in' => 'Kategori yang dipilih tidak valid.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'is_active' => $request->input('is_active', 0) == 1,
        ];

        if ($request->hasFile('image')) {
            // Log the old image path
            \Log::info('Old image path: ' . $gallery->image_path);
            
            // Delete old image
            Storage::disk('public')->delete($gallery->image_path);
            
            // Store new image
            $imagePath = $request->file('image')->store('gallery', 'public');
            $data['image_path'] = $imagePath;
            
            // Log the new image path
            \Log::info('New image path: ' . $imagePath);
        }

        // Log the data being updated
        \Log::info('Updating gallery with data:', $data);
        
        $gallery->update($data);
        
        // Log the updated gallery
        \Log::info('Updated gallery:', $gallery->toArray());

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto berhasil diperbarui!');
    }

    public function destroy(Gallery $gallery)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        // Delete image file
        Storage::disk('public')->delete($gallery->image_path);

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto berhasil dihapus!');
    }

    public function category($category)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        $galleries = Gallery::where('category', $category)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $categoryNames = [
            'academic' => 'Akademik',
            'extracurricular' => 'Ekstrakurikuler',
            'event' => 'Acara & Event',
            'common' => 'Umum'
        ];

        $currentCategory = $categoryNames[$category] ?? $category;

        return view('admin.galleries.category', compact('galleries', 'categoryNames', 'currentCategory', 'category'));
    }

    public function toggleStatus(Gallery $gallery)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        $gallery->update(['is_active' => !$gallery->is_active]);
        
        $status = $gallery->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
            ->with('success', "Foto berhasil {$status}!");
    }

    public function deleteComment(\App\Models\GalleryComment $comment)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        $comment->delete();
        
        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus!');
    }

    public function notifications()
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        // Get recent activities from different models
        $likes = \App\Models\GalleryLike::with(['user:id,name,username,profile_photo', 'gallery:id,title,image_path'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($like) {
                return [
                    'type' => 'like',
                    'user' => $like->user,
                    'gallery' => $like->gallery,
                    'created_at' => $like->created_at,
                    'message' => $like->user->name . ' menyukai foto',
                    'icon' => 'thumbs-up',
                    'color' => 'success'
                ];
            });

        $comments = \App\Models\GalleryComment::with(['user:id,name,username,profile_photo', 'gallery:id,title,image_path'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($comment) {
                return [
                    'type' => 'comment',
                    'user' => $comment->user,
                    'gallery' => $comment->gallery,
                    'created_at' => $comment->created_at,
                    'message' => $comment->user->name . ' mengomentari foto',
                    'comment' => $comment->comment,
                    'icon' => 'comment',
                    'color' => 'primary'
                ];
            });

        // Merge all activities and sort by created_at
        $activities = collect()
            ->merge($likes)
            ->merge($comments)
            ->sortByDesc('created_at')
            ->take(50);

        return view('admin.notifications.index', compact('activities'));
    }
}