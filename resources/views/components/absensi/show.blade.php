@props(['absensi'])

@php
    $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
    $attendanceRate = $absensi->jumlah_hari_bulan > 0 ? ($absensi->hadir / $absensi->jumlah_hari_bulan) * 100 : 0;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Karyawan Info -->
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Karyawan</label>
        <p class="text-lg font-semibold text-gray-900">{{ $absensi->karyawan->nama_karyawan ?? '-' }}</p>
        <p class="text-sm text-gray-600">Jenis: {{ $absensi->karyawan->jenis_karyawan ?? '-' }} | Jabatan: {{ $absensi->karyawan->jabatan ?? '-' }}</p>
    </div>

    <!-- Periode -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Periode</label>
        <p class="text-base font-semibold text-gray-900">
            {{ \Carbon\Carbon::createFromFormat('Y-m', $absensi->periode)->format('F Y') }}
        </p>
    </div>

    <!-- Jumlah Hari Bulan -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Hari dalam Bulan</label>
        <p class="text-base font-semibold text-gray-900">{{ $absensi->jumlah_hari_bulan }} hari</p>
    </div>

    <!-- Hadir -->
    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
        <label class="block text-sm font-medium text-green-700 mb-1">Hadir</label>
        <p class="text-2xl font-bold text-green-900">{{ $absensi->hadir }} hari</p>
    </div>

    <!-- On Site -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <label class="block text-sm font-medium text-blue-700 mb-1">On Site</label>
        <p class="text-2xl font-bold text-blue-900">{{ $absensi->on_site }} hari</p>
    </div>

    <!-- Absence -->
    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
        <label class="block text-sm font-medium text-red-700 mb-1">Absence</label>
        <p class="text-2xl font-bold text-red-900">{{ $absensi->absence }} hari</p>
    </div>

    <!-- Idle/Rest -->
    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
        <label class="block text-sm font-medium text-yellow-700 mb-1">Idle/Rest</label>
        <p class="text-2xl font-bold text-yellow-900">{{ $absensi->idle_rest }} hari</p>
    </div>

    <!-- Izin/Sakit/Cuti -->
    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
        <label class="block text-sm font-medium text-purple-700 mb-1">Izin/Sakit/Cuti</label>
        <p class="text-2xl font-bold text-purple-900">{{ $absensi->izin_sakit_cuti }} hari</p>
    </div>

    <!-- Tanpa Keterangan -->
    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
        <label class="block text-sm font-medium text-red-700 mb-1">Tanpa Keterangan</label>
        <p class="text-2xl font-bold text-red-900">{{ $absensi->tanpa_keterangan }} hari</p>
    </div>

    <!-- Attendance Rate -->
    <div class="bg-indigo-50 p-4 rounded-lg border-2 border-indigo-300 md:col-span-2">
        <label class="block text-sm font-medium text-indigo-700 mb-1">Tingkat Kehadiran</label>
        <p class="text-4xl font-bold 
                  @if($attendanceRate >= 95) text-green-600
                  @elseif($attendanceRate >= 85) text-yellow-600
                  @else text-red-600
                  @endif">
            {{ number_format($attendanceRate, 2) }}%
        </p>
        <p class="text-sm text-gray-600 mt-2">
            {{ $absensi->hadir }} dari {{ $absensi->jumlah_hari_bulan }} hari kerja
        </p>
    </div>

    <!-- Total Absence for Deduction -->
    <div class="bg-orange-50 p-4 rounded-lg border-2 border-orange-300 md:col-span-2">
        <label class="block text-sm font-medium text-orange-700 mb-1">Total Ketidakhadiran (Untuk Potongan)</label>
        <p class="text-3xl font-bold text-orange-900">{{ $totalAbsence }} hari</p>
        <p class="text-xs text-orange-600 mt-2">
            Absence ({{ $absensi->absence }}) + Tanpa Keterangan ({{ $absensi->tanpa_keterangan }})
        </p>
        <p class="text-xs text-orange-600 mt-1">
            <strong>Rumus Potongan:</strong> {{ $totalAbsence }} ÷ {{ $absensi->jumlah_hari_bulan }} × (Gaji Pokok + Tunjangan Prestasi + Operasional)
        </p>
    </div>

    <!-- Keterangan -->
    @if($absensi->keterangan)
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
        <p class="text-base text-gray-900">{{ $absensi->keterangan }}</p>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Dibuat</label>
        <p class="text-sm text-gray-900">{{ $absensi->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
        <p class="text-sm text-gray-900">{{ $absensi->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>
