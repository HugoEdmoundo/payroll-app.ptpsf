<?php

namespace App\Helpers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class KomponenGajiHelper
{
    /**
     * Get label for komponen gaji field
     * 
     * @param string $key
     * @return string
     */
    public static function getLabel($key)
    {
        // Cache labels for 1 hour
        $labels = Cache::remember('komponen_gaji_labels', 3600, function () {
            return SystemSetting::where('group', 'komponen_gaji_labels')
                               ->pluck('value', 'key')
                               ->toArray();
        });

        return $labels[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    /**
     * Get all labels
     * 
     * @return array
     */
    public static function getAllLabels()
    {
        return Cache::remember('komponen_gaji_labels', 3600, function () {
            return SystemSetting::where('group', 'komponen_gaji_labels')
                               ->orderBy('order')
                               ->pluck('value', 'key')
                               ->toArray();
        });
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget('komponen_gaji_labels');
    }
}
