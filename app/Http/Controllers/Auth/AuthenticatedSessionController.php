<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{ 
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            $admin = Admin::where('email', $credentials['email'])->first();

            if (!$admin) {
                Log::warning('Login attempt failed - Admin not found: ' . $credentials['email']);
                throw ValidationException::withMessages([
                    'email' => ['These credentials do not match our records.'],
                ]);
            }

            Log::debug('Login attempt details:', [
                'email' => $credentials['email'],
                'submitted_password_length' => strlen($credentials['password']),
                'stored_password_hash_length' => strlen($admin->password),
                'stored_hash_starts_with' => substr($admin->password, 0, 7)
            ]);

            if (!Hash::check($credentials['password'], $admin->password)) {
                Log::warning('Login attempt failed - Invalid password for: ' . $credentials['email']);
                throw ValidationException::withMessages([
                    'email' => ['These credentials do not match our records.'],
                ]);
            }

            Auth::guard('admin')->login($admin, $request->boolean('remember'));
            $request->session()->regenerate();

            Log::info('Admin logged in successfully: ' . $admin->email);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => route('dashboard')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
} 