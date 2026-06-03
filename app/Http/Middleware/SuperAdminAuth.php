<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuth
{
    /**
     * Handle an incoming request.
     * Jika belum login → redirect ke halaman akses (popup login diperlukan).
     * Jika sudah login tapi bukan superadmin → redirect ke halaman akses juga.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login sama sekali → tampilkan popup akses
        if (!Auth::check()) {
            return redirect()->route('error.akses');
        }

        // Sudah login tapi bukan superadmin → tampilkan popup akses
        if (Auth::user()->peran !== 'superadmin') {
            return redirect()->route('error.akses');
        }

        return $next($request);
    }
}
