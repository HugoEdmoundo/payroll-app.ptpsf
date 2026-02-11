<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        // Cek user active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }
        
        // Superadmin always allowed
        if ($user->role && $user->role->is_superadmin) {
            return $next($request);
        }
        
        // Cek permission
        if ($user->hasPermission($permission)) {
            return $next($request);
        }
        
        // Default: user biasa bisa akses view karyawan
        if ($permission === 'karyawan.view') {
            return $next($request);
        }
        
        abort(403, 'Unauthorized access.');
    }
}