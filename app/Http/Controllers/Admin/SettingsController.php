<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private function checkAdminLogin()
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    public function index()
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        // Get settings grouped by category
        $settingsGrouped = Setting::orderBy('group')->orderBy('label')->get()->groupBy('group');

        // Get system information
        $systemInfo = [
            'total_photos' => Gallery::count(),
            'storage_used' => $this->getStorageUsed(),
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_driver' => config('database.default')
        ];

        return view('admin.settings', compact('settingsGrouped', 'systemInfo'));
    }

    public function update(Request $request)
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        try {
            // Update all settings from request
            foreach ($request->except(['_token', '_method']) as $key => $value) {
                $setting = Setting::where('key', $key)->first();
                
                if ($setting) {
                    // Handle different types
                    if ($setting->type === 'boolean') {
                        $value = $request->has($key) ? '1' : '0';
                    }
                    
                    $setting->update(['value' => $value]);
                }
            }

            return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        $redirect = $this->checkAdminLogin();
        if ($redirect) return $redirect;
        
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ]);
        }
    }

    private function getStorageUsed()
    {
        try {
            $galleryPath = storage_path('app/public/gallery');
            if (!is_dir($galleryPath)) {
                return '0 MB';
            }

            $size = 0;
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($galleryPath)
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            // Convert bytes to human readable format
            $units = ['B', 'KB', 'MB', 'GB'];
            $unitIndex = 0;
            
            while ($size >= 1024 && $unitIndex < count($units) - 1) {
                $size /= 1024;
                $unitIndex++;
            }

            return round($size, 2) . ' ' . $units[$unitIndex];
        } catch (\Exception $e) {
            return 'Tidak dapat dihitung';
        }
    }
}