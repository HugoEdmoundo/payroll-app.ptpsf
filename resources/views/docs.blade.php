<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi — Payroll PSF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81' },
                        secondary: { 50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd', 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9', 800: '#5b21b6', 900: '#4c1d95' }
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        html { scroll-behavior: smooth; scroll-padding-top: 4.5rem; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f9fafb; min-height: 100vh; }

        /* ─── GLASS ─── */
        .glass { background: rgba(255,255,255,0.75); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.35); }
        .glass-strong { background: rgba(255,255,255,0.88); backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px); border-bottom: 1px solid rgba(226,232,240,0.5); }
        .glass-sidebar { background: rgba(255,255,255,0.6); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-right: 1px solid rgba(255,255,255,0.3); }
        .glass-card { background: rgba(255,255,255,0.5); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); }

        /* ─── SIDEBAR ─── */
        .sidebar-link { display: flex; align-items: center; gap: 0.5rem; padding: 0.45rem 0.75rem; margin: 0 0.375rem; font-size: 0.8125rem; color: #475569; border-radius: 0.5rem; transition: all 0.15s; }
        .sidebar-link:hover { background: rgba(255,255,255,0.6); color: #4f46e5; }
        .sidebar-link.active { background: rgba(238,242,255,0.6); color: #4f46e5; font-weight: 600; backdrop-filter: blur(8px); }
        .sidebar-category { padding: 1.25rem 0.75rem 0.375rem; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #94a3b8; }

        /* ─── TOC ─── */
        .toc-link { display: block; font-size: 0.8125rem; color: #94a3b8; padding: 0.2rem 0; border-left: 2px solid transparent; padding-left: 0.875rem; transition: all 0.15s; }
        .toc-link:hover { color: #4f46e5; }
        .toc-link.active { color: #4f46e5; border-left-color: #6366f1; font-weight: 500; }
        .toc-link.h2 { font-weight: 500; }
        .toc-link.h3 { padding-left: 1.75rem; }

        /* ─── CONTENT ─── */
        .content h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem; letter-spacing: -0.02em; }
        .content h2 { font-size: 1.375rem; font-weight: 700; color: #0f172a; margin-top: 3rem; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; }
        .content h3 { font-size: 1.0625rem; font-weight: 600; color: #1e293b; margin-top: 1.75rem; margin-bottom: 0.5rem; }
        .content h4 { font-size: 0.9375rem; font-weight: 600; color: #1e293b; margin-top: 1.25rem; margin-bottom: 0.375rem; }
        .content p { font-size: 0.9375rem; color: #475569; line-height: 1.75; margin-bottom: 0.875rem; }
        .content ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 0.875rem; }
        .content ul li { font-size: 0.9375rem; color: #475569; line-height: 1.75; }
        .content ul li + li { margin-top: 0.25rem; }
        .content ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 0.875rem; }
        .content ol li { font-size: 0.9375rem; color: #475569; line-height: 1.75; }
        .content ol li + li { margin-top: 0.25rem; }
        .content code:not(pre code) { font-size: 0.8125rem; background: rgba(238,242,255,0.5); padding: 0.125rem 0.375rem; border-radius: 0.25rem; color: #4f46e5; font-weight: 500; font-family: 'JetBrains Mono', ui-monospace, monospace; }
        .content pre { background: #0f172a; color: #e2e8f0; border-radius: 0.75rem; padding: 1.125rem; overflow-x: auto; margin-bottom: 1.25rem; font-size: 0.8125rem; line-height: 1.6; border: 1px solid rgba(30,41,59,0.5); }
        .content pre code { background: none; color: #e2e8f0; padding: 0; font-size: 0.8125rem; font-weight: 400; font-family: 'JetBrains Mono', ui-monospace, monospace; }
        .content strong { color: #1e293b; font-weight: 600; }
        .content blockquote { border-left: 3px solid #6366f1; background: rgba(238,242,255,0.5); backdrop-filter: blur(8px); border-radius: 0 0.625rem 0.625rem 0; padding: 0.875rem 1.25rem; margin-bottom: 1.25rem; }
        .content blockquote p { color: #4338ca; margin-bottom: 0; font-size: 0.875rem; }
        .content table { width: 100%; border-collapse: collapse; margin-bottom: 1.25rem; font-size: 0.875rem; border-radius: 0.75rem; overflow: hidden; border: 1px solid #e2e8f0; }
        .content th { background: #f8fafc; text-align: left; padding: 0.625rem 0.875rem; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0; font-size: 0.8125rem; }
        .content td { padding: 0.625rem 0.875rem; color: #475569; border-bottom: 1px solid #f1f5f9; font-size: 0.8125rem; }
        .content tbody tr:last-child td { border-bottom: none; }
        .content tbody tr:hover td { background: #f8fafc; }
        .content tbody tr:nth-child(even) td { background: rgba(248,250,252,0.5); }
        .content a { color: #4f46e5; }
        .content a:hover { color: #4338ca; text-decoration: underline; }

        /* ─── BADGES ─── */
        .badge { display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500; }
        .badge-green { background: #d1fae5; color: #047857; }
        .badge-indigo { background: #eef2ff; color: #4f46e5; }
        .badge-amber { background: #fef3c7; color: #b45309; }
        .badge-purple { background: #f3e8ff; color: #7e22ce; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        .badge-teal { background: #ccfbf1; color: #0f766e; }

        @media (max-width: 1023px) { .sidebar-desktop { display: none; } .toc-desktop { display: none; } }
        @media (min-width: 1024px) { .sidebar-mobile-toggle { display: none; } }
        .sidebar-mobile { transform: translateX(-100%); transition: transform 0.25s cubic-bezier(0.4,0,0.2,1); }
        .sidebar-mobile.open { transform: translateX(0); }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(203,213,225,0.5); border-radius: 4px; }

        .btt-docs { position: fixed; bottom: 2rem; right: 2rem; z-index: 40; width: 44px; height: 44px; border-radius: 50%; background: #4f46e5; color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(79,70,229,0.3); transition: all 0.2s; opacity: 0; pointer-events: none; }
        .btt-docs.show { opacity: 1; pointer-events: auto; }
        .btt-docs:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79,70,229,0.4); }
    </style>
</head>
<body>

    <!-- ═══ NAVBAR ═══ -->
    <header class="glass-strong fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center gap-3">
                    <button onclick="toggleMobile()" class="sidebar-mobile-toggle p-1.5 rounded-lg text-slate-500 hover:bg-white/60 transition" aria-label="Toggle">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
                        <span class="w-7 h-7 rounded-lg overflow-hidden shadow-sm ring-2 ring-white/80 flex-shrink-0 transition group-hover:shadow-md">
                            <img src="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg" alt="PSF" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-[10px]\'>P</div>'">
                        </span>
                        <span class="text-sm font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">PAYROLL PSF</span>
                    </a>
                    <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-indigo-50/80 text-indigo-700 border border-indigo-200/60 backdrop-blur-sm">Dokumentasi</span>
                    <span class="text-[11px] text-slate-400 font-mono hidden sm:inline">v1.0</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('landing') }}" class="text-xs text-slate-500 hover:text-indigo-600 transition hidden sm:inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-white/60"><i class="fas fa-arrow-left text-[10px]"></i> Beranda</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 transition shadow-md"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 transition shadow-md">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ═══ MOBILE OVERLAY ═══ -->
    <div id="mobileOverlay" class="fixed inset-0 z-30 bg-black/15 backdrop-blur-sm hidden" onclick="toggleMobile()"></div>

    <!-- ═══ MOBILE SIDEBAR ═══ -->
    <aside id="mobileSidebar" class="sidebar-mobile fixed top-14 left-0 z-30 w-64 h-[calc(100vh-3.5rem)] bg-white/80 backdrop-blur-2xl border-r border-white/30 overflow-y-auto shadow-xl">
        <div class="p-3 border-b border-slate-100/50">
            <div class="relative">
                <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                <input type="text" id="mobileSearch" placeholder="Cari..." oninput="filterDocs(this.value)" class="w-full pl-7 pr-2 py-1.5 text-xs border border-slate-200/60 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 bg-white/60 focus:bg-white transition">
            </div>
        </div>
        <nav class="p-2 space-y-0.5" id="mobileNav">@include('docs-sidebar')</nav>
    </aside>

    <!-- ═══ LAYOUT ═══ -->
    <div class="pt-14">
        <div class="max-w-7xl mx-auto flex">

            <!-- ═══ DESKTOP SIDEBAR ═══ -->
            <aside class="sidebar-desktop w-60 flex-shrink-0 glass-sidebar h-[calc(100vh-3.5rem)] sticky top-14 overflow-y-auto sidebar-scroll">
                <div class="p-3 border-b border-white/20">
                    <div class="relative">
                        <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                        <input type="text" id="docSearch" placeholder="Cari dokumentasi..." oninput="filterDocs(this.value)" class="w-full pl-7 pr-7 py-1.5 text-xs border border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 bg-white/50 focus:bg-white transition">
                    </div>
                </div>
                <nav class="p-2 space-y-0.5" id="sidebarNav">@include('docs-sidebar')</nav>
            </aside>

            <!-- ═══ MAIN CONTENT ═══ -->
            <main class="flex-1 min-w-0 px-5 sm:px-8 md:px-12 py-8 md:py-12">
                <div class="content max-w-3xl mx-auto">

                    <!-- OVERVIEW -->
                    <section id="overview" data-section="overview">
                        <h1>Gambaran Umum</h1>
                        <p class="text-slate-400 text-xs mb-6">Pengenalan Sistem Payroll Terpadu PT Putra Sinar Fisip.</p>
                        <p><strong>Sistem Payroll Terpadu</strong> adalah aplikasi berbasis web untuk mengelola penggajian karyawan secara terstruktur, fleksibel, dan terintegrasi — dari pengaturan gaji, acuan gaji, hitung gaji, penyesuaian, hingga slip gaji.</p>
                        <p>Dibangun dengan <strong>Laravel 11</strong>, <strong>PHP 8.2+</strong>, <strong>Tailwind CSS</strong>, dan <strong>Alpine.js</strong>. Basis data menggunakan <strong>SQLite</strong> dengan Eloquent ORM.</p>

                        <div class="grid grid-cols-2 gap-3 my-6">
                            <div class="glass-card rounded-xl p-4 hover:shadow-md transition-all"><div class="flex items-center gap-2.5 mb-1.5"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center text-indigo-600"><i class="fas fa-th-large text-xs"></i></div><span class="text-sm font-semibold text-slate-900">Data Master</span></div><p class="text-xs text-slate-500">Karyawan, pengaturan gaji, BPJS, wilayah, status</p></div>
                            <div class="glass-card rounded-xl p-4 hover:shadow-md transition-all"><div class="flex items-center gap-2.5 mb-1.5"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center text-amber-600"><i class="fas fa-calendar-alt text-xs"></i></div><span class="text-sm font-semibold text-slate-900">Input Bulanan</span></div><p class="text-xs text-slate-500">Absensi, NKI, Kasbon per periode</p></div>
                            <div class="glass-card rounded-xl p-4 hover:shadow-md transition-all"><div class="flex items-center gap-2.5 mb-1.5"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center text-purple-600"><i class="fas fa-cogs text-xs"></i></div><span class="text-sm font-semibold text-slate-900">Payroll Engine</span></div><p class="text-xs text-slate-500">Acuan &rarr; Hitung &rarr; Slip Gaji</p></div>
                            <div class="glass-card rounded-xl p-4 hover:shadow-md transition-all"><div class="flex items-center gap-2.5 mb-1.5"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center text-emerald-600"><i class="fas fa-file-export text-xs"></i></div><span class="text-sm font-semibold text-slate-900">Output</span></div><p class="text-xs text-slate-500">Slip PDF & Excel, laporan rekap</p></div>
                        </div>
                    </section>

                    <!-- INSTALASI -->
                    <section id="instalasi" data-section="instalasi">
                        <h2>Instalasi & Setup</h2>
                        <h3>Persyaratan Sistem</h3>
                        <ul><li>PHP ^8.2</li><li>Composer</li><li>Node.js ^20</li><li>SQLite / MySQL / PostgreSQL</li><li>Laragon / XAMPP / Valet</li></ul>
                        <h3>Langkah Instalasi</h3>
                        <ol>
                            <li><code>git clone https://github.com/HugoEdmoundo/payroll-app.ptpsf.git</code></li>
                            <li><code>cd payroll-app.ptpsf</code></li>
                            <li><code>composer install</code></li>
                            <li><code>copy .env.example .env</code></li>
                            <li><code>php artisan key:generate</code></li>
                            <li><code>php artisan migrate</code></li>
                            <li><code>npm install && npm run build</code></li>
                            <li><code>php artisan serve</code></li>
                        </ol>
                        <blockquote><p>Pastikan <code>database/database.sqlite</code> sudah ada sebelum migrasi.</p></blockquote>
                    </section>

                    <!-- AUTENTIKASI -->
                    <section id="autentikasi" data-section="autentikasi">
                        <h2>Autentikasi</h2>
                        <h3>Login</h3>
                        <p>Masukkan email dan password yang telah didaftarkan administrator.</p>
                        <h3>Session & Akses</h3>
                        <p>Sesi aktif hingga logout. Dua tingkat akses: <strong>Superadmin</strong> (full) dan <strong>User</strong> (terbatas). Detail di <a href="#pengguna-role">Pengguna & Role</a>.</p>
                    </section>

                    <!-- ALUR DATA -->
                    <section id="alur-data" data-section="alur-data">
                        <h2>Alur Data & Cascade</h2>
                        <p>Sistem menggunakan pendekatan <strong>Source of Truth</strong> pada modul Pengaturan Gaji.</p>
                        <pre>Karyawan (Harian/OJT/Kontrak)
  |
  v
Salary Templates + Pengaturan BpjsKoperasi
  |
  v
NKI + Absensi + Kasbon + Acuan Gaji
  |
  v
Hitung Gaji (kalkulasi final)
  |
  v
Slip Gaji (PDF/Excel)</pre>
                        <p><strong>Data Cascade:</strong> Perubahan pada NKI, Absensi, atau Acuan Gaji otomatis merambat ke Hitung Gaji via Observer Pattern.</p>
                    </section>

                    <!-- STRUKTUR DATABASE -->
                    <section id="struktur-database" data-section="struktur-database">
                        <h2>Struktur Database</h2>
                        <p><strong>11 tabel utama</strong> dengan SQLite:</p>
                        <table><thead><tr><th>Tabel</th><th>PK</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>karyawan</code></td><td><code>id_karyawan</code></td><td>Data master karyawan</td></tr>
                            <tr><td><code>salary_templates</code></td><td><code>id</code></td><td>Template gaji (standard & status)</td></tr>
                            <tr><td><code>pengaturan_bpjs_koperasi</code></td><td><code>id</code></td><td>Konfigurasi BPJS & koperasi</td></tr>
                            <tr><td><code>master_wilayah</code></td><td><code>id</code></td><td>Data wilayah</td></tr>
                            <tr><td><code>master_status_pegawai</code></td><td><code>id</code></td><td>Data status pegawai</td></tr>
                            <tr><td><code>acuan_gaji</code></td><td><code>id_acuan</code></td><td>Acuan gaji per periode</td></tr>
                            <tr><td><code>hitung_gaji</code></td><td><code>id</code></td><td>Hasil hitung + penyesuaian</td></tr>
                            <tr><td><code>slip_gaji</code></td><td><code>id</code></td><td>Slip gaji final</td></tr>
                            <tr><td><code>absensi</code></td><td><code>id_absensi</code></td><td>Absensi per periode</td></tr>
                            <tr><td><code>nki</code></td><td><code>id_nki</code></td><td>Nilai Kinerja Individu</td></tr>
                            <tr><td><code>kasbon</code></td><td><code>id_kasbon</code></td><td>Pinjaman karyawan</td></tr>
                        </tbody></table>
                        <blockquote><p>Unique constraint <code>(karyawan_id, periode)</code> di setiap tabel transaksi.</p></blockquote>

                        <h3>Relasi Antar Tabel</h3>
                        <table><thead><tr><th>Dari</th><th>Ke</th><th>Tipe</th><th>FK</th></tr></thead><tbody>
                            <tr><td><code>acuan_gaji</code></td><td><code>karyawan</code></td><td>N:1</td><td><code>id_karyawan</code></td></tr>
                            <tr><td><code>hitung_gaji</code></td><td><code>acuan_gaji</code></td><td>N:1</td><td><code>acuan_gaji_id</code></td></tr>
                            <tr><td><code>hitung_gaji</code></td><td><code>karyawan</code></td><td>N:1</td><td><code>karyawan_id</code></td></tr>
                            <tr><td><code>slip_gaji</code></td><td><code>hitung_gaji</code></td><td>N:1</td><td><code>hitung_gaji_id</code></td></tr>
                            <tr><td><code>absensi</code></td><td><code>karyawan</code></td><td>N:1</td><td><code>id_karyawan</code></td></tr>
                            <tr><td><code>kasbon</code></td><td><code>karyawan</code></td><td>N:1</td><td><code>id_karyawan</code></td></tr>
                            <tr><td><code>nki</code></td><td><code>karyawan</code></td><td>N:1</td><td><code>id_karyawan</code></td></tr>
                        </tbody></table>
                    </section>

                    <!-- OBSERVER -->
                    <section id="observer" data-section="observer">
                        <h2>Pola Observer</h2>
                        <p>Data cascade otomatis via <strong>Eloquent Observer</strong>:</p>
                        <table><thead><tr><th>Observer</th><th>Model</th><th>Fungsi</th></tr></thead><tbody>
                            <tr><td><code>NKIObserver</code></td><td>NKI</td><td>Update tunjangan_prestasi di Acuan Gaji & Hitung Gaji</td></tr>
                            <tr><td><code>AbsensiObserver</code></td><td>Absensi</td><td>Kalkulasi ulang potongan_absensi</td></tr>
                            <tr><td><code>AcuanGajiObserver</code></td><td>Acuan Gaji</td><td>Generate otomatis Hitung Gaji</td></tr>
                            <tr><td><code>HitungGajiObserver</code></td><td>Hitung Gaji</td><td>Update status cicilan Kasbon</td></tr>
                        </tbody></table>
                        <pre>PengaturanGaji::observe(PengaturanGajiObserver::class);
AcuanGaji::observe(AcuanGajiObserver::class);
HitungGaji::observe(HitungGajiObserver::class);
NKI::observe(NKIObserver::class);
Absensi::observe(AbsensiObserver::class);</pre>
                    </section>

                    <!-- KARYAWAN -->
                    <section id="karyawan" data-section="karyawan">
                        <h2>Karyawan</h2>
                        <p>Pusat data master karyawan dengan informasi personal, employment, dan kompensasi.</p>
                        <table><thead><tr><th>Field</th><th>Tipe</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>id_karyawan</code></td><td>PK</td><td>Primary key</td></tr>
                            <tr><td><code>nama_karyawan</code></td><td>string</td><td>Nama lengkap</td></tr>
                            <tr><td><code>email</code></td><td>string</td><td>Email (nullable)</td></tr>
                            <tr><td><code>join_date</code></td><td>datetime</td><td>Tanggal mulai bekerja</td></tr>
                            <tr><td><code>jabatan</code></td><td>string</td><td>Jabatan / posisi</td></tr>
                            <tr><td><code>lokasi_kerja</code></td><td>string</td><td>Wilayah kerja</td></tr>
                            <tr><td><code>jenis_karyawan</code></td><td>string</td><td>Konsultan, Organik, Teknisi, Borongan</td></tr>
                            <tr><td><code>status_pegawai</code></td><td>string</td><td>Harian/OJT/Kontrak (otomatis)</td></tr>
                            <tr><td><code>npwp</code></td><td>string</td><td>NPWP (nullable)</td></tr>
                            <tr><td><code>bpjs_kesehatan_no</code></td><td>string</td><td>No BPJS Kesehatan</td></tr>
                            <tr><td><code>bpjs_tk_no</code></td><td>string</td><td>No BPJS Ketenagakerjaan</td></tr>
                            <tr><td><code>no_rekening</code></td><td>string</td><td>Nomor rekening</td></tr>
                            <tr><td><code>bank</code></td><td>string</td><td>Nama bank</td></tr>
                            <tr><td><code>status_karyawan</code></td><td>string</td><td>Aktif / Nonaktif</td></tr>
                        </tbody></table>

                        <h3>Status Pegawai Otomatis</h3>
                        <pre>class Karyawan extends Model {
    public function calculateStatusPegawai() {
        $days = $this->join_date->diffInDays(now());
        if ($days &lt; 14)  return 'Harian';
        if ($days &lt; 104) return 'OJT';
        return 'Kontrak';
    }
}</pre>

                        <h3>CRUD & Fitur</h3>
                        <ul>
                            <li>Tambah, edit, lihat, hapus karyawan</li>
                            <li>Import/export Excel (dengan template)</li>
                            <li>Riwayat jabatan (Job Movement) tercatat otomatis</li>
                        </ul>
                        <blockquote><p>Penghapusan bersifat permanen (hard delete) — menghapus semua data terkait.</p></blockquote>
                    </section>

                    <!-- PENGATURAN GAJI -->
                    <section id="pengaturan-gaji" data-section="pengaturan-gaji">
                        <h2>Pengaturan Gaji</h2>
                        <p><strong>Source of Truth</strong> untuk komponen gaji. Disimpan di <code>salary_templates</code> dengan dua tipe: <span class="badge badge-indigo">Standard</span> dan <span class="badge badge-amber">Status</span>.</p>
                        <h3>Standard Template</h3>
                        <p>Untuk karyawan <span class="badge badge-indigo">Kontrak</span>, dikombinasikan dengan <code>jenis_karyawan</code>, <code>jabatan</code>, <code>lokasi_kerja</code>.</p>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>gaji_pokok</code></td><td>Gaji pokok bulanan</td></tr>
                            <tr><td><code>tunjangan_operasional</code></td><td>Tunjangan operasional</td></tr>
                            <tr><td><code>tunjangan_prestasi</code></td><td>Dasar tunjangan prestasi (x persentase NKI)</td></tr>
                        </tbody></table>
                        <h3>Status Template</h3>
                        <p>Untuk <span class="badge badge-amber">Harian</span> dan <span class="badge badge-amber">OJT</span>.</p>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>gaji_pokok</code></td><td>Gaji pokok (Rp90.000/hari untuk Harian)</td></tr>
                            <tr><td><code>lokasi_kerja</code></td><td>Wilayah kerja</td></tr>
                        </tbody></table>
                        <pre>$model->gaji_nett = $model->gaji_pokok + ($model->tunjangan_prestasi ?? 0);
$model->total_gaji = $model->gaji_nett;</pre>
                    </section>

                    <!-- BPJS & KOPERASI -->
                    <section id="bpjs-koperasi" data-section="bpjs-koperasi">
                        <h2>BPJS & Koperasi</h2>
                        <p>Konfigurasi global untuk semua karyawan di tabel <code>pengaturan_bpjs_koperasi</code>.</p>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>bpjs_kesehatan_pendapatan</code></td><td>BPJS Kesehatan (perusahaan)</td></tr>
                            <tr><td><code>bpjs_kecelakaan_kerja_pendapatan</code></td><td>BPJS Kecelakaan Kerja</td></tr>
                            <tr><td><code>bpjs_kematian_pendapatan</code></td><td>BPJS Kematian</td></tr>
                            <tr><td><code>bpjs_jht_pendapatan</code></td><td>BPJS JHT</td></tr>
                            <tr><td><code>bpjs_jp_pendapatan</code></td><td>BPJS JP</td></tr>
                            <tr><td><code>koperasi</code></td><td>Potongan koperasi</td></tr>
                        </tbody></table>
                        <blockquote><p>Karyawan <span class="badge badge-amber">Harian</span> & <span class="badge badge-amber">OJT</span> tidak mendapat BPJS.</p></blockquote>
                    </section>

                    <!-- WILAYAH & STATUS -->
                    <section id="master-wilayah-status" data-section="master-wilayah-status">
                        <h2>Master Wilayah & Status Pegawai</h2>
                        <h3>Master Wilayah</h3>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>kode</code></td><td>Kode wilayah (unique)</td></tr>
                            <tr><td><code>nama</code></td><td>Nama wilayah</td></tr>
                            <tr><td><code>is_active</code></td><td>Status aktif</td></tr>
                        </tbody></table>
                        <h3>Master Status Pegawai</h3>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>nama</code></td><td>Nama status</td></tr>
                            <tr><td><code>durasi_hari</code></td><td>Durasi dalam hari</td></tr>
                            <tr><td><code>gunakan_nki</code></td><td>Gunakan NKI</td></tr>
                            <tr><td><code>is_active</code></td><td>Status aktif</td></tr>
                        </tbody></table>
                    </section>

                    <!-- ABSENSI -->
                    <section id="absensi" data-section="absensi">
                        <h2>Absensi</h2>
                        <p>Mencatat kehadiran per periode untuk menghitung potongan absensi.</p>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>id_karyawan</code></td><td>FK ke karyawan</td></tr>
                            <tr><td><code>periode</code></td><td>YYYY-MM</td></tr>
                            <tr><td><code>hadir</code></td><td>Jumlah hari hadir</td></tr>
                            <tr><td><code>absence</code></td><td>Hari tidak hadir (izin)</td></tr>
                            <tr><td><code>tanpa_keterangan</code></td><td>Tanpa keterangan</td></tr>
                            <tr><td><code>potongan_absensi</code></td><td>Nominal potongan (otomatis)</td></tr>
                        </tbody></table>
                        <pre>potongan = (absence + tanpa_keterangan) / jumlah_hari_bulan
           x (gaji_pokok + tunjangan_prestasi + tunjangan_operasional)</pre>
                    </section>

                    <!-- NKI -->
                    <section id="nki" data-section="nki">
                        <h2>NKI (Nilai Kinerja Individu)</h2>
                        <p>Penilaian kinerja yang mempengaruhi <strong>Tunjangan Prestasi</strong>. Hanya untuk <span class="badge badge-indigo">Kontrak</span>.</p>
                        <table><thead><tr><th>Komponen</th><th>Bobot</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>kemampuan</code></td><td>20%</td><td>Kemampuan teknis</td></tr>
                            <tr><td><code>kontribusi_1</code></td><td>20%</td><td>Kontribusi pertama</td></tr>
                            <tr><td><code>kontribusi_2</code></td><td>40%</td><td>Kontribusi kedua</td></tr>
                            <tr><td><code>kedisiplinan</code></td><td>20%</td><td>Tingkat kedisiplinan</td></tr>
                        </tbody></table>
                        <pre>nilai_nki = (kemampuan x 0.20) + (kontribusi_1 x 0.20)
           + (kontribusi_2 x 0.40) + (kedisiplinan x 0.20)

if (nilai_nki >= 8.5) -> 100%  |  >= 8.0 -> 80%  |  < 8.0 -> 70%</pre>
                    </section>

                    <!-- KASBON -->
                    <section id="kasbon" data-section="kasbon">
                        <h2>Kasbon & Cicilan</h2>
                        <p>Dua metode: <span class="badge badge-green">Langsung</span> (potong sekali) dan <span class="badge badge-teal">Cicilan</span> (tiap bulan).</p>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>nominal</code></td><td>Jumlah pinjaman</td></tr>
                            <tr><td><code>metode_pembayaran</code></td><td>Langsung / Cicilan</td></tr>
                            <tr><td><code>status_pembayaran</code></td><td>Pending / Lunas</td></tr>
                            <tr><td><code>jumlah_cicilan</code></td><td>Jumlah cicilan</td></tr>
                            <tr><td><code>cicilan_terbayar</code></td><td>Cicilan terbayar</td></tr>
                            <tr><td><code>sisa_cicilan</code></td><td>Sisa pinjaman</td></tr>
                        </tbody></table>
                        <blockquote><p>Cicilan disimpan di <code>kasbon_cicilan</code> dengan field: <code>cicilan_ke</code>, <code>periode</code>, <code>nominal_cicilan</code>, <code>status</code>.</p></blockquote>
                    </section>

                    <!-- ACUAN GAJI -->
                    <section id="acuan-gaji" data-section="acuan-gaji">
                        <h2>Acuan Gaji</h2>
                        <p><strong>Base reference</strong> per karyawan per periode. Semua komponen dikumpulkan di sini sebelum Hitung Gaji.</p>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-emerald-50/70 rounded-xl p-4 border border-emerald-100/60 backdrop-blur-sm">
                                <h4 class="text-xs font-semibold text-emerald-800 mb-2"><i class="fas fa-arrow-up text-[10px] mr-1"></i> Pendapatan (12)</h4>
                                <ul class="text-[11px] text-emerald-700 space-y-1" style="list-style:none;padding-left:0"><li><code>gaji_pokok</code></li><li><code>bpjs_kesehatan</code></li><li><code>bpjs_kecelakaan_kerja</code></li><li><code>bpjs_kematian</code></li><li><code>bpjs_jht</code></li><li><code>bpjs_jp</code></li><li><code>tunjangan_prestasi</code></li><li><code>tunjangan_konjungtur</code></li><li><code>benefit_ibadah</code></li><li><code>benefit_komunikasi</code></li><li><code>benefit_operasional</code></li><li><code>reward</code></li></ul>
                            </div>
                            <div class="bg-red-50/70 rounded-xl p-4 border border-red-100/60 backdrop-blur-sm">
                                <h4 class="text-xs font-semibold text-red-800 mb-2"><i class="fas fa-arrow-down text-[10px] mr-1"></i> Pengeluaran (7)</h4>
                                <ul class="text-[11px] text-red-700 space-y-1" style="list-style:none;padding-left:0"><li><code>koperasi</code></li><li><code>kasbon</code></li><li><code>umroh</code></li><li><code>kurban</code></li><li><code>mutabaah</code></li><li><code>potongan_absensi</code></li><li><code>potongan_kehadiran</code></li></ul>
                            </div>
                        </div>
                        <pre>$model->total_pendapatan = sum(semua pendapatan);
$model->total_pengeluaran = sum(semua pengeluaran);
$model->gaji_bersih = total_pendapatan - total_pengeluaran;</pre>
                        <blockquote><p>Unique constraint: <code>unique(id_karyawan, periode)</code>.</p></blockquote>
                    </section>

                    <!-- HITUNG GAJI -->
                    <section id="hitung-gaji" data-section="hitung-gaji">
                        <h2>Hitung Gaji</h2>
                        <p>Inti sistem payroll — kalkulasi final dengan data Acuan Gaji + penyesuaian.</p>
                        <h3>Alur</h3>
                        <ol>
                            <li>Acuan Gaji dibuat &rarr; sistem generate Hitung Gaji (AcuanGajiObserver)</li>
                            <li>Kalkulasi ulang NKI & Absensi</li>
                            <li>Adjustment bisa ditambahkan via modal</li>
                            <li>Status: <span class="badge badge-amber">Draft</span> &rarr; <span class="badge badge-purple">Preview</span> &rarr; <span class="badge badge-green">Approved</span></li>
                        </ol>
                        <h3>Adjustments (JSON)</h3>
                        <pre>{
    "gaji_pokok": { "type": "+", "nominal": 500000, "description": "Bonus" },
    "koperasi":   { "type": "-", "nominal": 100000, "description": "Koreksi" }
}</pre>
                        <pre>total_pendapatan = sum(12 pendapatan + adjustments pendapatan)
total_pengeluaran = sum(7 pengeluaran + adjustments pengeluaran)
gaji_bersih = total_pendapatan - total_pengeluaran</pre>
                    </section>

                    <!-- PENYESUAIAN -->
                    <section id="penyesuaian" data-section="penyesuaian">
                        <h2>Penyesuaian</h2>
                        <p>Adjustment memungkinkan modifikasi komponen gaji tanpa mengubah data Acuan Gaji asli. Berguna untuk:</p>
                        <ul><li><strong>Bonus</strong> — pembayaran satu kali</li><li><strong>Denda</strong> — potongan tambahan</li><li><strong>Koreksi</strong> — error data approved</li><li><strong>Insentif</strong> — tambahan khusus</li></ul>
                    </section>

                    <!-- SLIP GAJI -->
                    <section id="slip-gaji" data-section="slip-gaji">
                        <h2>Slip Gaji</h2>
                        <p>Output akhir payroll. Setiap karyawan approved memiliki slip PDF & Excel.</p>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>nomor_slip</code></td><td>SG-YYYYMM-XXXX</td></tr>
                            <tr><td><code>take_home_pay</code></td><td>Gaji bersih</td></tr>
                            <tr><td><code>detail_pendapatan</code></td><td>JSON snapshot</td></tr>
                            <tr><td><code>detail_pengeluaran</code></td><td>JSON snapshot</td></tr>
                        </tbody></table>
                    </section>

                    <!-- PENGGUNA & ROLE -->
                    <section id="pengguna-role" data-section="pengguna-role">
                        <h2>Pengguna & Role</h2>
                        <div class="grid grid-cols-2 gap-3 my-4">
                            <div class="glass-card rounded-xl p-4"><div class="flex items-center gap-2.5 mb-2"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center text-purple-600"><i class="fas fa-user-shield text-xs"></i></div><div><span class="text-sm font-semibold text-slate-900">Superadmin</span> <span class="badge badge-purple ml-1">Full</span></div></div><ul class="text-xs text-slate-600 space-y-1" style="list-style:none;padding-left:0"><li>&check; Semua fitur payroll</li><li>&check; Manajemen pengguna</li><li>&check; Pengaturan sistem</li></ul></div>
                            <div class="glass-card rounded-xl p-4"><div class="flex items-center gap-2.5 mb-2"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-50 to-teal-100 flex items-center justify-center text-teal-600"><i class="fas fa-user text-xs"></i></div><div><span class="text-sm font-semibold text-slate-900">User</span> <span class="badge badge-amber ml-1">Terbatas</span></div></div><ul class="text-xs text-slate-600 space-y-1" style="list-style:none;padding-left:0"><li>&check; Input data payroll</li><li>&check; Export slip gaji</li><li>&cross; Tidak manage pengguna</li></ul></div>
                        </div>
                    </section>

                    <!-- HAK AKSES -->
                    <section id="hak-akses" data-section="hak-akses">
                        <h2>Hak Akses</h2>
                        <p>Hierarki pengecekan permission:</p>
                        <pre>public function hasPermission($key) {
    if ($this->isSuperadmin()) return true;
    if (!$this->is_active) return false;
    // Priority 1: User-specific permission
    // Priority 2: Role-based permission
    return false;
}</pre>
                        <p>Module permissions: <code>karyawan.view</code>, <code>karyawan.create</code>, <code>payroll.hitung-gaji</code>, <code>admin.users</code>, dll.</p>
                    </section>

                    <!-- PENGATURAN SISTEM -->
                    <section id="pengaturan-sistem" data-section="pengaturan-sistem">
                        <h2>Pengaturan Sistem</h2>
                        <p>Menu <strong>Admin &rarr; Settings</strong> untuk:</p>
                        <ul><li>Jabatan per Jenis Karyawan</li><li>Dynamic Fields custom</li><li>Konfigurasi umum aplikasi</li></ul>
                    </section>

                    <!-- LOG AKTIVITAS -->
                    <section id="log-aktivitas" data-section="log-aktivitas">
                        <h2>Log Aktivitas</h2>
                        <p>Semua aksi signifikan dicatat di <code>activity_logs</code>:</p>
                        <table><thead><tr><th>Field</th><th>Deskripsi</th></tr></thead><tbody>
                            <tr><td><code>user_id</code></td><td>Pengguna</td></tr>
                            <tr><td><code>action_type</code></td><td>create/update/delete/approve</td></tr>
                            <tr><td><code>entity_type</code></td><td>Karyawan, HitungGaji, dll</td></tr>
                            <tr><td><code>old_value</code></td><td>Data sebelum (JSON)</td></tr>
                            <tr><td><code>new_value</code></td><td>Data setelah (JSON)</td></tr>
                        </tbody></table>
                    </section>

                    <!-- IMPORT EXPORT -->
                    <section id="import-export" data-section="import-export">
                        <h2>Import & Export</h2>
                        <table><thead><tr><th>Modul</th><th>Import</th><th>Template</th><th>Export</th></tr></thead><tbody>
                            <tr><td>Karyawan</td><td>&check;</td><td>&check;</td><td>&check;</td></tr>
                            <tr><td>Acuan Gaji</td><td>&check;</td><td>&check;</td><td>&check;</td></tr>
                            <tr><td>Absensi</td><td>&check;</td><td>&check;</td><td>&check;</td></tr>
                            <tr><td>NKI</td><td>&check;</td><td>&check;</td><td>&check;</td></tr>
                            <tr><td>Kasbon</td><td>&mdash;</td><td>&mdash;</td><td>&check;</td></tr>
                            <tr><td>Slip Gaji</td><td>&mdash;</td><td>&mdash;</td><td>&check; (Excel+PDF)</td></tr>
                        </tbody></table>
                    </section>

                    <!-- FAQ -->
                    <section id="faq" data-section="faq">
                        <h2>FAQ</h2>
                        <div class="space-y-2.5">
                            <details class="group bg-white/50 rounded-xl border border-slate-200/60 overflow-hidden backdrop-blur-sm hover:border-slate-300 transition"><summary class="flex items-center justify-between px-4 py-3.5 cursor-pointer hover:bg-slate-50/40 transition"><span class="text-sm font-medium text-slate-900">Data tidak muncul setelah generate Acuan Gaji</span><i class="fas fa-chevron-down text-slate-400 group-open:rotate-180 transition text-xs"></i></summary><div class="px-4 pb-4 text-sm text-slate-600 border-t border-slate-100 pt-3"><p>Pastikan: karyawan <strong>Aktif</strong>, Pengaturan Gaji sudah diisi, periode benar (YYYY-MM).</p></div></details>
                            <details class="group bg-white/50 rounded-xl border border-slate-200/60 overflow-hidden backdrop-blur-sm hover:border-slate-300 transition"><summary class="flex items-center justify-between px-4 py-3.5 cursor-pointer hover:bg-slate-50/40 transition"><span class="text-sm font-medium text-slate-900">Duplicate entry error</span><i class="fas fa-chevron-down text-slate-400 group-open:rotate-180 transition text-xs"></i></summary><div class="px-4 pb-4 text-sm text-slate-600 border-t border-slate-100 pt-3"><p>Satu record per karyawan per periode. Gunakan periode berbeda atau edit yang sudah ada.</p></div></details>
                            <details class="group bg-white/50 rounded-xl border border-slate-200/60 overflow-hidden backdrop-blur-sm hover:border-slate-300 transition"><summary class="flex items-center justify-between px-4 py-3.5 cursor-pointer hover:bg-slate-50/40 transition"><span class="text-sm font-medium text-slate-900">Gaji bersih 0 atau tidak sesuai</span><i class="fas fa-chevron-down text-slate-400 group-open:rotate-180 transition text-xs"></i></summary><div class="px-4 pb-4 text-sm text-slate-600 border-t border-slate-100 pt-3"><p>Pastikan komponen gaji terisi. Gunakan <strong>Adjustment</strong> untuk koreksi jika sudah approved.</p></div></details>
                            <details class="group bg-white/50 rounded-xl border border-slate-200/60 overflow-hidden backdrop-blur-sm hover:border-slate-300 transition"><summary class="flex items-center justify-between px-4 py-3.5 cursor-pointer hover:bg-slate-50/40 transition"><span class="text-sm font-medium text-slate-900">Tidak bisa edit Hitung Gaji approved</span><i class="fas fa-chevron-down text-slate-400 group-open:rotate-180 transition text-xs"></i></summary><div class="px-4 pb-4 text-sm text-slate-600 border-t border-slate-100 pt-3"><p>Fitur keamanan. Gunakan <strong>Adjustment</strong> untuk koreksi.</p></div></details>
                            <details class="group bg-white/50 rounded-xl border border-slate-200/60 overflow-hidden backdrop-blur-sm hover:border-slate-300 transition"><summary class="flex items-center justify-between px-4 py-3.5 cursor-pointer hover:bg-slate-50/40 transition"><span class="text-sm font-medium text-slate-900">Cara reset password?</span><i class="fas fa-chevron-down text-slate-400 group-open:rotate-180 transition text-xs"></i></summary><div class="px-4 pb-4 text-sm text-slate-600 border-t border-slate-100 pt-3"><p>Hubungi administrator sistem.</p></div></details>
                        </div>
                    </section>

                    <!-- ═══ NO RESULTS ═══ -->
                    <div id="noResults" class="hidden text-center py-16">
                        <i class="fas fa-search text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500 text-sm">Tidak ditemukan hasil untuk pencarian ini.</p>
                        <p class="text-slate-400 text-xs mt-1">Coba gunakan kata kunci lain.</p>
                    </div>

                    <!-- ═══ FOOTER ═══ -->
                    <div class="mt-12 pt-5 border-t border-slate-200/50 text-center text-xs text-slate-400">&copy; 2026 PSF Payroll. All rights reserved. v1.0</div>

                </div>
            </main>

            <!-- ═══ TOC ═══ -->
            <aside class="toc-desktop w-52 flex-shrink-0 glass-sidebar border-l border-white/20 h-[calc(100vh-3.5rem)] sticky top-14 overflow-y-auto px-4 pt-8 sidebar-scroll" style="border-left-color: rgba(255,255,255,0.2);">
                <div class="text-[11px] font-bold tracking-wider uppercase text-slate-400 mb-3 flex items-center gap-1.5"><i class="fas fa-list text-[10px]"></i> Di halaman ini</div>
                <nav id="tocNav">
                    <a href="#overview" class="toc-link h2" data-target="overview">Gambaran Umum</a>
                    <a href="#instalasi" class="toc-link h2" data-target="instalasi">Instalasi</a>
                    <a href="#autentikasi" class="toc-link h2" data-target="autentikasi">Autentikasi</a>
                    <a href="#alur-data" class="toc-link h2" data-target="alur-data">Alur Data</a>
                    <a href="#struktur-database" class="toc-link h2" data-target="struktur-database">Database</a>
                    <a href="#observer" class="toc-link h2" data-target="observer">Observer</a>
                    <a href="#karyawan" class="toc-link h2" data-target="karyawan">Karyawan</a>
                    <a href="#pengaturan-gaji" class="toc-link h2" data-target="pengaturan-gaji">Pengaturan Gaji</a>
                    <a href="#bpjs-koperasi" class="toc-link h2" data-target="bpjs-koperasi">BPJS</a>
                    <a href="#master-wilayah-status" class="toc-link h2" data-target="master-wilayah-status">Wilayah</a>
                    <a href="#absensi" class="toc-link h2" data-target="absensi">Absensi</a>
                    <a href="#nki" class="toc-link h2" data-target="nki">NKI</a>
                    <a href="#kasbon" class="toc-link h2" data-target="kasbon">Kasbon</a>
                    <a href="#acuan-gaji" class="toc-link h2" data-target="acuan-gaji">Acuan Gaji</a>
                    <a href="#hitung-gaji" class="toc-link h2" data-target="hitung-gaji">Hitung Gaji</a>
                    <a href="#penyesuaian" class="toc-link h2" data-target="penyesuaian">Penyesuaian</a>
                    <a href="#slip-gaji" class="toc-link h2" data-target="slip-gaji">Slip Gaji</a>
                    <a href="#pengguna-role" class="toc-link h2" data-target="pengguna-role">Role</a>
                    <a href="#hak-akses" class="toc-link h2" data-target="hak-akses">Hak Akses</a>
                    <a href="#pengaturan-sistem" class="toc-link h2" data-target="pengaturan-sistem">Settings</a>
                    <a href="#log-aktivitas" class="toc-link h2" data-target="log-aktivitas">Log</a>
                    <a href="#import-export" class="toc-link h2" data-target="import-export">Import/Export</a>
                    <a href="#faq" class="toc-link h2" data-target="faq">FAQ</a>
                </nav>
            </aside>

        </div>
    </div>

    <!-- Back to Top -->
    <button id="bttDocs" class="btt-docs" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Back to top">
        <i class="fas fa-arrow-up text-sm"></i>
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"></script>
    <script>
        function toggleMobile() { document.getElementById('mobileSidebar').classList.toggle('open'); document.getElementById('mobileOverlay').classList.toggle('hidden'); }

        const sections = document.querySelectorAll('[data-section]');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const tocLinks = document.querySelectorAll('.toc-link');

        function highlight() {
            let current = '';
            const pos = window.scrollY + 110;
            sections.forEach(s => { if (pos >= s.offsetTop) current = s.id; });
            sidebarLinks.forEach(l => { l.classList.toggle('active', l.getAttribute('href') === '#' + current); });
            tocLinks.forEach(l => { l.classList.toggle('active', l.getAttribute('data-target') === current); });
        }
        window.addEventListener('scroll', highlight, { passive: true });

        function filterDocs(q) {
            const query = q.toLowerCase().trim();
            let found = false;
            document.querySelectorAll('[data-section]').forEach(s => {
                const match = !query || s.textContent.toLowerCase().includes(query);
                s.style.display = match ? '' : 'none';
                if (match) found = true;
            });
            document.querySelectorAll('.sidebar-link').forEach(l => {
                const m = !query || l.textContent.toLowerCase().includes(query);
                l.style.display = m ? '' : 'none';
            });
            document.querySelectorAll('.sidebar-category').forEach(c => {
                let n = c.nextElementSibling, hv = false;
                while (n && n.tagName === 'A') { if (n.style.display !== 'none') hv = true; n = n.nextElementSibling; }
                c.style.display = (!query || hv) ? '' : 'none';
            });
            document.querySelectorAll('.toc-link').forEach(l => {
                const s = document.querySelector('[data-section="' + l.getAttribute('data-target') + '"]');
                l.style.display = (!query || (s && s.style.display !== 'none')) ? '' : 'none';
            });
            document.getElementById('noResults').classList.toggle('hidden', found || !query);
        }

        // Back to Top
        window.addEventListener('scroll', () => {
            document.getElementById('bttDocs').classList.toggle('show', window.scrollY > 400);
        }, { passive: true });

        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash) setTimeout(() => document.querySelector(window.location.hash)?.scrollIntoView({ behavior: 'smooth' }), 200);
            setTimeout(highlight, 100);

            // Anime.js entrance — only if anime loaded
            if (typeof anime !== 'undefined') {
                document.querySelectorAll('[data-section]').forEach((el, i) => {
                    el.style.opacity = '0'; el.style.transform = 'translateY(16px)';
                    new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            anime({ targets: el, opacity: [0, 1], translateY: [16, 0], duration: 500, delay: Math.min(i * 30, 200), easing: 'easeOutCubic' });
                        }
                    }, { threshold: 0.06 }).observe(el);
                });
            }
        });

        function setActiveLink(el) { sidebarLinks.forEach(l => l.classList.remove('active')); el.classList.add('active'); }
    </script>
</body>
</html>
