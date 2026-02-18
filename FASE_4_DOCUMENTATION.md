# FASE 4: Payroll System - Complete Implementation ✅

## Overview
Sistem payroll lengkap sesuai spesifikasi dengan alur: Pengaturan Gaji → Acuan Gaji → Hitung Gaji → Preview → Slip Gaji.

## Database Schema

### 1. Master Data Tables

#### `master_wilayah`
Wilayah kerja untuk membedakan struktur gaji berdasarkan lokasi.
```sql
- id
- kode (unique) - CJ, EJ, WJ
- nama - Central Java, East Java, West Java
- keterangan
- is_active
- timestamps
```

#### `master_status_pegawai`
Status pegawai (Harian, OJT, Kontrak) dengan durasi dan aturan NKI.
```sql
- id
- nama - Harian, OJT, Kontrak
- durasi_hari - 14 (harian), 90 (OJT), null (kontrak)
- keterangan
- gunakan_nki - false (harian/OJT), true (kontrak)
- is_active
- order
- timestamps
```

#### `komponen_gaji`
Master komponen gaji (pendapatan & pengeluaran).
```sql
- id
- nama - Gaji Pokok, BPJS Kesehatan, dll
- kode (unique) - gaji_pokok, bpjs_kesehatan
- tipe - pendapatan, pengeluaran
- kategori - gaji, bpjs, tunjangan, benefit, potongan
- deskripsi
- is_system - cannot be deleted
- is_active
- order
- timestamps
```

### 2. Pengaturan Gaji (Salary Configuration)

#### `pengaturan_gaji`
Pusat kontrol untuk patokan gaji per jenis karyawan, jabatan, dan wilayah.
```sql
- id
- jenis_karyawan - Konsultan, Organik, Teknisi, Borongan
- jabatan
- wilayah_id (foreign key)

PENDAPATAN:
- gaji_pokok
- tunjangan_operasional
- tunjangan_prestasi
- tunjangan_konjungtur
- benefit_ibadah
- benefit_komunikasi
- benefit_operasional

BPJS (Pendapatan):
- bpjs_kesehatan
- bpjs_kecelakaan_kerja
- bpjs_kematian
- bpjs_jht
- bpjs_jp

POTONGAN:
- potongan_koperasi (default 100000)

CALCULATED:
- net_gaji (gaji + tunjangan)
- total_bpjs (sum BPJS)
- nett (net_gaji + total_bpjs)

- is_active
- catatan
- timestamps
- unique(jenis_karyawan, jabatan, wilayah_id)
```

### 3. Acuan Gaji (Salary Reference)

#### `acuan_gaji`
Acuan gaji per karyawan per periode, ditarik dari pengaturan gaji.
```sql
- id
- karyawan_id (foreign key)
- pengaturan_gaji_id (foreign key)
- periode - YYYY-MM

PENDAPATAN:
- gaji_pokok
- tunjangan_prestasi
- tunjangan_konjungtur
- benefit_ibadah
- benefit_komunikasi
- benefit_operasional
- reward
- bpjs_kesehatan
- bpjs_kecelakaan_kerja
- bpjs_kematian
- bpjs_jht
- bpjs_jp

PENGELUARAN:
- potongan_bpjs_kesehatan
- potongan_bpjs_kecelakaan
- potongan_bpjs_kematian
- potongan_bpjs_jht
- potongan_bpjs_jp
- potongan_koperasi
- potongan_tabungan_koperasi
- potongan_kasbon
- potongan_umroh
- potongan_kurban
- potongan_mutabaah
- potongan_absensi
- potongan_kehadiran

TOTALS:
- total_pendapatan
- total_pengeluaran
- take_home_pay

- catatan
- timestamps
- unique(karyawan_id, periode)
```

### 4. Hitung Gaji (Salary Calculation)

#### `hitung_gaji`
Area fleksibilitas untuk penyesuaian gaji tanpa merusak acuan.
```sql
- id
- acuan_gaji_id (foreign key)
- karyawan_id (foreign key)
- periode - YYYY-MM

DATA ACUAN (Read-only):
- pendapatan_acuan (JSON)
- pengeluaran_acuan (JSON)

PENYESUAIAN (Adjustments):
- penyesuaian_pendapatan (JSON) - [{komponen, nominal, catatan}]
- penyesuaian_pengeluaran (JSON) - [{komponen, nominal, catatan}]

CALCULATIONS:
- total_pendapatan_acuan
- total_penyesuaian_pendapatan
- total_pendapatan_akhir
- total_pengeluaran_acuan
- total_penyesuaian_pengeluaran
- total_pengeluaran_akhir
- take_home_pay

STATUS:
- status - draft, preview, approved
- approved_at
- approved_by (foreign key)

- catatan_umum
- timestamps
- unique(karyawan_id, periode)
```

### 5. Slip Gaji (Salary Slip)

#### `slip_gaji`
Output final read-only untuk karyawan.
```sql
- id
- hitung_gaji_id (foreign key)
- karyawan_id (foreign key)
- periode - YYYY-MM
- nomor_slip (unique) - SG-YYYYMM-XXXX

KARYAWAN INFO (Snapshot):
- nama_karyawan
- jabatan
- status_pegawai
- tanggal_mulai_bekerja
- masa_kerja

GAJI DETAILS (Snapshot):
- detail_pendapatan (JSON)
- detail_pengeluaran (JSON)
- total_pendapatan
- total_pengeluaran
- take_home_pay

METADATA:
- generated_at
- generated_by (foreign key)
- is_sent
- sent_at

- catatan
- timestamps
- unique(karyawan_id, periode)
```

### 6. Supporting Tables

#### `nki` (Nilai Kemampuan & Kontribusi)
Penilaian karyawan untuk tunjangan prestasi.
```sql
- id
- karyawan_id (foreign key)
- periode - YYYY-MM

COMPONENTS (0-10):
- kemampuan (20%)
- konstribusi (20%)
- kedisiplinan (40%)
- lainnya (20%)

CALCULATED:
- nilai_nki (weighted average)
- persentase_prestasi (70%, 80%, 100%)

- catatan
- dinilai_oleh (foreign key)
- timestamps
- unique(karyawan_id, periode)
```

**Rumus NKI:**
- NKI ≥ 8.5 → 100%
- NKI ≤ 8.0 → 80%
- NKI ≤ 7.0 → 70%

#### `absensi`
Data kehadiran karyawan per periode.
```sql
- id
- karyawan_id (foreign key)
- periode - YYYY-MM

ATTENDANCE:
- hadir
- onsite
- absence (alpha)
- idle_rest
- izin_sakit_cuti
- tanpa_keterangan

CALCULATED:
- total_hari_kerja
- potongan_absensi

- catatan
- timestamps
- unique(karyawan_id, periode)
```

**Rumus Potongan Absensi:**
```
(Absence + Tanpa Keterangan) / Jumlah Hari Bulan × (Gaji Pokok + Tunjangan Prestasi + Operasional)
```

#### `kasbon`
Pinjaman karyawan dengan metode pembayaran.
```sql
- id
- karyawan_id (foreign key)
- nomor_kasbon (unique) - KB-YYYYMMDD-XXXX
- tanggal_pengajuan
- deskripsi
- nominal

PAYMENT:
- metode_pembayaran - langsung, cicilan
- jumlah_cicilan
- nominal_cicilan

STATUS:
- status - pending, approved, rejected, lunas
- sisa_hutang

APPROVAL:
- approved_by (foreign key)
- approved_at
- catatan_approval

- timestamps
```

#### `kasbon_cicilan`
History pembayaran cicilan kasbon.
```sql
- id
- kasbon_id (foreign key)
- periode - YYYY-MM (periode potong gaji)
- nominal
- tanggal_bayar
- catatan
- timestamps
```

## Alur Sistem Payroll

### 1. Setup (One-time)
```
Master Wilayah → Master Status Pegawai → Komponen Gaji → Pengaturan Gaji
```

### 2. Monthly Process
```
1. Input Data Pendukung:
   - NKI (untuk karyawan kontrak)
   - Absensi
   - Kasbon (jika ada)

2. Generate Acuan Gaji:
   - Sistem otomatis pull dari Pengaturan Gaji
   - Sesuaikan dengan jenis karyawan & status pegawai

3. Hitung Gaji:
   - Copy dari Acuan Gaji
   - Tambah penyesuaian (bonus, denda, dll)
   - WAJIB ada catatan untuk setiap penyesuaian

4. Preview:
   - Review total pendapatan & pengeluaran
   - Cek take home pay
   - Jika salah → kembali ke Hitung Gaji

5. Approve & Generate Slip:
   - Approve hitung gaji
   - Generate slip gaji (read-only)
   - Kirim ke karyawan
```

## Rumus Perhitungan

### Take Home Pay
```
Take Home Pay = Total Pendapatan - Total Pengeluaran
```

### Total Pendapatan
```
Gaji Pokok
+ Tunjangan (Prestasi, Konjungtur, Operasional)
+ Benefit (Ibadah, Komunikasi, Operasional)
+ BPJS (Kesehatan, Kecelakaan Kerja, Kematian, JHT, JP)
+ Reward
+ Penyesuaian Pendapatan
```

### Total Pengeluaran
```
Potongan BPJS (Kesehatan, Kecelakaan, Kematian, JHT, JP)
+ Potongan Koperasi
+ Potongan Tabungan Koperasi
+ Kasbon
+ Umroh
+ Kurban
+ Mutabaah
+ Potongan Absensi
+ Potongan Kehadiran
+ Penyesuaian Pengeluaran
```

### Tunjangan Prestasi (dengan NKI)
```
Tunjangan Prestasi = Nilai Acuan Prestasi × Persentase NKI
```

### Potongan Absensi
```
Potongan = (Absence + Tanpa Keterangan) / Jumlah Hari Bulan × (Gaji Pokok + Tunjangan Prestasi + Operasional)
```

## Status Pegawai Logic

### Harian (14 hari pertama)
- Upah harian
- Tidak menggunakan NKI
- Pendapatan: Hadir × Tarif Harian + Reward

### OJT (3 bulan setelah harian)
- Gaji tetap (contoh: 3.1 jt)
- Tidak menggunakan NKI

### Kontrak (setelah OJT)
- Menggunakan struktur gaji penuh
- Menggunakan NKI untuk tunjangan prestasi
- Status berubah otomatis berdasarkan masa_kerja

## Prinsip Desain

### ✅ Aman dari Salah Input
- Acuan gaji read-only di hitung gaji
- Penyesuaian wajib ada catatan
- Preview sebelum approve

### ✅ Audit-Friendly
- Semua perubahan tercatat
- Slip gaji immutable (read-only)
- History lengkap

### ✅ Fleksibel Ekstrem
- Penyesuaian tanpa batas
- Komponen gaji dinamis
- Catatan untuk transparansi

### ✅ Struktur Rapi
- Separation of concerns
- Clear data flow
- Modular design

## Files Created

### Migrations (10 tables):
1. `master_wilayah` - Wilayah kerja
2. `master_status_pegawai` - Status pegawai
3. `komponen_gaji` - Master komponen
4. `pengaturan_gaji` - Salary configuration
5. `acuan_gaji` - Salary reference
6. `hitung_gaji` - Salary calculation
7. `slip_gaji` - Salary slip
8. `nki` - Performance rating
9. `absensi` - Attendance
10. `kasbon` + `kasbon_cicilan` - Loan management

### Next Steps (Controllers & Views):
- PengaturanGajiController
- AcuanGajiController
- HitungGajiController
- SlipGajiController
- NKIController
- AbsensiController
- KasbonController
- Payroll Dashboard
- Reports & Analytics

## Summary

FASE 4 memberikan sistem payroll lengkap dengan:
- ✅ 10 tabel database terstruktur
- ✅ Alur yang jelas dan aman
- ✅ Fleksibilitas tinggi
- ✅ Audit trail lengkap
- ✅ Support multiple jenis karyawan
- ✅ Automatic calculations
- ✅ Read-only slip gaji

Database schema sudah siap, tinggal implementasi controllers dan views!
