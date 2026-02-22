@props(['pengaturanGaji'])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Jenis Karyawan -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Karyawan</label>
        <p class="text-base font-semibold text-gray-900">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                @if($pengaturanGaji->jenis_karyawan == 'Konsultan') bg-purple-100 text-purple-800
                @elseif($pengaturanGaji->jenis_karyawan == 'Organik') bg-blue-100 text-blue-800
                @elseif($pengaturanGaji->jenis_karyawan == 'Teknisi') bg-green-100 text-green-800
                @else bg-orange-100 text-orange-800
                @endif">
                {{ $pengaturanGaji->jenis_karyawan }}
            </span>
        </p>
    </div>

    <!-- Jabatan -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Jabatan</label>
        <p class="text-base font-semibold text-gray-900">{{ $pengaturanGaji->jabatan }}</p>
    </div>

    <!-- Lokasi Kerja -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Lokasi Kerja</label>
        <p class="text-base font-semibold text-gray-900">{{ $pengaturanGaji->lokasi_kerja }}</p>
    </div>

    <!-- Gaji Pokok -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Gaji Pokok</label>
        <p class="text-base font-semibold text-gray-900 font-mono">Rp {{ number_format($pengaturanGaji->gaji_pokok, 0, ',', '.') }}</p>
    </div>

    <!-- Tunjangan Operasional -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Tunjangan Operasional</label>
        <p class="text-base font-semibold text-gray-900 font-mono">Rp {{ number_format($pengaturanGaji->tunjangan_operasional, 0, ',', '.') }}</p>
    </div>

    <!-- Potongan Koperasi -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Potongan Koperasi</label>
        <p class="text-base font-semibold text-red-600 font-mono">- Rp {{ number_format($pengaturanGaji->potongan_koperasi, 0, ',', '.') }}</p>
    </div>

    <!-- Gaji Nett -->
    <div class="bg-indigo-50 p-4 rounded-lg border-2 border-indigo-200">
        <label class="block text-sm font-medium text-indigo-700 mb-1">Gaji Nett</label>
        <p class="text-lg font-bold text-indigo-900 font-mono">Rp {{ number_format($pengaturanGaji->gaji_nett, 0, ',', '.') }}</p>
        <p class="text-xs text-indigo-600 mt-1">Gaji Pokok + Tunjangan - Potongan</p>
    </div>

    <!-- BPJS Kesehatan -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kesehatan</label>
        <p class="text-base font-semibold text-gray-900 font-mono">Rp {{ number_format($pengaturanGaji->bpjs_kesehatan, 0, ',', '.') }}</p>
    </div>

    <!-- BPJS Ketenagakerjaan -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Ketenagakerjaan</label>
        <p class="text-base font-semibold text-gray-900 font-mono">Rp {{ number_format($pengaturanGaji->bpjs_ketenagakerjaan, 0, ',', '.') }}</p>
    </div>

    <!-- BPJS Kecelakaan Kerja -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kecelakaan Kerja</label>
        <p class="text-base font-semibold text-gray-900 font-mono">Rp {{ number_format($pengaturanGaji->bpjs_kecelakaan_kerja, 0, ',', '.') }}</p>
    </div>

    <!-- BPJS Total -->
    <div class="bg-green-50 p-4 rounded-lg border-2 border-green-200">
        <label class="block text-sm font-medium text-green-700 mb-1">BPJS Total</label>
        <p class="text-lg font-bold text-green-900 font-mono">Rp {{ number_format($pengaturanGaji->bpjs_total, 0, ',', '.') }}</p>
        <p class="text-xs text-green-600 mt-1">Total semua BPJS</p>
    </div>

    <!-- Total Gaji -->
    <div class="bg-purple-50 p-4 rounded-lg border-2 border-purple-200 md:col-span-2">
        <label class="block text-sm font-medium text-purple-700 mb-1">Total Gaji</label>
        <p class="text-2xl font-bold text-purple-900 font-mono">Rp {{ number_format($pengaturanGaji->total_gaji, 0, ',', '.') }}</p>
        <p class="text-xs text-purple-600 mt-1">Gaji Nett + BPJS Total</p>
    </div>

    <!-- Keterangan -->
    @if($pengaturanGaji->keterangan)
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
        <p class="text-base text-gray-900">{{ $pengaturanGaji->keterangan }}</p>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Dibuat</label>
        <p class="text-sm text-gray-900">{{ $pengaturanGaji->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
        <p class="text-sm text-gray-900">{{ $pengaturanGaji->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>
