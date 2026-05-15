# 5.3 Cuplikan Kode Program

Dokumentasi ini menampilkan 10 cuplikan kode inti dari aplikasi payroll. Fokusnya pada logika karyawan, perhitungan gaji, slip gaji, dan otomatisasi dependensi.

## 1. Manajemen Karyawan: Validasi dan duplikasi

File: `app/Http/Controllers/KaryawanController.php`

Potongan ini menangani pembuatan karyawan dengan validasi dan pencegahan duplikasi yang cerdas.

```php
$request->validate([
    'nama_karyawan' => 'required|string|max:255',
    'email' => 'nullable|email|max:255',
    'no_telp' => 'nullable|string|max:20',
    'join_date' => 'required|date',
    'jabatan' => 'required|string',
    'lokasi_kerja' => 'required|string',
    'jenis_karyawan' => 'required|string',
    'no_rekening' => 'required|string|max:20',
    'bank' => 'required|string',
    'status_karyawan' => 'required|string',
]);

$exists = Karyawan::where('nama_karyawan', $request->nama_karyawan)->first();
if ($exists) {
    $differences = 0;
    $diffFields = [];
    $fieldsToCheck = ['email', 'no_telp', 'jenis_karyawan', 'jabatan', 'lokasi_kerja', 'bank', 'no_rekening'];

    foreach ($fieldsToCheck as $field) {
        if ($request->$field && $exists->$field != $request->$field) {
            $differences++;
            $diffFields[] = $field;
        }
    }

    if ($differences == 1) {
        return back()->withInput()->with('warning',
            "Data mirip dengan karyawan existing: {$exists->nama_karyawan}, berbeda di: " . implode(', ', $diffFields)
        );
    }

    return back()->withInput()->with('error',
        "Karyawan dengan nama '{$request->nama_karyawan}' sudah ada dalam sistem."
    );
}

$karyawan = Karyawan::create($request->all());
$this->logCreate('Karyawan', $karyawan->nama_karyawan);
```

Penjelasan:
- Menjaga kualitas data dengan memvalidasi format dan kolom wajib.
- Mendeteksi nama duplikat untuk mencegah entri ganda.
- Memberi peringatan bila data hanya berbeda satu field saja.

## 2. Pencarian dan statistik Karyawan

File: `app/Http/Controllers/KaryawanController.php`

Index karyawan juga mendukung pencarian global dan menghitung statistik status.

```php
$query = Karyawan::query();

if ($request->has('search') && $request->search != '') {
    $query = $this->applyGlobalSearch($query, $request->search, [
        'nama_karyawan',
        'email',
        'no_telp',
        'jabatan',
        'lokasi_kerja',
        'jenis_karyawan',
        'status_pegawai',
        'status_karyawan',
        'bank',
        'no_rekening',
        'npwp',
    ]);
}

$karyawan = $query->orderBy('created_at', 'desc')->paginate(10);

$stats = [
    'total' => Karyawan::count(),
    'active' => Karyawan::where('status_karyawan', 'Active')->count(),
    'non_active' => Karyawan::where('status_karyawan', 'Non-Active')->count(),
    'resign' => Karyawan::where('status_karyawan', 'Resign')->count(),
];
```

Penjelasan:
- Global search memudahkan pencarian data karyawan dari banyak kolom.
- Statistik ringkas ditampilkan pada dashboard karyawan.

## 3. Model Karyawan: Join date dan status pegawai otomatis

File: `app/Models/Karyawan.php`

Model `Karyawan` menghitung `join_date` dan `status_pegawai` secara otomatis saat membuat atau memperbarui data.

```php
static::creating(function ($karyawan) {
    if (request()->has('join_date')) {
        $tanggal = Carbon::parse(request()->join_date)->format('Y-m-d');
        $waktuSekarang = Carbon::now()->format('H:i:s');
        $karyawan->join_date = Carbon::parse($tanggal . ' ' . $waktuSekarang);
    }
    $karyawan->status_pegawai = $karyawan->calculateStatusPegawai();
});

public function calculateStatusPegawai()
{
    if (!$this->join_date) {
        return 'Kontrak';
    }

    $now = Carbon::now();
    $join = Carbon::parse($this->join_date);
    $daysSinceJoin = $join->diffInDays($now);

    if ($daysSinceJoin < 14) {
        return 'Harian';
    }

    if ($daysSinceJoin < 104) {
        return 'OJT';
    }

    return 'Kontrak';
}
```

Penjelasan:
- `join_date` di-normalisasi ke format tanggal + waktu sekarang.
- Status pegawai berubah otomatis dari `Harian` ke `OJT` lalu `Kontrak`.

## 4. Model HitungGaji: Kalkulasi total otomatis

File: `app/Models/HitungGaji.php`

Model ini memastikan total pendapatan dan pengeluaran dihitung kembali setiap kali data gaji tersimpan.

```php
static::saving(function ($model) {
    $pendapatanFields = [
        'gaji_pokok', 'bpjs_kesehatan', 'bpjs_kecelakaan_kerja',
        'bpjs_kematian', 'bpjs_jht', 'bpjs_jp',
        'tunjangan_prestasi', 'tunjangan_konjungtur', 'benefit_ibadah',
        'benefit_komunikasi', 'benefit_operasional', 'reward'
    ];

    $totalPendapatan = 0;
    foreach ($pendapatanFields as $field) {
        $value = $model->$field;
        if (isset($model->adjustments[$field])) {
            $adj = $model->adjustments[$field];
            $value += $adj['type'] === '+' ? $adj['nominal'] : -$adj['nominal'];
        }
        $totalPendapatan += $value;
    }

    $pengeluaranFields = ['koperasi', 'kasbon', 'umroh', 'kurban', 'mutabaah', 'potongan_absensi', 'potongan_kehadiran'];
    $totalPengeluaran = 0;
    foreach ($pengeluaranFields as $field) {
        $value = $model->$field;
        if (isset($model->adjustments[$field])) {
            $adj = $model->adjustments[$field];
            $value += $adj['type'] === '+' ? $adj['nominal'] : -$adj['nominal'];
        }
        $totalPengeluaran += $value;
    }

    $model->total_pendapatan = $totalPendapatan;
    $model->total_pengeluaran = $totalPengeluaran;
    $model->gaji_bersih = $totalPendapatan - $totalPengeluaran;
});
```

Penjelasan:
- Otomatisasi ini mengurangi risiko perhitungan manual di banyak tempat.
- `adjustments` diproses sebagai bagian dari setiap penyimpanan.

## 5. Load data modal payroll

File: `app/Http/Controllers/Payroll/HitungGajiController.php`

Method ini mengambil data karyawan, acuan gaji, NKI, dan absensi kemudian menyiapkan form modal untuk create/edit.

```php
$acuanGaji = AcuanGaji::where('id_karyawan', $karyawanId)
                     ->where('periode', $periode)
                     ->first();

$pengaturan = $karyawan->getPengaturanGaji();

$nki = NKI::where('id_karyawan', $karyawanId)
         ->where('periode', $periode)
         ->first();

$absensi = Absensi::where('id_karyawan', $karyawanId)
                  ->where('periode', $periode)
                  ->first();

$potonganAbsensi = ($absensi && $absensi->jumlah_hari_bulan > 0)
    ? (($absensi->absence + $absensi->tanpa_keterangan) / $absensi->jumlah_hari_bulan)
       * ($acuanGaji->gaji_pokok + $tunjanganPrestasi + $acuanGaji->benefit_operasional)
    : 0;
```

Penjelasan:
- Data modal dikumpulkan dari beberapa model agar form terlihat lengkap.
- Sistem menyesuaikan `tunjangan_prestasi` dan `potongan_absensi` sebelum rendering.

## 6. Simpan Hitung Gaji dengan penyesuaian

File: `app/Http/Controllers/Payroll/HitungGajiController.php`

Potongan ini menyimpan payroll `HitungGaji`, termasuk penyesuaian (adjustments) yang boleh positif atau negatif.

```php
$adjustments = $request->input('adjustments', []);
$processedAdjustments = [];

foreach ($adjustments as $field => $adj) {
    if (isset($adj['nominal']) && $adj['nominal'] != '' && $adj['nominal'] != 0) {
        $processedAdjustments[$field] = [
            'nominal' => (float) $adj['nominal'],
            'type' => $adj['type'] ?? '+',
            'description' => $adj['description'] ?? 'Adjustment'
        ];
    }
}

HitungGaji::create([
    'acuan_gaji_id' => $acuanGaji->id_acuan,
    'karyawan_id' => $request->karyawan_id,
    'periode' => $request->periode,
    'gaji_pokok' => $acuanGaji->gaji_pokok,
    'tunjangan_prestasi' => $tunjanganPrestasi,
    'benefit_operasional' => $acuanGaji->benefit_operasional,
    'kasbon' => $acuanGaji->kasbon,
    'potongan_absensi' => $potonganAbsensi,
    'adjustments' => $processedAdjustments,
    'status' => 'approved',
    'approved_at' => now(),
    'approved_by' => auth()->id(),
    'keterangan' => $request->keterangan,
]);
```

Penjelasan:
- Semua data payroll berasal dari `AcuanGaji` dengan update dari NKI/absensi.
- `adjustments` disimpan sebagai JSON terstruktur untuk setiap field.

## 7. Ekspor slip gaji ke PDF

File: `app/Http/Controllers/Payroll/SlipGajiController.php`

Slip gaji dibuat dalam format PDF menggunakan view khusus.

```php
$pdf = Pdf::loadView('payroll.slip-gaji.pdf', compact('data'))
          ->setPaper('a4', 'landscape');

$filename = 'slip-gaji-' . str_replace(' ', '-', $hitungGaji->karyawan->nama_karyawan) . '-' . $hitungGaji->periode . '.pdf';
return $pdf->download($filename);
```

Penjelasan:
- File PDF dibuat dari view `payroll.slip-gaji.pdf`.
- Nama file disesuaikan dengan nama karyawan dan periode.

## 8. Otomatis update status kasbon

File: `app/Observers/HitungGajiObserver.php`

Observer ini menjaga status kasbon tetap konsisten setiap kali payroll dibuat atau diperbarui.

```php
$kasbonAmount = $hitungGaji->getFinalValue('kasbon');
if ($kasbonAmount <= 0) {
    return;
}

$kasbon = Kasbon::where('id_karyawan', $hitungGaji->karyawan_id)
               ->whereIn('status_pembayaran', ['Pending', 'Cicilan', 'Lunas'])
               ->orderBy('tanggal_pengajuan', 'asc')
               ->first();

$cicilan = KasbonCicilan::firstOrNew([
    'id_kasbon' => $kasbon->id_kasbon,
    'periode' => $hitungGaji->periode,
]);
$cicilan->nominal_cicilan = $kasbonAmount;
$cicilan->tanggal_bayar = now();
$cicilan->status = 'Terbayar';
$cicilan->save();
```

Penjelasan:
- Jika payroll mencatat kasbon, observer membuat atau memperbarui cicilan.
- Status kasbon diupdate ke `Lunas` atau `Pending` sesuai total pembayaran.

## 9. Sinkron NKI ke Acuan Gaji dan Hitung Gaji

File: `app/Observers/NKIObserver.php`

Saat NKI disimpan, tunjangan prestasi dan potongan absensi dihitung ulang.

```php
$tunjanganPrestasi = 0;
if ($pengaturan->tunjangan_operasional > 0) {
    $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
}

$acuan->update(['tunjangan_prestasi' => $tunjanganPrestasi]);

if ($hitungGaji) {
    $hitungGaji->update([
        'tunjangan_prestasi' => $tunjanganPrestasi,
        'potongan_absensi' => $potonganAbsensi,
    ]);
}
```

Penjelasan:
- NKI mempengaruhi nilai prestasi dan otomatis mengubah Acuan Gaji.
- Hitung Gaji yang sudah ada ikut terupdate agar slip dan laporan tetap akurat.

## 10. Update massal ketika konfigurasi gaji berubah

File: `app/Observers/PengaturanGajiObserver.php`

Observer ini menyebarkan perubahan konfigurasi gaji ke `AcuanGaji` dan `HitungGaji`.

```php
$acuanGajiList = AcuanGaji::whereHas('karyawan', function($q) use ($pengaturan) {
    $q->where('jenis_karyawan', $pengaturan->jenis_karyawan)
      ->where('jabatan', $pengaturan->jabatan)
      ->where('lokasi_kerja', $pengaturan->lokasi_kerja);
})->get();

foreach ($acuanGajiList as $acuan) {
    $acuan->update([
        'gaji_pokok' => $pengaturan->gaji_pokok,
        'bpjs_kesehatan_pendapatan' => $pengaturan->bpjs_kesehatan,
        'benefit_operasional' => $pengaturan->tunjangan_operasional,
        'koperasi' => $pengaturan->potongan_koperasi,
    ]);

    if ($hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first()) {
        $hitungGaji->update([
            'gaji_pokok' => $acuan->gaji_pokok,
            'benefit_operasional' => $acuan->benefit_operasional,
            'tunjangan_prestasi' => $tunjanganPrestasi,
            'potongan_absensi' => $potonganAbsensi,
            'koperasi' => $acuan->koperasi,
        ]);
    }
}
```

Penjelasan:
- Perubahan tarif gaji langsung mempengaruhi semua acuan dan perhitungan gaji yang terkait.
- Ini penting agar update kebijakan gaji tidak menyebabkan data payroll usang.
