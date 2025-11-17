<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        try {
            $about = AboutPage::first();
            
            // If no data exists, use default values
            if (!$about) {
                $about = (object) [
                    'title' => 'Profil SMK Negeri 4 Bogor',
                    'subtitle' => 'Mengukir Prestasi, Menciptakan Generasi Berkompeten',
                    'image_url' => '/images/smk4-bogor.jpg',
                    'history_title' => 'Sejarah Berdiri',
                    'history_content' => 'SMK Negeri 4 Bogor resmi didirikan pada tanggal 15 Juni 2009 berdasarkan Surat Keputusan Wali Kota Bogor Nomor 421.3/224-Disdik/2009. Sekolah ini dibangun di atas lahan seluas 2,5 hektar di kawasan Tajur, Bogor Selatan. Awalnya, sekolah ini hanya memiliki 3 program keahlian, namun seiring perkembangannya, kini telah memiliki 4 kompetensi keahlian yang lebih beragam dan sesuai dengan kebutuhan industri.',
                    'development_title' => 'Perkembangan dan Prestasi',
                    'development_content' => 'SMK Negeri 4 Bogor telah mengalami perkembangan yang signifikan sejak berdiri. Berbagai prestasi telah diraih baik di tingkat regional maupun nasional, termasuk menjadi juara Lomba Kompetensi Siswa (LKS) tingkat kota dan provinsi. Sekolah ini juga telah menjalin kerjasama dengan berbagai perusahaan dan industri ternama untuk program Praktik Kerja Industri (Prakerin) dan penyaluran tenaga kerja. Pada tahun 2023, SMKN 4 Bogor meraih predikat Sekolah Adiwiyata tingkat nasional berkat komitmennya dalam pengelolaan lingkungan hidup.',
                    'vision_title' => 'Visi Sekolah',
                    'vision_content' => 'Menjadi SMK unggul berstandar nasional yang menghasilkan lulusan berkarakter, berdaya saing global, dan berwawasan lingkungan pada tahun 2025.',
                    'mission_title' => 'Misi Sekolah',
                    'mission_items' => [
                        'Menyelenggarakan pendidikan kejuruan yang berkualitas dan berkarakter sesuai dengan tuntutan dunia kerja',
                        'Mengembangkan kurikulum berbasis kompetensi yang adaptif terhadap perkembangan teknologi dan kebutuhan industri',
                        'Meningkatkan kualitas pendidik dan tenaga kependidikan melalui pengembangan profesionalisme berkelanjutan',
                        'Memperluas jejaring kerjasama dengan dunia usaha dan industri (DUDI) dalam rangka peningkatan mutu lulusan',
                        'Menerapkan manajemen berbasis sekolah yang transparan, akuntabel, dan berorientasi pada mutu',
                        'Mengembangkan budaya sekolah yang berwawasan lingkungan dan berlandaskan nilai-nilai karakter bangsa',
                        'Menyediakan sarana prasarana pendidikan yang memadai dan berwawasan lingkungan'
                    ],
                    'profile_image_url' => '/images/smk4-bogor-profile.jpg',
                    'address' => 'Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara Sari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137',
                    'phone' => '(0251) 7547381',
                    'email' => 'smkn4@smkn4bogor.sch.id',
                    'website' => 'www.smkn4bogor.sch.id',
                    'competencies' => [
                        ['icon' => 'fas fa-code text-primary', 'name' => 'Pengembangan Perangkat Lunak dan Gim'],
                        ['icon' => 'fas fa-network-wired text-success', 'name' => 'Teknik Komputer dan Jaringan'],
                        ['icon' => 'fas fa-fire text-danger', 'name' => 'Teknik Pengelasan'],
                        ['icon' => 'fas fa-car text-warning', 'name' => 'Teknik Kendaraan Ringan Otomotif']
                    ]
                ];
            }
            
            return view('user.about', compact('about'));
        } catch (\Exception $e) {
            \Log::error('AboutController Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman. Silakan coba lagi.');
        }
    }
}
