@extends('layouts.app')

@section('title', 'Detail Acuan Gaji')
@section('breadcrumb', 'Detail Acuan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Acuan Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">View salary reference details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('payroll.acuan-gaji.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <a href="{{ route('payroll.acuan-gaji.edit', $acuanGaji->id_acuan) }}" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Employee Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600">Employee Name</p>
                <p class="text-base font-medium text-gray-900 mt-1">{{ $acuanGaji->karyawan->nama_karyawan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jenis Karyawan</p>
                <p class="text-base font-medium text-gray-900 mt-1">{{ $acuanGaji->karyawan->jenis_karyawan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jabatan</p>
                <p class="text-base font-medium text-gray-900 mt-1">{{ $acuanGaji->karyawan->jabatan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Lokasi Kerja</p>
                <p class="text-base font-medium text-gray-900 mt-1">{{ $acuanGaji->karyawan->lokasi_kerja }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Periode</p>
                <p class="text-base font-medium text-gray-900 mt-1">{{ \Carbon\Carbon::parse($acuanGaji->periode . '-01')->format('F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Pendapatan Section -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-arrow-up text-green-600 mr-2"></i>
            Pendapatan (Income)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @include('components.acuan-gaji.show-field', ['label' => 'Gaji Pokok', 'value' => $acuanGaji->gaji_pokok])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS Kesehatan', 'value' => $acuanGaji->bpjs_kesehatan_pendapatan])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS Kecelakaan Kerja', 'value' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS Kematian', 'value' => $acuanGaji->bpjs_kematian_pendapatan])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS JHT', 'value' => $acuanGaji->bpjs_jht_pendapatan])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS JP', 'value' => $acuanGaji->bpjs_jp_pendapatan])
            @include('components.acuan-gaji.show-field', ['label' => 'Tunjangan Prestasi', 'value' => $acuanGaji->tunjangan_prestasi])
            @include('components.acuan-gaji.show-field', ['label' => 'Tunjangan Konjungtur', 'value' => $acuanGaji->tunjangan_konjungtur])
            @include('components.acuan-gaji.show-field', ['label' => 'Benefit Ibadah', 'value' => $acuanGaji->benefit_ibadah])
            @include('components.acuan-gaji.show-field', ['label' => 'Benefit Komunikasi', 'value' => $acuanGaji->benefit_komunikasi])
            @include('components.acuan-gaji.show-field', ['label' => 'Benefit Operasional', 'value' => $acuanGaji->benefit_operasional])
            @include('components.acuan-gaji.show-field', ['label' => 'Reward', 'value' => $acuanGaji->reward])
        </div>
        <div class="mt-6 pt-6 border-t">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total Pendapatan</span>
                <span class="text-2xl font-bold text-green-600">Rp {{ number_format($acuanGaji->total_pendapatan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Pengeluaran Section -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-arrow-down text-red-600 mr-2"></i>
            Pengeluaran (Deductions)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS Kesehatan', 'value' => $acuanGaji->bpjs_kesehatan_pengeluaran])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS Kecelakaan Kerja', 'value' => $acuanGaji->bpjs_kecelakaan_kerja_pengeluaran])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS Kematian', 'value' => $acuanGaji->bpjs_kematian_pengeluaran])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS JHT', 'value' => $acuanGaji->bpjs_jht_pengeluaran])
            @include('components.acuan-gaji.show-field', ['label' => 'BPJS JP', 'value' => $acuanGaji->bpjs_jp_pengeluaran])
            @include('components.acuan-gaji.show-field', ['label' => 'Tabungan Koperasi', 'value' => $acuanGaji->tabungan_koperasi])
            @include('components.acuan-gaji.show-field', ['label' => 'Koperasi', 'value' => $acuanGaji->koperasi])
            @include('components.acuan-gaji.show-field', ['label' => 'Kasbon', 'value' => $acuanGaji->kasbon])
            @include('components.acuan-gaji.show-field', ['label' => 'Umroh', 'value' => $acuanGaji->umroh])
            @include('components.acuan-gaji.show-field', ['label' => 'Kurban', 'value' => $acuanGaji->kurban])
            @include('components.acuan-gaji.show-field', ['label' => 'Mutabaah', 'value' => $acuanGaji->mutabaah])
            @include('components.acuan-gaji.show-field', ['label' => 'Potongan Absensi', 'value' => $acuanGaji->potongan_absensi])
            @include('components.acuan-gaji.show-field', ['label' => 'Potongan Kehadiran', 'value' => $acuanGaji->potongan_kehadiran])
        </div>
        <div class="mt-6 pt-6 border-t">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total Pengeluaran</span>
                <span class="text-2xl font-bold text-red-600">Rp {{ number_format($acuanGaji->total_pengeluaran, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Gaji Bersih -->
    <div class="card p-6 bg-gradient-to-r from-indigo-500 to-purple-600">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-white text-opacity-90 text-sm">Gaji Bersih (Net Salary)</p>
                <p class="text-white text-4xl font-bold mt-2">Rp {{ number_format($acuanGaji->gaji_bersih, 0, ',', '.') }}</p>
            </div>
            <div class="h-16 w-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <i class="fas fa-wallet text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    @if($acuanGaji->keterangan)
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Keterangan</h3>
        <p class="text-gray-700">{{ $acuanGaji->keterangan }}</p>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <form method="POST" action="{{ route('payroll.acuan-gaji.destroy', $acuanGaji->id_acuan) }}" 
              onsubmit="return confirm('Are you sure you want to delete this record?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-trash mr-2"></i>Delete
            </button>
        </form>
    </div>
</div>
@endsection
