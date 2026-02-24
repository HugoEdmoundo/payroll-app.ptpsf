@extends('layouts.app')

@section('title', 'Edit Hitung Gaji')
@section('breadcrumb', 'Edit Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Hitung Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $hitungGaji->karyawan->nama_karyawan }} - {{ \Carbon\Carbon::createFromFormat('Y-m', $hitungGaji->periode)->format('F Y') }}</p>
        </div>
        <a href="{{ route('payroll.hitung-gaji.show', $hitungGaji) }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Info Alert -->
    <div class="card p-4 bg-yellow-50 border-yellow-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-yellow-600 mt-0.5 mr-3"></i>
            <div class="text-sm text-yellow-800">
                <p class="font-medium mb-1">Edit Mode - Draft Status</p>
                <p>Data from Acuan Gaji is READ-ONLY. You can only edit adjustments (penyesuaian).</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('payroll.hitung-gaji.update', $hitungGaji) }}">
        @csrf
        @method('PUT')

        <!-- Pendapatan Acuan (Read-Only) -->
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                Pendapatan Acuan (Read-Only)
            </h3>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                @foreach($hitungGaji->pendapatan_acuan as $key => $value)
                    @if($value > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($value, 0, ',', '.') }}</span>
                    </div>
                    @endif
                @endforeach
                <div class="border-t border-gray-300 pt-2 mt-2">
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-700">Total</span>
                        <span class="text-green-600">Rp {{ number_format($hitungGaji->total_pendapatan_acuan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penyesuaian Pendapatan (Editable) -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Penyesuaian Pendapatan</h3>
                <button type="button" onclick="addPendapatan()" class="text-sm text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-plus mr-1"></i>Add Adjustment
                </button>
            </div>
            <div id="pendapatanContainer" class="space-y-3">
                @if($hitungGaji->penyesuaian_pendapatan)
                    @foreach($hitungGaji->penyesuaian_pendapatan as $index => $item)
                    <div class="grid grid-cols-12 gap-2 items-start">
                        <div class="col-span-3">
                            <input type="text" name="penyesuaian_pendapatan[{{ $index }}][komponen]" 
                                   value="{{ $item['komponen'] }}"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Komponen" required>
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="penyesuaian_pendapatan[{{ $index }}][nominal]" 
                                   value="{{ $item['nominal'] }}"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Nominal" min="0" required>
                        </div>
                        <div class="col-span-2">
                            <select name="penyesuaian_pendapatan[{{ $index }}][tipe]" 
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="+" {{ $item['tipe'] === '+' ? 'selected' : '' }}>+ Tambah</option>
                                <option value="-" {{ $item['tipe'] === '-' ? 'selected' : '' }}>- Kurang</option>
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="text" name="penyesuaian_pendapatan[{{ $index }}][deskripsi]" 
                                   value="{{ $item['deskripsi'] }}"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Deskripsi" required>
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="this.parentElement.parentElement.remove()" 
                                    class="w-full px-3 py-2 text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Pengeluaran Acuan (Read-Only) -->
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                Pengeluaran Acuan (Read-Only)
            </h3>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                @foreach($hitungGaji->pengeluaran_acuan as $key => $value)
                    @if($value > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($value, 0, ',', '.') }}</span>
                    </div>
                    @endif
                @endforeach
                <div class="border-t border-gray-300 pt-2 mt-2">
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-700">Total</span>
                        <span class="text-red-600">Rp {{ number_format($hitungGaji->total_pengeluaran_acuan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penyesuaian Pengeluaran (Editable) -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Penyesuaian Pengeluaran</h3>
                <button type="button" onclick="addPengeluaran()" class="text-sm text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-plus mr-1"></i>Add Adjustment
                </button>
            </div>
            <div id="pengeluaranContainer" class="space-y-3">
                @if($hitungGaji->penyesuaian_pengeluaran)
                    @foreach($hitungGaji->penyesuaian_pengeluaran as $index => $item)
                    <div class="grid grid-cols-12 gap-2 items-start">
                        <div class="col-span-3">
                            <input type="text" name="penyesuaian_pengeluaran[{{ $index }}][komponen]" 
                                   value="{{ $item['komponen'] }}"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Komponen" required>
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="penyesuaian_pengeluaran[{{ $index }}][nominal]" 
                                   value="{{ $item['nominal'] }}"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Nominal" min="0" required>
                        </div>
                        <div class="col-span-2">
                            <select name="penyesuaian_pengeluaran[{{ $index }}][tipe]" 
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="+" {{ $item['tipe'] === '+' ? 'selected' : '' }}>+ Tambah</option>
                                <option value="-" {{ $item['tipe'] === '-' ? 'selected' : '' }}>- Kurang</option>
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="text" name="penyesuaian_pengeluaran[{{ $index }}][deskripsi]" 
                                   value="{{ $item['deskripsi'] }}"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Deskripsi" required>
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="this.parentElement.parentElement.remove()" 
                                    class="w-full px-3 py-2 text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Catatan Umum -->
        <div class="card p-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Umum (Optional)</label>
            <textarea name="catatan_umum" rows="3" 
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Additional notes...">{{ $hitungGaji->catatan_umum }}</textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('payroll.hitung-gaji.show', $hitungGaji) }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i>Update
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let pendapatanIndex = {{ $hitungGaji->penyesuaian_pendapatan ? count($hitungGaji->penyesuaian_pendapatan) : 0 }};
let pengeluaranIndex = {{ $hitungGaji->penyesuaian_pengeluaran ? count($hitungGaji->penyesuaian_pengeluaran) : 0 }};

function addPendapatan() {
    const container = document.getElementById('pendapatanContainer');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 items-start';
    row.innerHTML = `
        <div class="col-span-3">
            <input type="text" name="penyesuaian_pendapatan[${pendapatanIndex}][komponen]" 
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Komponen" required>
        </div>
        <div class="col-span-3">
            <input type="number" name="penyesuaian_pendapatan[${pendapatanIndex}][nominal]" 
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Nominal" min="0" required>
        </div>
        <div class="col-span-2">
            <select name="penyesuaian_pendapatan[${pendapatanIndex}][tipe]" 
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="+">+ Tambah</option>
                <option value="-">- Kurang</option>
            </select>
        </div>
        <div class="col-span-3">
            <input type="text" name="penyesuaian_pendapatan[${pendapatanIndex}][deskripsi]" 
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Deskripsi" required>
        </div>
        <div class="col-span-1">
            <button type="button" onclick="this.parentElement.parentElement.remove()" 
                    class="w-full px-3 py-2 text-sm text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
    pendapatanIndex++;
}

function addPengeluaran() {
    const container = document.getElementById('pengeluaranContainer');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 items-start';
    row.innerHTML = `
        <div class="col-span-3">
            <input type="text" name="penyesuaian_pengeluaran[${pengeluaranIndex}][komponen]" 
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Komponen" required>
        </div>
        <div class="col-span-3">
            <input type="number" name="penyesuaian_pengeluaran[${pengeluaranIndex}][nominal]" 
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Nominal" min="0" required>
        </div>
        <div class="col-span-2">
            <select name="penyesuaian_pengeluaran[${pengeluaranIndex}][tipe]" 
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="+">+ Tambah</option>
                <option value="-">- Kurang</option>
            </select>
        </div>
        <div class="col-span-3">
            <input type="text" name="penyesuaian_pengeluaran[${pengeluaranIndex}][deskripsi]" 
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Deskripsi" required>
        </div>
        <div class="col-span-1">
            <button type="button" onclick="this.parentElement.parentElement.remove()" 
                    class="w-full px-3 py-2 text-sm text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
    pengeluaranIndex++;
}
</script>
@endpush
@endsection
