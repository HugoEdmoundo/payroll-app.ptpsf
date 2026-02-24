# User Guide: Hitung Gaji System

## 📋 Daftar Isi
1. [Pengenalan](#pengenalan)
2. [Alur Kerja](#alur-kerja)
3. [Cara Menggunakan](#cara-menggunakan)
4. [FAQ](#faq)

## Pengenalan

Sistem Hitung Gaji adalah modul untuk menghitung gaji karyawan berdasarkan data Acuan Gaji dengan kemampuan menambahkan penyesuaian (adjustment) seperti bonus atau potongan tambahan.

### Fitur Utama:
- ✅ Import otomatis data dari Acuan Gaji
- ✅ Tambah adjustment (bonus/potongan) dengan deskripsi
- ✅ Workflow approval (Draft → Preview → Approved)
- ✅ Data acuan read-only, hanya adjustment yang bisa diedit
- ✅ Perhitungan otomatis take home pay

## Alur Kerja

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   DRAFT     │────>│   PREVIEW   │────>│  APPROVED   │────>│  SLIP GAJI  │
│  (Editable) │     │ (Read-Only) │     │ (Final)     │     │  (Generate) │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
       │                    │
       │                    │
       └────────────────────┘
         Back to Draft
```

### Status Explanation:
1. **Draft**: Bisa diedit, tambah/hapus adjustment
2. **Preview**: Review sebelum approve, tidak bisa edit (bisa back to draft)
3. **Approved**: Final, tidak bisa edit, siap generate slip gaji

## Cara Menggunakan

### 1. Persiapan
Pastikan sudah ada data:
- ✅ Karyawan (Active)
- ✅ Pengaturan Gaji
- ✅ Komponen Gaji (NKI, Absensi, Kasbon)
- ✅ Acuan Gaji sudah di-generate

### 2. Create Hitung Gaji

#### Step 1: Buka Menu Hitung Gaji
- Klik menu **"Hitung Gaji"** di sidebar
- Klik tombol **"Create Hitung Gaji"**

#### Step 2: Pilih Periode
- Sistem akan menampilkan periode yang tersedia
- Pilih periode yang ingin dihitung

#### Step 3: Pilih Karyawan
- Sistem menampilkan daftar karyawan yang sudah punya Acuan Gaji
- Klik card karyawan yang ingin dihitung gajinya
- Modal form akan muncul

#### Step 4: Review Data Acuan (Read-Only)
Modal akan menampilkan:
- Nama karyawan
- Base salary dari Acuan Gaji
- Data ini READ-ONLY, tidak bisa diedit

#### Step 5: Tambah Adjustment (Optional)

**Penyesuaian Pendapatan** (Bonus/Tambahan):
- Klik **"Add Adjustment"** di section Penyesuaian Pendapatan
- Isi form:
  - **Komponen**: Nama adjustment (contoh: "Bonus Kinerja")
  - **Nominal**: Jumlah uang (contoh: 5000000)
  - **Tipe**: 
    - `+` = Tambah pendapatan
    - `-` = Kurangi pendapatan
  - **Deskripsi**: Alasan adjustment (WAJIB!)
- Klik icon trash untuk hapus adjustment

**Penyesuaian Pengeluaran** (Potongan Tambahan):
- Klik **"Add Adjustment"** di section Penyesuaian Pengeluaran
- Isi form sama seperti di atas
- **Tipe**:
  - `+` = Tambah potongan (potong lebih banyak)
  - `-` = Kurangi potongan (potong lebih sedikit)

**Contoh Adjustment Pendapatan**:
```
Komponen: Bonus Project Completion
Nominal: 5000000
Tipe: + (Tambah)
Deskripsi: Bonus untuk menyelesaikan project X tepat waktu
```

**Contoh Adjustment Pengeluaran**:
```
Komponen: Denda Keterlambatan
Nominal: 500000
Tipe: + (Tambah potongan)
Deskripsi: Terlambat 5x dalam bulan ini
```

#### Step 6: Catatan Umum (Optional)
- Isi catatan umum jika ada informasi tambahan
- Contoh: "Gaji bulan ini termasuk bonus akhir tahun"

#### Step 7: Save
- Klik **"Create as Draft"**
- Sistem akan menyimpan dengan status **Draft**

### 3. Edit Hitung Gaji (Draft Only)

- Buka detail hitung gaji
- Klik tombol **"Edit"** (hanya muncul jika status Draft)
- Edit adjustment sesuai kebutuhan
- Data acuan tetap READ-ONLY
- Klik **"Update"**

### 4. Preview

- Buka detail hitung gaji (status Draft)
- Klik tombol **"Preview"**
- Status berubah menjadi **Preview**
- Review semua data dengan teliti
- Jika ada yang salah, klik **"Back to Draft"** untuk edit lagi

### 5. Approve

- Buka detail hitung gaji (status Preview)
- Pastikan semua data sudah benar
- Klik tombol **"Approve"**
- Status berubah menjadi **Approved**
- Data tidak bisa diedit lagi
- Siap untuk generate Slip Gaji

### 6. View Detail

Detail hitung gaji menampilkan:

**Pendapatan**:
- Data dari Acuan Gaji (read-only, background abu-abu)
- Adjustments (editable, background biru)
- Total Pendapatan Akhir (hijau)

**Pengeluaran**:
- Data dari Acuan Gaji (read-only, background abu-abu)
- Adjustments (editable, background biru)
- Total Pengeluaran Akhir (merah)

**Take Home Pay**:
- Ditampilkan dengan background gradient indigo-purple
- Hasil akhir yang akan diterima karyawan

## FAQ

### Q: Kenapa data dari Acuan Gaji tidak bisa diedit?
**A**: Data acuan adalah hasil perhitungan otomatis dari Pengaturan Gaji + Komponen (NKI, Absensi, Kasbon). Jika ada yang salah, harus diperbaiki di sumber datanya, bukan di Hitung Gaji. Hitung Gaji hanya untuk adjustment tambahan.

### Q: Apa bedanya tipe + dan - di adjustment?
**A**: 
- **Pendapatan**: `+` = tambah pendapatan, `-` = kurangi pendapatan
- **Pengeluaran**: `+` = tambah potongan, `-` = kurangi potongan

### Q: Apakah deskripsi wajib diisi?
**A**: Ya! Setiap adjustment WAJIB punya deskripsi untuk audit trail dan transparansi.

### Q: Bisa tidak tambah adjustment?
**A**: Bisa! Adjustment bersifat optional. Jika tidak ada adjustment, langsung save saja.

### Q: Kenapa tidak bisa edit setelah Approved?
**A**: Untuk menjaga integritas data. Setelah approved, data sudah final dan akan digunakan untuk generate Slip Gaji. Jika ada kesalahan, harus buat hitung gaji baru.

### Q: Bagaimana cara menghapus hitung gaji?
**A**: Hanya hitung gaji dengan status **Draft** yang bisa dihapus. Klik icon trash di list atau di detail page.

### Q: Apakah kasbon otomatis masuk ke hitung gaji?
**A**: Ya! Kasbon sudah otomatis masuk ke Acuan Gaji, dan dari Acuan Gaji masuk ke Hitung Gaji. Sistem sudah handle kasbon Langsung dan Cicilan dengan benar.

### Q: Bagaimana sistem menghitung kasbon cicilan?
**A**: 
- Kasbon Langsung: Dipotong penuh di bulan kasbon dibuat
- Kasbon Cicilan: Dipotong per bulan sesuai cicilan
- Contoh: Kasbon Rp 6jt, cicilan 6 bulan = Rp 1jt per bulan

### Q: Bisa tidak create hitung gaji untuk karyawan yang sama di periode yang sama?
**A**: Tidak bisa. Satu karyawan hanya bisa punya satu hitung gaji per periode. Jika sudah ada, harus edit atau hapus yang lama.

## Tips & Best Practices

1. **Selalu Review di Preview**: Jangan langsung approve, gunakan status Preview untuk review dulu
2. **Deskripsi yang Jelas**: Tulis deskripsi adjustment dengan jelas untuk audit trail
3. **Konsisten**: Gunakan format yang konsisten untuk nama komponen adjustment
4. **Backup**: Sebelum approve, pastikan data sudah benar karena tidak bisa diedit lagi
5. **Komunikasi**: Jika ada adjustment besar, komunikasikan dengan karyawan

## Permissions Required

Untuk menggunakan fitur Hitung Gaji, user harus punya permission:
- `hitung_gaji.view` - Melihat list hitung gaji
- `hitung_gaji.create` - Membuat hitung gaji baru
- `hitung_gaji.edit` - Edit hitung gaji (draft only)
- `hitung_gaji.delete` - Hapus hitung gaji (draft only)

## Support

Jika ada pertanyaan atau masalah, hubungi:
- IT Support
- HRD Department

---

**Last Updated**: February 24, 2026
**Version**: 1.0
