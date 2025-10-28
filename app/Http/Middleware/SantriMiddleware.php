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
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah user memiliki role calon_santri
        if (Auth::user()->role !== 'calon_santri') {
            abort(403, 'Unauthorized access for santri area.');
        }

        return $next($request);
    }
}
