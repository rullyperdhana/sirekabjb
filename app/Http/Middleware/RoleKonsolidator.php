<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleKonsolidator
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && in_array(auth()->user()->role, ['konsolidator', 'admin'])) {
            return $next($request);
        }

        abort(403, 'Akses khusus Konsolidator Kas Daerah dan Administrator.');
    }
}
