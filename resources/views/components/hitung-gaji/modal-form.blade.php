<form id="hitungGajiForm" onsubmit="submitForm(event)">
    @csrf
    <input type="hidden" name="karyawan_id" value="{{ $data['karyawan']['id'] }}">
    <input type="hidden" name="periode" value="{{ $data['periode'] }}">
    @if($data['mode'] === 'edit')
    <input type="hidden" name="hitung_gaji_id" value="{{ $data['hitung_gaji_id'] }}">
    @endif

    <!-- Employee Info -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-lg mb-6 border border-indigo-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $data['karyawan']['nama'] }}</h3>
                <p class="text-sm text-gray-600">{{ $data['karyawan']['jabatan'] }} - {{ $data['karyawan']['jenis'] }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Periode</p>
                <p class="text-lg font-bold text-indigo-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $data['periode'])->format('F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Info Alerts -->
    @if($data['nki_info'])
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
            <div class="text-sm text-blue-800">
                <strong>NKI Calculation:</strong> Nilai NKI {{ $data['nki_info']['nilai'] }} → {{ $data['nki_info']['persentase'] }}% × Rp {{ number_format($data['nki_info']['acuan'], 0, ',', '.') }} = Rp {{ number_format($data['fields']['tunjangan_prestasi'], 0, ',', '.') }}
            </div>
        </div>
    </div>
    @endif

    @if($data['absensi_info'])
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-orange-600 mt-0.5 mr-2"></i>
            <div class="text-sm text-orange-800">
                <strong>Absensi Calculation:</strong> ({{ $data['absensi_info']['absence'] }} + {{ $data['absensi_info']['tanpa_keterangan'] }}) ÷ {{ $data['absensi_info']['jumlah_hari'] }} × Rp {{ number_format($data['absensi_info']['base_amount'], 0, ',', '.') }} = Rp {{ number_format($data['fields']['potongan_absensi'], 0, ',', '.') }}
            </div>
        </div>
    </div>
    @endif

    <!-- PENDAPATAN Section -->
    <div class="border-t-4 border-green-500 bg-green-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
            <i class="fas fa-arrow-up mr-2"></i>PENDAPATAN (Income)
        </h3>
        <div class="space-y-4">
            @php
            $pendapatanFields = [
                'gaji_pokok' => 'Gaji Pokok',
                'bpjs_kesehatan_pendapatan' => 'BPJS Kesehatan (Pendapatan)',
                'bpjs_kecelakaan_kerja_pendapatan' => 'BPJS Kecelakaan Kerja (Pendapatan)',
                'bpjs_kematian_pendapatan' => 'BPJS Kematian (Pendapatan)',
                'bpjs_jht_pendapatan' => 'BPJS JHT (Pendapatan)',
                'bpjs_jp_pendapatan' => 'BPJS JP (Pendapatan)',
                'tunjangan_prestasi' => 'Tunjangan Prestasi (from NKI)',
                'tunjangan_konjungtur' => 'Tunjangan Konjungtur',
                'benefit_ibadah' => 'Benefit Ibadah',
                'benefit_komunikasi' => 'Benefit Komunikasi',
                'benefit_operasional' => 'Benefit Operasional',
                'reward' => 'Reward'
            ];
            @endphp
            
            @foreach($pendapatanFields as $field => $label)
            <x-hitung-gaji.field-with-adjustment 
                :field="$field" 
                :label="$label" 
                :value="$data['fields'][$field]"
                :adjustment="$data['adjustments'][$field] ?? null"
            />
            @endforeach
        </div>
    </div>

    <!-- PENGELUARAN Section -->
    <div class="border-t-4 border-red-500 bg-red-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center">
            <i class="fas fa-arrow-down mr-2"></i>PENGELUARAN (Deductions)
        </h3>
        <div class="space-y-4">
            @php
            $pengeluaranFields = [
                'bpjs_kesehatan_pengeluaran' => 'BPJS Kesehatan (Pengeluaran)',
                'bpjs_kecelakaan_kerja_pengeluaran' => 'BPJS Kecelakaan Kerja (Pengeluaran)',
                'bpjs_kematian_pengeluaran' => 'BPJS Kematian (Pengeluaran)',
                'bpjs_jht_pengeluaran' => 'BPJS JHT (Pengeluaran)',
                'bpjs_jp_pengeluaran' => 'BPJS JP (Pengeluaran)',
                'tabungan_koperasi' => 'Tabungan Koperasi',
                'koperasi' => 'Koperasi',
                'kasbon' => 'Kasbon',
                'umroh' => 'Umroh',
                'kurban' => 'Kurban',
                'mutabaah' => 'Mutabaah',
                'potongan_absensi' => 'Potongan Absensi (from Absensi)',
                'potongan_kehadiran' => 'Potongan Kehadiran'
            ];
            @endphp
            
            @foreach($pengeluaranFields as $field => $label)
            <x-hitung-gaji.field-with-adjustment 
                :field="$field" 
                :label="$label" 
                :value="$data['fields'][$field]"
                :adjustment="$data['adjustments'][$field] ?? null"
            />
            @endforeach
        </div>
    </div>

    <!-- Keterangan -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Optional)</label>
        <textarea name="keterangan" 
                  rows="3"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Additional notes...">{{ $data['keterangan'] ?? '' }}</textarea>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3 pt-6 border-t">
        <button type="button" 
                onclick="closeModal()" 
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            Cancel
        </button>
        <button type="submit" 
                class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>

<script>
function submitForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    
    fetch('/payroll/hitung-gaji', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        alert('Error: ' + error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>
