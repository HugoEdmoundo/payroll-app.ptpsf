@extends('layouts.app')

@section('title', 'Proses Hitung Gaji')
@section('breadcrumb', 'Proses Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Proses Hitung Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
        </div>
        <a href="{{ route('payroll.hitung-gaji.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Info Alert -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Cara Kerja:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Klik karyawan untuk membuka form perhitungan gaji</li>
                    <li>Semua data dari Acuan Gaji sudah otomatis terisi (READ-ONLY)</li>
                    <li>NKI dan Absensi akan dihitung otomatis</li>
                    <li>Anda bisa tambah adjustment untuk setiap field (OPTIONAL)</li>
                    <li>Adjustment wajib punya: Nominal, Tipe (+/-), dan Deskripsi</li>
                    <li>Karyawan dengan badge hijau sudah diproses</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Employee List -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Daftar Karyawan ({{ $acuanGajiList->count() }} karyawan)</h3>
            <div class="flex items-center space-x-2">
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                    Belum Diproses
                </span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Sudah Diproses
                </span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($acuanGajiList as $acuan)
            @php
                $isProcessed = in_array($acuan->id_karyawan, $existingHitungGaji);
            @endphp
            <div class="border {{ $isProcessed ? 'border-green-300 bg-green-50' : 'border-gray-200' }} rounded-lg p-4 hover:border-indigo-500 hover:shadow-md transition cursor-pointer {{ $isProcessed ? '' : 'cursor-pointer' }}"
                 onclick="{{ $isProcessed ? '' : "selectEmployee({$acuan->id_acuan}, '{$acuan->karyawan->nama_karyawan}', '{$acuan->karyawan->jabatan}')" }}">
                <div class="flex items-center mb-3">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($acuan->karyawan->nama_karyawan, 0, 2)) }}
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $acuan->karyawan->nama_karyawan }}</p>
                        <p class="text-xs text-gray-500">{{ $acuan->karyawan->jabatan }}</p>
                    </div>
                    @if($isProcessed)
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Gaji Bersih (Acuan)</p>
                    <p class="text-sm font-bold text-indigo-600">Rp {{ number_format($acuan->gaji_bersih, 0, ',', '.') }}</p>
                </div>
                @if($isProcessed)
                <div class="mt-2 pt-2 border-t border-green-200">
                    <p class="text-xs text-green-700 font-medium text-center">
                        <i class="fas fa-check mr-1"></i>Sudah Diproses
                    </p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-5 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white mb-10">
        <form method="POST" action="{{ route('payroll.hitung-gaji.store') }}" id="hitungGajiForm">
            @csrf
            <input type="hidden" name="acuan_gaji_id" id="acuan_gaji_id">
            
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Create Hitung Gaji</h3>
                    <p class="text-sm text-gray-600" id="employeeInfo"></p>
                </div>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Loading data...</p>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function selectEmployee(acuanGajiId, nama, jabatan) {
    document.getElementById('acuan_gaji_id').value = acuanGajiId;
    document.getElementById('employeeInfo').textContent = nama + ' - ' + jabatan;
    document.getElementById('formModal').classList.remove('hidden');
    
    // Load form via AJAX
    fetch(`/payroll/hitung-gaji/form/${acuanGajiId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('hitungGajiForm').innerHTML = html;
        })
        .catch(error => {
            alert('Error loading form: ' + error);
            closeModal();
        });
}

function closeModal() {
    document.getElementById('formModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('formModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection
