<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        try {
            $contact = ContactPage::first();
            
            // If no data exists, use default values
            if (!$contact) {
                $contact = (object) [
                    'title' => 'Hubungi Kami',
                    'subtitle' => 'Jangan ragu untuk menghubungi kami jika ada pertanyaan',
                    'address' => 'Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137',
                    'phone' => '(0251) 7547381',
                    'phone_alt' => '0812-3456-7890',
                    'email' => 'info@smkn4bogor.sch.id',
                    'email_alt' => 'smkn4bogor@gmail.com',
                    'instagram_url' => '#',
                    'youtube_url' => '#',
                    'whatsapp_url' => '#',
                    'office_hours_weekday' => '07:00 - 16:00 WIB',
                    'office_hours_saturday' => '07:00 - 12:00 WIB',
                    'office_hours_sunday' => 'Tutup',
                    'note' => 'Untuk kunjungan, harap membuat janji terlebih dahulu melalui telepon atau email.'
                ];
            }
            
            return view('user.contact', compact('contact'));
        } catch (\Exception $e) {
            \Log::error('ContactController Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman. Silakan coba lagi.');
        }
    }
}
