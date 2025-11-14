<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.user-register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Validasi input
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ];

        // Hanya validasi CAPTCHA jika keys sudah diset dengan benar
        if (config('captcha.sitekey') && config('captcha.sitekey') !== 'your_recaptcha_site_key') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot',
            'g-recaptcha-response.captcha' => 'Verifikasi captcha gagal, silakan coba lagi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'is_admin' => false,
            ]);

            return redirect()->route('user.login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.')
                ->withInput();
        }
    }
}
