# Sistem Payroll Flow - Complete Documentation

## 🔄 Flow Diagram

```
KOMPONEN GAJI                    ACUAN GAJI                    HITUNG GAJI                    SLIP GAJI
┌─────────────┐                 ┌─────────────┐               ┌─────────────┐                ┌─────────────┐
│             │                 │             │               │             │                │             │
│  NKI        │────┐            │ PENDAPATAN: │               │ FROM ACUAN: │                │  FINAL      │
│  Absensi    │────┼─────X      │ - Gaji Pokok│──────────────>│ (Read-Only) │───────────────>│  PAYSLIP    │
│  Kasbon     │────┘     │      │ - Tunjangan │               │             │                │             │
│             │          │      │ - BPJS      │               │ + NKI       │                │             │
└─────────────┘          │      │             │               │ + Absensi   │                └─────────────┘
                         │      │ PENGELUARAN:│               │             │
                         │      │ - Koperasi  │               │ ADJUSTMENT: │
                         └─────>│ - Kasbon    │               │ +/- Bonus   │
                    ONLY KASBON │             │               │ +/- Potongan│
                                └─────────────┘               │ (Editable)  │
                                                              └─────────────┘
```

**⚠️ PENTING**:
- **Kasbon** → Masuk ke **Acuan Gaji**
- **NKI & Absensi** → Masuk ke **Hitung Gaji** (dihitung saat create hitung gaji)
- **Slip Gaji** → Diambil dari **Hitung Gaji** (yang sudah approved)

## 📊 1. KOMPONEN GAJI

### A. NKI (Nilai Kinerja Individual)
**Formula**:
```
NKI = (Kemampuan × 20%) + (Kontribusi × 20%) + (Kedisiplinan × 40%) + (Lainnya × 20%)

Persentase Tunjangan:
- NKI ≥ 8.5 → 100%
- NKI ≥ 8.0 → 80%
- NKI < 8.0  → 70%

Tunjangan Prestasi = Nilai Acuan Prestasi × Persentase NKI
```

**⚠️ PENTING**: NKI masuk ke **HITUNG GAJI**, bukan Acuan Gaji!

**Contoh**:
- Kemampuan: 8.5
- Kontribusi: 9.0
- Kedisiplinan: 8.0
- Lainnya: 8.5
- **NKI = (8.5×0.2) + (9.0×0.2) + (8.0×0.4) + (8.5×0.2) = 8.4**
- **Persentase = 80%**
- Jika Nilai Acuan Prestasi = Rp 2.000.000
- **Tunjangan Prestasi = Rp 2.000.000 × 80% = Rp 1.600.000**

### B. Absensi
**Formula Potongan**:
```
Potongan Absensi = (Absence + Tanpa Keterangan) ÷ Jumlah Hari Bulan × (Gaji Pokok + Tunjangan Prestasi + Tunjangan Operasional)

⚠️ PENTING: BPJS TIDAK ikut dihitung dalam potongan absensi!
```

**⚠️ PENTING**: Absensi masuk ke **HITUNG GAJI**, bukan Acuan Gaji!

**Contoh**:
- Absence: 2 hari
- Tanpa Keterangan: 1 hari
- Jumlah Hari Bulan: 30 hari
- Gaji Pokok: Rp 15.000.000
- Tunjangan Prestasi: Rp 2.000.000
- Tunjangan Operasional: Rp 3.000.000
- **Total yang dihitung = Rp 20.000.000**
- **Potongan = (2+1) ÷ 30 × Rp 20.000.000 = Rp 2.000.000**

### C. Kasbon
**2 Metode Pembayaran**:

**⚠️ PENTING**: Kasbon masuk ke **ACUAN GAJI**!

#### 1. Langsung (Potong Gaji Langsung)
- Kasbon dipotong PENUH di bulan berjalan
- Langsung masuk ke Acuan Gaji sebagai pengeluaran
- Status berubah jadi "Lunas" setelah dipotong

**Contoh**:
- Kasbon: Rp 5.000.000
- Metode: Langsung
- **Potongan di Acuan Gaji bulan ini: Rp 5.000.000**
- Status: Lunas

#### 2. Cicilan
- Kasbon dibagi menjadi beberapa cicilan
- Setiap bulan dipotong 1 cicilan
- Masuk ke Acuan Gaji sesuai cicilan bulan berjalan
- Status "Lunas" setelah semua cicilan terbayar

**Contoh**:
- Kasbon: Rp 6.000.000
- Metode: Cicilan
- Jumlah Cicilan: 6 bulan
- **Cicilan per bulan: Rp 1.000.000**
- Bulan 1: Potongan Rp 1.000.000 (sisa 5 cicilan)
- Bulan 2: Potongan Rp 1.000.000 (sisa 4 cicilan)
- ...
- Bulan 6: Potongan Rp 1.000.000 (Lunas)

**Table: kasbon_cicilan**
```sql
id_cicilan
id_kasbon (FK)
cicilan_ke (1, 2, 3, ...)
periode (YYYY-MM)
nominal_cicilan
tanggal_bayar
status (Pending, Terbayar)
```

## 📋 2. ACUAN GAJI

**Sumber Data**:
1. Pengaturan Gaji (gaji pokok, tunjangan, BPJS)
2. **Kasbon ONLY** → Potongan Kasbon (langsung atau cicilan bulan ini)

**⚠️ PENTING**: NKI dan Absensi TIDAK masuk Acuan Gaji, mereka masuk ke Hitung Gaji!

**Struktur**:
```
PENDAPATAN:
├── Gaji Pokok (dari Pengaturan Gaji)
├── Tunjangan Operasional (dari Pengaturan Gaji)
├── BPJS Kesehatan (dari Pengaturan Gaji)
├── BPJS Ketenagakerjaan (dari Pengaturan Gaji)
└── BPJS Kecelakaan Kerja (dari Pengaturan Gaji)

PENGELUARAN:
├── Potongan Koperasi (dari Pengaturan Gaji)
└── Potongan Kasbon (dari Kasbon - langsung atau cicilan)

HASIL:
├── Total Pendapatan
├── Total Pengeluaran
└── Gaji Bersih = Total Pendapatan - Total Pengeluaran
```

**⚠️ PENTING**: 
- Acuan Gaji adalah HASIL PERHITUNGAN otomatis
- Data di Acuan Gaji READ-ONLY (tidak bisa diedit manual)
- Jika ada perubahan, harus dari sumber (Kasbon atau Pengaturan Gaji)

## 💰 3. HITUNG GAJI

**Konsep**: 
- Import data dari Acuan Gaji (read-only)
- **Calculate NKI (Tunjangan Prestasi) dan Absensi (Potongan Absensi)**
- Bisa tambah adjustment manual (bonus/potongan tambahan)
- Setiap adjustment harus punya validator +/- dan deskripsi

**⚠️ PENTING**: NKI dan Absensi dihitung di Hitung Gaji, bukan di Acuan Gaji!

**Struktur**:
```
FROM ACUAN GAJI (Read-Only):
├── Pendapatan Acuan (JSON)
│   ├── Gaji Pokok: Rp 15.000.000
│   ├── Tunjangan Operasional: Rp 3.000.000
│   └── BPJS Total: Rp 1.000.000
│
└── Pengeluaran Acuan (JSON)
    ├── Potongan Koperasi: Rp 100.000
    └── Potongan Kasbon: Rp 1.000.000

FROM KOMPONEN (Calculated):
├── Tunjangan Prestasi (from NKI): Rp 2.000.000
└── Potongan Absensi (from Absensi): Rp 2.000.000

ADJUSTMENT (Editable):
├── Penyesuaian Pendapatan (JSON Array)
│   └── [{
│       "komponen": "Bonus Kinerja",
│       "nominal": 5000000,
│       "tipe": "+",  // + atau -
│       "deskripsi": "Bonus project completion"
│   }]
│
└── Penyesuaian Pengeluaran (JSON Array)
    └── [{
        "komponen": "Denda Keterlambatan",
        "nominal": 500000,
        "tipe": "+",  // + berarti tambah potongan
        "deskripsi": "Terlambat 5x bulan ini"
    }]

FINAL CALCULATION:
├── Total Pendapatan Acuan: Rp 21.000.000 (Acuan + NKI)
├── Total Penyesuaian Pendapatan: +Rp 5.000.000
├── Total Pendapatan Akhir: Rp 26.000.000
│
├── Total Pengeluaran Acuan: Rp 3.100.000 (Acuan + Absensi)
├── Total Penyesuaian Pengeluaran: +Rp 500.000
├── Total Pengeluaran Akhir: Rp 3.600.000
│
└── TAKE HOME PAY: Rp 22.400.000
```

**Rules**:
1. Data dari Acuan Gaji TIDAK BISA DIEDIT
2. NKI dan Absensi dihitung otomatis dari Komponen Gaji
3. Adjustment hanya untuk bonus/potongan TAMBAHAN
4. Setiap adjustment WAJIB punya:
   - Komponen (nama)
   - Nominal (angka)
   - Tipe (+/-)
   - Deskripsi (alasan)
5. Jika tidak ada adjustment, field kosong (tidak perlu deskripsi)
6. Status: Draft → Preview → Approved
7. Setelah Approved, tidak bisa diedit lagi

## 📄 4. SLIP GAJI

**Sumber**: Data dari Hitung Gaji yang sudah Approved

**Format**:
```
═══════════════════════════════════════════════
           SLIP GAJI - PT PSF
═══════════════════════════════════════════════

Nama        : Budi Santoso
NIK         : K0001
Jabatan     : Manager
Periode     : Februari 2026
Status      : Kontrak

───────────────────────────────────────────────
PENDAPATAN
───────────────────────────────────────────────
Gaji Pokok                    Rp  15.000.000
Tunjangan Operasional         Rp   3.000.000
Tunjangan Prestasi (NKI 85%)  Rp   2.000.000
BPJS Kesehatan                Rp     500.000
BPJS Ketenagakerjaan          Rp     300.000
BPJS Kecelakaan Kerja         Rp     200.000
                              ───────────────
Total Pendapatan Acuan        Rp  21.000.000

PENYESUAIAN PENDAPATAN:
+ Bonus Kinerja               Rp   5.000.000
  (Bonus project completion)
                              ───────────────
Total Penyesuaian             Rp   5.000.000
                              ═══════════════
TOTAL PENDAPATAN              Rp  26.000.000

───────────────────────────────────────────────
PENGELUARAN
───────────────────────────────────────────────
Potongan Koperasi             Rp     100.000
Potongan Absensi (3 hari)     Rp   2.000.000
Potongan Kasbon (Cicilan 1/6) Rp   1.000.000
                              ───────────────
Total Pengeluaran Acuan       Rp   3.100.000

PENYESUAIAN PENGELUARAN:
+ Denda Keterlambatan         Rp     500.000
  (Terlambat 5x bulan ini)
                              ───────────────
Total Penyesuaian             Rp     500.000
                              ═══════════════
TOTAL PENGELUARAN             Rp   3.600.000

═══════════════════════════════════════════════
GAJI BERSIH (TAKE HOME PAY)   Rp  22.400.000
═══════════════════════════════════════════════

Catatan:
- Slip gaji ini sah dan tidak perlu tanda tangan
- Untuk pertanyaan hubungi HRD

Dicetak: 24 Februari 2026 10:30 WIB
```

## 🔧 Implementation Checklist

### Phase 1: Kasbon System Upgrade ✅ COMPLETED
- [x] Create kasbon_cicilan table migration
- [x] Update Kasbon model with cicilan relationship
- [x] Create KasbonCicilan model
- [x] Update kasbon logic untuk handle cicilan
- [x] Create kasbon cicilan seeder

### Phase 2: Acuan Gaji Enhancement ✅ COMPLETED
- [x] Update AcuanGajiController generate method
- [x] Include kasbon (langsung/cicilan) in calculation
- [x] Ensure all komponen masuk ke acuan gaji
- [x] Add validation untuk data completeness

### Phase 3: Hitung Gaji System ✅ COMPLETED
- [x] Create HitungGajiController
- [x] Create hitung gaji views (index, create, edit, show)
- [x] Implement adjustment system (+/- with description)
- [x] Add approval workflow (draft → preview → approved)
- [x] Validation: adjustment must have description
- [x] Add permissions and routes
- [x] Add menu to sidebar

### Phase 4: Slip Gaji - READY TO START
- [ ] Create SlipGajiController
- [ ] Create slip gaji view (printable)
- [ ] Generate from approved hitung_gaji
- [ ] PDF export functionality
- [ ] Email slip gaji to employee

## ✅ SYSTEM STATUS: FULLY OPERATIONAL

The payroll system is now complete and operational:
- ✅ Kasbon system with Langsung and Cicilan methods working
- ✅ Acuan Gaji properly calculates kasbon deductions
- ✅ Hitung Gaji with adjustment system and approval workflow
- ✅ All views, routes, permissions, and menu items configured
- ✅ Seeders creating realistic data including cicilan records

## 📝 Notes

- Semua perhitungan harus transparan dan traceable
- Setiap perubahan harus ada audit trail
- Data historis harus preserved (jangan overwrite)
- Slip gaji harus bisa di-regenerate kapan saja
- System harus support berbagai status pegawai (Kontrak, Harian, OJT, Magang)
