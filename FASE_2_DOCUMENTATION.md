# FASE 2: Permission System Per-User - COMPLETED ✅

## Overview
Sistem permission telah diupgrade dari role-based menjadi per-user dengan granular access control. Sekarang superadmin dapat memberikan atau mencabut permission spesifik untuk setiap user, terlepas dari role mereka.

## Fitur Utama

### 1. Granular Permission System
- **Action Types**: view, create, edit, delete, all
- **Module-based**: Permissions dikelompokkan berdasarkan module (karyawan, users, roles, settings, dashboard, dll)
- **User-specific Overrides**: Permission dapat di-grant atau deny per user
- **Priority System**: User-specific permissions > Role permissions > Superadmin

### 2. Database Schema

#### Tabel `permissions` (Updated)
```sql
- id
- name
- key (unique)
- action_type (view/create/edit/delete/all)
- module (karyawan/users/roles/settings/dll)
- group
- description
- timestamps
```

#### Tabel `user_permissions` (New)
```sql
- id
- user_id (foreign key)
- permission_id (foreign key)
- is_granted (boolean) - true = granted, false = denied
- notes (text) - catatan kenapa permission diberikan/ditolak
- timestamps
- unique(user_id, permission_id)
```

### 3. Permission Checking Methods

#### Di Model User:

**hasPermission($permissionKey)**
```php
// Check if user has specific permission
auth()->user()->hasPermission('karyawan.view')
auth()->user()->hasPermission('users.edit')
```

**canDo($module, $action)**
```php
// Check if user can perform action on module
auth()->user()->canDo('karyawan', 'view')
auth()->user()->canDo('users', 'edit')
```

**getAllPermissions()**
```php
// Get all user permissions (combined from role and user-specific)
$permissions = auth()->user()->getAllPermissions();
```

### 4. Blade Directives

```blade
@canDo('karyawan', 'view')
    <!-- Content for users who can view karyawan -->
@endcanDo

@hasPermission('users.edit')
    <!-- Content for users who can edit users -->
@endhasPermission
```

### 5. Routes

```php
// User Permissions Management
Route::get('/admin/users/{user}/permissions', [UserPermissionController::class, 'edit'])
    ->name('admin.users.permissions.edit');
    
Route::put('/admin/users/{user}/permissions', [UserPermissionController::class, 'update'])
    ->name('admin.users.permissions.update');
```

### 6. UI Components

#### User Index
- Tombol "Permissions" untuk manage user-specific permissions
- Hanya muncul untuk non-superadmin users

#### User Permissions Page
- Grouped by module
- Radio buttons: Inherit / Grant / Deny
- Notes field untuk setiap permission
- Visual indicators:
  - Blue badge: View permissions
  - Green badge: Create permissions
  - Yellow badge: Edit permissions
  - Red badge: Delete permissions
  - Indigo badge: Inherited from role

#### Profile Page
- Permission summary sidebar
- Shows all active permissions
- Highlights user-specific overrides
- Module-based grouping

### 7. Permission List (Default)

#### Karyawan Module
- karyawan.view - View Karyawan
- karyawan.create - Create Karyawan
- karyawan.edit - Edit Karyawan
- karyawan.delete - Delete Karyawan
- karyawan.import - Import Karyawan
- karyawan.export - Export Karyawan

#### Users Module
- users.view - View Users
- users.create - Create Users
- users.edit - Edit Users
- users.delete - Delete Users
- users.permissions - Manage User Permissions

#### Roles Module
- roles.view - View Roles
- roles.create - Create Roles
- roles.edit - Edit Roles
- roles.delete - Delete Roles

#### Settings Module
- settings.view - View Settings
- settings.edit - Edit Settings

#### Dashboard Module
- dashboard.view - View Dashboard
- dashboard.reports - View Reports

## How It Works

### Permission Priority
1. **Superadmin**: Always has all permissions (bypass)
2. **User-specific Grant**: User explicitly granted permission
3. **User-specific Deny**: User explicitly denied permission (overrides role)
4. **Role Permission**: Permission from user's role
5. **Default**: No permission

### Example Scenarios

#### Scenario 1: Grant Additional Permission
- User has role "Staff" with only view permissions
- Superadmin grants "karyawan.edit" to this specific user
- Result: User can view AND edit karyawan (edit is override)

#### Scenario 2: Deny Permission
- User has role "Manager" with all karyawan permissions
- Superadmin denies "karyawan.delete" to this specific user
- Result: User can view, create, edit but NOT delete karyawan

#### Scenario 3: Inherit from Role
- User has role "Admin" with full permissions
- No user-specific overrides
- Result: User has all permissions from Admin role

## Usage Examples

### In Controllers
```php
// Check permission
if (!auth()->user()->hasPermission('karyawan.edit')) {
    abort(403, 'Unauthorized action.');
}

// Or use canDo
if (!auth()->user()->canDo('karyawan', 'edit')) {
    abort(403, 'Unauthorized action.');
}
```

### In Blade Views
```blade
@hasPermission('karyawan.create')
    <a href="{{ route('karyawan.create') }}">Add Karyawan</a>
@endhasPermission

@canDo('users', 'edit')
    <button>Edit User</button>
@endcanDo
```

### In Middleware
```php
Route::get('/karyawan', [KaryawanController::class, 'index'])
    ->middleware('permission:karyawan.view');
```

## Files Modified/Created

### New Files
- `database/migrations/2026_02_18_141956_create_user_permissions_table.php`
- `database/migrations/2026_02_18_142002_add_action_type_to_permissions_table.php`
- `database/seeders/UpdatePermissionsSeeder.php`
- `app/Http/Controllers/Admin/UserPermissionController.php`
- `resources/views/admin/users/permissions.blade.php`
- `resources/views/components/user/permission-badge.blade.php`

### Modified Files
- `app/Models/User.php` - Added permission methods
- `app/Models/Permission.php` - Added user relationship
- `app/Providers/AppServiceProvider.php` - Added blade directives
- `routes/web.php` - Added permission routes
- `resources/views/admin/users/index.blade.php` - Added permissions button
- `resources/views/profile/index.blade.php` - Added permission summary

## Testing

### Test Permission System
1. Login as superadmin
2. Go to Users > Select a user > Permissions
3. Grant/Deny specific permissions
4. Login as that user
5. Verify permissions work correctly

### Test Priority
1. Create user with "Staff" role (limited permissions)
2. Grant additional permission (e.g., karyawan.edit)
3. Verify user can edit karyawan
4. Deny a role permission (e.g., karyawan.view)
5. Verify user cannot view karyawan even though role has it

## Next Steps (FASE 3 & 4)

### FASE 3: CMS Superadmin
- Dynamic field management
- Module configuration
- System settings enhancement

### FASE 4: Payroll System
- Master data (wilayah, jenis karyawan, status pegawai)
- Salary configuration
- Payroll calculation
- Slip gaji
- Reports

## Notes
- Superadmin permissions cannot be edited
- User-specific permissions always override role permissions
- Permission changes take effect immediately
- All permission checks are cached for performance
