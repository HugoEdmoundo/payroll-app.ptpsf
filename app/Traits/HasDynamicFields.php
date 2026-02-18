<?php

namespace App\Traits;

use App\Models\FieldValue;
use App\Models\DynamicField;

trait HasDynamicFields
{
    /**
     * Get all dynamic field values for this entity
     */
    public function fieldValues()
    {
        return $this->morphMany(FieldValue::class, 'entity');
    }
    
    /**
     * Get dynamic field value by field name
     */
    public function getDynamicField($fieldName)
    {
        $fieldValue = $this->fieldValues()
            ->whereHas('dynamicField', function($query) use ($fieldName) {
                $query->where('field_name', $fieldName);
            })
            ->first();
            
        return $fieldValue ? $fieldValue->value : null;
    }
    
    /**
     * Set dynamic field value
     */
    public function setDynamicField($fieldName, $value)
    {
        $field = DynamicField::whereHas('module', function($query) {
            $moduleName = $this->getDynamicModuleName();
            $query->where('name', $moduleName);
        })->where('field_name', $fieldName)->first();
        
        if (!$field) {
            return false;
        }
        
        return FieldValue::updateOrCreate(
            [
                'dynamic_field_id' => $field->id,
                'entity_type' => get_class($this),
                'entity_id' => $this->id,
            ],
            [
                'value' => $value
            ]
        );
    }
    
    /**
     * Get all dynamic fields with values
     */
    public function getAllDynamicFields()
    {
        $moduleName = $this->getDynamicModuleName();
        
        $fields = DynamicField::whereHas('module', function($query) use ($moduleName) {
            $query->where('name', $moduleName)->where('is_active', true);
        })->where('is_active', true)->orderBy('order')->get();
        
        $result = [];
        foreach ($fields as $field) {
            $value = $this->getDynamicField($field->field_name);
            $result[$field->field_name] = [
                'field' => $field,
                'value' => $value
            ];
        }
        
        return $result;
    }
    
    /**
     * Get module name for this entity
     * Override this method in your model if needed
     */
    protected function getDynamicModuleName()
    {
        // Default: use table name
        return $this->getTable();
    }
}
