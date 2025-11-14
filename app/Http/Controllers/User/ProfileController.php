<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan oleh user lain',
        ]);

        // Update basic profile
        $user->name = $validated['name'];
        $user->username = $validated['username'];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            
            Log::info('Processing profile photo upload for user ' . $user->id);
            
            // Pastikan folder exists
            $profilePath = public_path('images/profiles');
            if (!file_exists($profilePath)) {
                mkdir($profilePath, 0755, true);
                Log::info('Created profiles directory');
            }
            
            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                @unlink(public_path($user->profile_photo));
                Log::info('Deleted old photo: ' . $user->profile_photo);
            }
            
            // Generate unique filename
            $photoName = 'profile_' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
            
            // Move file
            $photo->move($profilePath, $photoName);
            Log::info('Moved photo to: ' . $profilePath . '/' . $photoName);
            
            // Save path to database
            $photoPath = 'images/profiles/' . $photoName;
            $user->profile_photo = $photoPath;
            Log::info('Set profile_photo attribute to: ' . $photoPath);
        }

        // Update password if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                    ->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        // Save user data
        $saved = $user->save();
        
        Log::info('User save result: ' . ($saved ? 'success' : 'failed'));
        Log::info('User data after save: ' . json_encode($user->toArray()));

        return redirect()->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
