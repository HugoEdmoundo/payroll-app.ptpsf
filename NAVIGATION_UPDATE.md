# Navigation Update

## Perubahan yang Dilakukan

### 1. Sidebar Navigation (resources/views/partials/sidebar.blade.php)

#### Struktur Baru:
- **Section Headers**: Menambahkan section headers untuk grouping yang lebih jelas
  - **MAIN MENU**: Dashboard, Data Karyawan
  - **PAYROLL**: Pengaturan Gaji, Komponen (NKI, Absensi, Kasbon), Acuan Gaji, Hitung Gaji, Slip Gaji
  - **ADMINISTRATION**: System Settings, Manage Users, Manage Roles

#### Icon Updates:
- **Pengaturan Gaji**: `fa-cog` (lebih sesuai untuk settings)
- **Komponen Dropdown**: `fa-puzzle-piece` (lebih sesuai untuk komponen)
- **Slip Gaji**: `fa-receipt` (lebih sesuai untuk slip)
- **System Settings**: `fa-sliders-h` (lebih modern)
- **Manage Users**: `fa-user-shield` (lebih sesuai untuk user management)

#### Sub-menu Komponen:
- Setiap item di dropdown komponen sekarang memiliki icon kecil:
  - **NKI**: `fa-star`
  - **Absensi**: `fa-calendar-check`
  - **Kasbon**: `fa-hand-holding-usd`

#### Improvements:
- Section headers dengan uppercase text dan tracking-wider
- Spacing yang lebih baik antar section
- Konsisten dengan permission checking
- Fix active state untuk karyawan (exclude admin routes)

### 2. Mobile Navigation (resources/views/partials/mobile-nav.blade.php)

#### Perubahan dari Role-based ke Permission-based:
**Before**: Navigation items ditentukan berdasarkan role (Superadmin vs User)
**After**: Navigation items ditentukan berdasarkan permissions yang dimiliki user

#### Logic Baru:
```php
// Priority order:
1. Dashboard (jika ada permission)
2. Karyawan (jika ada permission)
3. Payroll module (prioritas: Acuan > Hitung > Slip)
4. Admin module (prioritas: Users > Roles > Settings)
5. Profile (jika slot masih tersisa)

// Max 5 items di mobile nav
```

#### Icon Updates:
- **Acuan Gaji**: `fa-file-invoice-dollar`
- **Hitung Gaji**: `fa-calculator`
- **Slip Gaji**: `fa-receipt`
- **Users**: `fa-user-shield`
- **Roles**: `fa-user-tag`
- **Settings**: `fa-sliders-h`

#### Benefits:
- Lebih flexible - tidak tergantung nama role
- Otomatis menyesuaikan dengan permissions user
- Prioritas menu yang lebih logis
- Mendukung custom roles dengan berbagai kombinasi permissions

## Comparison

### Sidebar - Before vs After

**Before:**
```
Dashboard
Data Karyawan
Pengaturan Gaji
Komponen (dropdown)
  - NKI (Tunjangan Prestasi)
  - Absensi
  - Kasbon
Acuan Gaji
Hitung Gaji
Slip Gaji
─────────────────
System Settings
Manage Users
Manage Roles
```

**After:**
```
Dashboard
Data Karyawan

PAYROLL
Pengaturan Gaji
Komponen (dropdown)
  - ⭐ NKI
  - 📅 Absensi
  - 💰 Kasbon
Acuan Gaji
Hitung Gaji
Slip Gaji

ADMINISTRATION
System Settings
Manage Users
Manage Roles
```

### Mobile Nav - Before vs After

**Before (Role-based):**
```php
if ($role === 'Superadmin') {
    // Fixed 5 items for superadmin
} else {
    // Fixed 3 items for others
}
```

**After (Permission-based):**
```php
// Dynamic based on permissions
// Priority: Dashboard > Karyawan > Payroll > Admin > Profile
// Max 5 items
```

## Testing Scenarios

### Scenario 1: Superadmin
**Permissions**: All permissions
**Sidebar**: All menu items visible with proper grouping
**Mobile Nav**: Dashboard, Karyawan, Acuan, Users, Roles

### Scenario 2: Payroll Staff
**Permissions**: dashboard.view, karyawan.view, acuan_gaji.view, hitung_gaji.view, slip_gaji.view
**Sidebar**: 
- Dashboard
- Data Karyawan
- PAYROLL section with Acuan, Hitung, Slip
**Mobile Nav**: Dashboard, Karyawan, Acuan, Hitung, Slip

### Scenario 3: HR Manager
**Permissions**: dashboard.view, karyawan.view, users.view
**Sidebar**:
- Dashboard
- Data Karyawan
- ADMINISTRATION section with Manage Users
**Mobile Nav**: Dashboard, Karyawan, Users, Profile

### Scenario 4: Finance
**Permissions**: dashboard.view, slip_gaji.view
**Sidebar**:
- Dashboard
- PAYROLL section with Slip Gaji only
**Mobile Nav**: Dashboard, Slip, Profile

## Icon Reference

### Sidebar Icons:
| Module | Icon | Class |
|--------|------|-------|
| Dashboard | 📊 | fa-chart-line |
| Data Karyawan | 👥 | fa-users |
| Pengaturan Gaji | ⚙️ | fa-cog |
| Komponen | 🧩 | fa-puzzle-piece |
| NKI | ⭐ | fa-star |
| Absensi | 📅 | fa-calendar-check |
| Kasbon | 💰 | fa-hand-holding-usd |
| Acuan Gaji | 💵 | fa-file-invoice-dollar |
| Hitung Gaji | 🧮 | fa-calculator |
| Slip Gaji | 🧾 | fa-receipt |
| System Settings | 🎚️ | fa-sliders-h |
| Manage Users | 🛡️ | fa-user-shield |
| Manage Roles | 🏷️ | fa-user-tag |

## Files Modified

1. `resources/views/partials/sidebar.blade.php`
   - Added section headers
   - Updated icons
   - Improved spacing
   - Fixed active states

2. `resources/views/partials/mobile-nav.blade.php`
   - Changed from role-based to permission-based
   - Dynamic menu generation
   - Priority-based item selection
   - Updated icons

## Benefits

### 1. Better Organization
- Clear visual separation between sections
- Logical grouping of related features
- Easier to scan and navigate

### 2. Flexibility
- Works with any custom role
- Automatically adapts to permissions
- No hardcoded role names

### 3. Consistency
- Same permission checking logic everywhere
- Consistent icon usage
- Unified styling

### 4. User Experience
- Clearer navigation structure
- Better mobile experience
- Intuitive icon choices

## Status

✅ Sidebar updated with sections and new icons
✅ Mobile nav updated to permission-based
✅ No diagnostics errors
✅ Backward compatible
✅ Ready for testing

## Next Steps

1. Test navigation dengan berbagai role combinations
2. Verify permission checking works correctly
3. Test mobile navigation di berbagai screen sizes
4. Verify active states work properly
5. Test dropdown functionality di sidebar

