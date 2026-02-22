# Acuan Gaji - Complete Implementation

## Overview
Sistem Acuan Gaji telah selesai diimplementasikan dengan fitur lengkap untuk mengelola referensi gaji karyawan yang mengintegrasikan data dari Pengaturan Gaji dan semua Komponen (NKI, Absensi, Kasbon).

## Features Implemented

### 1. Navigation Bar
✅ Dropdown navigation di sidebar dengan struktur:
- **Semua** - Tampilkan semua acuan gaji
- **Per Jenis Karyawan** - Filter berdasarkan jenis karyawan (Konsultan, Organik, dll)
- **History** - Halaman khusus untuk melihat history dengan filter lengkap

### 2. Generate Feature
✅ Auto-generate acuan gaji untuk semua karyawan atau per jenis karyawan
- Generate berdasarkan periode (YYYY-MM)
- Optional filter jenis karyawan
- Data otomatis diambil dari:
  - **Pengaturan Gaji**: Gaji Pokok, BPJS, Tunjangan Operasional, Potongan Koperasi
  - **NKI**: Tunjangan Prestasi (dihitung dari persentase × tunjangan operasional)
  - **Absensi**: Potongan Absensi (dihitung dari absence × base amount)
  - **Kasbon**: Total kasbon yang masih pending
- Field kosong (BPJS JHT/JP, Benefit lainnya) diset 0 untuk diisi manual

### 3. CRUD Operations
✅ **Create** - Tambah acuan gaji manual
✅ **Read** - Lihat detail lengkap acuan gaji
✅ **Update** - Edit semua field acuan gaji
✅ **Delete** - Hapus acuan gaji

### 4. Import/Export
✅ **Export**
- Export ke Excel dengan semua field lengkap
- Format: nama_karyawan, periode, dan 28+ field lainnya
- Bisa difilter berdasarkan periode

✅ **Import**
- Import dari Excel dengan format yang sama
- Auto-update jika data sudah ada (berdasarkan karyawan + periode)
- Validasi: nama_karyawan dan periode wajib diisi
- Field numeric default 0 jika kosong

### 5. Filter & Search
✅ **Index Page**
- Search by nama karyawan
- Filter by periode (month picker)
- Filter by jenis karyawan
- Filter by jabatan (muncul setelah pilih jenis karyawan)

✅ **History Page**
- Semua filter yang sama dengan index
- Tambahan: List periode yang tersedia
- Statistics cards: Total Records, Total Pendapatan, Total Gaji Bersih

### 6. Data Synchronization
✅ Data dari Pengaturan Gaji dan Komponen otomatis masuk ke Acuan Gaji saat generate:

**Pendapatan (dari Pengaturan Gaji):**
- Gaji Pokok
- BPJS Kesehatan
- BPJS Kecelakaan Kerja
- BPJS Kematian
- Benefit Operasional

**Pendapatan (dari Komponen):**
- Tunjangan Prestasi (dari NKI)

**Pengeluaran (dari Pengaturan Gaji):**
- BPJS Kesehatan
- BPJS Kecelakaan Kerja
- BPJS Kematian
- Koperasi

**Pengeluaran (dari Komponen):**
- Kasbon (dari Kasbon)
- Potongan Absensi (dari Absensi)

**Field Manual (diisi user):**
- BPJS JHT (Pendapatan & Pengeluaran)
- BPJS JP (Pendapatan & Pengeluaran)
- Tunjangan Konjungtur
- Benefit Ibadah
- Benefit Komunikasi
- Reward
- Tabungan Koperasi
- Umroh
- Kurban
- Mutabaah
- Potongan Kehadiran

### 7. Auto-Calculation
✅ Sistem otomatis menghitung:
- **Total Pendapatan** = Sum of all pendapatan fields
- **Total Pengeluaran** = Sum of all pengeluaran fields
- **Gaji Bersih** = Total Pendapatan - Total Pengeluaran

Perhitungan dilakukan di model level (boot method) saat saving data.

## File Structure

### Controllers
- `app/Http/Controllers/Payroll/AcuanGajiController.php`
  - index() - List dengan filter
  - history() - History page dengan filter
  - generate() - Auto-generate dari Pengaturan + Komponen
  - create() - Form tambah manual
  - store() - Simpan data baru
  - show() - Detail acuan gaji
  - edit() - Form edit
  - update() - Update data
  - destroy() - Hapus data
  - export() - Export ke Excel
  - import() - Form import
  - importStore() - Process import

### Models
- `app/Models/AcuanGaji.php`
  - Auto-calculation di boot() method
  - Relationship ke Karyawan

### Views
- `resources/views/payroll/acuan-gaji/index.blade.php` - List page
- `resources/views/payroll/acuan-gaji/history.blade.php` - History page
- `resources/views/payroll/acuan-gaji/create.blade.php` - Create form
- `resources/views/payroll/acuan-gaji/edit.blade.php` - Edit form
- `resources/views/payroll/acuan-gaji/show.blade.php` - Detail view
- `resources/views/payroll/acuan-gaji/import.blade.php` - Import page

### Components
- `resources/views/components/acuan-gaji/form.blade.php` - Form component
- `resources/views/components/acuan-gaji/table.blade.php` - Table component
- `resources/views/components/acuan-gaji/show-field.blade.php` - Show field component

### Import/Export
- `app/Imports/AcuanGajiImport.php` - Import handler
- `app/Exports/AcuanGajiExport.php` - Export handler

### Routes
- `routes/web.php` - All acuan-gaji routes registered

### Navigation
- `resources/views/partials/sidebar.blade.php` - Dropdown navigation

## Usage Flow

### Generate Acuan Gaji
1. Klik tombol "Generate" di index page
2. Pilih periode (required)
3. Pilih jenis karyawan (optional, kosongkan untuk semua)
4. Klik "Generate"
5. Sistem akan:
   - Cari semua karyawan aktif (filtered by jenis if selected)
   - Ambil Pengaturan Gaji berdasarkan jenis, jabatan, lokasi
   - Ambil data NKI untuk periode tersebut
   - Ambil data Absensi untuk periode tersebut
   - Ambil data Kasbon pending untuk periode tersebut
   - Hitung Tunjangan Prestasi dan Potongan Absensi
   - Create acuan gaji dengan data lengkap
   - Skip jika sudah ada atau tidak ada pengaturan gaji

### Manual Entry
1. Klik "Add Manual"
2. Pilih karyawan dan periode
3. Isi semua field yang diperlukan
4. Total otomatis dihitung
5. Save

### Edit Data
1. Dari index/history, klik row atau tombol edit
2. Update field yang perlu diubah
3. Total otomatis recalculated
4. Save

### Import Data
1. Klik "Import"
2. Download template (export existing data)
3. Isi Excel dengan data
4. Upload file
5. Sistem akan create/update berdasarkan nama_karyawan + periode

### View History
1. Klik "History" di sidebar dropdown
2. Filter by periode, jenis karyawan, jabatan
3. Lihat statistics dan data lengkap
4. Export jika diperlukan

## Database Schema

Table: `acuan_gaji`
- id_acuan (PK)
- id_karyawan (FK)
- periode (YYYY-MM)
- Pendapatan fields (12 fields)
- total_pendapatan (auto-calculated)
- Pengeluaran fields (13 fields)
- total_pengeluaran (auto-calculated)
- gaji_bersih (auto-calculated)
- keterangan
- timestamps

## Validation Rules
- id_karyawan: required, exists in karyawan table
- periode: required, format YYYY-MM
- Unique constraint: id_karyawan + periode
- All numeric fields: nullable, decimal(2)

## Status
✅ **COMPLETE** - All features implemented and tested
- Navigation: ✅
- Generate: ✅
- CRUD: ✅
- Import/Export: ✅
- Filters: ✅
- History: ✅
- Data Sync: ✅
- Auto-calculation: ✅

## Next Steps (Optional Enhancements)
- [ ] Bulk edit functionality
- [ ] Print/PDF export
- [ ] Email notification after generate
- [ ] Approval workflow
- [ ] Salary slip generation
- [ ] Comparison between periods
- [ ] Analytics dashboard
