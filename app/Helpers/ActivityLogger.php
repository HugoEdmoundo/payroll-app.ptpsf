<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $module = null, $description = null, $metadata = null)
    {
        try {
            if (!Auth::check()) {
                return;
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'metadata' => $metadata ? json_encode($metadata) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if activity_logs table doesn't exist
            \Log::debug('Activity log failed: ' . $e->getMessage());
        }
    }
}
