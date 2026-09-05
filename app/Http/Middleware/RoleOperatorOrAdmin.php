<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleOperatorOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && in_array(auth()->user()->role, ['operator', 'admin'])) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Fitur ini hanya diperuntukkan bagi Operator SKPD dan Administrator.');
    }
}
