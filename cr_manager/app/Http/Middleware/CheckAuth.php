<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('authenticated')) {
            return redirect()->route('login')->withErrors(['error' => 'Please login to access this page.']);
        }

        return $next($request);
    }
} 