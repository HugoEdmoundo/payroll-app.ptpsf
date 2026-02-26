@props(['acuanGaji'])

<div class="space-y-6">
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Karyawan</label>
        <p class="text-lg font-semibold text-gray-900">{{ $acuanGaji->karyawan->nama_karyawan ?? '-' }}</p>
        <p class="text-sm text-gray-600">Jenis: {{ $acuanGaji->karyawan->jenis_karyawan ?? '-' }} | Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $acuanGaji->periode)->format('F Y') }}</p>
    </div>

    <div class="border-t-4 border-green-500 bg-green-50 p-6 rounded-lg">
        <h3 class="text-lg font-bold text-green-800 mb-4"><i class="fas fa-plus-circle mr-2"></i>PENDAPATAN</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white p-3 rounded"><label class="text-xs text-gray-600">Gaji Pokok</label><p class="text-sm font-bold text-gray-900">Rp {{ number_format($acuanGaji->gaji_pokok, 0, ',', '.') }}</p></div>
            <div class="bg-white p-3 rounded"><label class="text-xs text-gray-600">Tunjangan Prestasi</label><p class="text-sm font-bold text-gray-900">Rp {{ number_format($acuanGaji->tunjangan_prestasi, 0, ',', '.') }}</p></div>
            <div class="bg-white p-3 rounded"><label class="text-xs text-gray-600">Tunjangan Konjungtur</label><p class="text-sm font-bold text-gray-900">Rp {{ number_format($acuanGaji->tunjangan_konjungtur, 0, ',', '.') }}</p></div>
            <div class="bg-white p-3 rounded"><label class="text-xs text-gray-600">Reward</label><p class="text-sm font-bold text-gray-900">Rp {{ number_format($acuanGaji->reward, 0, ',', '.') }}</p></div>
        </div>
        <div class="mt-4 bg-green-100 p-4 rounded-lg border-2 border-green-300">
            <label class="text-sm font-medium text-green-800">Total Pendapatan</label>
            <p class="text-2xl font-bold text-green-900">Rp {{ number_format($acuanGaji->total_pendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="border-t-4 border-red-500 bg-red-50 p-6 rounded-lg">
        <h3 class="text-lg font-bold text-red-800 mb-4"><i class="fas fa-minus-circle mr-2"></i>PENGELUARAN</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white p-3 rounded"><label class="text-xs text-gray-600">Kasbon</label><p class="text-sm font-bold text-gray-900">Rp {{ number_format($acuanGaji->kasbon, 0, ',', '.') }}</p></div>
            <div class="bg-white p-3 rounded"><label class="text-xs text-gray-600">Potongan Absensi</label><p class="text-sm font-bold text-gray-900">Rp {{ number_format($acuanGaji->potongan_absensi, 0, ',', '.') }}</p></div>
            <div class="bg-white p-3 rounded"><label class="text-xs text-gray-600">Koperasi</label><p class="text-sm font-bold text-gray-900">Rp {{ number_format($acuanGaji->koperasi, 0, ',', '.') }}</p></div>
        </div>
        <div class="mt-4 bg-red-100 p-4 rounded-lg border-2 border-red-300">
            <label class="text-sm font-medium text-red-800">Total Pengeluaran</label>
            <p class="text-2xl font-bold text-red-900">Rp {{ number_format($acuanGaji->total_pengeluaran, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-lg border-4 border-indigo-300">
        <label class="text-lg font-medium text-indigo-800">GAJI BERSIH</label>
        <p class="text-4xl font-bold text-indigo-900">Rp {{ number_format($acuanGaji->gaji_bersih, 0, ',', '.') }}</p>
        <p class="text-sm text-indigo-600 mt-2">Total Pendapatan - Total Pengeluaran</p>
    </div>

    @if($acuanGaji->keterangan)
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
        <p class="text-base text-gray-900">{{ $acuanGaji->keterangan }}</p>
    </div>
    @endif
</div>
