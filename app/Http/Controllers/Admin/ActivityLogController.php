<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activities = $query->latest()->paginate(20);
        
        // Get unique actions and modules for filters
        $actions = ActivityLog::distinct()->pluck('action');
        $modules = ActivityLog::distinct()->whereNotNull('module')->pluck('module');
        
        return view('admin.activity-logs.index', compact('activities', 'actions', 'modules'));
    }
    
    public function latest()
    {
        $activities = ActivityLog::with('user')
                                 ->where('user_id', '!=', auth()->id())
                                 ->latest()
                                 ->take(5)
                                 ->get();
        
        return response()->json([
            'activities' => $activities->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'user_name' => $activity->user->name ?? 'Unknown',
                    'action' => $activity->action,
                    'module' => $activity->module,
                    'description' => $activity->description ?? ucfirst($activity->action) . ' ' . ($activity->module ?? ''),
                    'time' => $activity->created_at->diffForHumans(),
                    'timestamp' => $activity->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }
}
