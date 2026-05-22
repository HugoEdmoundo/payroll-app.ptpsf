**Schemas & Models (ERD / Class Diagram Ready)**

Ringkasan schema untuk keperluan Class Diagram dan ERD. Saya mengambil kolom dari migration di folder `database/migrations` sehingga siap dipakai untuk menggambar relasi.

**Karyawan (karyawan)**
- PK: `id_karyawan` (bigint, auto-increment)
- Columns:
  - `nama_karyawan`: string
  - `join_date`: datetime
  - `masa_kerja`: integer, default 0
  - `jabatan`: string
  - `lokasi_kerja`: string
  - `jenis_karyawan`: string
  - `status_pegawai`: string
  - `npwp`: string, nullable
  - `bpjs_kesehatan_no`: string, nullable
  - `bpjs_tk_no`: string, nullable
  - `no_rekening`: string
  - `bank`: string
  - `status_perkawinan`: string, nullable
  - `nama_istri`: string, nullable
  - `jumlah_anak`: integer, default 0
  - `no_telp_istri`: string, nullable
  - `status_karyawan`: string
  - timestamps: `created_at`, `updated_at`
- Relationships:
  - hasMany `absensi` (Absensi.id_karyawan -> Karyawan.id_karyawan)
  - hasMany `kasbon` (Kasbon.id_karyawan -> Karyawan.id_karyawan)
  - hasMany `acuan_gaji` (AcuanGaji.id_karyawan -> Karyawan.id_karyawan)

**Absensi (absensi)**
- PK: `id_absensi` (bigint)
- Columns:
  - `id_karyawan`: unsignedBigInteger (FK)
  - `periode`: string (YYYY-MM)
  - `jumlah_hari_bulan`: integer, default 30
  - `hadir`, `on_site`, `absence`, `idle_rest`, `izin_sakit_cuti`, `tanpa_keterangan`: integer
  - `potongan_absensi`: decimal(15,2)
  - `keterangan`: text, nullable
  - timestamps
- Constraints:
  - unique(id_karyawan, periode)
  - FK -> `karyawan.id_karyawan` ON DELETE CASCADE

**Kasbon (kasbon)**
- PK: `id_kasbon`
- Columns:
  - `id_karyawan`: unsignedBigInteger (FK)
  - `periode`: string (YYYY-MM)
  - `tanggal_pengajuan`: date
  - `deskripsi`: text
  - `nominal`: decimal(15,2)
  - `metode_pembayaran`: enum [Langsung, Cicilan]
  - `status_pembayaran`: enum [Pending, Lunas]
  - `jumlah_cicilan`: integer, nullable
  - `cicilan_terbayar`: integer, default 0
  - `sisa_cicilan`: decimal(15,2), default 0
  - `keterangan`: text, nullable
  - timestamps
- Relationships:
  - FK -> `karyawan.id_karyawan` ON DELETE CASCADE

**AcuanGaji (acuan_gaji)**
- PK: `id_acuan`
- Columns: (per id_karyawan + periode)
  - banyak field decimal(15,2) untuk pendapatan: `gaji_pokok`, `bpjs_kesehatan`, `bpjs_kecelakaan_kerja`, `bpjs_kematian`, `bpjs_jht`, `bpjs_jp`, `tunjangan_prestasi`, `tunjangan_konjungtur`, `benefit_ibadah`, `benefit_komunikasi`, `benefit_operasional`, `reward`, `total_pendapatan`
  - pengeluaran: `koperasi`, `kasbon`, `umroh`, `kurban`, `mutabaah`, `potongan_absensi`, `potongan_kehadiran`, `total_pengeluaran`
  - `gaji_bersih`: decimal(15,2)
  - `keterangan`: text, nullable
  - timestamps
- Constraints:
  - unique(id_karyawan, periode)
  - FK -> `karyawan.id_karyawan` ON DELETE CASCADE

**PengaturanGaji (pengaturan_gaji)**
- PK: `id_pengaturan`
- Columns:
  - `jenis_karyawan`, `jabatan`, `lokasi_kerja` (kombinasi unik)
  - komponen: `gaji_pokok`, `tunjangan_prestasi` (decimal)
  - calculated: `gaji_nett`, `total_gaji`
  - `keterangan`, timestamps
- Constraints:
  - unique(jenis_karyawan, jabatan, lokasi_kerja)

**KomponenGaji (komponen_gaji)**
- PK: `id`
- Columns:
  - `nama`, `kode` (unique), `tipe` (pendapatan|pengeluaran), `kategori`, `deskripsi`, `is_system`, `is_active`, `order`, timestamps

**DynamicField (dynamic_fields)**
- PK: `id`
- Columns:
  - `module_id` (FK -> modules.id)
  - `field_name`, `field_label`, `field_type`, `field_options` (JSON), `validation_rules`, `default_value`, `help_text`, `placeholder`, `is_required`, `is_active`, `is_searchable`, `show_in_list`, `show_in_form`, `order`, `group`, timestamps
  - unique(module_id, field_name)

**FieldValue (field_values)**
- PK: `id`
- Columns:
  - `dynamic_field_id` (FK)
  - `entity_type` (string, e.g., App\\Models\\Karyawan)
  - `entity_id` (unsignedBigInteger)
  - `value`: text
  - timestamps
- Indexes/Constraints:
  - index(entity_type, entity_id)
  - unique(dynamic_field_id, entity_type, entity_id)
  - digunakan untuk menyimpan nilai kustom polymorphic

**Module (modules)**
- PK: `id`
- Columns: `name` (unique), `display_name`, `icon`, `description`, `is_active`, `is_system`, `order`, `settings` (json), timestamps

**Auth / RBAC (roles, permissions, role_permissions, user_permissions, users)**
- `roles`: id, name, display_name, description, is_system, timestamps
- `permissions`: id, name, group, action, timestamps
- `role_permissions`: pivot role_id <-> permission_id
- `user_permissions`: user-specific permission overrides (user_id, permission_id, module, action...)
- `users` migration exists (standard Laravel users table)

---

Relasi utama untuk ERD (summary):
- `Karyawan` 1---* `Absensi`
- `Karyawan` 1---* `Kasbon`
- `Karyawan` 1---* `AcuanGaji`
- `PengaturanGaji` adalah konfigurasi per combination (jenis_karyawan, jabatan, lokasi_kerja)
- `KomponenGaji` berdiri sendiri sebagai master list komponen
- `DynamicField` -> `FieldValue` (polymorphic storage per `entity_type`+`entity_id`)

Catatan:
- Sumber kolom diambil dari migration files di `database/migrations`.
- Untuk class diagram: pakai nama Model (contoh: `App\\Models\\Karyawan`) dan tunjukkan atribut yang ada di kolom tabel.
- Untuk ERD: gunakan tipe kolom penting (PK, FK, enum, decimal) dan constraints unik/foreign key seperti dicantumkan.

Jika mau, saya juga bisa:
- generate diagram PlantUML/mermaid dari ini
- buat file JSON/YAML untuk tool diagram (dbdiagram.io, Mermaid ERD)
