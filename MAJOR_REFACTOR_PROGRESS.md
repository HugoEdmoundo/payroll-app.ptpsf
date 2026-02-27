# MAJOR REFACTOR - BPJS & PENGATURAN GAJI STATUS PEGAWAI

## PERUBAHAN BESAR:

### 1. BPJS
- ❌ HAPUS: Semua BPJS Pengeluaran
- ✅ KEEP: BPJS Pendapatan (hanya untuk Kontrak)
- Fields: bpjs_kesehatan, bpjs_kecelakaan_kerja, bpjs_kematian, bpjs_jht, bpjs_jp

### 2. Koperasi
- ✅ Untuk: Kontrak dan OJT saja
- ❌ Tidak untuk: Harian

### 3. PengaturanBpjsKoperasi
- Structure: status_pegawai (Kontrak/OJT) + BPJS fields + koperasi
- Unique: status_pegawai
- Hapus: jenis_karyawan (tidak perlu lagi)

### 4. PengaturanGajiStatusPegawai
- Structure: status_pegawai (Harian/OJT) + lokasi_kerja + gaji_pokok
- Unique: status_pegawai + lokasi_kerja
- Hapus: jabatan (berlaku untuk semua jabatan & jenis karyawan)

## FILES UPDATED:

### Migrations ✅
- [x] 2026_02_27_150000_recreate_pengaturan_bpjs_koperasi_table.php (NEW)
- [x] 2026_02_27_150001_recreate_pengaturan_gaji_status_pegawai_table.php (NEW)
- [x] 2026_02_20_132410_create_acuan_gaji_table.php (UPDATED - removed BPJS pengeluaran)
- [x] 2026_02_24_160221_recreate_hitung_gaji_table_with_all_fields.php (UPDATED - removed BPJS pengeluaran)

### Models ✅
- [x] app/Models/PengaturanBpjsKoperasi.php (RECREATED)
- [x] app/Models/PengaturanGajiStatusPegawai.php (RECREATED)
- [x] app/Models/AcuanGaji.php (RECREATED)
- [x] app/Models/HitungGaji.php (RECREATED)

### Controllers (TODO)
- [ ] app/Http/Controllers/Payroll/PengaturanBpjsKoperasiController.php
- [ ] app/Http/Controllers/Payroll/PengaturanGajiController.php (statusPegawai methods)
- [ ] app/Http/Controllers/Payroll/AcuanGajiController.php (generate method - FIX 500 ERROR!)
- [ ] app/Http/Controllers/Payroll/HitungGajiController.php

### Seeders (TODO)
- [ ] database/seeders/PengaturanBpjsKoperasiSeeder.php
- [ ] database/seeders/PengaturanGajiSeeder.php

### Exports (TODO)
- [ ] app/Exports/PengaturanBpjsKoperasiExport.php
- [ ] app/Exports/AcuanGajiExport.php
- [ ] app/Exports/HitungGajiExport.php

### Views (TODO)
- [ ] resources/views/payroll/pengaturan-bpjs-koperasi/*.blade.php (ALL)
- [ ] resources/views/payroll/pengaturan-gaji/status-pegawai/*.blade.php (ALL)
- [ ] resources/views/payroll/slip-gaji/pdf.blade.php
- [ ] resources/views/components/slip-gaji/modal-slip.blade.php

## NEXT STEPS:

1. Commit migrations & models yang sudah diupdate
2. Update controllers (fix 500 error di generate acuan gaji)
3. Update seeders
4. Update exports
5. Update all views
6. Test everything!

## CRITICAL FIX NEEDED:
- **500 Error di generate acuan gaji** - Kemungkinan karena:
  - Field mismatch antara controller dan database
  - PengaturanBpjsKoperasi query error
  - Missing null checks

Status: IN PROGRESS - Migrations & Models DONE
