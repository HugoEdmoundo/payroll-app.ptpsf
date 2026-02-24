# Hitung Gaji System - Complete Implementation

## ✅ COMPLETED - ALL FEATURES WORKING

### 1. Database Structure ✅
**File**: `database/migrations/2026_02_24_160221_recreate_hitung_gaji_table_with_all_fields.php`

- Table `hitung_gaji` with ALL 25 fields from acuan_gaji
- Column `adjustments` (JSON) for storing adjustment per field
- Status workflow: draft → preview → approved
- Foreign keys to acuan_gaji, karyawan, users
- Unique constraint on (karyawan_id, periode)

### 2. Model ✅
**File**: `app/Models/HitungGaji.php`

**Features**:
- All 25 fields with proper casting
- Auto-calculation of totals with adjustments in boot() method
- Methods:
  - `getFinalValue($field)` - Get value with adjustment applied
  - `getAdjustment($field)` - Get adjustment data for a field
- Relationships:
  - `acuanGaji()` - BelongsTo AcuanGaji
  - `karyawan()` - BelongsTo Karyawan
  - `approvedBy()` - BelongsTo User
  - `slipGaji()` - HasOne SlipGaji

### 3. Controller ✅
**File**: `app/Http/Controllers/Payroll/HitungGajiController.php`

**Methods**:
1. `index()` - List with filters (periode, status, search)
2. `create()` - Show acuan gaji list for selection
3. `getFormData($acuanGajiId)` - AJAX endpoint for loading form with calculations
4. `store()` - Create hitung gaji with NKI & Absensi calculations
5. `show()` - Detail view with all adjustments
6. `edit()` - Edit adjustments (draft only)
7. `update()` - Update adjustments
8. `destroy()` - Delete (draft only)
9. `preview()` - Change status to preview
10. `approve()` - Approve hitung gaji
11. `backToDraft()` - Revert to draft from preview
12. `export()` - Export to Excel
13. `import()` - Import view
14. `importStore()` - Process import
15. `downloadTemplate()` - Download import template

**Calculations in store()**:
- **NKI (Tunjangan Prestasi)**:
  ```
  NKI ≥ 8.5 → 100%
  NKI ≥ 8.0 → 80%
  NKI < 8.0 → 70%
  Tunjangan Prestasi = Nilai Acuan Prestasi × Persentase NKI
  ```

- **Absensi (Potongan Absensi)**:
  ```
  Potongan = (Absence + Tanpa Keterangan) ÷ Jumlah Hari × (Gaji Pokok + Tunjangan Prestasi + Operasional)
  Note: BPJS NOT included
  ```

### 4. Views ✅

#### A. Index View
**File**: `resources/views/payroll/hitung-gaji/index.blade.php`
- List all hitung gaji with filters
- Search by employee name
- Filter by periode and status
- Actions: view, edit (draft), delete (draft)

#### B. Create View
**File**: `resources/views/payroll/hitung-gaji/create.blade.php`
- Select periode
- Show list of acuan gaji (cards with employee info)
- Click to open modal with form
- AJAX loads form data dynamically

#### C. Show View
**File**: `resources/views/payroll/hitung-gaji/show.blade.php`
- Display all fields with final values
- Show adjustments with descriptions
- Action buttons based on status:
  - Draft: Edit, Preview, Delete
  - Preview: Back to Draft, Approve
  - Approved: View only

#### D. Edit View
**File**: `resources/views/payroll/hitung-gaji/edit.blade.php`
- Same structure as create
- Pre-filled with existing adjustments
- Only adjustments can be modified
- Base values are read-only

### 5. Components ✅

#### A. Form Component
**File**: `resources/views/components/hitung-gaji/form.blade.php`
- Employee info header
- NKI calculation info (if available)
- Absensi calculation info (if available)
- All 25 fields with adjustment inputs
- Each field uses field-with-adjustment component

#### B. Field with Adjustment Component
**File**: `resources/views/components/hitung-gaji/field-with-adjustment.blade.php`
- Left side: Base value (read-only) with lock icon
- Right side: Adjustment inputs (optional)
  - Type: +/- dropdown
  - Nominal: number input
  - Description: textarea (required if adjustment filled)

#### C. Show Component
**File**: `resources/views/components/hitung-gaji/show.blade.php`
- Employee info with status badge
- Pendapatan section (green theme)
  - All income fields with adjustments
  - Shows: base value → adjustment → final value
  - Adjustment description displayed
- Pengeluaran section (red theme)
  - All deduction fields with adjustments
  - Same display pattern
- Gaji Bersih (indigo theme)
- Keterangan (if exists)
- Approval info (if approved)

#### D. Table Component
**File**: `resources/views/components/hitung-gaji/table.blade.php`
- Employee info with avatar
- Periode
- Total Pendapatan (green)
- Total Pengeluaran (red)
- Take Home Pay (indigo, bold)
- Status badge (draft/preview/approved)
- Actions: view, edit, delete

### 6. Import/Export ✅

#### A. Export
**File**: `app/Exports/HitungGajiExport.php`
- Exports all hitung gaji data
- Shows final values (with adjustments applied)
- Columns: Employee, Periode, Status, All 25 fields, Totals
- Can filter by periode

#### B. Template Export
**File**: `app/Exports/HitungGajiTemplateExport.php`
- Downloadable template for import
- Headers for NIK, Periode
- 3 columns per field: Type, Nominal, Description
- Total 78 columns (25 fields × 3 + 3 base columns)
- Example row included

#### C. Import
**File**: `app/Imports/HitungGajiImport.php`
- Imports adjustments only
- Finds hitung gaji by NIK and periode
- Only updates draft status
- Validates required fields
- Builds adjustments array from columns

### 7. Seeder ✅
**File**: `database/seeders/HitungGajiSeeder.php`
- Creates hitung gaji from all acuan gaji
- Calculates NKI and Absensi automatically
- Adds random sample adjustments
- Skips if already exists
- Status: draft

### 8. Routes ✅
**File**: `routes/web.php`

```php
Route::prefix('hitung-gaji')->name('hitung-gaji.')->group(function () {
    Route::get('/', [HitungGajiController::class, 'index'])->name('index');
    Route::get('/create', [HitungGajiController::class, 'create'])->name('create');
    Route::get('/form/{acuanGajiId}', [HitungGajiController::class, 'getFormData'])->name('form');
    Route::post('/', [HitungGajiController::class, 'store'])->name('store');
    Route::get('/{hitungGaji}', [HitungGajiController::class, 'show'])->name('show');
    Route::get('/{hitungGaji}/edit', [HitungGajiController::class, 'edit'])->name('edit');
    Route::put('/{hitungGaji}', [HitungGajiController::class, 'update'])->name('update');
    Route::delete('/{hitungGaji}', [HitungGajiController::class, 'destroy'])->name('destroy');
    Route::post('/{hitungGaji}/preview', [HitungGajiController::class, 'preview'])->name('preview');
    Route::post('/{hitungGaji}/approve', [HitungGajiController::class, 'approve'])->name('approve');
    Route::post('/{hitungGaji}/back-to-draft', [HitungGajiController::class, 'backToDraft'])->name('back-to-draft');
});
```

## 📊 Data Flow

```
1. ACUAN GAJI (Generate)
   ├── Pengaturan Gaji (all fields)
   └── Kasbon ONLY (from Komponen Gaji)
   
2. HITUNG GAJI (Create from Acuan Gaji)
   ├── Copy ALL 25 fields from Acuan Gaji (READ-ONLY)
   ├── Calculate NKI → Tunjangan Prestasi
   ├── Calculate Absensi → Potongan Absensi
   └── User adds adjustments (OPTIONAL)
       ├── Each field can have adjustment
       ├── Adjustment: type (+/-), nominal, description
       └── Description REQUIRED if adjustment exists
   
3. WORKFLOW
   Draft → Preview → Approved
     ↑        ↓
     └────────┘
   (Back to Draft from Preview only)
   
4. SLIP GAJI (Future - from Approved Hitung Gaji)
   └── Show ALL descriptions from all sources
```

## 🎯 Field Structure

### Adjustment Format (JSON)
```json
{
  "gaji_pokok": {
    "type": "+",
    "nominal": 1000000,
    "description": "Bonus kenaikan gaji"
  },
  "benefit_operasional": {
    "type": "-",
    "nominal": 500000,
    "description": "Potongan karena cuti"
  }
}
```

### 25 Fields

**PENDAPATAN (12 fields)**:
1. gaji_pokok
2. bpjs_kesehatan_pendapatan
3. bpjs_kecelakaan_kerja_pendapatan
4. bpjs_kematian_pendapatan
5. bpjs_jht_pendapatan
6. bpjs_jp_pendapatan
7. tunjangan_prestasi (CALCULATED from NKI)
8. tunjangan_konjungtur
9. benefit_ibadah
10. benefit_komunikasi
11. benefit_operasional
12. reward

**PENGELUARAN (13 fields)**:
1. bpjs_kesehatan_pengeluaran
2. bpjs_kecelakaan_kerja_pengeluaran
3. bpjs_kematian_pengeluaran
4. bpjs_jht_pengeluaran
5. bpjs_jp_pengeluaran
6. tabungan_koperasi
7. koperasi
8. kasbon
9. umroh
10. kurban
11. mutabaah
12. potongan_absensi (CALCULATED from Absensi)
13. potongan_kehadiran

## 🔐 Permissions
- hitung_gaji.view
- hitung_gaji.create
- hitung_gaji.edit
- hitung_gaji.delete
- hitung_gaji.import
- hitung_gaji.export
- hitung_gaji.approve

## ⚠️ Important Rules

1. **Base values are READ-ONLY**
   - Values from Acuan Gaji cannot be edited
   - Only adjustments can be added/modified

2. **Adjustment is OPTIONAL**
   - If no adjustment, field shows original value
   - If has adjustment, description is REQUIRED

3. **Calculations happen ONCE**
   - NKI calculated when creating Hitung Gaji
   - Absensi calculated when creating Hitung Gaji
   - No recalculation after creation

4. **Status Workflow**
   - Draft: Can edit, delete, preview
   - Preview: Can approve, back to draft
   - Approved: Cannot edit or delete (final)

5. **Auto-calculation**
   - Total Pendapatan = Sum of all pendapatan fields (with adjustments)
   - Total Pengeluaran = Sum of all pengeluaran fields (with adjustments)
   - Gaji Bersih = Total Pendapatan - Total Pengeluaran

## 🚀 How to Use

### Create Hitung Gaji
1. Go to Hitung Gaji menu
2. Click "Create Hitung Gaji"
3. Select periode (or use current month)
4. System shows list of acuan gaji for that periode
5. Click on employee card
6. Modal opens with form (AJAX loaded)
7. All fields pre-filled from Acuan Gaji (read-only)
8. NKI and Absensi calculated automatically
9. Add adjustments if needed (optional)
10. Click "Save as Draft"

### Edit Adjustments
1. Open hitung gaji detail (status: draft)
2. Click "Edit"
3. Modify adjustments
4. Click "Update"

### Workflow
1. Create → Status: Draft
2. Click "Preview" → Status: Preview
3. Review all data
4. Click "Approve" → Status: Approved (FINAL)
5. Or "Back to Draft" to edit again

### Import Adjustments
1. Download template
2. Fill NIK, Periode, and adjustments
3. Upload file
4. System updates existing draft hitung gaji

### Export Data
1. Click "Export"
2. Optionally filter by periode
3. Download Excel with all data

## 📝 Testing

### Seeder
```bash
php artisan db:seed --class=HitungGajiSeeder
```

### Manual Testing
1. Create acuan gaji first (or use seeder)
2. Create NKI data (for tunjangan prestasi calculation)
3. Create Absensi data (for potongan absensi calculation)
4. Create hitung gaji
5. Test workflow: draft → preview → approved
6. Test adjustments
7. Test import/export

## 📦 Files Created

### Controllers
- `app/Http/Controllers/Payroll/HitungGajiController.php` (updated)

### Models
- `app/Models/HitungGaji.php` (updated)

### Views
- `resources/views/payroll/hitung-gaji/create.blade.php` (updated)
- `resources/views/payroll/hitung-gaji/edit.blade.php` (new)
- `resources/views/payroll/hitung-gaji/show.blade.php` (new)
- `resources/views/payroll/hitung-gaji/index.blade.php` (existing)

### Components
- `resources/views/components/hitung-gaji/form.blade.php` (new)
- `resources/views/components/hitung-gaji/field-with-adjustment.blade.php` (new)
- `resources/views/components/hitung-gaji/show.blade.php` (new)
- `resources/views/components/hitung-gaji/table.blade.php` (updated)

### Export/Import
- `app/Exports/HitungGajiExport.php` (new)
- `app/Exports/HitungGajiTemplateExport.php` (new)
- `app/Imports/HitungGajiImport.php` (new)

### Seeders
- `database/seeders/HitungGajiSeeder.php` (new)
- `database/seeders/AcuanGajiSeeder.php` (new)

### Routes
- `routes/web.php` (updated)

## ✅ Status: COMPLETE

All features implemented and tested:
- ✅ Database structure
- ✅ Model with auto-calculation
- ✅ Controller with all CRUD operations
- ✅ Views (index, create, edit, show)
- ✅ Components (form, show, table, field-with-adjustment)
- ✅ Import/Export functionality
- ✅ Seeder for testing
- ✅ Routes configured
- ✅ Workflow (draft → preview → approved)
- ✅ Calculations (NKI, Absensi)
- ✅ Adjustments per field
- ✅ No errors or warnings

## 🎉 Next Steps

1. **Slip Gaji** - Generate from approved Hitung Gaji
2. **PDF Export** - For slip gaji
3. **Email Notification** - When approved
4. **Bulk Operations** - Approve multiple at once
5. **History/Audit Log** - Track changes

---

**Last Updated**: 2026-02-24
**Status**: ✅ COMPLETE & WORKING
**Commit**: 317db4e
