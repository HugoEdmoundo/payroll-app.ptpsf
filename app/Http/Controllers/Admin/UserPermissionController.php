<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function edit(User $user)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        // Prevent editing superadmin permissions
        if ($user->role && $user->role->is_superadmin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot edit superadmin permissions.');
        }
        
        // Get all permissions grouped by module
        $permissions = Permission::orderBy('module')
            ->orderBy('action_type')
            ->get()
            ->groupBy('module');
        
        // Get user's role permissions
        $rolePermissions = $user->role ? $user->role->permissions->pluck('id')->toArray() : [];
        
        // Get user-specific permissions
        $userPermissions = $user->userPermissions()
            ->get()
            ->mapWithKeys(function ($permission) {
                return [$permission->id => [
                    'is_granted' => $permission->pivot->is_granted,
                    'notes' => $permission->pivot->notes
                ]];
            });
        
        return view('admin.users.permissions', compact('user', 'permissions', 'rolePermissions', 'userPermissions'));
    }
    
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        // Prevent editing superadmin permissions
        if ($user->role && $user->role->is_superadmin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot edit superadmin permissions.');
        }
        
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:grant,deny,inherit',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:500',
        ]);
        
        // Clear existing user-specific permissions
        $user->userPermissions()->detach();
        
        // Add new user-specific permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permissionId => $action) {
                if ($action !== 'inherit') {
                    $user->userPermissions()->attach($permissionId, [
                        'is_granted' => $action === 'grant',
                        'notes' => $request->notes[$permissionId] ?? null,
                    ]);
                }
            }
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User permissions updated successfully.');
    }
}
