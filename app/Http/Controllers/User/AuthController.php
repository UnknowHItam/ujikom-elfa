<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Email atau username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // Try to login with email or username
        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $user = User::where($loginField, $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Email atau username tidak ditemukan.',
            ])->withInput($request->only('username'));
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->withInput($request->only('username'));
        }

        // Check if user is not admin
        if ($user->is_admin) {
            return back()->withErrors([
                'username' => 'Akun ini adalah akun admin. Silakan gunakan halaman login admin.',
            ])->withInput($request->only('username'));
        }

        // Login the user
        Auth::login($user, $request->filled('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard'))
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
