<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'dynamic_field_id',
        'entity_type',
        'entity_id',
        'value'
    ];

    public function dynamicField()
    {
        return $this->belongsTo(DynamicField::class);
    }
    
    public function entity()
    {
        return $this->morphTo();
    }
}
