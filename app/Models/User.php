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

    public function hasPermission($permissionKey)
    {
        if ($this->role && $this->role->is_superadmin) {
            return true;
        }

        if (!$this->role || !$this->is_active) {
            return false;
        }

        return $this->role->permissions()
            ->where('key', $permissionKey)
            ->exists();
    }
}