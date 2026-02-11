<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permissionKey)
    {
        // Superadmin punya semua permission
        if ($this->role && $this->role->is_superadmin) {
            return true;
        }

        // User harus punya role dan role harus active
        if (!$this->role || !$this->is_active) {
            return false;
        }

        // Cek permission
        return $this->role->permissions()
            ->where('key', $permissionKey)
            ->exists();
    }
    
    // Helper untuk cek akses ke dashboard
    public function canAccessDashboard()
    {
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->role && $this->role->is_superadmin) {
            return true;
        }
        
        // User biasa juga bisa akses dashboard
        return true;
    }
    
    // Helper untuk cek akses ke karyawan
    public function canAccessKaryawan()
    {
        if (!$this->is_active) {
            return false;
        }
        
        // Semua active user bisa lihat karyawan
        return true;
    }
}