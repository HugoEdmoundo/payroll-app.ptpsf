@props(['absensi' => null, 'karyawanList' => []])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan *</label>
        <select name="id_karyawan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Karyawan</option>
            @foreach($karyawanList as $karyawan)
                <option value="{{ $karyawan->id_karyawan }}" {{ old('id_karyawan', $absensi->id_karyawan ?? '') == $karyawan->id_karyawan ? 'selected' : '' }}>
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
               value="{{ old('periode', $absensi->periode ?? '') }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('periode')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Hadir -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Hadir *</label>
        <input type="number" name="hadir" min="0"
               value="{{ old('hadir', $absensi->hadir ?? 0) }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('hadir')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- On Site -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">On Site</label>
        <input type="number" name="on_site" min="0"
               value="{{ old('on_site', $absensi->on_site ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('on_site')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- On Base -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">On Base</label>
        <input type="number" name="on_base" min="0"
               value="{{ old('on_base', $absensi->on_base ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('on_base')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Absence -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Absence</label>
        <input type="number" name="absence" min="0"
               value="{{ old('absence', $absensi->absence ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('absence')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Idle/Rest -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Idle/Rest</label>
        <input type="number" name="idle_rest" min="0"
               value="{{ old('idle_rest', $absensi->idle_rest ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('idle_rest')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Izin/Sakit/Cuti -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Izin/Sakit/Cuti</label>
        <input type="number" name="izin_sakit_cuti" min="0"
               value="{{ old('izin_sakit_cuti', $absensi->izin_sakit_cuti ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('izin_sakit_cuti')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tanpa Keterangan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tanpa Keterangan</label>
        <input type="number" name="tanpa_keterangan" min="0"
               value="{{ old('tanpa_keterangan', $absensi->tanpa_keterangan ?? 0) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('tanpa_keterangan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Keterangan -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
        <textarea name="keterangan" rows="3"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('keterangan', $absensi->keterangan ?? '') }}</textarea>
        @error('keterangan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Info Box -->
<div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
    <p class="text-sm text-blue-800">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Catatan:</strong> Jumlah hari dalam bulan akan otomatis terdeteksi dari periode yang dipilih.
    </p>
    <p class="text-sm text-blue-800 mt-2">
        <strong>Rumus Potongan:</strong> (Absence + Tanpa Keterangan) ÷ Jumlah Hari × (Gaji Pokok + Tunjangan Prestasi + Operasional)
    </p>
</div>
