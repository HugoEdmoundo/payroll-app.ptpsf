<div class="space-y-6">
    <!-- Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan *</label>
            <select name="id_karyawan" 
                    required
                    {{ isset($acuanGaji) && $acuanGaji ? 'disabled' : '' }}
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('id_karyawan') border-red-500 @enderror">
                <option value="">Select Employee</option>
                @foreach($karyawanList as $karyawan)
                    <option value="{{ $karyawan->id_karyawan }}" 
                            {{ old('id_karyawan', isset($acuanGaji) ? $acuanGaji->id_karyawan : '') == $karyawan->id_karyawan ? 'selected' : '' }}>
                        {{ $karyawan->nama_karyawan }} - {{ $karyawan->jenis_karyawan }} - {{ $karyawan->jabatan }}
                    </option>
                @endforeach
            </select>
            @if(isset($acuanGaji) && $acuanGaji)
                <input type="hidden" name="id_karyawan" value="{{ $acuanGaji->id_karyawan }}">
            @endif
            @error('id_karyawan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Periode *</label>
            <input type="month" 
                   name="periode" 
                   value="{{ old('periode', isset($acuanGaji) ? $acuanGaji->periode : '') }}"
                   required
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('periode') border-red-500 @enderror">
            @error('periode')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Pendapatan Section -->
    <div class="border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-arrow-up text-green-600 mr-2"></i>
            Pendapatan (Income)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gaji Pokok</label>
                <input type="number" 
                       name="gaji_pokok" 
                       value="{{ old('gaji_pokok', isset($acuanGaji) ? $acuanGaji->gaji_pokok : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan (Pendapatan)</label>
                <input type="number" 
                       name="bpjs_kesehatan_pendapatan" 
                       value="{{ old('bpjs_kesehatan_pendapatan', isset($acuanGaji) ? $acuanGaji->bpjs_kesehatan_pendapatan : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja (Pendapatan)</label>
                <input type="number" 
                       name="bpjs_kecelakaan_kerja_pendapatan" 
                       value="{{ old('bpjs_kecelakaan_kerja_pendapatan', isset($acuanGaji) ? $acuanGaji->bpjs_kecelakaan_kerja_pendapatan : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kematian (Pendapatan)</label>
                <input type="number" 
                       name="bpjs_kematian_pendapatan" 
                       value="{{ old('bpjs_kematian_pendapatan', isset($acuanGaji) ? $acuanGaji->bpjs_kematian_pendapatan : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JHT (Pendapatan)</label>
                <input type="number" 
                       name="bpjs_jht_pendapatan" 
                       value="{{ old('bpjs_jht_pendapatan', isset($acuanGaji) ? $acuanGaji->bpjs_jht_pendapatan : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JP (Pendapatan)</label>
                <input type="number" 
                       name="bpjs_jp_pendapatan" 
                       value="{{ old('bpjs_jp_pendapatan', isset($acuanGaji) ? $acuanGaji->bpjs_jp_pendapatan : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Prestasi</label>
                <input type="number" 
                       name="tunjangan_prestasi" 
                       value="{{ old('tunjangan_prestasi', isset($acuanGaji) ? $acuanGaji->tunjangan_prestasi : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Konjungtur</label>
                <input type="number" 
                       name="tunjangan_konjungtur" 
                       value="{{ old('tunjangan_konjungtur', isset($acuanGaji) ? $acuanGaji->tunjangan_konjungtur : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Ibadah</label>
                <input type="number" 
                       name="benefit_ibadah" 
                       value="{{ old('benefit_ibadah', isset($acuanGaji) ? $acuanGaji->benefit_ibadah : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Komunikasi</label>
                <input type="number" 
                       name="benefit_komunikasi" 
                       value="{{ old('benefit_komunikasi', isset($acuanGaji) ? $acuanGaji->benefit_komunikasi : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Operasional</label>
                <input type="number" 
                       name="benefit_operasional" 
                       value="{{ old('benefit_operasional', isset($acuanGaji) ? $acuanGaji->benefit_operasional : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reward</label>
                <input type="number" 
                       name="reward" 
                       value="{{ old('reward', isset($acuanGaji) ? $acuanGaji->reward : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <!-- Pengeluaran Section -->
    <div class="border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-arrow-down text-red-600 mr-2"></i>
            Pengeluaran (Deductions)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan (Pengeluaran)</label>
                <input type="number" 
                       name="bpjs_kesehatan_pengeluaran" 
                       value="{{ old('bpjs_kesehatan_pengeluaran', isset($acuanGaji) ? $acuanGaji->bpjs_kesehatan_pengeluaran : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja (Pengeluaran)</label>
                <input type="number" 
                       name="bpjs_kecelakaan_kerja_pengeluaran" 
                       value="{{ old('bpjs_kecelakaan_kerja_pengeluaran', isset($acuanGaji) ? $acuanGaji->bpjs_kecelakaan_kerja_pengeluaran : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kematian (Pengeluaran)</label>
                <input type="number" 
                       name="bpjs_kematian_pengeluaran" 
                       value="{{ old('bpjs_kematian_pengeluaran', isset($acuanGaji) ? $acuanGaji->bpjs_kematian_pengeluaran : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JHT (Pengeluaran)</label>
                <input type="number" 
                       name="bpjs_jht_pengeluaran" 
                       value="{{ old('bpjs_jht_pengeluaran', isset($acuanGaji) ? $acuanGaji->bpjs_jht_pengeluaran : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JP (Pengeluaran)</label>
                <input type="number" 
                       name="bpjs_jp_pengeluaran" 
                       value="{{ old('bpjs_jp_pengeluaran', isset($acuanGaji) ? $acuanGaji->bpjs_jp_pengeluaran : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tabungan Koperasi</label>
                <input type="number" 
                       name="tabungan_koperasi" 
                       value="{{ old('tabungan_koperasi', isset($acuanGaji) ? $acuanGaji->tabungan_koperasi : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Koperasi</label>
                <input type="number" 
                       name="koperasi" 
                       value="{{ old('koperasi', isset($acuanGaji) ? $acuanGaji->koperasi : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kasbon</label>
                <input type="number" 
                       name="kasbon" 
                       value="{{ old('kasbon', isset($acuanGaji) ? $acuanGaji->kasbon : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Umroh</label>
                <input type="number" 
                       name="umroh" 
                       value="{{ old('umroh', isset($acuanGaji) ? $acuanGaji->umroh : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kurban</label>
                <input type="number" 
                       name="kurban" 
                       value="{{ old('kurban', isset($acuanGaji) ? $acuanGaji->kurban : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mutabaah</label>
                <input type="number" 
                       name="mutabaah" 
                       value="{{ old('mutabaah', isset($acuanGaji) ? $acuanGaji->mutabaah : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Potongan Absensi</label>
                <input type="number" 
                       name="potongan_absensi" 
                       value="{{ old('potongan_absensi', isset($acuanGaji) ? $acuanGaji->potongan_absensi : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Potongan Kehadiran</label>
                <input type="number" 
                       name="potongan_kehadiran" 
                       value="{{ old('potongan_kehadiran', isset($acuanGaji) ? $acuanGaji->potongan_kehadiran : 0) }}"
                       step="0.01"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="border-t pt-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
        <textarea name="keterangan" 
                  rows="3"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Additional notes...">{{ old('keterangan', isset($acuanGaji) ? $acuanGaji->keterangan : '') }}</textarea>
    </div>

    <!-- Info Note -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Auto-calculation:</p>
                <ul class="list-disc list-inside">
                    <li>Total Pendapatan and Total Pengeluaran will be calculated automatically</li>
                    <li>Gaji Bersih = Total Pendapatan - Total Pengeluaran</li>
                    <li>Data from Pengaturan Gaji and Komponen are pre-filled when using Generate</li>
                </ul>
            </div>
        </div>
    </div>
</div>
