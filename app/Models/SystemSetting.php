<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';
    
    protected $fillable = ['group', 'key', 'value', 'order'];

    public static function getOptions($group)
    {
        return self::where('group', $group)
            ->orderBy('order')
            ->orderBy('value')
            ->pluck('value', 'key')
            ->toArray();
    }
}