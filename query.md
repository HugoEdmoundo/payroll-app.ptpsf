# Database Schema - Payroll Application PT. PSF

## ERD SQL Queries

### 1. Users & Authentication Tables

```sql
-- Users Table
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL,
  `address` TEXT NULL,
  `join_date` DATE NULL,
  `position` VARCHAR(255) NULL,
  `profile_photo` VARCHAR(255) NULL,
  `role_id` BIGINT UNSIGNED NOT NULL DEFAULT 2,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  INDEX `users_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Roles Table
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `description` VARCHAR(255) NULL,
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `is_superadmin` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permissions Table
CREATE TABLE `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `key` VARCHAR(255) NOT NULL UNIQUE,
  `group` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `action_type` VARCHAR(255) NULL,
  `module` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role Permissions (Pivot Table)
CREATE TABLE `role_permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `role_id` BIGINT UNSIGNED NOT NULL,
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Permissions (Override)
CREATE TABLE `user_permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `is_granted` TINYINT(1) NOT NULL DEFAULT 1,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `user_permissions_user_id_permission_id_unique` (`user_id`, `permission_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. System Tables

```sql
-- System Settings Table
CREATE TABLE `system_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(255) NOT NULL UNIQUE,
  `value` TEXT NULL,
  `type` VARCHAR(255) NOT NULL DEFAULT 'string',
  `group` VARCHAR(255) NOT NULL DEFAULT 'general',
  `description` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modules Table
CREATE TABLE `modules` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `display_name` VARCHAR(255) NOT NULL,
  `icon` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `is_system` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions Table
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  INDEX `sessions_user_id_index` (`user_id`),
  INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache Tables
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs Tables
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` VARCHAR(255) NOT NULL UNIQUE,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. Employee (Karyawan) Tables

```sql
-- Karyawan Table
CREATE TABLE `karyawan` (
  `id_karyawan` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nama_karyawan` VARCHAR(255) NOT NULL,
  `join_date` DATETIME NOT NULL,
  `masa_kerja` INT NOT NULL DEFAULT 0,
  `jabatan` VARCHAR(255) NOT NULL,
  `lokasi_kerja` VARCHAR(255) NOT NULL,
  `jenis_karyawan` VARCHAR(255) NOT NULL,
  `status_pegawai` VARCHAR(255) NOT NULL,
  `npwp` VARCHAR(255) NULL,
  `bpjs_kesehatan_no` VARCHAR(255) NULL,
  `bpjs_tk_no` VARCHAR(255) NULL,
  `bpjs_kecelakaan_kerja` VARCHAR(255) NULL,
  `no_rekening` VARCHAR(255) NOT NULL,
  `bank` VARCHAR(255) NOT NULL,
  `status_perkawinan` VARCHAR(255) NULL,
  `nama_istri` VARCHAR(255) NULL,
  `jumlah_anak` INT NOT NULL DEFAULT 0,
  `no_telp_istri` VARCHAR(255) NULL,
  `status_karyawan` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dynamic Fields Table
CREATE TABLE `dynamic_fields` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `module_id` BIGINT UNSIGNED NOT NULL,
  `field_name` VARCHAR(255) NOT NULL,
  `field_label` VARCHAR(255) NOT NULL,
  `field_type` VARCHAR(255) NOT NULL,
  `field_options` JSON NULL,
  `is_required` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`module_id`) REFERENCES `modules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Field Values Table
CREATE TABLE `field_values` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `dynamic_field_id` BIGINT UNSIGNED NOT NULL,
  `entity_type` VARCHAR(255) NOT NULL,
  `entity_id` BIGINT UNSIGNED NOT NULL,
  `value` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`dynamic_field_id`) REFERENCES `dynamic_fields`(`id`) ON DELETE CASCADE,
  INDEX `field_values_entity_type_entity_id_index` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4. Master Data Tables

```sql
-- Master Wilayah Table
CREATE TABLE `master_wilayah` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `kode` VARCHAR(255) NOT NULL UNIQUE,
  `nama` VARCHAR(255) NOT NULL,
  `keterangan` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Komponen Gaji Table
CREATE TABLE `komponen_gaji` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(255) NOT NULL,
  `tipe` VARCHAR(255) NOT NULL,
  `kategori` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT NULL,
  `is_system` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Master Status Pegawai Table
CREATE TABLE `master_status_pegawai` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(255) NOT NULL UNIQUE,
  `durasi_hari` INT NOT NULL,
  `keterangan` TEXT NULL,
  `gunakan_nki` TINYINT(1) NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5. Payroll Tables

```sql
-- Pengaturan Gaji Table
CREATE TABLE `pengaturan_gaji` (
  `id_pengaturan` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `jenis_karyawan` VARCHAR(255) NOT NULL,
  `jabatan` VARCHAR(255) NOT NULL,
  `lokasi_kerja` VARCHAR(255) NOT NULL,
  `gaji_pokok` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tunjangan_operasional` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `potongan_koperasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `gaji_nett` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kesehatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_ketenagakerjaan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kecelakaan_kerja` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_total` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `total_gaji` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `pengaturan_gaji_unique` (`jenis_karyawan`, `jabatan`, `lokasi_kerja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NKI (Nilai Kinerja Individual) Table
CREATE TABLE `nki` (
  `id_nki` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_karyawan` BIGINT UNSIGNED NOT NULL,
  `periode` VARCHAR(255) NOT NULL,
  `kemampuan` INT NOT NULL DEFAULT 0,
  `kontribusi` INT NOT NULL DEFAULT 0,
  `kedisiplinan` INT NOT NULL DEFAULT 0,
  `lainnya` INT NOT NULL DEFAULT 0,
  `nilai_nki` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `persentase_tunjangan` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id_karyawan`) ON DELETE CASCADE,
  UNIQUE KEY `nki_karyawan_periode_unique` (`id_karyawan`, `periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Absensi Table
CREATE TABLE `absensi` (
  `id_absensi` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_karyawan` BIGINT UNSIGNED NOT NULL,
  `periode` VARCHAR(255) NOT NULL,
  `jumlah_hari_bulan` INT NOT NULL DEFAULT 0,
  `hadir` INT NOT NULL DEFAULT 0,
  `on_site` INT NOT NULL DEFAULT 0,
  `absence` INT NOT NULL DEFAULT 0,
  `idle_rest` INT NOT NULL DEFAULT 0,
  `izin_sakit_cuti` INT NOT NULL DEFAULT 0,
  `tanpa_keterangan` INT NOT NULL DEFAULT 0,
  `potongan_absensi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id_karyawan`) ON DELETE CASCADE,
  UNIQUE KEY `absensi_karyawan_periode_unique` (`id_karyawan`, `periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kasbon Table
CREATE TABLE `kasbon` (
  `id_kasbon` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_karyawan` BIGINT UNSIGNED NOT NULL,
  `periode` VARCHAR(255) NOT NULL,
  `tanggal_pengajuan` DATE NOT NULL,
  `deskripsi` TEXT NULL,
  `nominal` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `metode_pembayaran` VARCHAR(255) NOT NULL,
  `status_pembayaran` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `jumlah_cicilan` INT NOT NULL DEFAULT 1,
  `cicilan_terbayar` INT NOT NULL DEFAULT 0,
  `sisa_cicilan` INT NOT NULL DEFAULT 0,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id_karyawan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kasbon Cicilan Table
CREATE TABLE `kasbon_cicilan` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_kasbon` BIGINT UNSIGNED NOT NULL,
  `cicilan_ke` INT NOT NULL,
  `nominal_cicilan` DECIMAL(15,2) NOT NULL,
  `tanggal_bayar` DATE NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'belum_bayar',
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`id_kasbon`) REFERENCES `kasbon`(`id_kasbon`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Acuan Gaji Table
CREATE TABLE `acuan_gaji` (
  `id_acuan` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_karyawan` BIGINT UNSIGNED NOT NULL,
  `periode` VARCHAR(255) NOT NULL,
  `gaji_pokok` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kesehatan_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kecelakaan_kerja_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kematian_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jht_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jp_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tunjangan_prestasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tunjangan_konjungtur` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `benefit_ibadah` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `benefit_komunikasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `benefit_operasional` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `reward` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `total_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kesehatan_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kecelakaan_kerja_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kematian_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jht_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jp_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tabungan_koperasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `koperasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `kasbon` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `umroh` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `kurban` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `mutabaah` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `potongan_absensi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `potongan_kehadiran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `total_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `gaji_bersih` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id_karyawan`) ON DELETE CASCADE,
  UNIQUE KEY `acuan_gaji_karyawan_periode_unique` (`id_karyawan`, `periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hitung Gaji Table
CREATE TABLE `hitung_gaji` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `karyawan_id` BIGINT UNSIGNED NOT NULL,
  `periode` VARCHAR(255) NOT NULL,
  `gaji_pokok` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kesehatan_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kecelakaan_kerja_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kematian_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jht_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jp_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tunjangan_prestasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tunjangan_konjungtur` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `benefit_ibadah` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `benefit_komunikasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `benefit_operasional` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `reward` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `total_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kesehatan_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kecelakaan_kerja_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_kematian_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jht_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `bpjs_jp_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tabungan_koperasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `koperasi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `kasbon` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `umroh` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `kurban` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `mutabaah` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `potongan_absensi` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `potongan_kehadiran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `total_pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `gaji_bersih` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `approved_at` TIMESTAMP NULL,
  `approved_by` BIGINT UNSIGNED NULL,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan`(`id_karyawan`) ON DELETE CASCADE,
  UNIQUE KEY `hitung_gaji_karyawan_periode_unique` (`karyawan_id`, `periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Slip Gaji Table
CREATE TABLE `slip_gaji` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `hitung_gaji_id` BIGINT UNSIGNED NOT NULL,
  `karyawan_id` BIGINT UNSIGNED NOT NULL,
  `periode` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NULL,
  `is_sent` TINYINT(1) NOT NULL DEFAULT 0,
  `sent_at` TIMESTAMP NULL,
  `catatan` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`hitung_gaji_id`) REFERENCES `hitung_gaji`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan`(`id_karyawan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 6. Foreign Keys

```sql
-- Add foreign key for users.role_id
ALTER TABLE `users` 
  ADD CONSTRAINT `users_role_id_foreign` 
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT;
```

## Database Relationships

### User Management
- `users` → `roles` (Many-to-One)
- `roles` → `permissions` (Many-to-Many via `role_permissions`)
- `users` → `permissions` (Many-to-Many via `user_permissions` for overrides)

### Employee Management
- `karyawan` → `nki` (One-to-Many)
- `karyawan` → `absensi` (One-to-Many)
- `karyawan` → `kasbon` (One-to-Many)
- `kasbon` → `kasbon_cicilan` (One-to-Many)

### Payroll Processing
- `karyawan` → `acuan_gaji` (One-to-Many)
- `karyawan` → `hitung_gaji` (One-to-Many)
- `hitung_gaji` → `slip_gaji` (One-to-One)

### Dynamic Fields
- `modules` → `dynamic_fields` (One-to-Many)
- `dynamic_fields` → `field_values` (One-to-Many)

## Notes
- All tables use `InnoDB` engine for transaction support
- Character set: `utf8mb4` with `utf8mb4_unicode_ci` collation
- Timestamps are automatically managed by Laravel
- Soft deletes are not implemented (using hard deletes with CASCADE)
- Decimal fields use precision (15,2) for monetary values
