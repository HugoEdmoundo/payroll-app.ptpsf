<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicField extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'validation_rules',
        'default_value',
        'help_text',
        'placeholder',
        'is_required',
        'is_active',
        'is_searchable',
        'show_in_list',
        'show_in_form',
        'order',
        'group'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'is_searchable' => 'boolean',
        'show_in_list' => 'boolean',
        'show_in_form' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function fieldValues()
    {
        return $this->hasMany(FieldValue::class);
    }
    
    /**
     * Get field options as array
     */
    public function getOptionsArray()
    {
        if (empty($this->field_options)) {
            return [];
        }
        
        // If already array, return it
        if (is_array($this->field_options)) {
            return $this->field_options;
        }
        
        // Try to decode JSON
        $decoded = json_decode($this->field_options, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        // If comma-separated string
        return array_map('trim', explode(',', $this->field_options));
    }
}
