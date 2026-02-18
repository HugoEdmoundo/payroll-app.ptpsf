@props(['karyawan' => null, 'settings' => []])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Nama Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Karyawan *</label>
        <input type="text" name="nama_karyawan" value="{{ old('nama_karyawan', $karyawan->nama_karyawan ?? '') }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('nama_karyawan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email', $karyawan->email ?? '') }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- No Telp -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
        <input type="text" name="no_telp" value="{{ old('no_telp', $karyawan->no_telp ?? '') }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('no_telp')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Join Date -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Join Date *</label>
        <input type="date" name="join_date" value="{{ old('join_date', isset($karyawan) ? $karyawan->join_date->format('Y-m-d') : '') }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('join_date')
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
                <option value="{{ $value }}" {{ old('jabatan', $karyawan->jabatan ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('jabatan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Lokasi Kerja - DROPDOWN dari CMS -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kerja *</label>
        <select name="lokasi_kerja" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Lokasi Kerja</option>
            @foreach($settings['lokasi_kerja'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('lokasi_kerja', $karyawan->lokasi_kerja ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('lokasi_kerja')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Jenis Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Karyawan *</label>
        <select name="jenis_karyawan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Jenis</option>
            @foreach($settings['jenis_karyawan'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('jenis_karyawan', $karyawan->jenis_karyawan ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('jenis_karyawan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status Pegawai -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pegawai *</label>
        <select name="status_pegawai" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Status</option>
            @foreach($settings['status_pegawai'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('status_pegawai', $karyawan->status_pegawai ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('status_pegawai')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Bank -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Bank *</label>
        <select name="bank" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Bank</option>
            @foreach($settings['bank_options'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('bank', $karyawan->bank ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('bank')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- No Rekening -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">No Rekening *</label>
        <input type="text" name="no_rekening" value="{{ old('no_rekening', $karyawan->no_rekening ?? '') }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('no_rekening')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- NPWP -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">NPWP</label>
        <input type="text" name="npwp" value="{{ old('npwp', $karyawan->npwp ?? '') }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('npwp')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- BPJS Kesehatan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
        <input type="text" name="bpjs_kesehatan_no" value="{{ old('bpjs_kesehatan_no', $karyawan->bpjs_kesehatan_no ?? '') }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('bpjs_kesehatan_no')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- BPJS TK -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS TK</label>
        <input type="text" name="bpjs_tk_no" value="{{ old('bpjs_tk_no', $karyawan->bpjs_tk_no ?? '') }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('bpjs_tk_no')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status Perkawinan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Perkawinan</label>
        <select name="status_perkawinan"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Status</option>
            @foreach($settings['status_perkawinan'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('status_perkawinan', $karyawan->status_perkawinan ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('status_perkawinan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nama Istri -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Istri</label>
        <input type="text" name="nama_istri" value="{{ old('nama_istri', $karyawan->nama_istri ?? '') }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('nama_istri')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Jumlah Anak -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Anak</label>
        <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak', $karyawan->jumlah_anak ?? 0) }}" min="0"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('jumlah_anak')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- No Telp Istri -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">No Telp Istri</label>
        <input type="text" name="no_telp_istri" value="{{ old('no_telp_istri', $karyawan->no_telp_istri ?? '') }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('no_telp_istri')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Karyawan *</label>
        <select name="status_karyawan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Status</option>
            @foreach($settings['status_karyawan'] ?? [] as $key => $value)
                <option value="{{ $value }}" {{ old('status_karyawan', $karyawan->status_karyawan ?? '') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('status_karyawan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>