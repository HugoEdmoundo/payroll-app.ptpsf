@props(['nki'])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Karyawan Info -->
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Karyawan</label>
        <p class="text-lg font-semibold text-gray-900">{{ $nki->karyawan->nama_karyawan ?? '-' }}</p>
        <p class="text-sm text-gray-600">Jenis: {{ $nki->karyawan->jenis_karyawan ?? '-' }} | Jabatan: {{ $nki->karyawan->jabatan ?? '-' }}</p>
    </div>

    <!-- Periode -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Periode</label>
        <p class="text-base font-semibold text-gray-900">
            {{ \Carbon\Carbon::createFromFormat('Y-m', $nki->periode)->format('F Y') }}
        </p>
    </div>

    <!-- Spacer -->
    <div></div>

    <!-- Kemampuan (20%) -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <label class="block text-sm font-medium text-blue-700 mb-1">Kemampuan (20%)</label>
        <p class="text-2xl font-bold text-blue-900">{{ number_format($nki->kemampuan, 2) }}</p>
        <p class="text-xs text-blue-600 mt-1">Kontribusi: {{ number_format($nki->kemampuan * 0.20, 2) }}</p>
    </div>

    <!-- Kontribusi (20%) -->
    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
        <label class="block text-sm font-medium text-green-700 mb-1">Kontribusi (20%)</label>
        <p class="text-2xl font-bold text-green-900">{{ number_format($nki->kontribusi, 2) }}</p>
        <p class="text-xs text-green-600 mt-1">Kontribusi: {{ number_format($nki->kontribusi * 0.20, 2) }}</p>
    </div>

    <!-- Kedisiplinan (40%) -->
    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
        <label class="block text-sm font-medium text-purple-700 mb-1">Kedisiplinan (40%)</label>
        <p class="text-2xl font-bold text-purple-900">{{ number_format($nki->kedisiplinan, 2) }}</p>
        <p class="text-xs text-purple-600 mt-1">Kontribusi: {{ number_format($nki->kedisiplinan * 0.40, 2) }}</p>
    </div>

    <!-- Lainnya (20%) -->
    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
        <label class="block text-sm font-medium text-yellow-700 mb-1">Lainnya (20%)</label>
        <p class="text-2xl font-bold text-yellow-900">{{ number_format($nki->lainnya, 2) }}</p>
        <p class="text-xs text-yellow-600 mt-1">Kontribusi: {{ number_format($nki->lainnya * 0.20, 2) }}</p>
    </div>

    <!-- Nilai NKI -->
    <div class="bg-indigo-50 p-4 rounded-lg border-2 border-indigo-300 md:col-span-2">
        <label class="block text-sm font-medium text-indigo-700 mb-1">Nilai NKI (Auto-Calculated)</label>
        <p class="text-4xl font-bold text-indigo-900">{{ number_format($nki->nilai_nki, 2) }}</p>
        <p class="text-xs text-indigo-600 mt-2">
            Formula: ({{ number_format($nki->kemampuan, 2) }} × 20%) + ({{ number_format($nki->kontribusi, 2) }} × 20%) + 
            ({{ number_format($nki->kedisiplinan, 2) }} × 40%) + ({{ number_format($nki->lainnya, 2) }} × 20%)
        </p>
    </div>

    <!-- Persentase Tunjangan -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border-2 
                @if($nki->persentase_tunjangan == 100) border-green-300
                @elseif($nki->persentase_tunjangan == 80) border-yellow-300
                @else border-red-300
                @endif md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Persentase Tunjangan Prestasi</label>
        <p class="text-4xl font-bold 
                  @if($nki->persentase_tunjangan == 100) text-green-600
                  @elseif($nki->persentase_tunjangan == 80) text-yellow-600
                  @else text-red-600
                  @endif">
            {{ $nki->persentase_tunjangan }}%
        </p>
        <p class="text-sm text-gray-600 mt-2">
            @if($nki->persentase_tunjangan == 100)
                <i class="fas fa-check-circle text-green-600"></i> Excellent Performance (NKI ≥ 8.5)
            @elseif($nki->persentase_tunjangan == 80)
                <i class="fas fa-exclamation-circle text-yellow-600"></i> Good Performance (NKI ≥ 8.0)
            @else
                <i class="fas fa-times-circle text-red-600"></i> Needs Improvement (NKI < 8.0)
            @endif
        </p>
    </div>

    <!-- Keterangan -->
    @if($nki->keterangan)
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
        <p class="text-base text-gray-900">{{ $nki->keterangan }}</p>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Dibuat</label>
        <p class="text-sm text-gray-900">{{ $nki->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
        <p class="text-sm text-gray-900">{{ $nki->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>
