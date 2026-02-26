@props(['hitungGaji'])

<div class="space-y-6">
    <!-- Employee Info -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-lg border border-indigo-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Karyawan</label>
                <p class="text-lg font-semibold text-gray-900">{{ $hitungGaji->karyawan->nama_karyawan }}</p>
                <p class="text-sm text-gray-600">{{ $hitungGaji->karyawan->jabatan }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Periode</label>
                <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $hitungGaji->periode)->format('F Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                @if($hitungGaji->status === 'draft')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-edit mr-1"></i> Draft
                    </span>
                @elseif($hitungGaji->status === 'preview')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        <i class="fas fa-eye mr-1"></i> Preview
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Approved
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- PENDAPATAN Section -->
    <div class="border-t-4 border-green-500 bg-green-50 p-6 rounded-lg">
        <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
            <i class="fas fa-arrow-up mr-2"></i>PENDAPATAN (Income)
        </h3>
        <div class="space-y-3">
            @php
            $pendapatanFields = [
                'gaji_pokok' => 'Gaji Pokok',
                'bpjs_kesehatan_pendapatan' => 'BPJS Kesehatan',
                'bpjs_kecelakaan_kerja_pendapatan' => 'BPJS Kecelakaan Kerja',
                'bpjs_kematian_pendapatan' => 'BPJS Kematian',
                'bpjs_jht_pendapatan' => 'BPJS JHT',
                'bpjs_jp_pendapatan' => 'BPJS JP',
                'tunjangan_prestasi' => 'Tunjangan Prestasi',
                'tunjangan_konjungtur' => 'Tunjangan Konjungtur',
                'benefit_ibadah' => 'Benefit Ibadah',
                'benefit_komunikasi' => 'Benefit Komunikasi',
                'benefit_operasional' => 'Benefit Operasional',
                'reward' => 'Reward'
            ];
            @endphp
            
            @foreach($pendapatanFields as $field => $label)
                @php
                    $baseValue = $hitungGaji->$field;
                    $adjustment = $hitungGaji->getAdjustment($field);
                    $finalValue = $hitungGaji->getFinalValue($field);
                @endphp
                
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
                            <div class="mt-1 flex items-center space-x-2">
                                <span class="text-base text-gray-900">Rp {{ number_format($baseValue, 0, ',', '.') }}</span>
                                @if($adjustment)
                                    <span class="text-sm {{ $adjustment['type'] === '+' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $adjustment['type'] }} Rp {{ number_format($adjustment['nominal'], 0, ',', '.') }}
                                    </span>
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    <span class="text-base font-bold text-indigo-600">Rp {{ number_format($finalValue, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            @if($adjustment && isset($adjustment['description']))
                                <p class="mt-1 text-xs text-gray-600 italic">
                                    <i class="fas fa-info-circle mr-1"></i>{{ $adjustment['description'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-4 bg-green-100 p-4 rounded-lg border-2 border-green-300">
            <label class="text-sm font-medium text-green-800">Total Pendapatan</label>
            <p class="text-2xl font-bold text-green-900">Rp {{ number_format($hitungGaji->total_pendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- PENGELUARAN Section -->
    <div class="border-t-4 border-red-500 bg-red-50 p-6 rounded-lg">
        <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center">
            <i class="fas fa-arrow-down mr-2"></i>PENGELUARAN (Deductions)
        </h3>
        <div class="space-y-3">
            @php
            $pengeluaranFields = [
                'bpjs_kesehatan_pengeluaran' => 'BPJS Kesehatan',
                'bpjs_kecelakaan_kerja_pengeluaran' => 'BPJS Kecelakaan Kerja',
                'bpjs_kematian_pengeluaran' => 'BPJS Kematian',
                'bpjs_jht_pengeluaran' => 'BPJS JHT',
                'bpjs_jp_pengeluaran' => 'BPJS JP',
                'koperasi' => 'Koperasi',
                'kasbon' => 'Kasbon',
                'umroh' => 'Umroh',
                'kurban' => 'Kurban',
                'mutabaah' => 'Mutabaah',
                'potongan_absensi' => 'Potongan Absensi',
                'potongan_kehadiran' => 'Potongan Kehadiran'
            ];
            @endphp
            
            @foreach($pengeluaranFields as $field => $label)
                @php
                    $baseValue = $hitungGaji->$field;
                    $adjustment = $hitungGaji->getAdjustment($field);
                    $finalValue = $hitungGaji->getFinalValue($field);
                @endphp
                
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
                            <div class="mt-1 flex items-center space-x-2">
                                <span class="text-base text-gray-900">Rp {{ number_format($baseValue, 0, ',', '.') }}</span>
                                @if($adjustment)
                                    <span class="text-sm {{ $adjustment['type'] === '+' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $adjustment['type'] }} Rp {{ number_format($adjustment['nominal'], 0, ',', '.') }}
                                    </span>
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    <span class="text-base font-bold text-indigo-600">Rp {{ number_format($finalValue, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            @if($adjustment && isset($adjustment['description']))
                                <p class="mt-1 text-xs text-gray-600 italic">
                                    <i class="fas fa-info-circle mr-1"></i>{{ $adjustment['description'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-4 bg-red-100 p-4 rounded-lg border-2 border-red-300">
            <label class="text-sm font-medium text-red-800">Total Pengeluaran</label>
            <p class="text-2xl font-bold text-red-900">Rp {{ number_format($hitungGaji->total_pengeluaran, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- GAJI BERSIH -->
    <div class="bg-gradient-to-r from-indigo-100 to-purple-100 p-6 rounded-lg border-4 border-indigo-300">
        <label class="text-lg font-medium text-indigo-800">GAJI BERSIH (TAKE HOME PAY)</label>
        <p class="text-4xl font-bold text-indigo-900">Rp {{ number_format($hitungGaji->gaji_bersih, 0, ',', '.') }}</p>
        <p class="text-sm text-indigo-600 mt-2">Total Pendapatan - Total Pengeluaran</p>
    </div>

    @if($hitungGaji->keterangan)
    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
        <label class="block text-sm font-medium text-gray-600 mb-1">Keterangan</label>
        <p class="text-base text-gray-900">{{ $hitungGaji->keterangan }}</p>
    </div>
    @endif

    @if($hitungGaji->status === 'approved')
    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
            <div>
                <p class="text-sm font-medium text-green-800">Approved by {{ $hitungGaji->approvedBy->name ?? 'System' }}</p>
                <p class="text-xs text-green-600">{{ $hitungGaji->approved_at->format('d F Y, H:i') }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
