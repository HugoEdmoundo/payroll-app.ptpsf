<?php

namespace App\Traits;

use App\Helpers\ActivityLogger;

trait LogsActivity
{
    protected function logActivity($action, $module, $description = null, $metadata = null)
    {
        ActivityLogger::log($action, $module, $description, $metadata);
    }
    
    protected function logCreate($module, $itemName = null)
    {
        $description = $itemName ? "Created {$itemName}" : "Created new {$module}";
        $this->logActivity('create', $module, $description);
    }
    
    protected function logUpdate($module, $itemName = null)
    {
        $description = $itemName ? "Updated {$itemName}" : "Updated {$module}";
        $this->logActivity('update', $module, $description);
    }
    
    protected function logDelete($module, $itemName = null)
    {
        $description = $itemName ? "Deleted {$itemName}" : "Deleted {$module}";
        $this->logActivity('delete', $module, $description);
    }
    
    protected function logImport($module, $count = null)
    {
        $description = $count ? "Imported {$count} {$module} records" : "Imported {$module} data";
        $this->logActivity('import', $module, $description);
    }
    
    protected function logExport($module)
    {
        $description = "Exported {$module} data";
        $this->logActivity('export', $module, $description);
    }
    
    protected function logGenerate($module, $itemName = null)
    {
        $description = $itemName ? "Generated {$itemName}" : "Generated {$module}";
        $this->logActivity('generate', $module, $description);
    }
}
