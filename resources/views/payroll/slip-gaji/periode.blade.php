@extends('layouts.app')

@section('title', 'Slip Gaji - ' . \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y'))
@section('breadcrumb', 'Slip Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('payroll.slip-gaji.index') }}" 
                   class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Slip Gaji</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <i class="fas fa-calendar-alt text-purple-600 text-xs sm:text-sm"></i>
                        <p class="text-xs sm:text-sm font-medium text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Export Button (Excel Only) -->
            <a href="{{ route('payroll.slip-gaji.export-excel', $periode) }}" 
               class="inline-flex items-center px-3 py-2 text-xs sm:text-sm bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 transition whitespace-nowrap">
                <i class="fas fa-file-excel mr-1.5"></i>Export Excel
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form method="GET" action="{{ route('payroll.slip-gaji.periode', $periode) }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama, jenis, lokasi, jabatan..."
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               onkeyup="if(event.key === 'Enter') this.form.submit()">
                    </div>
                </div>
                
                <!-- Lokasi Kerja Filter -->
                <div>
                    <select name="lokasi_kerja" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            onchange="this.form.submit()">
                        <option value="">Semua Lokasi Kerja</option>
                        @foreach($lokasiKerjaList as $lokasi)
                        <option value="{{ $lokasi }}" {{ request('lokasi_kerja') == $lokasi ? 'selected' : '' }}>
                            {{ $lokasi }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Jabatan Filter -->
                <div>
                    <select name="jabatan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            onchange="this.form.submit()">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatanList as $jabatan)
                        <option value="{{ $jabatan }}" {{ request('jabatan') == $jabatan ? 'selected' : '' }}>
                            {{ $jabatan }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @if(request('search') || request('lokasi_kerja') || request('jabatan'))
            <div class="mt-3">
                <a href="{{ route('payroll.slip-gaji.periode', $periode) }}" 
                   class="text-sm text-purple-600 hover:text-purple-800">
                    <i class="fas fa-times mr-1"></i>Clear Filters
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Table with Real-Time Auto-Refresh -->
    <div class="card overflow-hidden" x-data="realtimeTable({ interval: 'normal' })">
        <div x-show="loading" class="absolute top-2 right-2 z-10">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full">
                <i class="fas fa-sync fa-spin mr-1"></i> Updating...
            </span>
        </div>
        
        <div data-realtime-content>
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
                    @forelse($slipGajiList as $slip)
                    <tr class="hover:bg-gray-50 cursor-pointer transition" 
                        onclick="openSlipModal({{ $slip->id }}, '{{ $slip->karyawan->nama_karyawan }}')">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($slip->karyawan->nama_karyawan, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $slip->karyawan->nama_karyawan }}</div>
                                    <div class="text-sm text-gray-500">{{ $slip->karyawan->jabatan }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $slip->karyawan->jenis_karyawan }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-gray-900">
                            Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-green-600 font-medium">
                            Rp {{ number_format($slip->total_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-red-600 font-medium">
                            Rp {{ number_format($slip->total_pengeluaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-purple-600 font-bold">
                            Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-file-invoice text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data</h3>
                            <p class="text-gray-500">Belum ada slip gaji untuk periode ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($slipGajiList->hasPages())
    <div class="flex justify-center">
        {{ $slipGajiList->links() }}
    </div>
    @endif
</div>

<!-- Modal -->
<div id="slipGajiModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-5 mx-auto w-full max-w-4xl shadow-lg rounded-lg bg-white mb-10">
        <div id="modalContent" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading...</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// Store current slip info for refresh
let currentSlipGajiId = null;
let currentNamaKaryawan = null;

function openSlipModal(hitungGajiId, namaKaryawan) {
    currentSlipGajiId = hitungGajiId;
    currentNamaKaryawan = namaKaryawan;
    
    document.getElementById('slipGajiModal').classList.remove('hidden');
    loadSlipData(hitungGajiId, namaKaryawan);
}

function refreshSlipModal() {
    if (currentSlipGajiId) {
        loadSlipData(currentSlipGajiId, currentNamaKaryawan);
    }
}

function loadSlipData(hitungGajiId, namaKaryawan) {
    // Show loading
    document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading...</p>
        </div>
    `;
    
    // Load slip via AJAX with cache-busting parameter
    const timestamp = new Date().getTime();
    fetch(`/payroll/slip-gaji/slip/${hitungGajiId}?t=${timestamp}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            // Set nama karyawan setelah content dimuat
            const employeeInfo = document.getElementById('modalEmployeeInfo');
            if (employeeInfo) {
                employeeInfo.textContent = namaKaryawan;
            }
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = `
                <div class="text-red-600 py-8">
                    <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                    <p>Error loading slip: ${error}</p>
                </div>
            `;
        });
}

function closeSlipModal() {
    document.getElementById('slipGajiModal').classList.add('hidden');
}

// Download & Print Functions
function printSlip() {
    window.print();
}

function downloadPDF(hitungGajiId) {
    // Open PDF in new tab for download
    const url = `/payroll/slip-gaji/download-pdf/${hitungGajiId}`;
    window.open(url, '_blank');
}

function downloadPNG() {
    const element = document.getElementById('printable-slip');
    
    if (!element) {
        alert('Element tidak ditemukan');
        return;
    }
    
    html2canvas(element, {
        scale: 2,
        backgroundColor: '#ffffff',
        logging: false,
        useCORS: true,
        allowTaint: true,
        windowWidth: element.scrollWidth,
        windowHeight: element.scrollHeight
    }).then(canvas => {
        // Convert canvas to blob
        canvas.toBlob(function(blob) {
            if (!blob) {
                alert('Gagal membuat gambar');
                return;
            }
            
            // Create download link
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const timestamp = new Date().getTime();
            link.download = `slip-gaji-${timestamp}.png`;
            link.href = url;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Clean up
            setTimeout(() => URL.revokeObjectURL(url), 100);
        }, 'image/png');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        alert('Gagal download PNG: ' + error.message);
    });
}

function copyText() {
    const textElement = document.getElementById("text-slip");
    
    if (!textElement) {
        alert('Text tidak ditemukan');
        return;
    }
    
    const text = textElement.innerText;
    const button = document.getElementById("copyButton");
    const buttonText = document.getElementById("copyButtonText");
    const icon = button.querySelector('i');
    
    navigator.clipboard.writeText(text).then(() => {
        // Change button appearance
        icon.className = 'fas fa-check mr-3 w-4 text-green-600';
        buttonText.textContent = 'Copied!';
        button.classList.add('bg-green-50');
        
        // Reset after 2 seconds
        setTimeout(() => {
            icon.className = 'fas fa-copy mr-3 w-4 text-gray-600';
            buttonText.textContent = 'Copy Text';
            button.classList.remove('bg-green-50');
        }, 2000);
    }).catch((error) => {
        console.error('Copy failed:', error);
        alert("Gagal copy. Silakan coba manual.");
    });
}

// Close modal when clicking outside
document.getElementById('slipGajiModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSlipModal();
    }
});
</script>
@endpush
@endsection
