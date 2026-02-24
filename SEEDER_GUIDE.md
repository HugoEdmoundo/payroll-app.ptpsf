# Database Seeder Guide

## Overview
Seeders telah dibuat dengan data yang sinkron dengan `SystemSettingSeeder`. Semua data menggunakan nilai yang sama dari system settings untuk konsistensi.

## Seeders yang Tersedia

### 1. SystemSettingSeeder ✅ (JANGAN DIUBAH)
**Status**: Default, sudah sempurna
**Fungsi**: Master data untuk dropdown options
**Data**:
- Jenis Karyawan: Konsultan, Organik, Teknisi, Borongan
- Status Pegawai: Harian, OJT, Kontrak, Magang
- Lokasi Kerja: Central Java, East Java, West Java, Bali
- Jabatan: Finance, Manager, Engineers, Installers, Team Leaders
- Banks: BCA, Mandiri, BNI, BRI, CIMB Niaga, BSI
- Status Perkawinan: Belum Kawin, Kawin, Cerai Hidup, Cerai Mati
- Status Karyawan: Active, Non-Active, Resign

### 2. RoleSeeder & PermissionSeeder
**Fungsi**: Setup roles dan permissions untuk authorization system
**Data**:
- Roles: Superadmin, User, Manager, Staff
- Permissions: Semua module permissions (view, create, edit, delete, import, export)

### 3. UserSeeder
**Fungsi**: Create default users
**Data**:
- Superadmin user
- Regular users dengan berbagai roles

### 4. KaryawanSeeder ⭐ NEW
**Fungsi**: Create sample employees
**Data**: 6 karyawan dengan variasi:
- Budi Santoso (Manager, Organik, Central Java)
- Ahmad Fauzi (Senior Engineer, Konsultan, East Java)
- Dewi Lestari (Finance, Organik, Central Java)
- Eko Prasetyo (Junior Engineer, Teknisi, West Java)
- Fitri Handayani (Senior Installer, Teknisi, Bali)
- Gunawan Wijaya (Team Leader, Borongan, Central Java)

### 5. PengaturanGajiSeeder ⭐ NEW
**Fungsi**: Create salary configurations
**Data**: 10 konfigurasi gaji untuk berbagai kombinasi:
- Organik: Manager, Finance
- Konsultan: Senior Engineer, Project Manager
- Teknisi: Junior Engineer, Senior Installer, Junior Installer
- Borongan: Team Leader (junior & senior)

### 6. KomponenGajiSeeder ⭐ NEW
**Fungsi**: Create salary components (NKI, Absensi, Kasbon)
**Data**:
- NKI: Untuk semua karyawan aktif dengan nilai realistis (7.0-10.0)
- Absensi: Untuk semua karyawan dengan data kehadiran
- Kasbon: Untuk 30% karyawan (random) dengan metode Langsung/Cicilan

### 7. AddMissingPermissionsSeeder
**Fungsi**: Add additional permissions yang kurang
**Data**:
- acuan_gaji.generate
- kasbon.approve
- kasbon.reject

## Cara Menggunakan

### Fresh Install (Recommended)
```bash
# 1. Reset database
php artisan migrate:fresh

# 2. Run all seeders
php artisan db:seed

# 3. Done! Database siap digunakan
```

### Selective Seeding
```bash
# Seed specific seeder
php artisan db:seed --class=KaryawanSeeder
php artisan db:seed --class=PengaturanGajiSeeder
php artisan db:seed --class=KomponenGajiSeeder
```

### Production Deployment
```bash
# Di VPS setelah deploy
cd /opt/just-atesting

# Fresh install
php artisan migrate:fresh --seed

# Atau step by step
php artisan migrate:fresh
php artisan db:seed
```

## Data Flow

```
SystemSettingSeeder (Master Data)
    ↓
RoleSeeder & PermissionSeeder (Auth System)
    ↓
UserSeeder (Users with Roles)
    ↓
KaryawanSeeder (6 Employees)
    ↓
PengaturanGajiSeeder (10 Salary Configs)
    ↓
KomponenGajiSeeder (NKI, Absensi, Kasbon)
    ↓
AddMissingPermissionsSeeder (Additional Permissions)
```

## Acuan Gaji

**PENTING**: Acuan Gaji TIDAK di-seed!

Acuan Gaji harus di-generate melalui aplikasi menggunakan fitur "Generate":
1. Login ke aplikasi
2. Buka menu "Acuan Gaji"
3. Klik button "Generate"
4. Pilih periode (bulan/tahun)
5. Pilih jenis karyawan (optional)
6. Klik "Generate"

Sistem akan otomatis:
- Ambil data dari Pengaturan Gaji
- Hitung komponen dari NKI, Absensi, Kasbon
- Generate Acuan Gaji untuk semua karyawan

## Verifikasi Data

Setelah seeding, cek data:

```bash
php artisan tinker
```

```php
// Cek jumlah data
echo "Karyawan: " . App\Models\Karyawan::count() . "\n";
echo "Pengaturan Gaji: " . App\Models\PengaturanGaji::count() . "\n";
echo "NKI: " . App\Models\NKI::count() . "\n";
echo "Absensi: " . App\Models\Absensi::count() . "\n";
echo "Kasbon: " . App\Models\Kasbon::count() . "\n";

// Expected output:
// Karyawan: 6
// Pengaturan Gaji: 10
// NKI: 6
// Absensi: 6
// Kasbon: ~2 (30% dari 6 = random 1-2)
```

## Troubleshooting

### Error: Foreign key constraint fails
**Solusi**: Pastikan run seeders dalam urutan yang benar (gunakan `php artisan db:seed`)

### Error: Duplicate entry
**Solusi**: Reset database dengan `php artisan migrate:fresh` lalu seed lagi

### Data tidak muncul
**Solusi**: 
1. Cek apakah seeder berhasil run: `php artisan db:seed --class=NamaSeeder`
2. Cek database langsung: `php artisan tinker` lalu query model

### Acuan Gaji kosong
**Solusi**: Ini normal! Generate via aplikasi, bukan via seeder.

## Notes

- SystemSettingSeeder adalah sumber kebenaran untuk semua master data
- Semua seeder lain harus sinkron dengan SystemSettingSeeder
- Jangan ubah SystemSettingSeeder tanpa update seeder lainnya
- Acuan Gaji di-generate via aplikasi, bukan di-seed
- Seeder bisa di-run berkali-kali dengan `migrate:fresh --seed`
