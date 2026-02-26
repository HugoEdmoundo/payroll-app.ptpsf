@props(['karyawan' => null, 'settings' => []])

<div class="space-y-6" x-data="karyawanForm()" x-init="init()">
    
    <!-- Section 1: Informasi Dasar -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-user-circle text-indigo-600 mr-2"></i>
            Informasi Dasar
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Karyawan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Karyawan *</label>
                <input type="text" name="nama_karyawan" value="{{ old('nama_karyawan', $karyawan->nama_karyawan ?? '') }}" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                @error('nama_karyawan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $karyawan->email ?? '') }}" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="email@example.com">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- No Telp -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No Telp *</label>
                <input type="tel" name="no_telp" value="{{ old('no_telp', $karyawan->no_telp ?? '') }}" required
                       onkeypress="return hanyaAngka(event)"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="08xxxxxxxxxx"
                       maxlength="15">
                @error('no_telp')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Join Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Join Date *</label>
                <input type="date" name="join_date" value="{{ old('join_date', isset($karyawan) ? $karyawan->join_date->format('Y-m-d') : '') }}" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                @error('join_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Section 2: Posisi & Lokasi -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-briefcase text-purple-600 mr-2"></i>
            Posisi & Lokasi Kerja
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Jenis Karyawan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Karyawan *</label>
                <select name="jenis_karyawan" required
                        x-model="jenisKaryawan"
                        @change="loadJabatan()"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
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

            <!-- Jabatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan *</label>
                <select name="jabatan" required
                        x-model="jabatan"
                        :disabled="!jenisKaryawan"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition">
                    <option value="" x-text="jenisKaryawan ? 'Pilih Jabatan' : 'Pilih Jenis Karyawan dulu'"></option>
                    <template x-for="jab in jabatanList" :key="jab">
                        <option :value="jab" x-text="jab"></option>
                    </template>
                </select>
                @error('jabatan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lokasi Kerja -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kerja *</label>
                <select name="lokasi_kerja" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
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

            <!-- Status Karyawan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Karyawan *</label>
                <select name="status_karyawan" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
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
    </div>

    <!-- Section 3: Informasi Bank -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-university text-green-600 mr-2"></i>
            Informasi Bank & Rekening
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Bank -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bank *</label>
                <select name="bank" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
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
                <input type="tel" name="no_rekening" value="{{ old('no_rekening', $karyawan->no_rekening ?? '') }}" required
                       onkeypress="return hanyaAngka(event)"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 20)"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="Nomor Rekening"
                       maxlength="20">
                @error('no_rekening')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Section 4: NPWP & BPJS -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-id-card text-amber-600 mr-2"></i>
            NPWP & BPJS
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- NPWP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NPWP</label>
                <input type="tel" name="npwp" value="{{ old('npwp', $karyawan->npwp ?? '') }}"
                       onkeypress="return hanyaAngka(event)"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="15 digit"
                       maxlength="15">
                @error('npwp')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- BPJS Kesehatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
                <input type="tel" name="bpjs_kesehatan_no" value="{{ old('bpjs_kesehatan_no', $karyawan->bpjs_kesehatan_no ?? '') }}"
                       onkeypress="return hanyaAngka(event)"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="13 digit"
                       maxlength="13">
                @error('bpjs_kesehatan_no')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- BPJS Kecelakaan Kerja -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja</label>
                <input type="tel" name="bpjs_kecelakaan_kerja_no" value="{{ old('bpjs_kecelakaan_kerja_no', $karyawan->bpjs_kecelakaan_kerja_no ?? '') }}"
                       onkeypress="return hanyaAngka(event)"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="13 digit"
                       maxlength="13">
                @error('bpjs_kecelakaan_kerja_no')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- BPJS TK -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">BPJS TK</label>
                <input type="tel" name="bpjs_tk_no" value="{{ old('bpjs_tk_no', $karyawan->bpjs_tk_no ?? '') }}"
                       onkeypress="return hanyaAngka(event)"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="11 digit"
                       maxlength="11">
                @error('bpjs_tk_no')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Section 5: Informasi Keluarga -->
    <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl p-6 border border-rose-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-heart text-rose-600 mr-2"></i>
            Informasi Keluarga
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status Perkawinan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Perkawinan</label>
                <select name="status_perkawinan"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
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

            <!-- Jumlah Anak -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Anak</label>
                <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak', $karyawan->jumlah_anak ?? 0) }}" 
                       min="0" max="50" step="1"
                       onkeypress="return hanyaAngka(event)"
                       oninput="if(this.value.length > 2) this.value = this.value.slice(0,2); if(parseInt(this.value) > 50) this.value = 50; if(parseInt(this.value) < 0) this.value = 0;"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                @error('jumlah_anak')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Istri -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Istri</label>
                <input type="text" name="nama_istri" value="{{ old('nama_istri', $karyawan->nama_istri ?? '') }}"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="Nama lengkap istri">
                @error('nama_istri')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- No Telp Istri -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No Telp Istri</label>
                <input type="tel" name="no_telp_istri" value="{{ old('no_telp_istri', $karyawan->no_telp_istri ?? '') }}"
                       onkeypress="return hanyaAngka(event)"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                       placeholder="08xxxxxxxxxx"
                       maxlength="15">
                @error('no_telp_istri')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

</div>

<script>
function hanyaAngka(event) {
    var charCode = (event.which) ? event.which : event.keyCode;
    // Izinkan hanya angka (0-9)
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        event.preventDefault();
        return false;
    }
    return true;
}

function karyawanForm() {
    return {
        jenisKaryawan: '{{ old('jenis_karyawan', $karyawan->jenis_karyawan ?? '') }}',
        jabatan: '{{ old('jabatan', $karyawan->jabatan ?? '') }}',
        jabatanList: [],
        
        init() {
            // Load jabatan on init if jenis already selected
            if (this.jenisKaryawan) {
                this.loadJabatan();
            }
        },
        
        async loadJabatan() {
            if (!this.jenisKaryawan) {
                this.jabatanList = [];
                this.jabatan = '';
                return;
            }
            
            try {
                const response = await fetch(`/admin/api/jabatan-by-jenis/${encodeURIComponent(this.jenisKaryawan)}`);
                const data = await response.json();
                
                this.jabatanList = data;
                
                // If current jabatan not in list, reset
                if (this.jabatan && !data.includes(this.jabatan)) {
                    this.jabatan = '';
                }
            } catch (error) {
                console.error('Failed to load jabatan:', error);
                // Fallback to all jabatan options
                this.jabatanList = @json(array_values($settings['jabatan_options'] ?? []));
            }
        }
    }
}
</script>
