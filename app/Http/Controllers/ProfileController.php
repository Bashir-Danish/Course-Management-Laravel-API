<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController
{
    public function getProfile()
    {
        $admin = Auth::guard('admin')->user();
        return response()->json([
            'email' => $admin->email,
            'profile_image' => $admin->profile_image === 'default-profile.png' ? 
                'https://via.placeholder.com/100' : 
                asset('storage/' . $admin->profile_image)
        ]);
    }

    public function updateProfile(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            $validated = $request->validate([
                'email' => 'sometimes|email|unique:admins,email,' . $admin->id,
                'password' => 'sometimes|min:6',
                'profile_image' => 'sometimes|image|max:2048'
            ]);

            if ($request->hasFile('profile_image')) {
                if ($admin->profile_image && $admin->profile_image !== 'default-profile.png') {
                    Storage::delete('public/' . $admin->profile_image);
                }
                
                $path = $request->file('profile_image')->store('profile-images', 'public');
                $admin->profile_image = $path;
            }

            if ($request->filled('email')) {
                $admin->email = $request->email;
            }

            if ($request->filled('password')) {
                $admin->password = bcrypt($request->password);
            }

            $admin->save();

            return response()->json([
                'success' => true,
                'profile_image' => $admin->profile_image === 'default-profile.png' ? 
                    'https://via.placeholder.com/100' : 
                    asset('storage/' . $admin->profile_image),
                'message' => 'Profile updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
} 