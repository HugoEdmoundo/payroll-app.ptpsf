<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        $roles = Role::withCount('users')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_superadmin' => false,
            'is_default' => false, // ROLE BARU = BUKAN DEFAULT
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($role->is_superadmin) {
            abort(403, 'Cannot edit superadmin role.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($role->is_superadmin) {
            abort(403, 'Cannot edit superadmin role.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        // SUPER ADMIN - TIDAK BISA DI DELETE
        if ($role->is_superadmin) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete Super Admin role.');
        }
        
        // ROLE DEFAULT (User) - TIDAK BISA DI DELETE
        if ($role->is_default && $role->name === 'User') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete default User role.');
        }
        
        // CEK ROLE SEDANG DIPAKAI USER
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role that has users assigned. Please reassign users first.');
        }

        try {
            // Hapus relasi permissions
            $role->permissions()->detach();
            
            // Hapus role
            $role->delete();
            
            return redirect()->route('admin.roles.index')
                ->with('success', 'Role "' . $role->name . '" deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}