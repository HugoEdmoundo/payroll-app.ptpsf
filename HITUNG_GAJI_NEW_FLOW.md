# Hitung Gaji - New Flow (Periode-Based)

## 🎯 Konsep Baru

Flow yang lebih sederhana dan intuitif:
1. **Pilih Periode** → Lihat list periode yang sudah ada di Acuan Gaji
2. **Proses Karyawan** → Klik periode, lihat semua karyawan untuk periode tersebut
3. **Tambah Adjustment** → Klik karyawan, form otomatis terisi, tinggal tambah adjustment
4. **Approve** → Setelah selesai, approve untuk generate slip gaji

## 📊 Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    HITUNG GAJI INDEX                        │
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │ Februari 2026│  │  Maret 2026  │  │  April 2026  │    │
│  │              │  │              │  │              │    │
│  │ Total: 50    │  │ Total: 50    │  │ Total: 50    │    │
│  │ Proses: 45   │  │ Proses: 30   │  │ Proses: 0    │    │
│  │ Pending: 5   │  │ Pending: 20  │  │ Pending: 50  │    │
│  │              │  │              │  │              │    │
│  │ Draft: 5     │  │ Draft: 10    │  │              │    │
│  │ Preview: 10  │  │ Preview: 5   │  │              │    │
│  │ Approved: 30 │  │ Approved: 15 │  │              │    │
│  │              │  │              │  │              │    │
│  │ [Proses >]   │  │ [Proses >]   │  │ [Proses >]   │    │
│  └──────────────┘  └──────────────┘  └──────────────┘    │
└─────────────────────────────────────────────────────────────┘
                            ↓ Click "Proses"
┌─────────────────────────────────────────────────────────────┐
│              PROSES HITUNG GAJI - Februari 2026             │
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │ ✓ Budi       │  │   Andi       │  │ ✓ Citra      │    │
│  │ Sudah Proses │  │ Belum Proses │  │ Sudah Proses │    │
│  │ Rp 15.000.000│  │ Rp 12.000.000│  │ Rp 18.000.000│    │
│  └──────────────┘  └──────────────┘  └──────────────┘    │
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │   Dedi       │  │ ✓ Eka        │  │   Fitri      │    │
│  │ Belum Proses │  │ Sudah Proses │  │ Belum Proses │    │
│  │ Rp 14.000.000│  │ Rp 16.000.000│  │ Rp 13.000.000│    │
│  └──────────────┘  └──────────────┘  └──────────────┘    │
└─────────────────────────────────────────────────────────────┘
                            ↓ Click Karyawan
┌─────────────────────────────────────────────────────────────┐
│                    MODAL FORM (AJAX)                        │
│                                                             │
│  Employee: Andi - Manager                                  │
│  Periode: Februari 2026                                    │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐  │
│  │ PENDAPATAN                                          │  │
│  │                                                     │  │
│  │ Gaji Pokok: Rp 10.000.000 [READ-ONLY]             │  │
│  │ Adjustment: [+/-] [Nominal] [Description]          │  │
│  │                                                     │  │
│  │ Tunjangan Prestasi: Rp 2.000.000 [CALCULATED]     │  │
│  │ Adjustment: [+/-] [Nominal] [Description]          │  │
│  │ ...                                                 │  │
│  └─────────────────────────────────────────────────────┘  │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐  │
│  │ PENGELUARAN                                         │  │
│  │                                                     │  │
│  │ Potongan Absensi: Rp 500.000 [CALCULATED]         │  │
│  │ Adjustment: [+/-] [Nominal] [Description]          │  │
│  │ ...                                                 │  │
│  └─────────────────────────────────────────────────────┘  │
│                                                             │
│  [Cancel] [Save as Draft]                                  │
└─────────────────────────────────────────────────────────────┘
```

## 🔄 User Journey

### 1. Masuk Hitung Gaji
**URL**: `/payroll/hitung-gaji`

**Tampilan**:
- List periode dalam bentuk cards
- Setiap card menampilkan:
  - Nama periode (Februari 2026)
  - Total karyawan
  - Sudah diproses / Belum diproses
  - Status breakdown (Draft, Preview, Approved)
  - Button "Proses Periode Ini"

**Action**: Click "Proses Periode Ini"

### 2. Proses Periode
**URL**: `/payroll/hitung-gaji/create?periode=2026-02`

**Tampilan**:
- Header dengan nama periode
- Info cara kerja
- List semua karyawan untuk periode tersebut
- Badge hijau untuk karyawan yang sudah diproses
- Badge abu-abu untuk yang belum

**Action**: Click karyawan yang belum diproses

### 3. Form Hitung Gaji (Modal)
**Loaded via AJAX**: `/payroll/hitung-gaji/form/{acuanGajiId}`

**Tampilan**:
- Employee info
- NKI calculation info (jika ada)
- Absensi calculation info (jika ada)
- Semua field dari Acuan Gaji (READ-ONLY)
- Setiap field punya input adjustment (OPTIONAL):
  - Type: +/-
  - Nominal: angka
  - Description: text (WAJIB jika ada adjustment)

**Action**: 
- Isi adjustment jika perlu
- Click "Save as Draft"

### 4. Setelah Save
**Redirect**: Kembali ke halaman proses periode

**Tampilan**:
- Karyawan yang baru diproses sekarang punya badge hijau
- Bisa lanjut proses karyawan lain
- Atau kembali ke index untuk lihat progress

### 5. Review & Approve
**URL**: `/payroll/hitung-gaji/{id}`

**Workflow**:
1. Draft → Click "Preview"
2. Preview → Review data → Click "Approve"
3. Approved → Siap generate Slip Gaji

## 📋 Fitur Utama

### Index Page
✅ List periode dari Acuan Gaji
✅ Statistics per periode:
  - Total karyawan
  - Sudah diproses
  - Belum diproses
  - Breakdown status (draft, preview, approved)
✅ Visual cards dengan icon
✅ Direct link ke proses periode

### Create Page
✅ Tampilkan semua karyawan untuk periode terpilih
✅ Visual indicator (badge hijau) untuk yang sudah diproses
✅ Tidak ada filter (periode sudah dipilih)
✅ Click karyawan → Modal form (AJAX)
✅ Form auto-load dengan data dari Acuan Gaji
✅ NKI & Absensi calculated automatically

### Form (Modal)
✅ Employee info header
✅ Calculation info (NKI, Absensi)
✅ All 25 fields with base values (read-only)
✅ Adjustment inputs per field (optional)
✅ Validation: description required if adjustment filled
✅ Save as draft

## 🎨 UI/UX Improvements

### Before (Old Flow)
❌ Index page dengan filter periode
❌ Harus pilih periode di filter
❌ Tidak jelas berapa yang sudah/belum diproses
❌ Harus create satu-satu
❌ Tidak ada overview per periode

### After (New Flow)
✅ Index page dengan list periode
✅ Periode sudah dipilih dari awal
✅ Jelas terlihat progress per periode
✅ Bisa proses banyak karyawan dalam satu periode
✅ Visual indicator untuk yang sudah diproses
✅ Better overview dan tracking

## 🔧 Technical Changes

### Controller
```php
// OLD
public function index() {
    // Return list of hitung gaji with filters
}

// NEW
public function index() {
    // Return list of periodes with statistics
    $periodes = AcuanGaji::select('periode')
                        ->distinct()
                        ->with statistics
                        ->get();
}
```

### Views
```
OLD Structure:
- index.blade.php → List hitung gaji + filters
- create.blade.php → Select from acuan gaji

NEW Structure:
- index.blade.php → List periodes with cards
- create.blade.php → List all employees for periode
```

## 📊 Data Flow

```
1. ACUAN GAJI (Generate)
   ├── Periode: 2026-02
   ├── 50 Karyawan
   └── Data lengkap per karyawan

2. HITUNG GAJI INDEX
   ├── Show periode: 2026-02
   ├── Statistics:
   │   ├── Total: 50
   │   ├── Processed: 30
   │   └── Pending: 20
   └── Click "Proses"

3. HITUNG GAJI CREATE (Periode: 2026-02)
   ├── Show all 50 employees
   ├── 30 with green badge (processed)
   ├── 20 without badge (pending)
   └── Click employee → Modal form

4. FORM (AJAX)
   ├── Load data from Acuan Gaji
   ├── Calculate NKI & Absensi
   ├── Show all fields (read-only)
   ├── User adds adjustments (optional)
   └── Save as Draft

5. WORKFLOW
   Draft → Preview → Approved → Slip Gaji
```

## ✅ Benefits

1. **Simpler Navigation**
   - One click to select periode
   - No confusion with filters
   - Clear path: periode → employees → form

2. **Better Overview**
   - See all periodes at once
   - Know progress per periode
   - Visual indicators for status

3. **Efficient Processing**
   - Process multiple employees in one session
   - No need to go back to index
   - Stay in same periode until done

4. **Clear Status**
   - Green badge = processed
   - Gray = pending
   - Status breakdown visible

5. **User-Friendly**
   - Less clicks
   - Less confusion
   - Better UX

## 🚀 Next Steps

1. ✅ Periode-based index
2. ✅ Employee list per periode
3. ✅ Modal form with AJAX
4. ✅ Visual indicators
5. ⏳ Import/Export per periode
6. ⏳ Bulk approve
7. ⏳ Slip Gaji generation

---

**Last Updated**: 2026-02-24
**Status**: ✅ IMPLEMENTED
**Commit**: e68cd71
