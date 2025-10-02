<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SantriMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan memiliki role santri
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'santri') {
            abort(403, 'Unauthorized access for santri area.');
        }

        return $next($request);
    }
}
