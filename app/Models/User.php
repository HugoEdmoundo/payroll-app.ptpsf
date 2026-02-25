<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_valid', // Email untuk verifikasi forgot password
        'password',
        'phone',
        'address',
        'join_date',
        'position',
        'profile_photo',
        'role_id',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'join_date' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    // Boot method untuk set join_date otomatis
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->join_date)) {
                $user->join_date = Carbon::now();
            }
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    /**
     * Check if user is superadmin
     */
    public function isSuperadmin()
    {
        return $this->role && $this->role->is_superadmin;
    }
    
    public function userPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot('is_granted', 'notes')
            ->withTimestamps();
    }

    /**
     * Check if user has permission
     * Priority: User-specific permissions > Role permissions > Superadmin
     */
    public function hasPermission($permissionKey)
    {
        // Superadmin always has all permissions
        if ($this->isSuperadmin()) {
            return true;
        }

        // Check if user is active
        if (!$this->is_active) {
            return false;
        }

        // Check user-specific permissions first (highest priority)
        $userPermission = $this->userPermissions()
            ->where('key', $permissionKey)
            ->first();
        
        if ($userPermission) {
            // If user has specific permission, use that (granted or denied)
            return $userPermission->pivot->is_granted;
        }

        // Fall back to role permissions
        if ($this->role) {
            return $this->role->permissions()
                ->where('key', $permissionKey)
                ->exists();
        }

        return false;
    }
    
    /**
     * Check if user can perform specific action on module
     * Example: canDo('karyawan', 'view'), canDo('users', 'edit')
     */
    public function canDo($module, $action)
    {
        // Superadmin can do everything
        if ($this->isSuperadmin()) {
            return true;
        }

        if (!$this->is_active) {
            return false;
        }

        // Build permission key
        $permissionKey = "{$module}.{$action}";
        
        return $this->hasPermission($permissionKey);
    }
    
    /**
     * Get all user permissions (combined from role and user-specific)
     */
    public function getAllPermissions()
    {
        if ($this->isSuperadmin()) {
            return Permission::all();
        }

        $rolePermissions = $this->role ? $this->role->permissions : collect();
        $userPermissions = $this->userPermissions;
        
        // Merge and prioritize user-specific permissions
        $merged = $rolePermissions->keyBy('id');
        
        foreach ($userPermissions as $userPerm) {
            if ($userPerm->pivot->is_granted) {
                $merged->put($userPerm->id, $userPerm);
            } else {
                // If denied, remove from merged
                $merged->forget($userPerm->id);
            }
        }
        
        return $merged->values();
    }
}