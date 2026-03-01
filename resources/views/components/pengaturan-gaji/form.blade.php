@props(['pengaturanGaji' => null, 'settings' => []])

<!-- Info Box -->
<div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
    <div class="flex items-start">
        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
        <div class="text-sm text-blue-800">
            <p class="font-semibold mb-1">Pengaturan Gaji untuk Karyawan Kontrak</p>
            <ul class="list-disc list-inside space-y-1">
                <li>BPJS & Koperasi diatur di menu <strong>BPJS & Koperasi</strong> (data global)</li>
                <li>Tunjangan Prestasi akan dikalikan dengan NKI per karyawan</li>
            </ul>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Jenis Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Jenis Karyawan <span class="text-red-500">*</span>
        </label>
        <select name="jenis_karyawan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('jenis_karyawan') border-red-500 @enderror">
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
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Jabatan <span class="text-red-500">*</span>
        </label>
        <select name="jabatan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('jabatan') border-red-500 @enderror">
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
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Lokasi Kerja <span class="text-red-500">*</span>
        </label>
        <select name="lokasi_kerja" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('lokasi_kerja') border-red-500 @enderror">
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
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Gaji Pokok <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input type="number" name="gaji_pokok" step="0.01" min="0" 
                   value="{{ old('gaji_pokok', $pengaturanGaji->gaji_pokok ?? 0) }}" required
                   class="w-full pl-12 pr-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('gaji_pokok') border-red-500 @enderror"
                   placeholder="0.00">
        </div>
        @error('gaji_pokok')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tunjangan Operasional -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Tunjangan Operasional
        </label>
        <div class="relative">
            <input type="number" name="tunjangan_operasional" step="0.01" min="0" 
                   value="{{ old('tunjangan_operasional', $pengaturanGaji->tunjangan_operasional ?? 0) }}"
                   class="w-full pl-12 pr-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="0.00">
        </div>
        @error('tunjangan_operasional')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tunjangan Prestasi (Base untuk NKI) -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Tunjangan Prestasi (Base untuk NKI)
        </label>
        <div class="relative">
            <input type="number" name="tunjangan_prestasi" step="0.01" min="0" 
                   value="{{ old('tunjangan_prestasi', $pengaturanGaji->tunjangan_prestasi ?? 0) }}"
                   class="w-full pl-12 pr-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="0.00">
        </div>
        <p class="mt-1 text-xs text-gray-500">
            <i class="fas fa-info-circle mr-1"></i>
            Nilai ini akan dikalikan dengan persentase NKI per karyawan
        </p>
        @error('tunjangan_prestasi')
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
