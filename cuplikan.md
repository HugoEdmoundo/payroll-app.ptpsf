# 5.3 Cuplikan Kode Program

Dokumentasi ini menampilkan 10 cuplikan kode inti dari aplikasi payroll. Fokusnya pada logika karyawan, perhitungan gaji, slip gaji, dan integrasi data.

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
    $fieldsToCheck = [
        'email', 'no_telp', 'jenis_karyawan', 'jabatan',
        'lokasi_kerja', 'bank', 'no_rekening'
    ];

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

static::updating(function ($karyawan) {
    if (request()->has('join_date') && request()->join_date != $karyawan->getOriginal('join_date')->format('Y-m-d')) {
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

## 4. Model Karyawan: Pengaturan gaji dan masa kerja

File: `app/Models/Karyawan.php`

Model ini mengarahkan `Karyawan` ke pengaturan gaji yang tepat untuk status pegawai saat ini.

```php
public function getPengaturanGaji()
{
    $statusPegawai = $this->status_pegawai;

    if (in_array($statusPegawai, ['Harian', 'OJT'])) {
        return \App\Models\PengaturanGajiStatusPegawai::where('status_pegawai', $statusPegawai)
            ->where('lokasi_kerja', $this->lokasi_kerja)
            ->first();
    }

    return \App\Models\PengaturanGaji::where('jenis_karyawan', $this->jenis_karyawan)
        ->where('jabatan', $this->jabatan)
        ->where('lokasi_kerja', $this->lokasi_kerja)
        ->first();
}

public function getMasaKerjaReadableAttribute()
{
    if (!$this->join_date) {
        return '0 Bulan 0 Hari';
    }

    $now = Carbon::now();
    $join = Carbon::parse($this->join_date);
    $diff = $join->diff($now);
    $totalMonths = ($diff->y * 12) + $diff->m;

    return $totalMonths . ' Bulan ' . $diff->d . ' Hari';
}
```

Penjelasan:
- `Harian` dan `OJT` menggunakan `PengaturanGajiStatusPegawai`.
- `Masa Kerja` disajikan dalam format `X Bulan Y Hari`.

## 5. Model HitungGaji: Kalkulasi total otomatis

File: `app/Models/HitungGaji.php`

Model ini mengakumulasi pendapatan, pengeluaran, dan gaji bersih saat menyimpan.

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

    $pengeluaranFields = [
        'koperasi', 'kasbon', 'umroh', 'kurban',
        'mutabaah', 'potongan_absensi', 'potongan_kehadiran'
    ];
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
- Otomatisasi ini mengurangi risiko perhitungan manual.
- `adjustments` diproses sebagai bagian dari setiap penyimpanan.

## 6. Daftar periode payroll dan statistik

File: `app/Http/Controllers/Payroll/HitungGajiController.php`

Halaman `Hitung Gaji` menampilkan periode berdasarkan `AcuanGaji`.

```php
$periodes = AcuanGaji::select('periode')
                    ->distinct()
                    ->orderBy('periode', 'desc')
                    ->get()
                    ->map(function($item) {
                        return [
                            'periode' => $item->periode,
                            'total_karyawan' => HitungGaji::where('periode', $item->periode)->count(),
                        ];
                    });
```

Penjelasan:
- Periode payroll mengikuti `AcuanGaji` yang tersedia.
- Total karyawan per periode dihitung dari `HitungGaji`.

## 7. Load modal Hitung Gaji dengan data terkait

File: `app/Http/Controllers/Payroll/HitungGajiController.php`

Data modal disusun dari `AcuanGaji`, `Karyawan`, `PengaturanGaji`, `NKI`, dan `Absensi`.

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

$potonganAbsensi = 0;
if ($absensi) {
    $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
    $gajiPokok = $pengaturan->gaji_pokok ?? 0;
    $operasional = $isStatusPegawai ? 0 : ($pengaturan->tunjangan_operasional ?? 0);
    $baseAmount = $gajiPokok + $tunjanganPrestasi + $operasional;
    if ($absensi->jumlah_hari_bulan > 0) {
        $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
    }
}
```

Penjelasan:
- Data modal siap untuk create/edit dengan nilai acuan terbaru.
- `tunjangan_prestasi` dan `potongan_absensi` dihitung sebelum rendering.

## 8. Simpan Hitung Gaji dengan penyesuaian

File: `app/Http/Controllers/Payroll/HitungGajiController.php`

Saat menyimpan payroll, penyesuaian (`adjustments`) diproses menjadi struktur JSON.

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
    'bpjs_kesehatan' => $acuanGaji->bpjs_kesehatan,
    'bpjs_kecelakaan_kerja' => $acuanGaji->bpjs_kecelakaan_kerja,
    'bpjs_kematian' => $acuanGaji->bpjs_kematian,
    'bpjs_jht' => $acuanGaji->bpjs_jht,
    'bpjs_jp' => $acuanGaji->bpjs_jp,
    'tunjangan_prestasi' => $tunjanganPrestasi,
    'tunjangan_konjungtur' => $acuanGaji->tunjangan_konjungtur,
    'benefit_ibadah' => $acuanGaji->benefit_ibadah,
    'benefit_komunikasi' => $acuanGaji->benefit_komunikasi,
    'benefit_operasional' => $acuanGaji->benefit_operasional,
    'reward' => $acuanGaji->reward,
    'koperasi' => $acuanGaji->koperasi,
    'kasbon' => $acuanGaji->kasbon,
    'umroh' => $acuanGaji->umroh,
    'kurban' => $acuanGaji->kurban,
    'mutabaah' => $acuanGaji->mutabaah,
    'potongan_absensi' => $potonganAbsensi,
    'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
    'adjustments' => $processedAdjustments,
    'status' => 'approved',
    'approved_at' => now(),
    'approved_by' => auth()->id(),
    'keterangan' => $request->keterangan,
]);
```

Penjelasan:
- Semua data payroll berasal dari `AcuanGaji` dengan penyesuaian NKI/absensi.
- `adjustments` disimpan sebagai JSON terstruktur untuk setiap field.

## 9. Filter slip gaji berdasarkan periode, lokasi kerja, dan jabatan

File: `app/Http/Controllers/Payroll/SlipGajiController.php`

Slip gaji mendukung pencarian global dan filter `lokasi_kerja` serta `jabatan`.

```php
$query = HitungGaji::with(['karyawan'])
                  ->where('periode', $periode);

if ($request->has('search') && $request->search != '') {
    $query = $this->applyGlobalSearch($query, $request->search, [
        'karyawan' => ['nama_karyawan', 'jenis_karyawan', 'lokasi_kerja', 'jabatan']
    ]);
}

if ($request->has('lokasi_kerja') && $request->lokasi_kerja != '') {
    $query->whereHas('karyawan', function($q) use ($request) {
        $q->where('lokasi_kerja', $request->lokasi_kerja);
    });
}

if ($request->has('jabatan') && $request->jabatan != '') {
    $query->whereHas('karyawan', function($q) use ($request) {
        $q->where('jabatan', $request->jabatan);
    });
}
```

Penjelasan:
- UI slip gaji dapat dibatasi berdasarkan lokasi kerja dan jabatan.
- Data karyawan dimuat dengan relasi `karyawan` agar tampilan lengkap.

## 10. Generate PDF slip gaji dari view khusus

File: `app/Http/Controllers/Payroll/SlipGajiController.php`

Slip gaji diekspor ke PDF dengan view `payroll.slip-gaji.pdf`.

```php
$pdf = Pdf::loadView('payroll.slip-gaji.pdf', compact('data'))
          ->setPaper('a4', 'landscape');

$filename = 'slip-gaji-' . str_replace(' ', '-', $hitungGaji->karyawan->nama_karyawan) . '-' . $hitungGaji->periode . '.pdf';
return $pdf->download($filename);
```

Penjelasan:
- File PDF dibuat dari view `payroll.slip-gaji.pdf`.
- Nama file mengikuti format `slip-gaji-{nama}-{periode}.pdf`.












https://i.pinimg.com/736x/4f/5a/1e/4f5a1e30cb6d49bfb1f957d81405e627.jpg

cdn2.vid7me.com/o9u0p2Bl1 