# Fleksibilitas Slip Gaji untuk Semua Status Pegawai

## Overview
Sistem slip gaji harus fleksibel untuk semua jenis status pegawai, tidak hanya Kontrak. Setiap status pegawai (Harian, OJT, Magang, Kontrak) harus bisa mendapatkan slip gaji/kuitansi income mereka.

## Status Pegawai dan Perhitungan Gaji

### 1. Kontrak (Normal)
**Perhitungan**: Gaji bulanan penuh
- Gaji Pokok
- Tunjangan Operasional
- BPJS (Kesehatan, Ketenagakerjaan, Kecelakaan Kerja)
- Potongan (Koperasi, Absensi, Kasbon)
- NKI (Tunjangan Prestasi)

**Periode**: Bulanan (1 bulan penuh)

### 2. Harian
**Perhitungan**: Gaji per hari kerja
- Gaji Pokok ÷ Jumlah Hari Bulan × Hari Kerja
- Tunjangan Operasional (proporsional)
- BPJS (optional, tergantung kebijakan)
- Potongan Kasbon (jika ada)

**Periode**: Bisa mingguan atau bulanan
**Contoh**: 
- Gaji Pokok: Rp 5.000.000/bulan
- Hari Kerja: 20 hari
- Gaji per hari: Rp 250.000
- Total (20 hari): Rp 5.000.000

### 3. OJT (On Job Training)
**Perhitungan**: Gaji training/magang
- Uang Saku/Training Allowance (fixed amount)
- Tunjangan Transport (optional)
- Tunjangan Makan (optional)
- Tidak ada BPJS
- Tidak ada potongan

**Periode**: Bulanan atau sesuai durasi training
**Contoh**:
- Uang Saku: Rp 2.000.000/bulan
- Transport: Rp 500.000/bulan
- Total: Rp 2.500.000/bulan

### 4. Magang
**Perhitungan**: Uang saku magang
- Uang Saku (fixed amount, lebih kecil dari OJT)
- Tunjangan Transport (optional)
- Tidak ada BPJS
- Tidak ada potongan

**Periode**: Bulanan atau sesuai durasi magang
**Contoh**:
- Uang Saku: Rp 1.500.000/bulan
- Transport: Rp 300.000/bulan
- Total: Rp 1.800.000/bulan

## Implementasi di Sistem

### Database Schema
Tabel `pengaturan_gaji` sudah support semua status pegawai:
- `jenis_karyawan`: Konsultan, Organik, Teknisi, Borongan
- `status_pegawai`: Harian, OJT, Kontrak, Magang (dari SystemSetting)

### Generate Acuan Gaji
Saat generate acuan gaji, sistem harus:
1. Cek status_pegawai dari karyawan
2. Ambil pengaturan gaji sesuai jenis_karyawan + jabatan + lokasi_kerja
3. Hitung gaji sesuai status_pegawai:
   - **Kontrak**: Gaji penuh
   - **Harian**: Gaji × (hari_kerja / hari_bulan)
   - **OJT**: Uang saku training
   - **Magang**: Uang saku magang

### Slip Gaji
Slip gaji harus menampilkan:
- Nama Karyawan
- Status Pegawai (Kontrak/Harian/OJT/Magang)
- Periode
- Rincian Pendapatan (sesuai status)
- Rincian Potongan (jika ada)
- Total Gaji Bersih

### Contoh Slip Gaji

#### Slip Gaji - Kontrak
```
SLIP GAJI
Nama: Budi Santoso
Status: Kontrak
Periode: Februari 2026

PENDAPATAN:
- Gaji Pokok: Rp 15.000.000
- Tunjangan Operasional: Rp 3.000.000
- Tunjangan Prestasi (NKI): Rp 2.000.000
- BPJS Total: Rp 1.000.000
Total Pendapatan: Rp 21.000.000

POTONGAN:
- Potongan Koperasi: Rp 100.000
- Potongan Absensi: Rp 0
- Potongan Kasbon: Rp 0
Total Potongan: Rp 100.000

GAJI BERSIH: Rp 20.900.000
```

#### Slip Gaji - Harian
```
SLIP GAJI
Nama: Gunawan Wijaya
Status: Harian
Periode: Februari 2026

PENDAPATAN:
- Gaji Pokok (20 hari): Rp 5.000.000
- Tunjangan Operasional: Rp 800.000
Total Pendapatan: Rp 5.800.000

POTONGAN:
- Potongan Kasbon: Rp 500.000
Total Potongan: Rp 500.000

GAJI BERSIH: Rp 5.300.000

Keterangan: Gaji dihitung berdasarkan 20 hari kerja dari 28 hari
```

#### Slip Gaji - OJT
```
SLIP GAJI
Nama: Eko Prasetyo
Status: OJT (On Job Training)
Periode: Februari 2026

PENDAPATAN:
- Uang Saku Training: Rp 2.000.000
- Tunjangan Transport: Rp 500.000
Total Pendapatan: Rp 2.500.000

POTONGAN:
- Tidak ada potongan
Total Potongan: Rp 0

GAJI BERSIH: Rp 2.500.000

Keterangan: Periode training bulan ke-2 dari 3 bulan
```

#### Slip Gaji - Magang
```
SLIP GAJI
Nama: Fitri Handayani
Status: Magang
Periode: Februari 2026

PENDAPATAN:
- Uang Saku Magang: Rp 1.500.000
- Tunjangan Transport: Rp 300.000
Total Pendapatan: Rp 1.800.000

POTONGAN:
- Tidak ada potongan
Total Potongan: Rp 0

GAJI BERSIH: Rp 1.800.000

Keterangan: Periode magang bulan ke-1 dari 3 bulan
```

## Action Items

### 1. Update Pengaturan Gaji
Buat pengaturan gaji untuk setiap status pegawai:
- Kontrak: Gaji penuh dengan BPJS
- Harian: Gaji harian tanpa BPJS
- OJT: Uang saku training
- Magang: Uang saku magang

### 2. Update Generate Acuan Gaji
Tambah logic untuk handle berbagai status pegawai:
```php
switch ($karyawan->status_pegawai) {
    case 'Kontrak':
        // Gaji penuh
        break;
    case 'Harian':
        // Gaji proporsional berdasarkan hari kerja
        break;
    case 'OJT':
        // Uang saku training
        break;
    case 'Magang':
        // Uang saku magang
        break;
}
```

### 3. Update Slip Gaji View
Tampilkan informasi yang relevan sesuai status pegawai:
- Kontrak: Tampilkan semua komponen
- Harian: Tampilkan hari kerja dan perhitungan proporsional
- OJT/Magang: Tampilkan uang saku dan keterangan periode

### 4. Testing
Test generate dan slip gaji untuk semua status pegawai:
- ✅ Kontrak
- ✅ Harian
- ✅ OJT
- ✅ Magang

## Notes
- Semua karyawan berhak mendapat slip gaji/kuitansi income
- Format slip gaji bisa berbeda sesuai status pegawai
- Perhitungan gaji harus jelas dan transparan
- Keterangan tambahan penting untuk status Harian, OJT, Magang
