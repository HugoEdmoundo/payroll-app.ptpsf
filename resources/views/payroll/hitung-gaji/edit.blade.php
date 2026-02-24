@extends('layouts.app')

@section('title', 'Edit Hitung Gaji')
@section('breadcrumb', 'Edit Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Hitung Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Update salary calculation adjustments</p>
        </div>
        <a href="{{ route('payroll.hitung-gaji.show', $hitungGaji) }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Info Alert -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Edit Mode:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Base values from Acuan Gaji cannot be changed (READ-ONLY)</li>
                    <li>You can only modify adjustments for each field</li>
                    <li>If you add adjustment, description is REQUIRED</li>
                    <li>Leave adjustment empty if no changes needed</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="card p-6">
        <form method="POST" action="{{ route('payroll.hitung-gaji.update', $hitungGaji) }}">
            @csrf
            @method('PUT')
            
            @php
            $data = [
                'acuan_gaji_id' => $hitungGaji->acuan_gaji_id,
                'karyawan' => [
                    'nama' => $hitungGaji->karyawan->nama_karyawan,
                    'jabatan' => $hitungGaji->karyawan->jabatan,
                    'jenis' => $hitungGaji->karyawan->jenis_karyawan
                ],
                'periode' => $hitungGaji->periode,
                'fields' => [
                    'gaji_pokok' => $hitungGaji->gaji_pokok,
                    'bpjs_kesehatan_pendapatan' => $hitungGaji->bpjs_kesehatan_pendapatan,
                    'bpjs_kecelakaan_kerja_pendapatan' => $hitungGaji->bpjs_kecelakaan_kerja_pendapatan,
                    'bpjs_kematian_pendapatan' => $hitungGaji->bpjs_kematian_pendapatan,
                    'bpjs_jht_pendapatan' => $hitungGaji->bpjs_jht_pendapatan,
                    'bpjs_jp_pendapatan' => $hitungGaji->bpjs_jp_pendapatan,
                    'tunjangan_prestasi' => $hitungGaji->tunjangan_prestasi,
                    'tunjangan_konjungtur' => $hitungGaji->tunjangan_konjungtur,
                    'benefit_ibadah' => $hitungGaji->benefit_ibadah,
                    'benefit_komunikasi' => $hitungGaji->benefit_komunikasi,
                    'benefit_operasional' => $hitungGaji->benefit_operasional,
                    'reward' => $hitungGaji->reward,
                    'bpjs_kesehatan_pengeluaran' => $hitungGaji->bpjs_kesehatan_pengeluaran,
                    'bpjs_kecelakaan_kerja_pengeluaran' => $hitungGaji->bpjs_kecelakaan_kerja_pengeluaran,
                    'bpjs_kematian_pengeluaran' => $hitungGaji->bpjs_kematian_pengeluaran,
                    'bpjs_jht_pengeluaran' => $hitungGaji->bpjs_jht_pengeluaran,
                    'bpjs_jp_pengeluaran' => $hitungGaji->bpjs_jp_pengeluaran,
                    'tabungan_koperasi' => $hitungGaji->tabungan_koperasi,
                    'koperasi' => $hitungGaji->koperasi,
                    'kasbon' => $hitungGaji->kasbon,
                    'umroh' => $hitungGaji->umroh,
                    'kurban' => $hitungGaji->kurban,
                    'mutabaah' => $hitungGaji->mutabaah,
                    'potongan_absensi' => $hitungGaji->potongan_absensi,
                    'potongan_kehadiran' => $hitungGaji->potongan_kehadiran,
                ],
                'nki_info' => null,
                'absensi_info' => null
            ];
            @endphp
            
            <!-- Employee Info -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-lg mb-6 border border-indigo-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $data['karyawan']['nama'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $data['karyawan']['jabatan'] }} - {{ $data['karyawan']['jenis'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Periode</p>
                        <p class="text-lg font-bold text-indigo-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $data['periode'])->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- PENDAPATAN Section -->
            <div class="border-t-4 border-green-500 bg-green-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                    <i class="fas fa-arrow-up mr-2"></i>PENDAPATAN (Income)
                </h3>
                <div class="space-y-4">
                    @php
                    $pendapatanFields = [
                        'gaji_pokok' => 'Gaji Pokok',
                        'bpjs_kesehatan_pendapatan' => 'BPJS Kesehatan (Pendapatan)',
                        'bpjs_kecelakaan_kerja_pendapatan' => 'BPJS Kecelakaan Kerja (Pendapatan)',
                        'bpjs_kematian_pendapatan' => 'BPJS Kematian (Pendapatan)',
                        'bpjs_jht_pendapatan' => 'BPJS JHT (Pendapatan)',
                        'bpjs_jp_pendapatan' => 'BPJS JP (Pendapatan)',
                        'tunjangan_prestasi' => 'Tunjangan Prestasi (from NKI)',
                        'tunjangan_konjungtur' => 'Tunjangan Konjungtur',
                        'benefit_ibadah' => 'Benefit Ibadah',
                        'benefit_komunikasi' => 'Benefit Komunikasi',
                        'benefit_operasional' => 'Benefit Operasional',
                        'reward' => 'Reward'
                    ];
                    @endphp
                    
                    @foreach($pendapatanFields as $field => $label)
                    <x-hitung-gaji.field-with-adjustment 
                        :field="$field" 
                        :label="$label" 
                        :value="$data['fields'][$field]"
                        :adjustment="$hitungGaji->getAdjustment($field)"
                    />
                    @endforeach
                </div>
            </div>

            <!-- PENGELUARAN Section -->
            <div class="border-t-4 border-red-500 bg-red-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center">
                    <i class="fas fa-arrow-down mr-2"></i>PENGELUARAN (Deductions)
                </h3>
                <div class="space-y-4">
                    @php
                    $pengeluaranFields = [
                        'bpjs_kesehatan_pengeluaran' => 'BPJS Kesehatan (Pengeluaran)',
                        'bpjs_kecelakaan_kerja_pengeluaran' => 'BPJS Kecelakaan Kerja (Pengeluaran)',
                        'bpjs_kematian_pengeluaran' => 'BPJS Kematian (Pengeluaran)',
                        'bpjs_jht_pengeluaran' => 'BPJS JHT (Pengeluaran)',
                        'bpjs_jp_pengeluaran' => 'BPJS JP (Pengeluaran)',
                        'tabungan_koperasi' => 'Tabungan Koperasi',
                        'koperasi' => 'Koperasi',
                        'kasbon' => 'Kasbon',
                        'umroh' => 'Umroh',
                        'kurban' => 'Kurban',
                        'mutabaah' => 'Mutabaah',
                        'potongan_absensi' => 'Potongan Absensi (from Absensi)',
                        'potongan_kehadiran' => 'Potongan Kehadiran'
                    ];
                    @endphp
                    
                    @foreach($pengeluaranFields as $field => $label)
                    <x-hitung-gaji.field-with-adjustment 
                        :field="$field" 
                        :label="$label" 
                        :value="$data['fields'][$field]"
                        :adjustment="$hitungGaji->getAdjustment($field)"
                    />
                    @endforeach
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Optional)</label>
                <textarea name="keterangan" 
                          rows="3"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Additional notes...">{{ old('keterangan', $hitungGaji->keterangan) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('payroll.hitung-gaji.show', $hitungGaji) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
