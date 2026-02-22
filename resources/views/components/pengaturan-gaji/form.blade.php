@props(['pengaturanGaji' => null, 'settings' => []])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Jenis Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Karyawan *</label>
        <select name="jenis_karyawan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Jenis Karyawan</option>
            @foreach($settings['jenis_karyawan'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('jenis_karyawan', $pengaturanGaji->jenis_karyawan ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('jenis_karyawan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Jabatan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan *</label>
        <select name="jabatan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Jabatan</option>
            @foreach($settings['jabatan_options'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('jabatan', $pengaturanGaji->jabatan ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('jabatan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Lokasi Kerja -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kerja *</label>
        <select name="lokasi_kerja" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Lokasi Kerja</option>
            @foreach($settings['lokasi_kerja'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('lokasi_kerja', $pengaturanGaji->lokasi_kerja ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('lokasi_kerja')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Gaji Pokok -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Gaji Pokok *</label>
        <input type="number" name="gaji_pokok" step="0.01" min="0" 
               value="{{ old('gaji_pokok', $pengaturanGaji->gaji_pokok ?? 0) }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="0.00">
        @error('gaji_pokok')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tunjangan Operasional -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Operasional</label>
        <input type="number" name="tunjangan_operasional" step="0.01" min="0" 
               value="{{ old('tunjangan_operasional', $pengaturanGaji->tunjangan_operasional ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="0.00">
        @error('tunjangan_operasional')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Potongan Koperasi -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Potongan Koperasi</label>
        <input type="number" name="potongan_koperasi" step="0.01" min="0" 
               value="{{ old('potongan_koperasi', $pengaturanGaji->potongan_koperasi ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="0.00">
        @error('potongan_koperasi')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- BPJS Kesehatan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
        <input type="number" name="bpjs_kesehatan" step="0.01" min="0" 
               value="{{ old('bpjs_kesehatan', $pengaturanGaji->bpjs_kesehatan ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="0.00">
        @error('bpjs_kesehatan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- BPJS Ketenagakerjaan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Ketenagakerjaan</label>
        <input type="number" name="bpjs_ketenagakerjaan" step="0.01" min="0" 
               value="{{ old('bpjs_ketenagakerjaan', $pengaturanGaji->bpjs_ketenagakerjaan ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="0.00">
        @error('bpjs_ketenagakerjaan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- BPJS Kecelakaan Kerja -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja</label>
        <input type="number" name="bpjs_kecelakaan_kerja" step="0.01" min="0" 
               value="{{ old('bpjs_kecelakaan_kerja', $pengaturanGaji->bpjs_kecelakaan_kerja ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="0.00">
        @error('bpjs_kecelakaan_kerja')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Keterangan (Full Width) -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
        <textarea name="keterangan" rows="3"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Catatan tambahan...">{{ old('keterangan', $pengaturanGaji->keterangan ?? '') }}</textarea>
        @error('keterangan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Auto-calculated fields info -->
<div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
    <p class="text-sm text-blue-800">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Catatan:</strong> Gaji Nett, BPJS Total, dan Total Gaji akan dihitung otomatis berdasarkan input di atas.
    </p>
</div>
