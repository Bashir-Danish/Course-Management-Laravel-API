<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('login');
        }

        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized. Insufficient permissions.'
                ], 403);
            }
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
} 