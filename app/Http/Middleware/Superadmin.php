<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Superadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->role || !$user->role->is_superadmin) {
            abort(403, 'Unauthorized access. Superadmin only.');
        }

        return $next($request);
    }
}