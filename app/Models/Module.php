<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'icon',
        'description',
        'is_active',
        'is_system',
        'order',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'settings' => 'array',
    ];

    public function dynamicFields()
    {
        return $this->hasMany(DynamicField::class)->orderBy('order');
    }
    
    public function activeFields()
    {
        return $this->hasMany(DynamicField::class)->where('is_active', true)->orderBy('order');
    }
}
