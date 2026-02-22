# Komponen & Acuan Gaji Implementation Progress

## Status: 🚧 IN PROGRESS (Database & Backend Complete, Views Pending)

### ✅ Completed

#### 1. Database Migrations
- ✅ `nki` table - NKI (Tunjangan Prestasi) dengan auto-calculation
- ✅ `absensi` table - Attendance tracking dengan auto-detect jumlah hari
- ✅ `kasbon` table - Loan management (Langsung & Cicilan)
- ✅ `acuan_gaji` table - Salary reference dengan pendapatan & pengeluaran lengkap

#### 2. Models dengan Auto-Calculation
- ✅ **NKI Model**:
  - Auto-calculate `nilai_nki` = Kemampuan(20%) + Kontribusi(20%) + Kedisiplinan(40%) + Lainnya(20%)
  - Auto-determine `persentase_tunjangan`: ≥8.5→100%, ≥8.0→80%, <8.0→70%
  
- ✅ **Absensi Model**:
  - Auto-detect `jumlah_hari_bulan` dari periode
  - Method `calculatePotongan()` untuk hitung potongan absensi
  
- ✅ **Kasbon Model**:
  - Auto-calculate `sisa_cicilan` untuk metode Cicilan
  - Auto-update `status_pembayaran` ke 'Lunas' jika cicilan selesai
  - Attribute `nominal_per_cicilan` untuk tampilan
  
- ✅ **AcuanGaji Model**:
  - Auto-calculate `total_pendapatan` (sum semua pendapatan)
  - Auto-calculate `total_pengeluaran` (sum semua pengeluaran)
  - Auto-calculate `gaji_bersih` = total_pendapatan - total_pengeluaran

#### 3. Controllers
- ✅ NKIController - Full CRUD + export/import placeholders
- ✅ AbsensiController - Needs implementation
- ✅ KasbonController - Needs implementation + bayar cicilan method
- ✅ AcuanGajiController - Needs implementation

#### 4. Routes
- ✅ All routes registered for NKI, Absensi, Kasbon, Acuan Gaji
- ✅ Export/Import routes included
- ✅ Special route for `bayar-cicilan` in Kasbon

#### 5. Navigation
- ✅ Sidebar updated with "Komponen" dropdown menu
- ✅ Sub-menu: NKI, Absensi, Kasbon
- ✅ "Acuan Gaji" as separate menu item
- ✅ Active state highlighting

#### 6. Directory Structure
- ✅ Views directories created:
  - `resources/views/payroll/nki/`
  - `resources/views/payroll/absensi/`
  - `resources/views/payroll/kasbon/`
  - `resources/views/payroll/acuan-gaji/`
- ✅ Components directories created:
  - `resources/views/components/nki/`
  - `resources/views/components/absensi/`
  - `resources/views/components/kasbon/`
  - `resources/views/components/acuan-gaji/`

### 🚧 Pending Implementation

#### 1. Controllers Implementation
- ⏳ AbsensiController - Full CRUD logic
- ⏳ KasbonController - Full CRUD logic + bayar cicilan
- ⏳ AcuanGajiController - Full CRUD logic + generate from pengaturan gaji

#### 2. Views (All modules need complete views)

**NKI Views:**
- ⏳ index.blade.php - List with filter by periode
- ⏳ create.blade.php - Form with karyawan dropdown
- ⏳ edit.blade.php - Edit form
- ⏳ show.blade.php - Detail view with calculation breakdown
- ⏳ import.blade.php - Import form

**NKI Components:**
- ⏳ form.blade.php - Reusable form (kemampuan, kontribusi, kedisiplinan, lainnya)
- ⏳ table.blade.php - Table with NKI value and percentage
- ⏳ show.blade.php - Detail display with visual indicators

**Absensi Views:**
- ⏳ index.blade.php - List with filter by periode
- ⏳ create.blade.php - Form with attendance fields
- ⏳ edit.blade.php - Edit form
- ⏳ show.blade.php - Detail view
- ⏳ import.blade.php - Import form

**Absensi Components:**
- ⏳ form.blade.php - Form with all attendance fields
- ⏳ table.blade.php - Table with attendance summary
- ⏳ show.blade.php - Detail display

**Kasbon Views:**
- ⏳ index.blade.php - List with filter by status & metode
- ⏳ create.blade.php - Form with metode pembayaran selection
- ⏳ edit.blade.php - Edit form
- ⏳ show.blade.php - Detail view with cicilan history
- ⏳ No import (as per requirements)

**Kasbon Components:**
- ⏳ form.blade.php - Form with conditional cicilan fields
- ⏳ table.blade.php - Table with status badges
- ⏳ show.blade.php - Detail with payment history
- ⏳ cicilan-history.blade.php - Cicilan payment history table

**Acuan Gaji Views:**
- ⏳ index.blade.php - List with filter by periode
- ⏳ create.blade.php - Complex form with pendapatan & pengeluaran
- ⏳ edit.blade.php - Edit form
- ⏳ show.blade.php - Detailed salary slip view

**Acuan Gaji Components:**
- ⏳ form.blade.php - Large form with all income/deduction fields
- ⏳ table.blade.php - Summary table
- ⏳ show.blade.php - Detailed salary breakdown
- ⏳ pendapatan-section.blade.php - Income section
- ⏳ pengeluaran-section.blade.php - Deduction section

#### 3. Export/Import Functionality
- ⏳ NKI Export class
- ⏳ NKI Import class
- ⏳ Absensi Export class
- ⏳ Absensi Import class
- ⏳ Kasbon Export class (no import as per requirements)
- ⏳ Acuan Gaji Export class (no import as per requirements)

#### 4. Permissions
- ⏳ Add permissions for NKI, Absensi, Kasbon, Acuan Gaji to PermissionSeeder
- ⏳ Update controllers with permission checks

#### 5. Seeders
- ⏳ Sample data for NKI
- ⏳ Sample data for Absensi
- ⏳ Sample data for Kasbon
- ⏳ Sample data for Acuan Gaji

### Database Schema Summary

#### NKI Table
```
- id_nki (PK)
- id_karyawan (FK)
- periode (YYYY-MM)
- kemampuan (0-10)
- kontribusi (0-10)
- kedisiplinan (0-10)
- lainnya (0-10)
- nilai_nki (auto-calculated)
- persentase_tunjangan (70/80/100, auto-determined)
- keterangan
- Unique: (id_karyawan, periode)
```

#### Absensi Table
```
- id_absensi (PK)
- id_karyawan (FK)
- periode (YYYY-MM)
- jumlah_hari_bulan (auto-detected)
- hadir
- on_site
- absence
- idle_rest
- izin_sakit_cuti
- tanpa_keterangan
- potongan_absensi (calculated in acuan gaji)
- keterangan
- Unique: (id_karyawan, periode)
```

#### Kasbon Table
```
- id_kasbon (PK)
- id_karyawan (FK)
- periode (YYYY-MM)
- tanggal_pengajuan
- deskripsi
- nominal
- metode_pembayaran (Langsung/Cicilan)
- status_pembayaran (Pending/Lunas)
- jumlah_cicilan (for Cicilan)
- cicilan_terbayar (for Cicilan)
- sisa_cicilan (auto-calculated)
- keterangan
```

#### Acuan Gaji Table
```
PENDAPATAN:
- gaji_pokok
- bpjs_kesehatan_pendapatan
- bpjs_kecelakaan_kerja_pendapatan
- bpjs_kematian_pendapatan
- bpjs_jht_pendapatan
- bpjs_jp_pendapatan
- tunjangan_prestasi
- tunjangan_konjungtur
- benefit_ibadah
- benefit_komunikasi
- benefit_operasional
- reward
- total_pendapatan (auto-calculated)

PENGELUARAN:
- bpjs_kesehatan_pengeluaran
- bpjs_kecelakaan_kerja_pengeluaran
- bpjs_kematian_pengeluaran
- bpjs_jht_pengeluaran
- bpjs_jp_pengeluaran
- tabungan_koperasi
- koperasi
- kasbon
- umroh
- kurban
- mutabaah
- potongan_absensi
- potongan_kehadiran
- total_pengeluaran (auto-calculated)
- gaji_bersih (auto-calculated)

- Unique: (id_karyawan, periode)
```

### Next Steps Priority

1. **Implement remaining controllers** (AbsensiController, KasbonController, AcuanGajiController)
2. **Create all views and components** following the pattern from Karyawan and Pengaturan Gaji
3. **Add permissions** to PermissionSeeder
4. **Create Export/Import classes** for NKI and Absensi
5. **Add sample seeders** for testing
6. **Test all CRUD operations**
7. **Test auto-calculations**
8. **Test kasbon cicilan payment**

### Important Notes

- All numeric fields use `type="number"` inputs
- All auto-calculations happen in model boot() method
- Components follow the same structure as Karyawan module
- Kasbon has special "bayar cicilan" functionality
- Acuan Gaji is the most complex with many fields
- Period format is YYYY-MM throughout all modules
- Unique constraints prevent duplicate entries per employee per period
