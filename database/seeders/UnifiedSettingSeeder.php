<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class UnifiedSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Website Settings
            [
                'key' => 'site_name',
                'value' => 'SMK Negeri 4 Bogor',
                'type' => 'text',
                'group' => 'website',
                'label' => 'Nama Website',
                'description' => 'Nama website yang akan ditampilkan di header dan title'
            ],
            [
                'key' => 'site_description',
                'value' => 'Galeri Foto SMK Negeri 4 Bogor',
                'type' => 'text',
                'group' => 'website',
                'label' => 'Deskripsi Website',
                'description' => 'Deskripsi singkat website untuk SEO'
            ],
            
            // Upload Settings (untuk semua konten)
            [
                'key' => 'upload_max_file_size',
                'value' => '10240', // 10MB in KB
                'type' => 'number',
                'group' => 'website',
                'label' => 'Ukuran Maksimal Upload (KB)',
                'description' => 'Ukuran maksimal file yang dapat diupload untuk semua konten (1MB = 1024KB). Default: 10MB'
            ],
            [
                'key' => 'upload_allowed_extensions',
                'value' => 'jpg,jpeg,png,gif',
                'type' => 'text',
                'group' => 'website',
                'label' => 'Format File yang Diizinkan',
                'description' => 'Format file yang diizinkan untuk upload, pisahkan dengan koma'
            ],
            [
                'key' => 'upload_image_quality',
                'value' => '85',
                'type' => 'number',
                'group' => 'website',
                'label' => 'Kualitas Kompresi Gambar (%)',
                'description' => 'Kualitas gambar setelah kompresi (1-100, semakin tinggi semakin bagus)'
            ],
            
            // Display Settings
            [
                'key' => 'items_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'website',
                'label' => 'Item per Halaman',
                'description' => 'Jumlah item yang ditampilkan per halaman (galeri, agenda, informasi)'
            ],
            [
                'key' => 'enable_public_upload',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'website',
                'label' => 'Izinkan Upload Publik',
                'description' => 'Izinkan pengunjung untuk mengupload foto dari halaman publik'
            ],
            [
                'key' => 'auto_approve_content',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'website',
                'label' => 'Auto Approve Konten',
                'description' => 'Otomatis menyetujui konten yang diupload tanpa review admin'
            ],
            
            // Gallery User Settings
            [
                'key' => 'gallery_require_login',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'gallery_user',
                'label' => 'Wajib Login untuk Interaksi',
                'description' => 'User harus daftar akun dan login terlebih dahulu agar bisa menikmati fitur like, dislike, komen dan unduh foto di galeri'
            ],
            [
                'key' => 'gallery_auto_approve_comments',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'gallery_user',
                'label' => 'Auto Approve Komentar',
                'description' => 'User bebas komen tanpa harus persetujuan admin'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
        // Hapus pengaturan lama yang terpisah-pisah
        Setting::whereIn('key', [
            'gallery_max_file_size',
            'gallery_allowed_extensions',
            'gallery_image_quality',
            'agenda_max_file_size',
            'agenda_allowed_extensions',
            'agenda_items_per_page',
            'informasi_max_file_size',
            'informasi_allowed_extensions',
            'informasi_items_per_page',
            'upload_max_file_size_mb'
        ])->delete();
    }
}