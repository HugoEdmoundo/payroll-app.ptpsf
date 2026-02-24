@extends('layouts.app')

@section('title', 'Create Hitung Gaji')
@section('breadcrumb', 'Create Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Hitung Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Import data from Acuan Gaji and add adjustments</p>
        </div>
        <a href="{{ route('payroll.hitung-gaji.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Periode Info -->
    <div class="card p-4 bg-indigo-50 border-indigo-200">
        <div class="flex items-center">
            <i class="fas fa-calendar text-indigo-600 mr-3"></i>
            <div>
                <p class="text-sm font-medium text-indigo-900">Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
                <p class="text-xs text-indigo-700">{{ $acuanGajiList->count() }} acuan gaji available for this periode</p>
            </div>
        </div>
    </div>

    @if($acuanGajiList->count() > 0)
    <!-- Select Acuan Gaji -->
    <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Employee</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($acuanGajiList as $acuan)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-500 hover:shadow-md transition cursor-pointer"
                 onclick="selectAcuanGaji({{ $acuan->id_acuan }}, '{{ $acuan->karyawan->nama_karyawan }}', {{ $acuan->gaji_bersih }})">
                <div class="flex items-center mb-3">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($acuan->karyawan->nama_karyawan, 0, 2)) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $acuan->karyawan->nama_karyawan }}</p>
                        <p class="text-xs text-gray-500">{{ $acuan->karyawan->jabatan }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Gaji Bersih</p>
                    <p class="text-sm font-bold text-indigo-600">Rp {{ number_format($acuan->gaji_bersih, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Modal Form -->
<div id="formModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white mb-10">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Create Hitung Gaji</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form method="POST" action="{{ route('payroll.hitung-gaji.store') }}" id="hitungGajiForm">
                @csrf
                <input type="hidden" name="acuan_gaji_id" id="acuan_gaji_id">
                
                <div class="space-y-6">
                    <!-- Employee Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Employee: <span id="employeeName" class="font-medium text-gray-900"></span></p>
                        <p class="text-sm text-gray-600">Base Salary: <span id="baseSalary" class="font-medium text-gray-900"></span></p>
                    </div>

                    <!-- Penyesuaian Pendapatan -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700">Penyesuaian Pendapatan (Optional)</label>
                            <button type="button" onclick="addPendapatan()" class="text-sm text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-plus mr-1"></i>Add Adjustment
                            </button>
                        </div>
                        <div id="pendapatanContainer" class="space-y-3">
                            <!-- Dynamic rows will be added here -->
                        </div>
                    </div>

                    <!-- Penyesuaian Pengeluaran -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700">Penyesuaian Pengeluaran (Optional)</label>
                            <button type="button" onclick="addPengeluaran()" class="text-sm text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-plus mr-1"></i>Add Adjustment
                            </button>
                        </div>
                        <div id="pengeluaranContainer" class="space-y-3">
                            <!-- Dynamic rows will be added here -->
                        </div>
                    </div>

                    <!-- Catatan Umum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Umum (Optional)</label>
                        <textarea name="catatan_umum" rows="3" 
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Additional notes..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Create as Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let pendapatanIndex = 0;
let pengeluaranIndex = 0;

function selectAcuanGaji(id, name, salary) {
    document.getElementById('acuan_gaji_id').value = id;
    document.getElementById('employeeName').textContent = name;
    document.getElementById('baseSalary').textContent = 'Rp ' + salary.toLocaleString('id-ID');
    document.getElementById('formModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('formModal').classList.add('hidden');
    document.getElementById('pendapatanContainer').innerHTML = '';
    document.getElementById('pengeluaranContainer').innerHTML = '';
    pendapatanIndex = 0;
    pengeluaranIndex = 0;
}

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
