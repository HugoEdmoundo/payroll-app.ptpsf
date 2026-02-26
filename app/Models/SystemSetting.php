<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';
    
    protected $fillable = ['group', 'key', 'value', 'order', 'jenis_karyawan'];

    public static function getOptions($group, $jenisKaryawan = null)
    {
        $query = self::where('group', $group);
        
        // Filter by jenis_karyawan if provided (for jabatan_options)
        if ($jenisKaryawan && $group === 'jabatan_options') {
            $query->where(function($q) use ($jenisKaryawan) {
                $q->where('jenis_karyawan', $jenisKaryawan)
                  ->orWhereNull('jenis_karyawan');
            });
        }
        
        return $query->orderBy('order')
            ->orderBy('value')
            ->pluck('value', 'key')
            ->toArray();
    }
    
    public static function getJabatanByJenisKaryawan($jenisKaryawan)
    {
        return self::where('group', 'jabatan_options')
            ->where(function($q) use ($jenisKaryawan) {
                $q->where('jenis_karyawan', $jenisKaryawan)
                  ->orWhereNull('jenis_karyawan');
            })
            ->orderBy('order')
            ->orderBy('value')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->key => $item->value];
            })
            ->toArray();
    }
}