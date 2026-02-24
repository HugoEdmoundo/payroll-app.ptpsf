# Hitung Gaji - Complete Requirements

## 📋 Overview
Hitung Gaji adalah modul untuk menghitung gaji final dengan sistem adjustment per field.

## 🎯 Core Concept

### Data Structure
```
Hitung Gaji = Copy ALL fields from Acuan Gaji + NKI + Absensi
```

### Field Structure
Setiap field memiliki:
1. **Value** (dari Acuan Gaji/NKI/Absensi) - READ ONLY
2. **Adjustment** (optional):
   - Tipe: + (tambah) atau - (kurang)
   - Nominal: angka adjustment
   - Deskripsi: alasan adjustment (WAJIB jika ada adjustment)

### Example Field with Adjustment
```json
{
  "gaji_pokok": {
    "value": 15000000,
    "adjustment": {
      "type": "+",
      "nominal": 1000000,
      "description": "Bonus kenaikan gaji"
    },
    "final": 16000000
  }
}
```

## 📊 Data Flow

```
1. ACUAN GAJI (Generate)
   ├── Pengaturan Gaji
   └── Kasbon ONLY
   
2. HITUNG GAJI (Create from Acuan Gaji)
   ├── Copy ALL fields from Acuan Gaji
   ├── Calculate NKI (Tunjangan Prestasi)
   ├── Calculate Absensi (Potongan Absensi)
   └── User can add adjustment to ANY field (optional)
   
3. SLIP GAJI (Generate from Approved Hitung Gaji)
   ├── Show ALL fields with final values
   └── Show ALL descriptions from:
       - Pengaturan Gaji
       - Komponen (NKI, Absensi, Kasbon)
       - Acuan Gaji
       - Hitung Gaji (adjustments)
```

## 🔢 Calculations

### NKI (Tunjangan Prestasi)
```
Formula:
NKI = (Kemampuan × 20%) + (Kontribusi × 20%) + (Kedisiplinan × 40%) + (Lainnya × 20%)

Persentase:
- NKI ≥ 8.5 → 100%
- NKI ≥ 8.0 → 80%
- NKI < 8.0  → 70%

Tunjangan Prestasi = Nilai Acuan Prestasi × Persentase NKI

Calculated in: HITUNG GAJI (saat create)
```

### Absensi (Potongan Absensi)
```
Formula:
Potongan Absensi = (Absence + Tanpa Keterangan) ÷ Jumlah Hari Bulan × (Gaji Pokok + Tunjangan Prestasi + Operasional)

Note: BPJS TIDAK ikut dihitung

Calculated in: HITUNG GAJI (saat create)
```

### Kasbon
```
Langsung: Potong penuh di periode kasbon dibuat
Cicilan: Potong per bulan sesuai cicilan

Calculated in: ACUAN GAJI (saat generate)
```

## 📝 Hitung Gaji Fields

### PENDAPATAN (Income)
1. Gaji Pokok (from Acuan Gaji)
2. BPJS Kesehatan Pendapatan (from Acuan Gaji)
3. BPJS Kecelakaan Kerja Pendapatan (from Acuan Gaji)
4. BPJS Kematian Pendapatan (from Acuan Gaji)
5. BPJS JHT Pendapatan (from Acuan Gaji)
6. BPJS JP Pendapatan (from Acuan Gaji)
7. **Tunjangan Prestasi (CALCULATED from NKI)**
8. Tunjangan Konjungtur (from Acuan Gaji)
9. Benefit Ibadah (from Acuan Gaji)
10. Benefit Komunikasi (from Acuan Gaji)
11. Benefit Operasional (from Acuan Gaji)
12. Reward (from Acuan Gaji)

### PENGELUARAN (Deductions)
1. BPJS Kesehatan Pengeluaran (from Acuan Gaji)
2. BPJS Kecelakaan Kerja Pengeluaran (from Acuan Gaji)
3. BPJS Kematian Pengeluaran (from Acuan Gaji)
4. BPJS JHT Pengeluaran (from Acuan Gaji)
5. BPJS JP Pengeluaran (from Acuan Gaji)
6. Tabungan Koperasi (from Acuan Gaji)
7. Koperasi (from Acuan Gaji)
8. Kasbon (from Acuan Gaji)
9. Umroh (from Acuan Gaji)
10. Kurban (from Acuan Gaji)
11. Mutabaah (from Acuan Gaji)
12. **Potongan Absensi (CALCULATED from Absensi)**
13. Potongan Kehadiran (from Acuan Gaji)

## 🎨 UI/UX Requirements

### Create/Edit Form
```
For EACH field:
┌─────────────────────────────────────────────┐
│ Field Name: Gaji Pokok                      │
│                                             │
│ Value (Read-Only): Rp 15.000.000           │
│                                             │
│ Adjustment (Optional):                      │
│ ┌─────────┬──────────┬──────────────────┐  │
│ │ Type    │ Nominal  │ Description      │  │
│ │ [+/-]   │ [input]  │ [textarea]       │  │
│ └─────────┴──────────┴──────────────────┘  │
│                                             │
│ Final Value: Rp 16.000.000                  │
└─────────────────────────────────────────────┘
```

### Show/Detail View
```
For EACH field:
┌─────────────────────────────────────────────┐
│ Gaji Pokok                                  │
│ ├── Value: Rp 15.000.000                   │
│ ├── Adjustment: +Rp 1.000.000              │
│ │   └── Desc: Bonus kenaikan gaji          │
│ └── Final: Rp 16.000.000                   │
└─────────────────────────────────────────────┘
```

## 📤 Import/Export

### Export Format (Excel)
```
Columns:
- Karyawan
- Periode
- [All Pendapatan Fields with Adjustment columns]
- [All Pengeluaran Fields with Adjustment columns]
- Total Pendapatan
- Total Pengeluaran
- Take Home Pay
```

### Import Template
```
Same structure as export
User can fill adjustment columns
System will validate and calculate
```

## 🖨️ Slip Gaji

### Content
```
═══════════════════════════════════════════════
           SLIP GAJI - PT PSF
═══════════════════════════════════════════════

Employee Info:
- Nama
- NIK
- Jabatan
- Periode
- Status

───────────────────────────────────────────────
PENDAPATAN
───────────────────────────────────────────────
For EACH pendapatan field:
  Field Name                    Value
  └── Adjustment: +/- Nominal
      Desc: Description
                              ───────────────
TOTAL PENDAPATAN              Rp XX.XXX.XXX

───────────────────────────────────────────────
PENGELUARAN
───────────────────────────────────────────────
For EACH pengeluaran field:
  Field Name                    Value
  └── Adjustment: +/- Nominal
      Desc: Description
                              ───────────────
TOTAL PENGELUARAN             Rp XX.XXX.XXX

═══════════════════════════════════════════════
GAJI BERSIH (TAKE HOME PAY)   Rp XX.XXX.XXX
═══════════════════════════════════════════════

ALL DESCRIPTIONS:
- From Pengaturan Gaji
- From NKI (persentase, nilai)
- From Absensi (jumlah hari, potongan)
- From Kasbon (metode, cicilan)
- From Hitung Gaji (all adjustments)
```

## 🔐 Permissions
- hitung_gaji.view
- hitung_gaji.create
- hitung_gaji.edit
- hitung_gaji.delete
- hitung_gaji.import
- hitung_gaji.export
- hitung_gaji.approve

## 📊 Status Workflow
```
Draft → Preview → Approved
  ↑        ↓
  └────────┘
  (Back to Draft)
```

## ⚠️ Important Rules

1. **Data from Acuan Gaji is READ-ONLY**
   - Cannot edit the base value
   - Can only add adjustment

2. **Adjustment is OPTIONAL**
   - If no adjustment, field shows original value
   - If has adjustment, must have description

3. **Calculation happens ONCE**
   - NKI calculated when create Hitung Gaji
   - Absensi calculated when create Hitung Gaji
   - No recalculation after that

4. **Slip Gaji shows EVERYTHING**
   - All fields with final values
   - All adjustments with descriptions
   - All source descriptions (Pengaturan, Komponen, etc.)

## 🎯 Implementation Priority

1. ✅ Database structure (hitung_gaji table)
2. ⏳ HitungGajiController (CRUD + calculations)
3. ⏳ Views (create, edit, show with adjustment UI)
4. ⏳ Components (form, show, table)
5. ⏳ Import/Export functionality
6. ⏳ Slip Gaji generation
7. ⏳ PDF export for Slip Gaji

---

**Status**: Requirements Documented
**Next**: Implementation
