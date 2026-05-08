<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware IsAdmin — versi FIXED ✅
 *
 * ✅ FIX #2: Middleware ini mengecek apakah user yang sudah login
 *    memiliki role 'admin'. Kalau bukan admin → 403 Forbidden.
 *
 *    Kode Yoga: tidak ada middleware sama sekali. Route admin
 *               terbuka untuk siapapun, bahkan tanpa login.
 *    Kode Fix:  middleware ini dipasang di route group admin
 *               bersama 'auth:sanctum', sehingga:
 *               - Harus login dulu (auth:sanctum)
 *               - Harus punya role admin (IsAdmin)
 */
class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN role-nya admin
        if (!$request->user() || $request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak. Anda bukan admin.',
            ], 403);
        }

        return $next($request);
    }
}
