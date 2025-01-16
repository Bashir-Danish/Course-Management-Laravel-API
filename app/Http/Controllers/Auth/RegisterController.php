<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8',
        ]);

        try {
            $admin = Admin::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'employee'
            ]);

            Auth::guard('admin')->login($admin);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 422);
            }

            return back()->withInput()->withErrors([
                'email' => 'Registration failed. Please try again.'
            ]);
        }
    }
} 