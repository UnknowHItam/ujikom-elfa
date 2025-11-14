<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryDownloadController extends Controller
{
    public function download(Request $request, Gallery $gallery)
    {
        // Cek pengaturan apakah login diperlukan
        $requireLogin = Setting::get('gallery_require_login', true);
        
        // Jika login diperlukan, cek apakah user sudah login
        if ($requireLogin && !auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk mendownload foto'
            ], 401);
        }

        // Get file path
        $imagePath = $gallery->image_path;
        
        // Cek apakah file adalah URL eksternal
        if (str_starts_with($imagePath, 'http')) {
            return response()->json([
                'success' => false,
                'message' => 'File eksternal tidak dapat didownload'
            ], 400);
        }

        // Path ke file di storage
        $filePath = storage_path('app/public/' . $imagePath);
        
        // Cek apakah file ada
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 404);
        }

        // Generate nama file untuk download
        $fileName = $gallery->title . '_' . time() . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
        
        // Log download activity jika user login
        if (auth()->check()) {
            \Log::info('User ' . auth()->user()->name . ' downloaded gallery image: ' . $gallery->title);
        }

        // Return file download
        return response()->download($filePath, $fileName);
    }

    public function generateCaptcha(Request $request)
    {
        // Generate random captcha text (6 karakter)
        $captcha = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        
        // Simpan ke session
        $request->session()->put('captcha', $captcha);
        
        // Cek apakah GD library tersedia
        if (!function_exists('imagecreatetruecolor')) {
            // Fallback: Return captcha as SVG
            $svg = $this->generateSvgCaptcha($captcha);
            return response()->json([
                'success' => true,
                'captcha' => 'data:image/svg+xml;base64,' . base64_encode($svg)
            ]);
        }
        
        // Buat image captcha dengan GD
        $width = 150;
        $height = 50;
        $image = imagecreatetruecolor($width, $height);
        
        // Warna background
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);
        
        // Tambah noise lines
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }
        
        // Tambah noise dots
        for ($i = 0; $i < 100; $i++) {
            $dotColor = imagecolorallocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $dotColor);
        }
        
        // Tulis text captcha menggunakan built-in font
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $x = 25;
        $y = 15;
        
        for ($i = 0; $i < strlen($captcha); $i++) {
            imagestring($image, 5, $x, $y, $captcha[$i], $textColor);
            $x += 20;
        }
        
        // Output image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        // Return as base64
        return response()->json([
            'success' => true,
            'captcha' => 'data:image/png;base64,' . base64_encode($imageData)
        ]);
    }

    private function generateSvgCaptcha($text)
    {
        $width = 200;
        $height = 60;
        
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="100%" height="100%" fill="#f0f0f0"/>';
        
        // Add noise lines
        for ($i = 0; $i < 5; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $x2 = rand(0, $width);
            $y2 = rand(0, $height);
            $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="#ddd" stroke-width="1"/>';
        }
        
        // Add text with random positions and rotations
        $x = 20;
        for ($i = 0; $i < strlen($text); $i++) {
            $y = rand(35, 45);
            $rotation = rand(-15, 15);
            $color = 'rgb(' . rand(0, 100) . ',' . rand(0, 100) . ',' . rand(0, 100) . ')';
            $svg .= '<text x="' . $x . '" y="' . $y . '" font-size="24" font-weight="bold" fill="' . $color . '" transform="rotate(' . $rotation . ' ' . $x . ' ' . $y . ')">' . $text[$i] . '</text>';
            $x += 28;
        }
        
        $svg .= '</svg>';
        return $svg;
    }
}