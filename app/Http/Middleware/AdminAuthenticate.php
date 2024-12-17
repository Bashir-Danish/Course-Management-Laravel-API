<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        if (!Auth::guard('admin')->check()) {
            return route('login');
        }

        return $request;
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
} 