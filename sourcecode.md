# Source Code (Logika Inti) — Payroll App PT PSF

**Teknologi:** Laravel 11, PHP 8.2+, SQLite  
**Fitur Utama:** Karyawan, Pengaturan Gaji, Acuan Gaji, Absensi, NKI, Kasbon, Hitung Gaji, Slip Gaji, BPJS & Koperasi

---

## 1. Routes — Peta Aplikasi

Semua endpoint aplikasi didefinisikan di `routes/web.php`. Berikut struktur utamanya:

```php
// routes/web.php
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Karyawan: CRUD + Import/Export
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('create');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::get('/import', [KaryawanController::class, 'import'])->name('import');
        Route::post('/import', [KaryawanController::class, 'importStore'])->name('import.store');
        Route::get('/export', [KaryawanController::class, 'export'])->name('export');
        Route::get('/{karyawan}', [KaryawanController::class, 'show'])->name('show');
        Route::get('/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('edit');
        Route::put('/{karyawan}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('/{karyawan}', [KaryawanController::class, 'destroy'])->name('destroy');
    });

    // Payroll: Pengaturan Gaji → Acuan Gaji → Absensi → NKI → Kasbon → Hitung Gaji → Slip Gaji
    Route::prefix('payroll')->name('payroll.')->group(function () {
        // Pengaturan Gaji (konfigurasi gaji per jabatan/jenis/lokasi)
        Route::prefix('pengaturan-gaji')->name('pengaturan-gaji.')->group(function () { /* CRUD */ });
        Route::prefix('pengaturan-bpjs-koperasi')->name('pengaturan-bpjs-koperasi.')->group(function () { /* Edit/Update */ });
        Route::prefix('nki')->name('nki.')->group(function () { /* CRUD + Import/Export */ });
        Route::prefix('absensi')->name('absensi.')->group(function () { /* CRUD + Import/Export */ });
        Route::prefix('kasbon')->name('kasbon.')->group(function () { /* CRUD + Approve/Reject + Cicilan */ });
        Route::prefix('acuan-gaji')->name('acuan-gaji.')->group(function () { /* CRUD + Generate Periode */ });
        Route::prefix('hitung-gaji')->name('hitung-gaji.')->group(function () { /* Index + Periode + Modal + Store */ });
        Route::prefix('slip-gaji')->name('slip-gaji.')->group(function () { /* Index + Periode + Download PDF + Export Excel */ });
    });

    // Admin: Users, Roles, Settings, Activity Logs
    Route::prefix('admin')->name('admin.')->group(function () { /* CRUD + Permission Management */ });
});
```

---

## 2. Fase Status Pegawai (Karyawan Model)

Karyawan otomatis melewati 3 fase sejak join_date. Ini menentukan skema gaji yang dipakai.

```php
// app/Models/Karyawan.php
public function calculateStatusPegawai()
{
    $now = Carbon::now();
    $join = Carbon::parse($this->join_date);
    $daysSinceJoin = $join->diffInDays($now);

    if ($daysSinceJoin < 14)  return 'Harian';  // 14 hari pertama: 90rb/hari
    if ($daysSinceJoin < 104) return 'OJT';      // 14 + 90 hari: gaji tetap
    return 'Kontrak';                             // Setelah OJT: karyawan normal
}

// Ambil konfigurasi gaji yang sesuai dengan status pegawai
public function getPengaturanGaji()
{
    if (in_array($this->status_pegawai, ['Harian', 'OJT'])) {
        // Karyawan Harian/OJT pakai PengaturanGajiStatusPegawai (by status + lokasi)
        return PengaturanGajiStatusPegawai::where('status_pegawai', $this->status_pegawai)
            ->where('lokasi_kerja', $this->lokasi_kerja)->first();
    }
    // Karyawan Kontrak pakai PengaturanGaji (by jenis + jabatan + lokasi)
    return PengaturanGaji::where('jenis_karyawan', $this->jenis_karyawan)
        ->where('jabatan', $this->jabatan)
        ->where('lokasi_kerja', $this->lokasi_kerja)->first();
}
```

---

## 3. Komponen Gaji (Pengaturan Gaji + Pengaturan BpjsKoperasi)

### Pengaturan Gaji — Auto-hitung gaji nett & total

```php
// app/Models/PengaturanGaji.php
protected static function boot()
{
    parent::boot();
    static::saving(function ($model) {
        $model->gaji_nett = $model->gaji_pokok + ($model->tunjangan_prestasi ?? 0);
        $model->total_gaji = $model->gaji_nett;
    });
}
```

### BPJS & Koperasi — Global config (persentase)

```php
// app/Models/PengaturanBpjsKoperasi.php
class PengaturanBpjsKoperasi extends Model
{
    protected $fillable = [
        'bpjs_kesehatan_pendapatan', 'bpjs_kecelakaan_kerja_pendapatan',
        'bpjs_kematian_pendapatan', 'bpjs_jht_pendapatan', 'bpjs_jp_pendapatan',
        'koperasi',
    ];

    public function getTotalBpjsAttribute()
    {
        return $this->bpjs_kesehatan_pendapatan + $this->bpjs_kecelakaan_kerja_pendapatan +
               $this->bpjs_kematian_pendapatan + $this->bpjs_jht_pendapatan + $this->bpjs_jp_pendapatan;
    }

    public static function getGlobal() { return static::first(); }
}
```

---

## 4. NKI (Nilai Kinerja Individu) — Tunjangan Prestasi

Menilai performa karyawan. Nilai NKI menentukan persentase tunjangan prestasi.

```php
// app/Models/NKI.php
protected static function boot()
{
    parent::boot();
    static::saving(function ($model) {
        // Bobot: Kemampuan(20%) + Kontribusi_1(20%) + Kontribusi_2(40%) + Kedisiplinan(20%)
        $model->nilai_nki = ($model->kemampuan * 0.20) + ($model->kontribusi_1 * 0.20) +
                           ($model->kontribusi_2 * 0.40) + ($model->kedisiplinan * 0.20);

        if      ($model->nilai_nki >= 8.5) $model->persentase_tunjangan = 100;
        elseif  ($model->nilai_nki >= 8.0) $model->persentase_tunjangan = 80;
        else                               $model->persentase_tunjangan = 70;
    });
}
```

### Observer — Cascade ke Acuan Gaji & Hitung Gaji

```php
// app/Observers/NKIObserver.php
public function saved(NKI $nki): void
{
    $acuan = AcuanGaji::where('id_karyawan', $nki->id_karyawan)
                     ->where('periode', $nki->periode)->first();
    if (!$acuan) return;

    $karyawan = $acuan->karyawan;
    $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                               ->where('jabatan', $karyawan->jabatan)
                               ->where('lokasi_kerja', $karyawan->lokasi_kerja)->first();
    if (!$pengaturan) return;

    // Update tunjangan_prestasi di Acuan Gaji
    $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
    $acuan->update(['tunjangan_prestasi' => $tunjanganPrestasi]);

    // Cascade ke Hitung Gaji jika sudah ada
    $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
    if ($hitungGaji) {
        $absensi = Absensi::where('id_karyawan', $nki->id_karyawan)
                         ->where('periode', $nki->periode)->first();
        $potonganAbsensi = 0;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }
        $hitungGaji->update([
            'tunjangan_prestasi' => $tunjanganPrestasi,
            'potongan_absensi' => $potonganAbsensi,
        ]);
    }
}
```

---

## 5. Absensi — Potongan Kehadiran

Absensi otomatis mendeteksi jumlah hari dalam bulan dari periode (format YYYY-MM).

```php
// app/Models/Absensi.php
protected static function boot()
{
    parent::boot();
    static::saving(function ($model) {
        if ($model->periode) {
            $date = Carbon::createFromFormat('Y-m', $model->periode);
            $model->jumlah_hari_bulan = $date->daysInMonth;
        }
    });
}

// Formula potongan: (Absence + Tanpa Keterangan) / Jumlah Hari x (Gaji Pokok + Tunjangan + Operasional)
public function calculatePotongan($gajiPokok, $tunjanganPrestasi, $operasional)
{
    $totalAbsence = $this->absence + $this->tanpa_keterangan;
    $baseAmount = $gajiPokok + $tunjanganPrestasi + $operasional;
    return ($totalAbsence / $this->jumlah_hari_bulan) * $baseAmount;
}
```

### Observer — Cascade perubahan ke Acuan Gaji & Hitung Gaji

```php
// app/Observers/AbsensiObserver.php
public function saved(Absensi $absensi): void
{
    $acuan = AcuanGaji::where('id_karyawan', $absensi->id_karyawan)
                     ->where('periode', $absensi->periode)->first();
    if (!$acuan) return;

    $karyawan = $acuan->karyawan;
    $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                               ->where('jabatan', $karyawan->jabatan)
                               ->where('lokasi_kerja', $karyawan->lokasi_kerja)->first();
    if (!$pengaturan) return;

    $nki = NKI::where('id_karyawan', $absensi->id_karyawan)
             ->where('periode', $absensi->periode)->first();
    $tunjanganPrestasi = ($nki && $pengaturan->tunjangan_operasional > 0)
        ? $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100) : 0;

    $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
    $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
    $potongan = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;

    $acuan->update(['potongan_absensi' => $potongan]);

    $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
    if ($hitungGaji) $hitungGaji->update(['potongan_absensi' => $potongan]);
}
```

---

## 6. Acuan Gaji — Acuan Per Periode

Acuan Gaji adalah "base reference" per karyawan per periode. Semua komponen gaji dihitung di sini.

```php
// app/Models/AcuanGaji.php
protected static function boot()
{
    parent::boot();
    static::saving(function ($model) {
        $model->total_pendapatan = $model->gaji_pokok + $model->bpjs_kesehatan +
            $model->bpjs_kecelakaan_kerja + $model->bpjs_kematian + $model->bpjs_jht +
            $model->bpjs_jp + $model->tunjangan_prestasi + $model->tunjangan_konjungtur +
            $model->benefit_ibadah + $model->benefit_komunikasi + $model->benefit_operasional +
            $model->reward;

        $model->total_pengeluaran = $model->koperasi + $model->kasbon + $model->umroh +
            $model->kurban + $model->mutabaah + $model->potongan_absensi + $model->potongan_kehadiran;

        $model->gaji_bersih = $model->total_pendapatan - $model->total_pengeluaran;
    });
}
```

### Observer — Auto-generate Hitung Gaji

**INILAH INTI DATA CASCADE.** Saat Acuan Gaji dibuat/diubah, Hitung Gaji otomatis tergenerate/diupdate dengan kalkulasi NKI & Absensi.

```php
// app/Observers/AcuanGajiObserver.php
public function created(AcuanGaji $acuanGaji): void
{
    // Skip jika Hitung Gaji sudah ada
    if (HitungGaji::where('karyawan_id', $acuanGaji->id_karyawan)
                 ->where('periode', $acuanGaji->periode)->exists()) return;

    $karyawan = $acuanGaji->karyawan;
    $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

    // Hitung NKI: Tunjangan Prestasi x Persentase NKI
    $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
             ->where('periode', $acuanGaji->periode)->first();
    $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi;
    if (!$isStatusPegawai && $nki && $acuanGaji->tunjangan_prestasi > 0) {
        $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
    }

    // Hitung Potongan Absensi
    $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
                     ->where('periode', $acuanGaji->periode)->first();
    $potonganAbsensi = $acuanGaji->potongan_absensi;
    if ($absensi && $absensi->jumlah_hari_bulan > 0) {
        $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
        $baseAmount = $acuanGaji->gaji_pokok + $tunjanganPrestasi + $acuanGaji->benefit_operasional;
        $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
    }

    // Create Hitung Gaji otomatis
    HitungGaji::create([
        'acuan_gaji_id' => $acuanGaji->id_acuan,
        'karyawan_id' => $acuanGaji->id_karyawan,
        'periode' => $acuanGaji->periode,
        'gaji_pokok' => $acuanGaji->gaji_pokok,
        // ... semua fields dari acuan gaji
        'tunjangan_prestasi' => $tunjanganPrestasi,   // hasil kalkulasi NKI
        'potongan_absensi' => $potonganAbsensi,        // hasil kalkulasi Absensi
        'adjustments' => [],
        'status' => 'approved',
        'approved_at' => now(),
        'approved_by' => auth()->id(),
        'keterangan' => 'Auto-generated from Acuan Gaji',
    ]);
}
```

---

## 7. Hitung Gaji — KALKULASI GAJI (CORE)

### Model — Auto-hitung total pendapatan, pengeluaran, dan gaji bersih

```php
// app/Models/HitungGaji.php — THE CORE
protected static function boot()
{
    parent::boot();
    static::saving(function ($model) {
        // Total Pendapatan = semua field pendapatan + adjustments
        $totalPendapatan = 0;
        foreach (['gaji_pokok','bpjs_kesehatan','bpjs_kecelakaan_kerja','bpjs_kematian',
                  'bpjs_jht','bpjs_jp','tunjangan_prestasi','tunjangan_konjungtur',
                  'benefit_ibadah','benefit_komunikasi','benefit_operasional','reward'] as $field) {
            $value = $model->$field;
            if (isset($model->adjustments[$field])) {
                $adj = $model->adjustments[$field];
                $value += ($adj['type'] === '+') ? $adj['nominal'] : -$adj['nominal'];
            }
            $totalPendapatan += $value;
        }

        // Total Pengeluaran = semua field pengeluaran + adjustments
        $totalPengeluaran = 0;
        foreach (['koperasi','kasbon','umroh','kurban','mutabaah',
                  'potongan_absensi','potongan_kehadiran'] as $field) {
            $value = $model->$field;
            if (isset($model->adjustments[$field])) {
                $adj = $model->adjustments[$field];
                $value += ($adj['type'] === '+') ? $adj['nominal'] : -$adj['nominal'];
            }
            $totalPengeluaran += $value;
        }

        $model->total_pendapatan = $totalPendapatan;
        $model->total_pengeluaran = $totalPengeluaran;
        $model->gaji_bersih = $totalPendapatan - $totalPengeluaran; // TAKE HOME PAY
    });
}
```

### Controller — Kalkulasi lengkap untuk modal form

```php
// app/Http/Controllers/Payroll/HitungGajiController.php

// Menyiapkan data untuk modal form (bisa create baru atau edit existing)
public function getModalData($karyawanId, $periode)
{
    $karyawan = Karyawan::findOrFail($karyawanId);
    $acuanGaji = AcuanGaji::where('id_karyawan', $karyawanId)
                         ->where('periode', $periode)->first();
    if (!$acuanGaji) return response()->json(['error' => 'Acuan gaji tidak ditemukan'], 404);

    $pengaturan = $karyawan->getPengaturanGaji();
    if (!$pengaturan) return response()->json(['error' => 'Pengaturan gaji tidak ditemukan'], 404);

    $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

    // KALKULASI NKI: Tunjangan Prestasi = Base x Persentase NKI
    $nki = NKI::where('id_karyawan', $karyawanId)->where('periode', $periode)->first();
    $tunjanganPrestasi = 0;
    if (!$isStatusPegawai && $nki) {
        $base = $acuanGaji->tunjangan_prestasi ?? 0;
        if ($base > 0) $tunjanganPrestasi = $base * ($nki->persentase_tunjangan / 100);
    }

    // KALKULASI ABSENSI: (Absence + Tanpa Ket) / Hari x (Pokok + Tunjangan + Operasional)
    $absensi = Absensi::where('id_karyawan', $karyawanId)->where('periode', $periode)->first();
    $potonganAbsensi = 0;
    if ($absensi) {
        $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
        $gajiPokok = $pengaturan->gaji_pokok ?? 0;
        $operasional = $isStatusPegawai ? 0 : ($pengaturan->tunjangan_operasional ?? 0);
        $baseAmount = $gajiPokok + $tunjanganPrestasi + $operasional;
        if ($absensi->jumlah_hari_bulan > 0)
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
    }

    // Return data ke form modal (create atau edit)
    return view('components.hitung-gaji.modal-form', compact('data'));
}

// Simpan Hitung Gaji (create/update dengan kalkulasi ulang)
public function store(Request $request)
{
    // ... validasi
    if ($exists) return $this->updateExisting($exists, $request);

    $acuanGaji = AcuanGaji::where('id_karyawan', $request->karyawan_id)
                         ->where('periode', $request->periode)->first();
    $karyawan = Karyawan::findOrFail($request->karyawan_id);

    // Kalkulasi ulang NKI & Absensi (sama seperti di getModalData)
    $tunjanganPrestasi = /* ... kalkulasi NKI ... */;
    $potonganAbsensi = /* ... kalkulasi Absensi ... */;

    // Proses adjustments dari form
    $processedAdjustments = [];
    foreach ($request->input('adjustments', []) as $field => $adj) {
        if (isset($adj['nominal']) && $adj['nominal'] != '' && $adj['nominal'] != 0) {
            $processedAdjustments[$field] = [
                'nominal' => (float) $adj['nominal'],
                'type' => $adj['type'] ?? '+',
                'description' => $adj['description'] ?? 'Adjustment'
            ];
        }
    }

    // Simpan dengan status langsung approved
    HitungGaji::create([ /* semua fields + hasil kalkulasi + adjustments */ ]);
}
```

### Observer — Auto-update Status Kasbon

Saat Hitung Gaji dibuat/diubah, sistem otomatis mencatat cicilan kasbon.

```php
// app/Observers/HitungGajiObserver.php
class HitungGajiObserver
{
    public function created(HitungGaji $hitungGaji): void
    {
        $this->updateKasbonStatus($hitungGaji);
    }

    private function updateKasbonStatus(HitungGaji $hitungGaji)
    {
        $kasbonAmount = $hitungGaji->getFinalValue('kasbon');
        if ($kasbonAmount <= 0) return;

        $kasbon = Kasbon::where('id_karyawan', $hitungGaji->karyawan_id)
                       ->whereIn('status_pembayaran', ['Pending', 'Cicilan', 'Lunas'])
                       ->orderBy('tanggal_pengajuan')->first();
        if (!$kasbon) return;

        // Buat/update cicilan untuk periode ini
        $cicilan = KasbonCicilan::where('id_kasbon', $kasbon->id_kasbon)
                               ->where('periode', $hitungGaji->periode)->first()
                ?? new KasbonCicilan();

        $cicilan->fill([
            'nominal_cicilan' => $kasbonAmount,
            'status' => 'Terbayar',
            'tanggal_bayar' => now(),
        ])->save();

        // Update status kasbon (Lunas / Pending)
        $totalPaid = KasbonCicilan::where('id_kasbon', $kasbon->id_kasbon)->sum('nominal_cicilan');
        if ($totalPaid >= $kasbon->nominal) {
            $kasbon->update(['status_pembayaran' => 'Lunas', 'sisa_cicilan' => 0]);
        } else {
            $kasbon->update(['status_pembayaran' => 'Pending', 'sisa_cicilan' => $kasbon->nominal - $totalPaid]);
        }
    }
}
```

---

## 8. Kasbon — Pinjaman Karyawan

Kasbon punya 2 metode: **Langsung** (potong sekaligus) atau **Cicilan** (dicicil tiap bulan).

```php
// app/Models/Kasbon.php
protected static function boot()
{
    parent::boot();
    static::saving(function ($model) {
        if ($model->metode_pembayaran === 'Langsung') {
            $model->jumlah_cicilan = null;
            $model->cicilan_terbayar = 0;
            $model->sisa_cicilan = 0;
        } else {
            // Cicilan: hitung sisa berdasarkan cicilan_terbayar
            $nominalPerCicilan = $model->nominal / $model->jumlah_cicilan;
            $totalTerbayar = $nominalPerCicilan * $model->cicilan_terbayar;
            $model->sisa_cicilan = $model->nominal - $totalTerbayar;
            if ($model->cicilan_terbayar >= $model->jumlah_cicilan) {
                $model->status_pembayaran = 'Lunas';
                $model->sisa_cicilan = 0;
            }
        }
    });
}

// Ambil potongan kasbon untuk periode tertentu
public function getPotonganForPeriode($periode)
{
    if ($this->metode_pembayaran === 'Langsung') {
        return $this->periode === $periode ? $this->nominal : 0;
    }
    // Cicilan: cek apakah sudah ada record cicilan untuk periode ini
    $cicilan = $this->getCicilanForPeriode($periode);
    if ($cicilan) return $cicilan->nominal_cicilan;

    if ($this->status_pembayaran !== 'Lunas' && $this->jumlah_cicilan > 0)
        return $this->nominal / $this->jumlah_cicilan;
    return 0;
}
```

---

## 9. Auth & Permission System

### Middleware — Check Permission

```php
// app/Http/Middleware/CheckPermission.php
public function handle(Request $request, Closure $next, $permission)
{
    if (!auth()->check()) return redirect()->route('login');

    $user = auth()->user();
    if (!$user->is_active) {
        auth()->logout();
        return redirect()->route('login')->with('error', 'Your account is inactive.');
    }

    if ($user->role && $user->role->is_superadmin) return $next($request);
    if ($user->hasPermission($permission)) return $next($request);
    if ($permission === 'karyawan.view') return $next($request); // default access

    abort(403, 'Unauthorized access.');
}
```

### User Model — Hierarki Pengecekan Permission

```php
// app/Models/User.php
public function hasPermission($permissionKey)
{
    if ($this->isSuperadmin()) return true;
    if (!$this->is_active) return false;

    // Priority 1: User-specific permission (granted/denied)
    $userPermission = $this->userPermissions()->where('key', $permissionKey)->first();
    if ($userPermission) return $userPermission->pivot->is_granted;

    // Priority 2: Role-based permission
    if ($this->role) return $this->role->permissions()->where('key', $permissionKey)->exists();

    return false;
}
```

---

## 10. App Service Provider — Registrasi Observer & Blade Directives

```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    // Cascade Data: Perubahan data otomatis merambat ke tabel terkait
    \App\Models\PengaturanGaji::observe(\App\Observers\PengaturanGajiObserver::class);
    \App\Models\AcuanGaji::observe(\App\Observers\AcuanGajiObserver::class);
    \App\Models\HitungGaji::observe(\App\Observers\HitungGajiObserver::class);
    \App\Models\NKI::observe(\App\Observers\NKIObserver::class);
    \App\Models\Absensi::observe(\App\Observers\AbsensiObserver::class);

    // Blade Directives untuk permission checking di view
    \Blade::directive('canDo', fn($exp) => "<?php if(auth()->user()->canDo({$exp})): ?>");
    \Blade::directive('endcanDo', fn() => "<?php endif; ?>");
    \Blade::directive('hasPermission', fn($exp) => "<?php if(auth()->user()->hasPermission({$exp})): ?>");
    \Blade::directive('endhasPermission', fn() => "<?php endif; ?>");
}
```

---

## 11. Console Command — Sinkronisasi Hitung Gaji

Artisan command `php artisan hitung-gaji:sync` untuk mensinkronisasi ulang semua data Hitung Gaji dengan Acuan Gaji, NKI, dan Absensi terbaru.

```php
// app/Console/Commands/SyncHitungGaji.php
class SyncHitungGaji extends Command
{
    protected $signature = 'hitung-gaji:sync';
    protected $description = 'Sync Hitung Gaji with Acuan Gaji + recalculate NKI & Absensi';

    public function handle()
    {
        $hitungGajiList = HitungGaji::with(['acuanGaji', 'karyawan'])->get();
        $updated = 0;

        foreach ($hitungGajiList as $hitungGaji) {
            $acuanGaji = $hitungGaji->acuanGaji;
            $karyawan = $hitungGaji->karyawan;
            if (!$acuanGaji || !$karyawan) continue;

            $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

            // Rekalkulasi NKI
            $nki = NKI::where('id_karyawan', $karyawan->id_karyawan)
                     ->where('periode', $hitungGaji->periode)->first();
            $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi;
            if (!$isStatusPegawai && $nki && $acuanGaji->tunjangan_prestasi > 0)
                $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);

            // Rekalkulasi Absensi
            $absensi = Absensi::where('id_karyawan', $karyawan->id_karyawan)
                             ->where('periode', $hitungGaji->periode)->first();
            $potonganAbsensi = $acuanGaji->potongan_absensi;
            if ($absensi && $absensi->jumlah_hari_bulan > 0) {
                $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
                $baseAmount = $acuanGaji->gaji_pokok + $tunjanganPrestasi + $acuanGaji->benefit_operasional;
                $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
            }

            $hitungGaji->update([ /* semua fields */ ]);
            $updated++;
        }

        $this->info("Successfully synchronized {$updated} records.");
    }
}
```

---

## Ringkasan Alur Data

```
Karyawan (dengan status_pegawai: Harian/OJT/Kontrak)
    │
    ▼
PengaturanGaji (konfigurasi gaji per jabatan+jenis+lokasi)
PengaturanBpjsKoperasi (persentase BPJS global)
PengaturanGajiStatusPegawai (gaji khusus Harian/OJT per lokasi)
    │
    ▼
NKI (nilai kinerja → persentase tunjangan) ──┐
Absensi (kehadiran → potongan absensi) ──────┤
Kasbon (pinjaman → cicilan per periode) ─────┤
AcuanGaji (base reference per periode) ───────┤
                                              ▼
                                      HitungGaji (kalkulasi final)
                                          │ (total_pendapatan - total_pengeluaran = gaji_bersih)
                                          ▼
                                      SlipGaji (output PDF/Excel)
```

**Data Cascade:** Setiap perubahan di NKI, Absensi, atau Acuan Gaji otomatis merambat ke Hitung Gaji melalui Observer pattern.
