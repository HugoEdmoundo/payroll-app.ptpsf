<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request and log activity after response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only log for authenticated users
        if (!auth()->check()) {
            return $response;
        }
        
        // Only log successful requests (2xx status codes)
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            return $response;
        }
        
        // Determine action and module from route
        $route = $request->route();
        if (!$route) {
            return $response;
        }
        
        $routeName = $route->getName();
        $method = $request->method();
        
        // Skip logging for certain routes
        $skipRoutes = ['dashboard', 'profile', 'login', 'logout', 'api.'];
        foreach ($skipRoutes as $skip) {
            if (str_contains($routeName ?? '', $skip)) {
                return $response;
            }
        }
        
        // Extract module and action from route name
        // Example: payroll.nki.store -> module: NKI, action: create
        // Example: karyawan.update -> module: Karyawan, action: update
        // Example: admin.users.destroy -> module: User, action: delete
        
        if (!$routeName) {
            return $response;
        }
        
        $parts = explode('.', $routeName);
        $action = end($parts);
        
        // Map route actions to activity actions
        $actionMap = [
            'store' => 'create',
            'update' => 'update',
            'destroy' => 'delete',
            'import' => 'import',
            'importStore' => 'import',
            'export' => 'export',
        ];
        
        if (!isset($actionMap[$action])) {
            return $response;
        }
        
        $activityAction = $actionMap[$action];
        
        // Extract module name
        $module = '';
        if (count($parts) >= 2) {
            $module = ucfirst($parts[count($parts) - 2]);
        }
        
        // Clean up module name
        $module = str_replace('-', ' ', $module);
        $module = ucwords($module);
        
        // Create description
        $description = ucfirst($activityAction) . ' ' . $module;
        
        // Log the activity
        ActivityLogger::log($activityAction, $module, $description);
        
        return $response;
    }
}
