# ACUAN GAJI - DATA FLOW BY STATUS PEGAWAI

## OVERVIEW
Acuan Gaji automatically populates data based on employee's `status_pegawai` which is calculated from `join_date`:
- **Harian**: First 14 days
- **OJT**: Days 15-104 (3 months after Harian)
- **Kontrak**: After day 104 (normal employee)

---

## DATA SOURCES

### 1. PENGATURAN GAJI STATUS PEGAWAI (Harian & OJT)
**Location**: `pengaturan_gaji_status_pegawai` table
**Configuration**: Single form for both Harian & OJT by lokasi_kerja
**Fields**: `status_pegawai`, `lokasi_kerja`, `gaji_pokok`

### 2. PENGATURAN GAJI (Kontrak)
**Location**: `pengaturan_gaji` table
**Configuration**: Per jenis_karyawan + jabatan + lokasi_kerja
**Fields**: `gaji_pokok`, `tunjangan_operasional`, `tunjangan_prestasi`

### 3. PENGATURAN BPJS & KOPERASI (Global)
**Location**: `pengaturan_bpjs_koperasi` table
**Configuration**: Single global record
**Fields**: 
- `bpjs_kesehatan_pendapatan`
- `bpjs_kecelakaan_kerja_pendapatan`
- `bpjs_kematian_pendapatan`
- `bpjs_jht_pendapatan`
- `bpjs_jp_pendapatan`
- `koperasi`

### 4. NKI (Nilai Kinerja Individual)
**Location**: `nki` table
**Configuration**: Per karyawan per periode
**Fields**: `persentase_tunjangan` (used to calculate tunjangan_prestasi)

### 5. KASBON
**Location**: `kasbon` table
**Configuration**: Per karyawan with cicilan
**Fields**: Calculated potongan for the periode

---

## ACUAN GAJI FIELDS BY STATUS PEGAWAI

### STATUS: HARIAN (First 14 days)
**Source**: PengaturanGajiStatusPegawai

| Field | Value | Source |
|-------|-------|--------|
| gaji_pokok | ✅ | PengaturanGajiStatusPegawai.gaji_pokok |
| bpjs_kesehatan | ❌ 0 | - |
| bpjs_kecelakaan_kerja | ❌ 0 | - |
| bpjs_kematian | ❌ 0 | - |
| bpjs_jht | ❌ 0 | - |
| bpjs_jp | ❌ 0 | - |
| tunjangan_prestasi | ❌ 0 | - |
| tunjangan_konjungtur | ❌ 0 | - |
| benefit_ibadah | ❌ 0 | - |
| benefit_komunikasi | ❌ 0 | - |
| benefit_operasional | ❌ 0 | - |
| reward | ❌ 0 | - |
| koperasi | ❌ 0 | - |
| kasbon | ✅ | Calculated from Kasbon |
| umroh | ❌ 0 | - |
| kurban | ❌ 0 | - |
| mutabaah | ❌ 0 | - |
| potongan_absensi | ❌ 0 | - |
| potongan_kehadiran | ❌ 0 | - |

**TOTAL PENDAPATAN**: Gaji Pokok only
**TOTAL PENGELUARAN**: Kasbon only (if any)
**GAJI BERSIH**: Gaji Pokok - Kasbon

---

### STATUS: OJT (Days 15-104)
**Source**: PengaturanGajiStatusPegawai + PengaturanBpjsKoperasi

| Field | Value | Source |
|-------|-------|--------|
| gaji_pokok | ✅ | PengaturanGajiStatusPegawai.gaji_pokok |
| bpjs_kesehatan | ❌ 0 | - |
| bpjs_kecelakaan_kerja | ❌ 0 | - |
| bpjs_kematian | ❌ 0 | - |
| bpjs_jht | ❌ 0 | - |
| bpjs_jp | ❌ 0 | - |
| tunjangan_prestasi | ❌ 0 | - |
| tunjangan_konjungtur | ❌ 0 | - |
| benefit_ibadah | ❌ 0 | - |
| benefit_komunikasi | ❌ 0 | - |
| benefit_operasional | ❌ 0 | - |
| reward | ❌ 0 | - |
| koperasi | ✅ | PengaturanBpjsKoperasi.koperasi |
| kasbon | ✅ | Calculated from Kasbon |
| umroh | ❌ 0 | - |
| kurban | ❌ 0 | - |
| mutabaah | ❌ 0 | - |
| potongan_absensi | ❌ 0 | - |
| potongan_kehadiran | ❌ 0 | - |

**TOTAL PENDAPATAN**: Gaji Pokok only
**TOTAL PENGELUARAN**: Koperasi + Kasbon
**GAJI BERSIH**: Gaji Pokok - Koperasi - Kasbon

---

### STATUS: KONTRAK (After day 104 - Normal Employee)
**Source**: PengaturanGaji + PengaturanBpjsKoperasi + NKI + Kasbon

| Field | Value | Source |
|-------|-------|--------|
| gaji_pokok | ✅ | PengaturanGaji.gaji_pokok |
| bpjs_kesehatan | ✅ | PengaturanBpjsKoperasi.bpjs_kesehatan_pendapatan |
| bpjs_kecelakaan_kerja | ✅ | PengaturanBpjsKoperasi.bpjs_kecelakaan_kerja_pendapatan |
| bpjs_kematian | ✅ | PengaturanBpjsKoperasi.bpjs_kematian_pendapatan |
| bpjs_jht | ✅ | PengaturanBpjsKoperasi.bpjs_jht_pendapatan |
| bpjs_jp | ✅ | PengaturanBpjsKoperasi.bpjs_jp_pendapatan |
| tunjangan_prestasi | ✅ | **HYBRID**: If NKI exists: `PengaturanGaji.tunjangan_prestasi × NKI%`, else: `PengaturanGaji.tunjangan_prestasi` |
| tunjangan_konjungtur | ❌ 0 | Manual entry only |
| benefit_ibadah | ❌ 0 | Manual entry only |
| benefit_komunikasi | ❌ 0 | Manual entry only |
| benefit_operasional | ✅ | PengaturanGaji.tunjangan_operasional |
| reward | ❌ 0 | Manual entry only |
| koperasi | ✅ | PengaturanBpjsKoperasi.koperasi |
| kasbon | ✅ | Calculated from Kasbon |
| umroh | ❌ 0 | Manual entry only |
| kurban | ❌ 0 | Manual entry only |
| mutabaah | ❌ 0 | Manual entry only |
| potongan_absensi | ❌ 0 | Manual entry only |
| potongan_kehadiran | ❌ 0 | Manual entry only |

**TOTAL PENDAPATAN**: Gaji Pokok + ALL BPJS (5 fields) + Tunjangan Prestasi + Benefit Operasional
**TOTAL PENGELUARAN**: Koperasi + Kasbon
**GAJI BERSIH**: Total Pendapatan - Total Pengeluaran

---

## GENERATE LOGIC FLOW

```php
foreach ($karyawanList as $karyawan) {
    // 1. Get Pengaturan Gaji based on status_pegawai
    $pengaturan = $karyawan->getPengaturanGaji();
    // Returns: PengaturanGajiStatusPegawai (Harian/OJT) OR PengaturanGaji (Kontrak)
    
    // 2. Get BPJS & Koperasi (Global)
    $bpjsKoperasi = PengaturanBpjsKoperasi::first();
    
    // 3. Get NKI (for Tunjangan Prestasi calculation)
    $nki = NKI::where('id_karyawan', $karyawan->id_karyawan)
              ->where('periode', $periode)
              ->first();
    
    // 4. Calculate Tunjangan Prestasi (HYBRID)
    if ($pengaturan->tunjangan_prestasi > 0) {
        if ($nki) {
            $tunjanganPrestasi = $pengaturan->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
        } else {
            $tunjanganPrestasi = $pengaturan->tunjangan_prestasi;
        }
    }
    
    // 5. Get Kasbon
    $kasbonTotal = calculateKasbonForPeriode($karyawan, $periode);
    
    // 6. Apply BPJS & Koperasi based on status_pegawai
    if ($karyawan->status_pegawai === 'Kontrak') {
        // ALL BPJS + Koperasi
        $bpjs_kesehatan = $bpjsKoperasi->bpjs_kesehatan_pendapatan;
        $bpjs_kecelakaan_kerja = $bpjsKoperasi->bpjs_kecelakaan_kerja_pendapatan;
        $bpjs_kematian = $bpjsKoperasi->bpjs_kematian_pendapatan;
        $bpjs_jht = $bpjsKoperasi->bpjs_jht_pendapatan;
        $bpjs_jp = $bpjsKoperasi->bpjs_jp_pendapatan;
        $koperasi = $bpjsKoperasi->koperasi;
        $tunjanganOperasional = $pengaturan->tunjangan_operasional;
    } elseif ($karyawan->status_pegawai === 'OJT') {
        // Koperasi only
        $koperasi = $bpjsKoperasi->koperasi;
    }
    // Harian: all remain 0
    
    // 7. Create Acuan Gaji
    AcuanGaji::create([...]);
}
```

---

## CONFIGURATION CHECKLIST

Before generating Acuan Gaji, ensure:

### ✅ HARIAN & OJT Configuration
- [ ] Navigate to: Pengaturan Gaji → "Harian & OJT" button
- [ ] Set gaji_pokok for each lokasi_kerja for BOTH Harian and OJT
- [ ] Default: Harian = 90,000, OJT = 3,100,000

### ✅ KONTRAK Configuration
- [ ] Navigate to: Pengaturan Gaji → "Add Configuration"
- [ ] Create records for each: jenis_karyawan + jabatan + lokasi_kerja
- [ ] Set: gaji_pokok, tunjangan_operasional, tunjangan_prestasi

### ✅ BPJS & KOPERASI Configuration
- [ ] Navigate to: Pengaturan Gaji → "BPJS & Koperasi" button
- [ ] Set global values for all 5 BPJS fields + Koperasi
- [ ] These apply automatically to Kontrak (BPJS) and Kontrak+OJT (Koperasi)

### ✅ NKI Data (Optional but Recommended)
- [ ] Navigate to: NKI module
- [ ] Create NKI records for employees for the periode
- [ ] If NKI exists: tunjangan_prestasi = base × NKI%
- [ ] If no NKI: tunjangan_prestasi = base value from PengaturanGaji

---

## TROUBLESHOOTING

### Issue: Tunjangan Operasional = 0
**Cause**: PengaturanGaji.tunjangan_operasional is 0
**Solution**: Update PengaturanGaji records to add tunjangan_operasional values

### Issue: Tunjangan Prestasi = 0
**Cause**: Either no PengaturanGaji.tunjangan_prestasi OR no NKI data
**Solution**: 
1. Check PengaturanGaji has tunjangan_prestasi > 0
2. Create NKI data for the periode (optional - will use default if missing)

### Issue: BPJS = 0 for Kontrak
**Cause**: PengaturanBpjsKoperasi not configured
**Solution**: Configure BPJS & Koperasi module

### Issue: Employee skipped during generation
**Cause**: No matching PengaturanGaji or PengaturanGajiStatusPegawai
**Solution**: 
- Harian/OJT: Check PengaturanGajiStatusPegawai has record for lokasi_kerja
- Kontrak: Check PengaturanGaji has record for jenis_karyawan + jabatan + lokasi_kerja

---

## SUMMARY

**HARIAN**: Gaji Pokok only
**OJT**: Gaji Pokok + Koperasi
**KONTRAK**: Gaji Pokok + ALL BPJS (5) + Koperasi + Tunjangan Operasional + Tunjangan Prestasi (with NKI)

All calculations are AUTOMATIC based on status_pegawai!
