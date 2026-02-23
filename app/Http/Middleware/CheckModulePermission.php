<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module, string $action = 'view'): Response
    {
        $user = auth()->user();
        
        // Superadmin bypass
        if ($user->role && $user->role->is_superadmin) {
            return $next($request);
        }
        
        // Check permission
        $permissionKey = "{$module}.{$action}";
        
        if (!$user->hasPermission($permissionKey)) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }
        
        return $next($request);
    }
}
