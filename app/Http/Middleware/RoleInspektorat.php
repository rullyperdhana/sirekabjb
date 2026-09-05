<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleInspektorat
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && in_array(auth()->user()->role, ['inspektorat', 'admin'])) {
            return $next($request);
        }

        abort(403, 'Akses terbatas untuk Auditor Inspektorat dan Administrator.');
    }
}
