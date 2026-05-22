<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PSF Payroll Enterprise Suite — Precision. Integrity. Intelligence.</title>
  <meta name="description" content="Sistem penggajian terpadu untuk PT Pangestu Suryaning Famili. Fleksibel, audit trail, multi-jenis karyawan, dan akurasi tinggi berbasis Source of Truth.">
  <link rel="icon" href="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'sans-serif'] },
          letterSpacing: { supertight: '-0.04em', tight: '-0.02em' }
        }
      }
    }
  </script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
      color: #111827;
      overflow-x: hidden;
    }
    .glass-nav {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(0, 0, 0, 0.04);
    }
    .premium-mask {
      mask-image: linear-gradient(to bottom, white 60%, transparent 100%);
      -webkit-mask-image: linear-gradient(to bottom, white 60%, transparent 100%);
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f9fafb; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    #hero-badge, #hero-headline, #hero-sub {
      opacity: 1 !important;
      transform: translateY(0) !important;
    }
    .btt {
      position: fixed; bottom: 2rem; right: 2rem; z-index: 40;
      width: 44px; height: 44px; border-radius: 50%;
      background: #4f46e5; color: white; display: flex;
      align-items: center; justify-content: center;
      box-shadow: 0 4px 16px rgba(79,70,229,0.3);
      transition: all 0.2s; opacity: 0; pointer-events: none;
    }
    .btt.show { opacity: 1; pointer-events: auto; }
    .btt:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79,70,229,0.4); }
  </style>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
</head>
<body class="antialiased selection:bg-indigo-600 selection:text-white" x-data="{ mobileOpen: false }">

<div id="glow-1" class="fixed top-[-10%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-100/40 to-purple-100/30 blur-[120px] pointer-events-none z-0 mix-blend-multiply"></div>
<div id="glow-2" class="fixed bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-50/40 to-purple-100/30 blur-[140px] pointer-events-none z-0 mix-blend-multiply"></div>

<!-- Mobile Overlay -->
<div x-show="mobileOpen" @click="mobileOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 md:hidden" x-cloak></div>

<!-- Mobile Sidebar -->
<aside class="fixed top-0 left-0 z-50 h-full w-64 bg-white/90 backdrop-blur-2xl shadow-xl border-r border-gray-100 transform transition-transform duration-300 md:hidden"
       :class="{ '-translate-x-full': !mobileOpen, 'translate-x-0': mobileOpen }">
  <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
    <a href="{{ route('landing') }}" class="flex items-center gap-2">
      <span class="w-7 h-7 rounded-lg overflow-hidden shadow-sm ring-2 ring-white/80 flex-shrink-0">
        <img src="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg" alt="PSF" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-[10px]\'>P</div>'">
      </span>
      <span class="text-sm font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">PAYROLL PSF</span>
    </a>
    <button @click="mobileOpen = false" class="p-1.5 rounded-lg hover:bg-gray-100 transition"><i class="fas fa-times text-gray-500"></i></button>
  </div>
  <nav class="p-4 space-y-1">
    <a href="#architecture" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">Arsitektur</a>
    <a href="#modules" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">Modul Sistem</a>
    <a href="#specifications" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">Spesifikasi</a>
    <a href="#compliance" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">Kualitas</a>
    <div class="border-t border-gray-100 my-3"></div>
    <a href="{{ route('docs') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition"><i class="fas fa-book text-xs text-indigo-500"></i> Dokumentasi</a>
    @auth
      <a href="{{ url('/dashboard') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition mt-2"><i class="fas fa-tachometer-alt text-xs"></i> Dashboard</a>
    @else
      <a href="{{ route('login') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition mt-2"><i class="fas fa-sign-in-alt text-xs"></i> Masuk</a>
    @endauth
  </nav>
</aside>

<header class="fixed top-0 left-0 w-full z-30 px-6 py-4">
  <div class="max-w-7xl mx-auto flex items-center justify-between h-14 px-6 rounded-2xl glass-nav">
    <div class="flex items-center gap-3">
      <button @click="mobileOpen = true" class="md:hidden p-1.5 rounded-lg hover:bg-gray-100 transition mr-1">
        <i class="fas fa-bars text-gray-600 text-lg"></i>
      </button>
      <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
        <span class="w-7 h-7 rounded-lg overflow-hidden shadow-sm ring-2 ring-white/80 flex-shrink-0 transition group-hover:shadow-md">
          <img src="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg" alt="PSF" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-[10px]\'>P</div>'">
        </span>
        <span class="font-semibold text-sm tracking-tight bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">PAYROLL PSF</span>
      </a>
    </div>
    <nav class="hidden md:flex items-center gap-8 text-[13px] font-medium text-gray-500">
      <a href="#architecture" class="hover:text-indigo-600 transition-colors">Arsitektur</a>
      <a href="#modules" class="hover:text-indigo-600 transition-colors">Modul Sistem</a>
      <a href="#specifications" class="hover:text-indigo-600 transition-colors">Spesifikasi</a>
      <a href="#compliance" class="hover:text-indigo-600 transition-colors">Kualitas</a>
      <a href="{{ route('docs') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1"><i class="fa-solid fa-book text-[10px]"></i> Dokumentasi</a>
    </nav>
    <div class="flex items-center gap-4">
      @auth
        <a href="{{ url('/dashboard') }}" class="hidden md:inline-flex bg-indigo-600 text-white text-[12px] font-medium px-4 py-2 rounded-xl hover:bg-indigo-700 transition-all shadow-sm">Dashboard <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i></a>
      @else
        <a href="{{ route('login') }}" class="hidden md:inline text-[13px] font-medium text-gray-600 hover:text-indigo-600 transition-colors">Masuk</a>
        <a href="{{ route('login') }}" class="bg-gray-900 text-white text-[12px] font-medium px-4 py-2 rounded-xl hover:bg-gray-800 transition-all shadow-sm">Akses Sistem <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i></a>
      @endauth
    </div>
  </div>
</header>

<main class="relative z-10 pt-20">

  <section class="relative min-h-[90vh] flex flex-col items-center justify-center px-6 text-center">
    <div class="max-w-4xl mx-auto mt-12 mb-8">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-900/5 border border-gray-900/5 mb-6" id="hero-badge">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
        <span class="text-[11px] font-semibold tracking-wider uppercase text-gray-600">PT Pangestu Suryaning Famili</span>
      </div>
      <h1 id="hero-headline" class="text-5xl md:text-8xl font-extrabold tracking-supertight text-gray-900 leading-[0.95] mb-8">
        Precision in Every<br><span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Payroll Payload</span>
      </h1>
      <p id="hero-sub" class="text-base md:text-xl text-gray-500 max-w-2xl mx-auto font-normal tracking-tight leading-relaxed">
        Sistem penggajian korporat terpadu berbasis <span class="text-gray-900 font-medium">Source of Truth</span>. Mengelola multi-jenis entitas karyawan dengan fleksibilitas mutlak, otomasi penuh, dan integritas historis yang teraudit.
      </p>
    </div>

    <div id="mockup-container" class="w-full max-w-5xl mx-auto px-4 pointer-events-none mt-4 mb-20">
      <div class="bg-white rounded-2xl shadow-[0_32px_64px_-16px_rgba(0,0,0,0.06)] border border-gray-200/60 p-6 text-left overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-gray-200"></span>
            <span class="w-3 h-3 rounded-full bg-gray-200"></span>
            <span class="w-3 h-3 rounded-full bg-gray-200"></span>
            <span class="text-xs text-gray-400 font-mono ml-2">psf-engine://payroll.v1</span>
          </div>
          <span class="text-[11px] font-mono bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md font-medium"><i class="fa-solid fa-shield-halved mr-1"></i> RBAC Secured Mode</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="md:col-span-2 space-y-4">
            <div class="h-8 w-1/3 bg-gray-100 rounded-lg"></div>
            <div class="grid grid-cols-3 gap-3">
              <div class="border border-gray-100 p-4 rounded-xl bg-gray-50/50">
                <div class="text-[11px] text-gray-400 font-medium uppercase">Total MTD Volume</div>
                <div class="text-lg font-bold text-gray-900 mt-1">Rp 1.588.420.000</div>
              </div>
              <div class="border border-gray-100 p-4 rounded-xl bg-gray-50/50">
                <div class="text-[11px] text-gray-400 font-medium uppercase">Active Objects</div>
                <div class="text-lg font-bold text-gray-900 mt-1">312 Headcount</div>
              </div>
              <div class="border border-gray-100 p-4 rounded-xl bg-gray-50/50">
                <div class="text-[11px] text-gray-400 font-medium uppercase">Audit Integrity</div>
                <div class="text-lg font-bold text-emerald-600 mt-1"><i class="fa-solid fa-circle-check mr-1 text-xs"></i> 100% Clean</div>
              </div>
            </div>
            <div class="border border-gray-100 rounded-xl p-4 space-y-2">
              <div class="h-4 w-1/4 bg-gray-100 rounded"></div>
              <div class="h-3 w-full bg-gray-50 rounded"></div>
              <div class="h-3 w-5/6 bg-gray-50 rounded"></div>
            </div>
          </div>
          <div class="bg-gray-900 rounded-xl p-4 text-white font-mono text-[11px] space-y-2 relative overflow-hidden flex flex-col justify-between">
            <div>
              <div class="text-gray-400 border-b border-gray-800 pb-2 mb-2 flex items-center justify-between">
                <span>PAYROLL ENGINE RUNNING</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
              </div>
              <p class="text-indigo-400">[info] Initializing Source of Truth core</p>
              <p class="text-gray-300">[calc] NKI logic injection for multi-tier criteria</p>
              <p class="text-gray-300">[calc] Absensi modifier formula dynamic evaluation</p>
              <p class="text-emerald-400">[done] Compilation success: 0 inconsistencies detected</p>
            </div>
            <div class="text-[10px] text-gray-500 pt-4">Build version 4.1.2-stable</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="architecture" class="py-24 px-6 border-t border-gray-100 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 items-start">
        <div>
          <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider block mb-3">Data Flow Strategy</span>
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 mb-6">Arsitektur Pipeline Gaji Terpadu</h2>
          <p class="text-gray-500 text-sm leading-relaxed mb-6">
            Sistem memisahkan entitas konfigurasi master dari mutasi kalkulasi transaksi operasional. Menjamin data riwayat bersifat mutlak tanpa resiko over-writing.
          </p>
          <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="text-xs font-semibold text-gray-700 mb-1"><i class="fa-solid fa-database mr-2 text-indigo-600"></i> Immutable History Principle</div>
            <p class="text-gray-500 text-[12px]">Perubahan pada pengaturan komponen masa kini tidak akan merusak atau memanipulasi histori slip gaji yang telah dicairkan pada bulan-bulan sebelumnya.</p>
          </div>
        </div>
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 relative">
          <div class="border border-gray-100 p-6 rounded-2xl bg-gray-50/50 hover:bg-white transition-all group">
            <div class="text-xs font-mono text-gray-400 mb-4">STAGE 01</div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Pengaturan Gaji (Source of Truth)</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Pusat kontrol terpusat untuk nominal basis, batasan wilayah, pengelompokan jenis karyawan, serta persentase komponen wajib.</p>
          </div>
          <div class="border border-gray-100 p-6 rounded-2xl bg-gray-50/50 hover:bg-white transition-all group">
            <div class="text-xs font-mono text-gray-400 mb-4">STAGE 02</div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Pembentukan Acuan Gaji</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Sistem membekukan data acuan dasar sebagai acuan default sebelum diproses ke tahapan kompilasi mutasi payroll variabel.</p>
          </div>
          <div class="border border-gray-100 p-6 rounded-2xl bg-gray-50/50 hover:bg-white transition-all group">
            <div class="text-xs font-mono text-gray-400 mb-4">STAGE 03</div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Hitung Gaji (Payroll Engine)</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Eksekusi perhitungan dinamis yang mempertemukan performa riil, pemotongan kasbon aktif, absensi formula, dan adjustment manual.</p>
          </div>
          <div class="border border-gray-100 p-6 rounded-2xl bg-gray-50/50 hover:bg-white transition-all group">
            <div class="text-xs font-mono text-gray-400 mb-4">STAGE 04</div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Slip Akhir & Audit Logs</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Kompilasi dokumen keluaran dalam format digital enkriptif, lengkap dengan visualisasi audit trail log atas perubahan variabel internal.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="modules" class="py-24 px-6 bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-7xl mx-auto">
      <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider block mb-3">Enterprise Suite</span>
        <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-gray-900">10 Modul Fungsional Utama</h2>
        <p class="text-gray-500 text-sm mt-4">Kombinasi modularitas tinggi yang dirancang untuk mengcover segala kebutuhan operasional HR, Payroll, Keuangan, dan Pengawas Audit Perusahaan.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-200 transition-colors">
              <i class="fa-solid fa-user-shield text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Authentication & Access Control</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Mengamankan fungsionalitas sistem melalui pengelolaan akses berbasis peran (RBAC) dan izin granular. Mendukung activity logging mendalam, penanganan sesi, dan proteksi timeout otomatis.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Security Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-200 transition-colors">
              <i class="fa-solid fa-chart-line text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Dashboard & Analytics</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Menyediakan panel indikator real-time mengenai agregat pengeluaran gaji perusahaan, grafik tren periodik, statistik kepegawaian, sisa pagu kasbon, serta komparasi pembiayaan per wilayah.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Analytics Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mb-4 group-hover:bg-purple-200 transition-colors">
              <i class="fa-solid fa-address-book text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Manajemen Karyawan</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Repositori data profil 360° lengkap dengan proteksi soft-delete, pencarian dinamis, filter taksonomi, serta bulk import-export data personal, perbankan, dan data legalitas perpajakan.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Core HR Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 mb-4 group-hover:bg-teal-200 transition-colors">
              <i class="fa-solid fa-sliders text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Manajemen Gaji (Payroll Config)</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Modul sentral pengaturan skema penggajian pokok, formula dinamis tunjangan prestasi, klasterisasi potongan iuran wajib BPJS, asuransi, tabungan koperasi, hingga penentuan tanggal efektif.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Configuration Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mb-4 group-hover:bg-amber-200 transition-colors">
              <i class="fa-solid fa-calendar-check text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Manajemen Absensi</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Sinkronisasi log kehadiran harian (sakit, cuti, alfa, terlambat, pulang cepat). Menyediakan fungsi import berkas mesin absensi eksternal serta pemrosesan dampak absensi ke hitungan gaji.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Operations Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mb-4 group-hover:bg-green-200 transition-colors">
              <i class="fa-solid fa-hand-holding-dollar text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Manajemen Kasbon & Cicilan</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Mengotomatisasi siklus pengajuan pinjaman uang muka karyawan, validasi berjenjang (HR & Finance), pembentukan tabel tenor cicilan, penanganan gagal bayar, hingga pemotongan otomatis.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Finance Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 mb-4 group-hover:bg-violet-200 transition-colors">
              <i class="fa-solid fa-file-contract text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">NKI (Normalisasi Komposisi Ikatan)</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Pencatatan metrik capaian performa ikatan/binding pegawai secara berkala. Mendukung manajemen unggah berkas pembuktian, pelacakan histori, serta penentuan variabel pengali prestasi.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Performance Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mb-4 group-hover:bg-rose-200 transition-colors">
              <i class="fa-solid fa-receipt text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Slip Gaji & Reporting</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Generator dokumen slip gaji digital terenkripsi (PDF format) secara massal/satuan, distribusi email instan, rekap kompilasi setoran pajak korporat, dan rincian mutasi pengeluaran berkala.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Reporting Module</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col justify-between group hover:border-indigo-500/30 transition-colors">
          <div>
            <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 mb-4 group-hover:bg-cyan-200 transition-colors">
              <i class="fa-solid fa-file-excel text-sm"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-2">Import / Export Data</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Subsistem integrasi data massal berbasis dokumen spreadsheet Excel. Memiliki modul internal pemeriksa validasi tipe data struktur, penangan error log baris, dan proteksi karakter simbolik khusus.</p>
          </div>
          <div class="pt-4 border-t border-gray-50 mt-6 text-[11px] font-mono text-gray-400">Integration Module</div>
        </div>

      </div>
    </div>
  </section>

  <section id="specifications" class="py-24 px-6 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="max-w-3xl mb-16">
        <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider block mb-3">Functional Capabilities</span>
        <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-gray-900">Spesifikasi Kebutuhan Sistem & Kalkulasi Logika</h2>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <div class="space-y-8">
          <div class="border-b border-gray-100 pb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><i class="fa-solid fa-folder-tree text-indigo-600 text-sm"></i> Manajemen Data Master & Otomasi</h3>
          </div>
          <div class="space-y-4 text-sm text-gray-600 leading-relaxed">
            <p>Sistem menampung struktur <span class="text-gray-900 font-semibold">Master Karyawan</span> dengan parameter identifikasi rigid: <code class="font-mono text-xs bg-gray-100 px-1 rounded text-indigo-600">id_karyawan</code> (Primary Key), identitas personal, detail finansial (NPWP, BPJS Kesehatan/TK, Nomor Rekening), penentuan 4 Jenis Karyawan utama (<span class="text-gray-900 font-medium">Konsultan, Organik, Teknisi, Borongan</span>), serta penandaan status aktif/nonaktif.</p>
            <p><i class="fa-solid fa-robot text-gray-400 mr-1"></i> <span class="text-gray-900 font-medium">Otomatisasi Status Teknisi:</span> Khusus untuk entitas Teknisi, sistem mengevaluasi parameter <code class="font-mono text-xs bg-gray-100 px-1 rounded text-indigo-600">join_date</code> secara berkala untuk mengubah klaster status pegawai secara mandiri tanpa intervensi manual:</p>
            <ul class="list-disc pl-5 space-y-1 text-xs text-gray-500">
              <li><span class="font-medium text-gray-800">Teknisi Harian:</span> Berlaku otomatis selama 14 hari pertama pasca bergabung.</li>
              <li><span class="font-medium text-gray-800">Teknisi OJT:</span> Berlaku otomatis selama 3 bulan setelah melewati fase harian.</li>
              <li><span class="font-medium text-gray-800">Teknisi Kontrak:</span> Fase matang pasca masa evaluasi OJT selesai diselesaikan.</li>
            </ul>
            <p>Sistem juga mendukung fleksibilitas pemetaan regional lewat <span class="text-gray-900 font-medium">Master Wilayah</span> dinamis yang secara instan menjadi data pilihan konfigurable di seluruh layer arsitektur.</p>
          </div>
        </div>

        <div class="space-y-8">
          <div class="border-b border-gray-100 pb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><i class="fa-solid fa-calculator text-indigo-600 text-sm"></i> Formula Engine & Komponen Variabel</h3>
          </div>
          <div class="space-y-6">

            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
              <div class="text-xs font-semibold text-gray-900 mb-2">1. Formula Take Home Pay (THP)</div>
              <div class="bg-white p-3 rounded-lg border border-gray-200/80 font-mono text-xs text-gray-800 mb-2 shadow-inner">THP = Total Pendapatan - Total Pengeluaran</div>
              <p class="text-[12px] text-gray-500 leading-relaxed">
                <span class="text-indigo-600 font-medium">Pendapatan:</span> Gaji Pokok, Tunjangan Prestasi, BPJS Cover Perusahaan, Operasional, Reward (Harian), & Nilai Borongan. <br>
                <span class="text-rose-600 font-medium">Pengeluaran:</span> Iuran BPJS Mandiri, Potongan Qurban/Koperasi, Kasbon, TGR Tools, & Potongan Absensi.
              </p>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
              <div class="text-xs font-semibold text-gray-900 mb-2">2. Formula Tunjangan Prestasi (Berdasarkan NKI)</div>
              <div class="bg-white p-3 rounded-lg border border-gray-200/80 font-mono text-xs text-gray-800 mb-2 shadow-inner">Tunjangan = Nilai Acuan Prestasi &times; Faktor Pengali NKI</div>
              <p class="text-[12px] text-gray-500 leading-relaxed">
                Evaluasi rasio pengali performa dilakukan berdasarkan tingkatan nilai: <br>
                <span class="font-medium text-gray-800">NKI &ge; 8.5</span> (Pengali 100%) &bull; <span class="font-medium text-gray-800">NKI &le; 8.0</span> (Pengali 80%) &bull; <span class="font-medium text-gray-800">NKI &le; 7.0</span> (Pengali 70%).
              </p>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
              <div class="text-xs font-semibold text-gray-900 mb-2">3. Formula Potongan Absensi Dinamis</div>
              <div class="bg-white p-3 rounded-lg border border-gray-200/80 font-mono text-xs text-gray-800 mb-2 shadow-inner">Potongan = ((Absence + TK) / Jumlah Hari Bulan) &times; (Gapok + Prestasi + Operasional)</div>
              <p class="text-[12px] text-gray-500 leading-relaxed">
                Variabel <code class="font-mono text-xs bg-white px-1 rounded">Jumlah Hari Bulan</code> dikonfigurasi otomatis mendeteksi kalender riil (30 atau 31 hari). Komponen BPJS dikecualikan penuh dari pengali potongan absensi.
              </p>
            </div>

          </div>
        </div>

      </div>

      <div class="mt-16">
        <div class="border-b border-gray-100 pb-4 mb-6">
          <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><i class="fa-solid fa-table-list text-indigo-600 text-sm"></i> Matriks Konfigurasi Gaji Berdasarkan Klasifikasi Karyawan</h3>
        </div>
        <div class="overflow-x-auto border border-gray-200/60 rounded-xl bg-white shadow-sm">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 font-semibold">
                <th class="p-4">Jenis Entitas</th>
                <th class="p-4">Metode Sourcing</th>
                <th class="p-4">Komponen Wajib / Struktur Input</th>
                <th class="p-4">Aturan Khusus / Validasi Lapangan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
              <tr><td class="p-4 font-bold text-gray-900">Teknisi</td><td class="p-4 font-mono text-indigo-600">AUTOMATIC DEFAULT</td><td class="p-4">Jabatan, Lokasi, Gapok, Operasional, Potongan Koperasi, BPJS Kesehatan, BPJS JKK, BPJS Kematian.</td><td class="p-4 text-gray-500">Sistem mengkalkulasi secara mandiri <span class="text-gray-800 font-medium">BPJS Total</span> dan <span class="text-gray-800 font-medium">NETT Referensi</span> secara langsung.</td></tr>
              <tr><td class="p-4 font-bold text-gray-900">Konsultan</td><td class="p-4 font-mono text-amber-600">MANUAL OVERRIDE</td><td class="p-4">Rate / Gaji Pokok Mandiri, Tabungan Koperasi, Tingkat Kehadiran, Kasbon Aktif.</td><td class="p-4 text-gray-500">Input mandiri penuh per periode, mengabaikan otomasi pengisian default sistem.</td></tr>
              <tr><td class="p-4 font-bold text-gray-900">Organik</td><td class="p-4 font-mono text-amber-600">MANUAL OVERRIDE</td><td class="p-4">Gapok, Tunjangan Konjungtur, Tunjangan Prestasi (NKI), BPJS Komplit (Kes, JKK, Kematian, JHT, JP), Benefit Ekstra.</td><td class="p-4 text-gray-500">Mendukung pengelolaan insentif tambahan (Ibadah, Komunikasi, Operasional Lapangan).</td></tr>
              <tr><td class="p-4 font-bold text-gray-900">Borongan</td><td class="p-4 font-mono text-amber-600">MANUAL OVERRIDE</td><td class="p-4">Jabatan, Kontribusi Site, Tunjangan Checklist (Presensi, Mutabaah, Tools), BPJS Trio, Reward Khusus.</td><td class="p-4 text-gray-500">Seluruh field bersifat dapat diubah secara bebas dan diizinkan bernilai nol (<code class="font-mono text-xs bg-gray-50 px-1">0</code>).</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
          <div class="text-xs font-mono text-indigo-600 mb-2">REQ-09</div>
          <h4 class="font-bold text-gray-900 mb-2">Mekanisme Job Movement</h4>
          <p class="text-gray-500 text-xs leading-relaxed">Setiap riwayat mutasi jabatan, perpindahan grade, atau rotasi wilayah kerja karyawan akan secara otomatis diisolasi ke dalam baris record tabel histori log terpisah.</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
          <div class="text-xs font-mono text-indigo-600 mb-2">REQ-10</div>
          <h4 class="font-bold text-gray-900 mb-2">Modul Hitung Gaji Fleksibel</h4>
          <p class="text-gray-500 text-xs leading-relaxed">Eksekutor payroll diizinkan melakukan penyesuaian nominal/persentase secara on-the-fly tanpa merusak integritas master acuan data asli, tersimpan sebagai entitas mutasi.</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
          <div class="text-xs font-mono text-indigo-600 mb-2">REQ-11</div>
          <h4 class="font-bold text-gray-900 mb-2">Multi Audit Logs & Catatan Slip</h4>
          <p class="text-gray-500 text-xs leading-relaxed">Mendukung penyematan catatan korektif berlapis pada modul penghitungan gaji yang secara transparan akan dicetak langsung pada lembar slip gaji karyawan.</p>
        </div>
      </div>

    </div>
  </section>

  <section id="compliance" class="py-24 px-6 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto">
      <div class="max-w-3xl mb-16">
        <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider block mb-3">System Compliance</span>
        <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-gray-900">Kepatuhan Standar Non-Fungsional</h2>
        <p class="text-gray-500 text-sm mt-4">Menjamin ketahanan sistem payroll korporat melalui pemenuhan 10 pilar kualitas arsitektur perangkat lunak skala enterprise.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">

        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">01. Fleksibilitas</div>
          <p class="text-gray-500 text-xs leading-relaxed">Komponen konfigurasi angka, parameter persen, dan regional wilayah bebas diubah tanpa resiko merusak struktur inti aplikasi.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">02. Skalabilitas</div>
          <p class="text-gray-500 text-xs leading-relaxed">Infrastruktur basis data siap menangani lonjakan kapasitas hingga 10.000+ data karyawan tanpa mengalami degradasi latensi.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">03. Auditabilitas</div>
          <p class="text-gray-500 text-xs leading-relaxed">Menyediakan jejak audit mutlak bagi pengawas internal atas setiap pergeseran konfigurasi keuangan dan nominal payroll.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">04. Konsistensi Data</div>
          <p class="text-gray-500 text-xs leading-relaxed">Seluruh agregat hitungan dipaksa merujuk secara eksklusif pada satu muara data terpusat, mengeliminasi anomali duplikasi.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">05. Integritas Historis</div>
          <p class="text-gray-500 text-xs leading-relaxed">Setiap penyesuaian nominal baru diproteksi agar tidak memanipulasi riwayat catatan nominal penggajian periode lampau.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">06. Read-Only Payroll</div>
          <p class="text-gray-500 text-xs leading-relaxed">Modul kalkulasi operasional hanya memiliki izin membaca data acuan konfigurasi tanpa hak memanipulasi konfig asli master.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">07. Otomatisasi Inti</div>
          <p class="text-gray-500 text-xs leading-relaxed">Kalkulasi masa bakti kerja, transisi berkala status teknisi, dan kuota hari kalender dieksekusi mandiri secara komputasi.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">08. Keamanan Keras</div>
          <p class="text-gray-500 text-xs leading-relaxed">Enkripsi tingkat tinggi disematkan untuk memproteksi data privat, nomor rekening perbankan, dan data sensitif karyawan.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">09. Ketersediaan (HA)</div>
          <p class="text-gray-500 text-xs leading-relaxed">Jaminan ketersediaan operasional konstan mencapai rasio batas 99.9% guna memfasilitasi kebutuhan running payroll berkala.</p>
        </div>
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
          <div class="text-base font-bold text-gray-900 mb-2">10. Performa Optimal</div>
          <p class="text-gray-500 text-xs leading-relaxed">Penyusunan query database dioptimasi ketat untuk menyelesaikan proses kalkulasi massal dalam hitungan detik yang efisien.</p>
        </div>

      </div>
    </div>
  </section>

  <section class="py-24 px-6 text-center bg-gray-50 border-t border-gray-100">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 mb-4">Siap Mengimplementasikan Akurasi Mutlak?</h2>
      <p class="text-gray-500 text-sm max-w-lg mx-auto mb-8">Kendalikan ekosistem payroll korporat PT Pangestu Suryaning Famili dalam satu platform terintegrasi terpusat.</p>
      <div class="flex items-center justify-center gap-4">
        <a href="{{ url('/dashboard') }}" class="bg-gray-900 text-white text-xs font-semibold px-6 py-3 rounded-xl hover:bg-gray-800 transition-all shadow-sm">Masuk ke Sistem</a>
        <a href="{{ route('docs') }}" class="border border-gray-200 text-gray-600 text-xs font-medium px-6 py-3 rounded-xl hover:bg-white hover:border-indigo-200 hover:text-indigo-600 transition-all">Dokumentasi Teknis</a>
      </div>
      <div class="mt-16 text-[11px] text-gray-400 font-normal">
        &copy; 2026 PT Pangestu Suryaning Famili &bull; Enterprise Payroll Engine Suite v4.1.2. All rights reserved.
      </div>
    </div>
  </section>

</main>

<button id="btt" class="btt" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Back to top">
  <i class="fas fa-arrow-up text-sm"></i>
</button>

<script>
  window.addEventListener('scroll', () => {
    document.getElementById('btt').classList.toggle('show', window.scrollY > 400);
  }, { passive: true });

  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    gsap.set("#hero-badge", { opacity: 0, y: 20 });
    gsap.set("#hero-headline", { opacity: 0, y: 30 });
    gsap.set("#hero-sub", { opacity: 0, y: 30 });
    window.addEventListener("DOMContentLoaded", () => {
      const tl = gsap.timeline();
      tl.to("#hero-badge", { opacity: 1, y: 0, duration: 0.6, ease: "power3.out" })
        .to("#hero-headline", { opacity: 1, y: 0, duration: 0.8, ease: "power4.out" }, "-=0.4")
        .to("#hero-sub", { opacity: 1, y: 0, duration: 0.8, ease: "power3.out" }, "-=0.6");
    });
    gsap.to("#hero-headline", {
      scrollTrigger: { trigger: "body", start: "top top", end: "bottom top", scrub: true },
      scale: 1.05, y: -40, opacity: 0.3, ease: "none"
    });
    gsap.fromTo("#mockup-container",
      { rotationX: 22, scale: 0.92, transformOrigin: "top center", z: -100 },
      { rotationX: 0, scale: 1, z: 0,
        scrollTrigger: { trigger: "#hero-headline", start: "top 20%", end: "bottom top", scrub: 1.2, invalidateOnRefresh: true },
        ease: "power2.out" }
    );
    gsap.to("#glow-1", {
      scrollTrigger: { trigger: "body", start: "top top", end: "bottom bottom", scrub: 2 },
      x: 150, y: 100, ease: "none"
    });
    gsap.to("#glow-2", {
      scrollTrigger: { trigger: "body", start: "top top", end: "bottom bottom", scrub: 2 },
      x: -120, y: -150, ease: "none"
    });
  }
</script>

</body>
</html>
