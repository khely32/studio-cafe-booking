<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Please log in to access your dashboard.');
        }

        if (Auth::user()->role !== 'client') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Invalid access. Please use the correct login page.');
        }

        return $next($request);
    }
}
