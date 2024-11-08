<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins',
                'password' => 'required|string|min:6',
                'role' => 'required|string|in:admin'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $admin = Admin::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role
            ]);

            // Generate token for the new admin
            $token = auth('admin')->login($admin);

            return response()->json([
                'status' => 'success',
                'message' => 'Admin registered successfully',
                'data' => [
                    'admin' => [
                        'id' => $admin->id,
                        'email' => $admin->email,
                        'first_name' => $admin->first_name,
                        'last_name' => $admin->last_name,
                        'role' => $admin->role,
                    ],
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('admin')->factory()->getTTL() * 60
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register admin',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');
            
            // Log the attempt
            Log::info('Login attempt for email: ' . $credentials['email']);

            // Check if admin exists
            $admin = Admin::where('email', $credentials['email'])->first();
            if (!$admin) {
                Log::warning('Admin not found with email: ' . $credentials['email']);
                return $this->error('Invalid credentials - User not found', 401);
            }

            // Attempt authentication
            if (!$token = auth('admin')->attempt($credentials)) {
                Log::warning('Failed login attempt for: ' . $credentials['email']);
                return $this->error('Invalid credentials - Password incorrect', 401);
            }

            $admin = auth('admin')->user();
            Log::info('Successful login for admin: ' . $admin->email);

            auth('admin')->factory()->setTTL(24 * 60);

            return $this->success([
                'admin' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                    'first_name' => $admin->first_name,
                    'last_name' => $admin->last_name,
                    'role' => $admin->role,
                ],
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('admin')->factory()->getTTL() * 60
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->error($e->getMessage(), 500);
        }
    }

    public function logout()
    {
        try {
            auth('admin')->logout();
            return $this->success(null, 'Successfully logged out');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function me()
    {
        try {
            return $this->success(auth('admin')->user());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function refresh()
    {
        // Set token TTL to 1 day (24 hours * 60 minutes)
        auth('admin')->factory()->setTTL(24 * 60);

        return $this->success([
            'token' => auth('admin')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
} 