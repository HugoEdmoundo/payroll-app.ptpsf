@props(['kasbon'])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Karyawan Info -->
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Karyawan</label>
        <p class="text-lg font-semibold text-gray-900">{{ $kasbon->karyawan->nama_karyawan ?? '-' }}</p>
        <p class="text-sm text-gray-600">Jenis: {{ $kasbon->karyawan->jenis_karyawan ?? '-' }} | Jabatan: {{ $kasbon->karyawan->jabatan ?? '-' }}</p>
    </div>

    <!-- Periode -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Periode</label>
        <p class="text-base font-semibold text-gray-900">
            {{ \Carbon\Carbon::createFromFormat('Y-m', $kasbon->periode)->format('F Y') }}
        </p>
    </div>

    <!-- Tanggal Pengajuan -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</label>
        <p class="text-base font-semibold text-gray-900">{{ $kasbon->tanggal_pengajuan->format('d F Y') }}</p>
    </div>

    <!-- Nominal -->
    <div class="bg-indigo-50 p-4 rounded-lg border-2 border-indigo-300">
        <label class="block text-sm font-medium text-indigo-700 mb-1">Nominal Kasbon</label>
        <p class="text-3xl font-bold text-indigo-900 font-mono">Rp {{ number_format($kasbon->nominal, 0, ',', '.') }}</p>
    </div>

    <!-- Metode Pembayaran -->
    <div class="bg-purple-50 p-4 rounded-lg border-2 border-purple-300">
        <label class="block text-sm font-medium text-purple-700 mb-1">Metode Pembayaran</label>
        <p class="text-2xl font-bold text-purple-900">{{ $kasbon->metode_pembayaran }}</p>
    </div>

    @if($kasbon->metode_pembayaran == 'Cicilan')
    <!-- Jumlah Cicilan -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <label class="block text-sm font-medium text-blue-700 mb-1">Jumlah Cicilan</label>
        <p class="text-2xl font-bold text-blue-900">{{ $kasbon->jumlah_cicilan }} bulan</p>
    </div>

    <!-- Nominal Per Cicilan -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <label class="block text-sm font-medium text-blue-700 mb-1">Nominal Per Cicilan</label>
        <p class="text-xl font-bold text-blue-900 font-mono">Rp {{ number_format($kasbon->nominal_per_cicilan, 0, ',', '.') }}</p>
    </div>

    <!-- Cicilan Terbayar -->
    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
        <label class="block text-sm font-medium text-green-700 mb-1">Cicilan Terbayar</label>
        <p class="text-2xl font-bold text-green-900">{{ $kasbon->cicilan_terbayar }} / {{ $kasbon->jumlah_cicilan }}</p>
        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($kasbon->cicilan_terbayar / $kasbon->jumlah_cicilan) * 100 }}%"></div>
        </div>
    </div>

    <!-- Sisa Cicilan -->
    <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
        <label class="block text-sm font-medium text-orange-700 mb-1">Sisa Cicilan</label>
        <p class="text-xl font-bold text-orange-900 font-mono">Rp {{ number_format($kasbon->sisa_cicilan, 0, ',', '.') }}</p>
    </div>
    @endif

    <!-- Status Pembayaran -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border-2 
                {{ $kasbon->status_pembayaran == 'Lunas' ? 'border-green-300' : 'border-yellow-300' }} md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
        <p class="text-3xl font-bold {{ $kasbon->status_pembayaran == 'Lunas' ? 'text-green-600' : 'text-yellow-600' }}">
            {{ $kasbon->status_pembayaran }}
        </p>
        @if($kasbon->status_pembayaran == 'Lunas')
            <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle"></i> Kasbon telah lunas
            </p>
        @else
            <p class="text-sm text-yellow-600 mt-2">
                <i class="fas fa-clock"></i> Menunggu pembayaran
            </p>
        @endif
    </div>

    <!-- Deskripsi -->
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Deskripsi</label>
        <p class="text-base text-gray-900">{{ $kasbon->deskripsi }}</p>
    </div>

    <!-- Keterangan -->
    @if($kasbon->keterangan)
    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
        <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
        <p class="text-base text-gray-900">{{ $kasbon->keterangan }}</p>
    </div>
    @endif

    <!-- Bayar Cicilan Button -->
    @if($kasbon->metode_pembayaran == 'Cicilan' && $kasbon->status_pembayaran == 'Pending')
    <div class="md:col-span-2">
        <form action="{{ route('payroll.kasbon.bayar-cicilan', $kasbon->id_kasbon) }}" method="POST" class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            @csrf
            <label class="block text-sm font-medium text-blue-700 mb-2">Bayar Cicilan</label>
            <div class="flex gap-4">
                <input type="number" name="jumlah_bayar" min="1" max="{{ $kasbon->jumlah_cicilan - $kasbon->cicilan_terbayar }}" value="1" required
                       class="flex-1 px-4 py-2 rounded-lg border border-blue-300 focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150">
                    <i class="fas fa-money-bill-wave mr-2"></i>Bayar
                </button>
            </div>
            <p class="text-xs text-blue-600 mt-2">Sisa cicilan yang belum dibayar: {{ $kasbon->jumlah_cicilan - $kasbon->cicilan_terbayar }} bulan</p>
        </form>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Dibuat</label>
        <p class="text-sm text-gray-900">{{ $kasbon->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
        <p class="text-sm text-gray-900">{{ $kasbon->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>
