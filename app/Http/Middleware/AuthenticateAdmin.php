<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthenticateAdmin extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        Log::info('AuthenticateAdmin middleware started');
        
        try {
            DB::connection()->getPdo();
            Log::info('Database connection successful');
            
            if ($this->auth->guard('admin')->check()) {
                return $this->auth->shouldUse('admin');
            }

            $this->unauthenticated($request, ['admin']);
            
        } catch (\Exception $e) {
            Log::error('Database connection failed: ' . $e->getMessage());
            abort(response()->json([
                'status' => 'error',
                'message' => 'Database connection error',
                'error' => $e->getMessage()
            ], 500));
        }
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated',
        ], 401));
    }
}