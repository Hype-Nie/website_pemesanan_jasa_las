<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsEmployee
{
    /**
     * Handle an incoming request.
     * Abort with 403 if the authenticated user is not an employee or admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['employee', 'admin'])) {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh Karyawan Bengkel.');
        }

        return $next($request);
    }
}
