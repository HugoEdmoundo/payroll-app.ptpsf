@extends('layouts.app')

@section('title', 'Hitung Gaji - ' . \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y'))
@section('breadcrumb', 'Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card p-4 sm:p-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('payroll.hitung-gaji.index') }}" 
               class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div class="min-w-0">
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Hitung Gaji</h1>
                <div class="flex items-center gap-2 mt-1">
                    <i class="fas fa-calendar-alt text-indigo-600 text-xs sm:text-sm"></i>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Karyawan -->
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-blue-500 flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-600">Total Karyawan</p>
                    <p class="text-xl lg:text-2xl font-bold text-blue-900 truncate">{{ number_format($stats->total_karyawan ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Total BPJS -->
        <div class="card p-4 bg-gradient-to-br from-green-50 to-green-100 border-green-200">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-green-500 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-green-600">Total BPJS</p>
                    <p class="text-base lg:text-lg font-bold text-green-900 truncate">Rp {{ number_format($stats->total_bpjs ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Gaji Bersih -->
        <div class="card p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 border-indigo-200">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-indigo-500 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-indigo-600">Total Gaji Bersih</p>
                    <p class="text-base lg:text-lg font-bold text-indigo-900 truncate">Rp {{ number_format($stats->total_gaji_bersih ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Perusahaan -->
        <div class="card p-4 bg-gradient-to-br from-red-50 to-red-100 border-red-200">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-red-500 flex items-center justify-center">
                    <i class="fas fa-building text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-red-600">Pengeluaran</p>
                    <p class="text-base lg:text-lg font-bold text-red-900 truncate">Rp {{ number_format($stats->total_pengeluaran_perusahaan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="card p-4">
        <form method="GET" action="{{ route('payroll.hitung-gaji.periode', $periode) }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama, jenis karyawan, lokasi kerja, jabatan..."
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       onkeyup="if(event.key === 'Enter') this.form.submit()">
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Nama Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Jenis Karyawan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Gaji Pokok</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Total Pendapatan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Total Pengeluaran</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Gaji Bersih</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($hitungGajiList as $hitung)
                    <tr class="hover:bg-gray-50 cursor-pointer transition" 
                        onclick="openModal({{ $hitung->karyawan_id }}, '{{ $hitung->periode }}', '{{ $hitung->karyawan->nama_karyawan }}')">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($hitung->karyawan->nama_karyawan, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $hitung->karyawan->nama_karyawan }}</div>
                                    <div class="text-sm text-gray-500">{{ $hitung->karyawan->jabatan }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $hitung->karyawan->jenis_karyawan }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-gray-900">
                            Rp {{ number_format($hitung->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-green-600 font-medium">
                            Rp {{ number_format($hitung->total_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-red-600 font-medium">
                            Rp {{ number_format($hitung->total_pengeluaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-indigo-600 font-bold">
                            Rp {{ number_format($hitung->gaji_bersih, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-calculator text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data</h3>
                            <p class="text-gray-500">Belum ada data hitung gaji untuk periode ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($hitungGajiList->hasPages())
    <div class="flex justify-center">
        {{ $hitungGajiList->links() }}
    </div>
    @endif
</div>

<!-- Modal -->
<div id="hitungGajiModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-5 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white mb-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Hitung Gaji</h3>
                <p class="text-sm text-gray-600" id="modalEmployeeInfo"></p>
            </div>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="modalContent" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModal(karyawanId, periode, namaKaryawan) {
    document.getElementById('modalEmployeeInfo').textContent = namaKaryawan + ' - ' + periode;
    document.getElementById('hitungGajiModal').classList.remove('hidden');
    
    // Load form via AJAX
    fetch(`/payroll/hitung-gaji/modal/${karyawanId}/${periode}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = `
                <div class="text-red-600">
                    <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                    <p>Error loading data: ${error}</p>
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('hitungGajiModal').classList.add('hidden');
    // Reload page to show updated data
    window.location.reload();
}

// Close modal when clicking outside
document.getElementById('hitungGajiModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection
