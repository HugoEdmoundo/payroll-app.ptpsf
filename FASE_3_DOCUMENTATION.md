# FASE 3: CMS Superadmin - Dynamic System Management ✅

## Overview
Sistem CMS yang memungkinkan superadmin mengelola seluruh aspek sistem tanpa perlu membuka kode. Termasuk dynamic field management, module configuration, dan system settings.

## Fitur Utama

### 1. Dynamic Field System
Superadmin dapat menambahkan field custom ke module manapun tanpa coding.

#### Field Types Supported:
- text - Text input
- email - Email input with validation
- number - Numeric input
- select - Dropdown selection
- textarea - Multi-line text
- date - Date picker
- datetime - Date and time picker
- checkbox - Boolean checkbox
- radio - Radio buttons
- file - File upload

#### Field Properties:
- **field_name**: Internal name (snake_case)
- **field_label**: Display label
- **field_type**: Type of input
- **field_options**: Options for select/radio (JSON or comma-separated)
- **validation_rules**: Laravel validation rules
- **default_value**: Default value
- **help_text**: Help text below field
- **placeholder**: Placeholder text
- **is_required**: Required field
- **is_active**: Active/inactive
- **is_searchable**: Can be searched
- **show_in_list**: Show in table list
- **show_in_form**: Show in forms
- **order**: Display order
- **group**: Field grouping

### 2. Module Management
Manage system modules with full configuration.

#### Module Properties:
- **name**: Internal name (lowercase, no spaces)
- **display_name**: Display name
- **icon**: Font Awesome icon class
- **description**: Module description
- **is_active**: Active/inactive
- **is_system**: System module (cannot be deleted)
- **order**: Display order
- **settings**: JSON settings (module-specific)

#### Default Modules:
1. **karyawan** - Employee Management
2. **users** - User Management
3. **roles** - Role Management
4. **settings** - System Settings
5. **dashboard** - Dashboard

### 3. Database Schema

#### Table: `modules`
```sql
- id
- name (unique)
- display_name
- icon
- description
- is_active (boolean)
- is_system (boolean)
- order (integer)
- settings (json)
- timestamps
```

#### Table: `dynamic_fields`
```sql
- id
- module_id (foreign key)
- field_name
- field_label
- field_type
- field_options (text/json)
- validation_rules
- default_value
- help_text
- placeholder
- is_required (boolean)
- is_active (boolean)
- is_searchable (boolean)
- show_in_list (boolean)
- show_in_form (boolean)
- order (integer)
- group
- timestamps
- unique(module_id, field_name)
```

#### Table: `field_values`
```sql
- id
- dynamic_field_id (foreign key)
- entity_type (polymorphic)
- entity_id (polymorphic)
- value (text)
- timestamps
- index(entity_type, entity_id)
- unique(dynamic_field_id, entity_type, entity_id)
```

### 4. Trait: HasDynamicFields

Models yang ingin support dynamic fields harus use trait ini.

```php
use App\Traits\HasDynamicFields;

class Karyawan extends Model
{
    use HasDynamicFields;
    
    // Optional: override module name
    protected function getDynamicModuleName()
    {
        return 'karyawan';
    }
}
```

#### Methods Available:

**getDynamicField($fieldName)**
```php
$email = $karyawan->getDynamicField('custom_email');
```

**setDynamicField($fieldName, $value)**
```php
$karyawan->setDynamicField('custom_email', 'test@example.com');
```

**getAllDynamicFields()**
```php
$fields = $karyawan->getAllDynamicFields();
// Returns: ['field_name' => ['field' => DynamicField, 'value' => 'value']]
```

### 5. Controllers

#### CMSController
- `index()` - CMS Dashboard with stats

#### ModuleController (Resource)
- `index()` - List all modules
- `create()` - Create module form
- `store()` - Save new module
- `show($module)` - Show module details
- `edit($module)` - Edit module form
- `update($module)` - Update module
- `destroy($module)` - Delete module (except system modules)

#### DynamicFieldController (Resource)
- `index()` - List all fields (filterable by module)
- `create()` - Create field form
- `store()` - Save new field
- `show($field)` - Show field details
- `edit($field)` - Edit field form
- `update($field)` - Update field
- `destroy($field)` - Delete field

### 6. Routes

```php
// CMS Dashboard
Route::get('/admin/cms', [CMSController::class, 'index'])->name('admin.cms.index');

// Modules Management
Route::resource('admin/modules', ModuleController::class);

// Dynamic Fields Management
Route::resource('admin/fields', DynamicFieldController::class);
```

### 7. Views Created

#### CMS Dashboard
- `resources/views/admin/cms/index.blade.php`
  - Stats cards (modules, fields, settings)
  - Quick actions
  - Module list preview
  - System settings preview
  - CMS features overview

### 8. Usage Examples

#### Creating a Dynamic Field via UI:
1. Go to CMS Dashboard
2. Click "Create Field"
3. Select Module (e.g., karyawan)
4. Fill field details:
   - Field Name: `emergency_contact`
   - Field Label: `Emergency Contact`
   - Field Type: `text`
   - Validation: `required|max:255`
   - Show in Form: Yes
   - Show in List: No
5. Save

#### Using Dynamic Field in Code:
```php
// Get karyawan
$karyawan = Karyawan::find(1);

// Set dynamic field value
$karyawan->setDynamicField('emergency_contact', '08123456789');

// Get dynamic field value
$contact = $karyawan->getDynamicField('emergency_contact');

// Get all dynamic fields with values
$allFields = $karyawan->getAllDynamicFields();
```

#### Displaying Dynamic Fields in Blade:
```blade
@foreach($karyawan->getAllDynamicFields() as $fieldName => $data)
    <div>
        <label>{{ $data['field']->field_label }}</label>
        <p>{{ $data['value'] ?? '-' }}</p>
    </div>
@endforeach
```

### 9. Benefits

#### For Superadmin:
- ✅ Add fields without coding
- ✅ Configure modules easily
- ✅ Full control over system
- ✅ No developer needed for simple changes

#### For Developers:
- ✅ Extensible architecture
- ✅ Clean separation of concerns
- ✅ Easy to add new modules
- ✅ Trait-based implementation

#### For System:
- ✅ Flexible and scalable
- ✅ Database-driven configuration
- ✅ Audit-friendly
- ✅ Version-controlled settings

### 10. Files Created/Modified

#### New Files:
- `database/migrations/2026_02_18_144810_create_modules_table.php`
- `database/migrations/2026_02_18_144812_create_dynamic_fields_table.php`
- `database/migrations/2026_02_18_144836_create_field_values_table.php`
- `app/Models/Module.php`
- `app/Models/DynamicField.php`
- `app/Models/FieldValue.php`
- `app/Traits/HasDynamicFields.php`
- `app/Http/Controllers/Admin/CMSController.php`
- `app/Http/Controllers/Admin/ModuleController.php`
- `app/Http/Controllers/Admin/DynamicFieldController.php`
- `database/seeders/ModuleSeeder.php`
- `resources/views/admin/cms/index.blade.php`

#### Modified Files:
- `app/Models/Karyawan.php` - Added HasDynamicFields trait
- `routes/web.php` - Added CMS routes
- `database/seeders/UserSeeder.php` - Fixed role ID issue
- `database/seeders/SystemSettingSeeder.php` - Fixed merge conflicts

### 11. Next Steps

#### Additional Views Needed (Optional):
- Module CRUD views (index, create, edit, show)
- Dynamic Field CRUD views (index, create, edit, show)
- Field value management in entity forms

#### Future Enhancements:
- Field validation preview
- Field dependency (show field A if field B = value)
- Field calculations (computed fields)
- Field templates (reusable field sets)
- Import/export field configurations
- Field versioning

### 12. Testing

#### Test Dynamic Fields:
1. Login as superadmin
2. Go to `/admin/cms`
3. View modules and stats
4. Create a new dynamic field for karyawan module
5. Test field in karyawan form
6. Verify field value is saved

#### Test Module Management:
1. Go to `/admin/modules`
2. Create a new module
3. Edit module settings
4. Verify module appears in CMS dashboard

### 13. Security

- ✅ Only superadmin can access CMS
- ✅ System modules cannot be deleted
- ✅ Field validation enforced
- ✅ SQL injection protected (Eloquent ORM)
- ✅ XSS protected (Blade escaping)

### 14. Performance Considerations

- Dynamic fields use polymorphic relationships
- Indexed by entity_type and entity_id
- Lazy loading available
- Can be eager loaded: `$karyawan->load('fieldValues.dynamicField')`

## Summary

FASE 3 memberikan superadmin kemampuan penuh untuk mengelola sistem tanpa coding:
- ✅ Dynamic field management
- ✅ Module configuration
- ✅ System settings integration
- ✅ Flexible and extensible architecture
- ✅ User-friendly CMS dashboard

Sistem sekarang sangat fleksibel dan dapat disesuaikan dengan kebutuhan bisnis tanpa perlu developer!
