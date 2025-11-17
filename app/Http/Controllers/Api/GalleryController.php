<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Gallery::where('is_active', true);

            // Filter by category
            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category', $request->category);
            }

            // Search by title
            if ($request->has('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            $galleries = $query->orderBy('created_at', 'desc')->paginate(12);

            return response()->json([
                'success' => true,
                'data' => $galleries->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Gallery Index Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading galleries',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|in:academic,extracurricular,event,general'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = $request->file('image')->store('gallery', 'public');

        $gallery = Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'category' => $request->category,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gallery created successfully',
            'data' => $gallery
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $gallery = Gallery::find($id);

            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gallery not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $gallery->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Gallery Show Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading gallery',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|in:academic,extracurricular,event,general'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($gallery->image_path);
            
            // Store new image
            $imagePath = $request->file('image')->store('gallery', 'public');
            $data['image_path'] = $imagePath;
        }

        $gallery->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Gallery updated successfully',
            'data' => $gallery
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found'
            ], 404);
        }

        // Delete image file
        Storage::disk('public')->delete($gallery->image_path);

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gallery deleted successfully'
        ]);
    }

    /**
     * Get categories
     */
    public function categories()
    {
        try {
            $categories = [
                'academic' => 'Akademik',
                'extracurricular' => 'Ekstrakurikuler',
                'event' => 'Acara & Event',
                'general' => 'Umum'
            ];

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            \Log::error('Gallery Categories Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading categories',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
}
