@props(['kasbon' => null, 'karyawanList' => []])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Karyawan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan *</label>
        <select name="id_karyawan" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Karyawan</option>
            @foreach($karyawanList as $karyawan)
                <option value="{{ $karyawan->id_karyawan }}" {{ old('id_karyawan', $kasbon->id_karyawan ?? '') == $karyawan->id_karyawan ? 'selected' : '' }}>
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
               value="{{ old('periode', $kasbon->periode ?? '') }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('periode')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tanggal Pengajuan -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengajuan *</label>
        <input type="date" name="tanggal_pengajuan" 
               value="{{ old('tanggal_pengajuan', $kasbon->tanggal_pengajuan ? $kasbon->tanggal_pengajuan->format('Y-m-d') : '') }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('tanggal_pengajuan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nominal -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal *</label>
        <input type="number" name="nominal" step="0.01" min="0"
               value="{{ old('nominal', $kasbon->nominal ?? 0) }}" required
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('nominal')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Metode Pembayaran -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
        <select name="metode_pembayaran" id="metode_pembayaran" required
                onchange="toggleCicilanFields()"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="Langsung" {{ old('metode_pembayaran', $kasbon->metode_pembayaran ?? '') == 'Langsung' ? 'selected' : '' }}>Langsung (Potong Sekali)</option>
            <option value="Cicilan" {{ old('metode_pembayaran', $kasbon->metode_pembayaran ?? '') == 'Cicilan' ? 'selected' : '' }}>Cicilan</option>
        </select>
        @error('metode_pembayaran')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Jumlah Cicilan (only for Cicilan method) -->
    <div id="cicilan_field" style="display: {{ old('metode_pembayaran', $kasbon->metode_pembayaran ?? 'Langsung') == 'Cicilan' ? 'block' : 'none' }}">
        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Cicilan *</label>
        <input type="number" name="jumlah_cicilan" min="1"
               value="{{ old('jumlah_cicilan', $kasbon->jumlah_cicilan ?? 1) }}"
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('jumlah_cicilan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @if($kasbon)
    <!-- Status Pembayaran (only for edit) -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran *</label>
        <select name="status_pembayaran" required
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="Pending" {{ old('status_pembayaran', $kasbon->status_pembayaran) == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Lunas" {{ old('status_pembayaran', $kasbon->status_pembayaran) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
        </select>
        @error('status_pembayaran')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    @endif

    <!-- Deskripsi -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
        <textarea name="deskripsi" rows="3" required
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $kasbon->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Keterangan -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
        <textarea name="keterangan" rows="2"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('keterangan', $kasbon->keterangan ?? '') }}</textarea>
        @error('keterangan')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Info Box -->
<div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
    <p class="text-sm text-blue-800">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Metode Langsung:</strong> Kasbon akan dipotong sekali pada periode yang dipilih.
    </p>
    <p class="text-sm text-blue-800 mt-2">
        <strong>Metode Cicilan:</strong> Kasbon akan dipotong secara bertahap sesuai jumlah cicilan yang ditentukan.
    </p>
</div>

<script>
function toggleCicilanFields() {
    const metode = document.getElementById('metode_pembayaran').value;
    const cicilanField = document.getElementById('cicilan_field');
    
    if (metode === 'Cicilan') {
        cicilanField.style.display = 'block';
    } else {
        cicilanField.style.display = 'none';
    }
}
</script>
