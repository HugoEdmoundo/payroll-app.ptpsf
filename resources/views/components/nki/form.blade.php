@props(['nki' => null, 'karyawanList' => []])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan *</label>
        <select name="id_karyawan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Karyawan</option>
            @foreach($karyawanList as $karyawan)
                <option value="{{ $karyawan->id_karyawan }}" {{ old('id_karyawan', $nki->id_karyawan ?? '') == $karyawan->id_karyawan ? 'selected' : '' }}>
                    {{ $karyawan->nama_karyawan }} - {{ $karyawan->jenis_karyawan }}
                </option>
            @endforeach
        </select>
        @error('id_karyawan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Periode -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Periode (YYYY-MM) *</label>
        <input type="month" name="periode" 
               value="{{ old('periode', $nki->periode ?? '') }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('periode')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kemampuan (20%) -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Kemampuan (20%) *
            <span class="text-xs text-gray-500">Max: 10</span>
        </label>
        <input type="number" name="kemampuan" step="0.01" min="0" max="10"
               value="{{ old('kemampuan', $nki->kemampuan ?? 0) }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('kemampuan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kontribusi 1 (20%) -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Kontribusi 1 (20%) *
            <span class="text-xs text-gray-500">Max: 10</span>
        </label>
        <input type="number" name="kontribusi_1" step="0.01" min="0" max="10"
               value="{{ old('kontribusi_1', $nki->kontribusi_1 ?? 0) }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('kontribusi_1')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kontribusi 2 (40%) -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Kontribusi 2 (40%) *
            <span class="text-xs text-gray-500">Max: 10</span>
        </label>
        <input type="number" name="kontribusi_2" step="0.01" min="0" max="10"
               value="{{ old('kontribusi_2', $nki->kontribusi_2 ?? 0) }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('kontribusi_2')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kedisiplinan (20%) -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Kedisiplinan (20%) *
            <span class="text-xs text-gray-500">Max: 10</span>
        </label>
        <input type="number" name="kedisiplinan" step="0.01" min="0" max="10"
               value="{{ old('kedisiplinan', $nki->kedisiplinan ?? 0) }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('kedisiplinan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Keterangan -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
        <textarea name="keterangan" rows="3"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('keterangan', $nki->keterangan ?? '') }}</textarea>
        @error('keterangan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Info Box -->
<div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
    <p class="text-sm text-blue-800">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Rumus NKI:</strong> Kemampuan(20%) + Kontribusi 1(20%) + Kontribusi 2(40%) + Kedisiplinan(20%)
    </p>
    <p class="text-sm text-blue-800 mt-2">
        <strong>Persentase Tunjangan:</strong> NKI ≥ 8.5 → 100% | NKI ≥ 8.0 → 80% | NKI < 8.0 → 70%
    </p>
</div>
