<div x-data="{ activeTab: 'resmi', downloadOpen: false }">

    <!-- Modal Header with Close Button -->
    <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-200">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Slip Gaji</h3>
            <p class="text-sm text-gray-600 mt-1" id="modalEmployeeInfo"></p>
        </div>
        <button type="button" onclick="closeSlipModal()" class="text-gray-400 hover:text-gray-600 transition">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>

    <!-- Content Area -->
    <div class="max-w-4xl mx-auto px-6 py-6">

    <!-- ================= HEADER CONTROL ================= -->
    <div class="flex items-center justify-between mb-6 pb-4 relative z-10">

        <!-- Tabs -->
        <div class="inline-flex rounded-lg bg-gray-100 p-1">
            <button @click="activeTab = 'resmi'"
                :class="activeTab === 'resmi' ? 'bg-gray-900 text-white shadow-md' : 'bg-white text-gray-900 border border-gray-300 shadow-sm'"
                class="px-6 py-2 text-sm font-medium rounded-md transition-all">
                <i class="fas fa-file-alt mr-2" :class="activeTab === 'resmi' ? 'text-white' : 'text-gray-700'"></i>Slip Resmi
            </button>

            <button @click="activeTab = 'text'"
                :class="activeTab === 'text' ? 'bg-gray-900 text-white shadow-md' : 'bg-white text-gray-900 border border-gray-300 shadow-sm'"
                class="px-6 py-2 text-sm font-medium rounded-md transition-all">
                <i class="fas fa-terminal mr-2" :class="activeTab === 'text' ? 'text-white' : 'text-gray-700'"></i>Format Text
            </button>
        </div>

        <!-- Download -->
        <div class="relative z-20" @keydown.escape="downloadOpen = false">
            <button @click="downloadOpen = !downloadOpen" type="button"
                class="inline-flex items-center px-5 py-2.5 bg-white text-gray-900 border border-gray-300 shadow-sm text-sm font-medium rounded-lg hover:bg-gray-50 transition-all">
                <i class="fas fa-download mr-2 text-gray-700"></i>Download
                <i class="fas fa-chevron-down ml-2 text-xs text-gray-700"></i>
            </button>

            <div x-show="downloadOpen"
                x-transition
                @click.away="downloadOpen = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-300 py-1 z-[100]">

                <button onclick="downloadPDF({{ $data['hitung_gaji']->id }})" type="button"
                        class="w-full text-left px-4 py-2.5 text-sm text-gray-900 hover:bg-gray-100 transition flex items-center font-medium">
                    <i class="fas fa-file-pdf mr-3 w-4 text-red-500"></i>Download PDF
                </button>

                <button onclick="downloadPNG()" type="button"
                        class="w-full text-left px-4 py-2.5 text-sm text-gray-900 hover:bg-gray-100 transition flex items-center font-medium">
                    <i class="fas fa-file-image mr-3 w-4 text-purple-600"></i>Download PNG
                </button>

                <div class="my-1 border-t border-gray-200"></div>

                <button onclick="printSlip()" type="button"
                        class="w-full text-left px-4 py-2.5 text-sm text-gray-900 hover:bg-gray-100 transition flex items-center font-medium">
                    <i class="fas fa-print mr-3 w-4 text-blue-600"></i>Print
                </button>

                <button onclick="copyText()" type="button" id="copyButton"
                        class="w-full text-left px-4 py-2.5 text-sm text-gray-900 hover:bg-gray-100 transition flex items-center font-medium">
                    <i class="fas fa-copy mr-3 w-4 text-gray-600"></i>
                    <span id="copyButtonText">Copy Text</span>
                </button>
            </div>
        </div>
    </div>
    <!-- ================= END HEADER CONTROL ================= -->


    <!-- ================= SLIP RESMI ================= -->
    <div x-show="activeTab === 'resmi'" x-transition>

        <div id="printable-slip"
             class="bg-white p-10">

            <!-- Header Perusahaan -->
            <div class="mb-10">

                <div class="flex items-center justify-between">
                    <!-- Logo kiri -->
                    <div class="w-20">
                        <img src="{{ asset('images/LOGOPSF_v4fq0w.jpg') }}"
                             class="h-14 w-auto object-contain">
                    </div>

                    <!-- Text tengah -->
                    <div class="text-center flex-1">
                        <div class="font-bold text-base">
                            PT. PSF Pangestu Suryaning Family
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            SCBD Ruko, Jl. Sentrakota Timur No.B 16
                        </div>
                        <div class="text-xs text-gray-600">
                            Jatibening Baru, Kec. Pd. Gede, Kota Bekasi, Jawa Barat 17412
                        </div>
                    </div>

                    <!-- Spacer kanan -->
                    <div class="w-20"></div>
                </div>

                <div class="my-6"></div>
                <br>

                <div class="text-center">
                    <div class="font-bold text-lg">
                        SLIP GAJI KARYAWAN
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        Periode: {{ $data['periode_formatted'] }}
                    </div>
                </div>
            </div>

            <br>

            <div class="max-w-3xl mx-auto py-12 px-8 bg-white">

                <!-- ===================== -->
                <!-- INFORMASI KARYAWAN -->
                <!-- ===================== -->
                <div class="mb-14">

                    <div class="font-bold text-base text-center mb-8">
                        INFORMASI KARYAWAN
                    </div>

                    <table class="w-full text-sm text-left">
                        <tr>
                            <td class="py-2 w-40">Nama</td>
                            <td class="w-4">:</td>
                            <td>{{ $data['karyawan']->nama_karyawan ?? '-' }}</td>

                            <td class="w-40 pl-10">Lokasi Kerja</td>
                            <td class="w-4">:</td>
                            <td>{{ $data['karyawan']->lokasi_kerja ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="py-2">Jabatan</td>
                            <td>:</td>
                            <td>{{ $data['karyawan']->jabatan ?? '-' }}</td>

                            <td class="pl-10">No. Rekening</td>
                            <td>:</td>
                            <td>{{ $data['karyawan']->no_rekening ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="py-2">Jenis Karyawan</td>
                            <td>:</td>
                            <td>{{ $data['karyawan']->jenis_karyawan ?? '-' }}</td>

                            <td class="pl-10">Bank</td>
                            <td>:</td>
                            <td>{{ $data['karyawan']->bank ?? '-' }}</td>
                        </tr>
                    </table>

                </div>

                <br>

                <!-- ===================== -->
                <!-- PENDAPATAN & PENGELUARAN -->
                <!-- ===================== -->
                <div class="grid grid-cols-2 text-sm">

                    <!-- Pendapatan -->
                    <div class="text-left">
                        <div class="font-bold text-base text-left mb-6">
                            PENDAPATAN
                        </div>

                        @php $pendapatan_fields = [
                                'gaji_pokok' => 'Gaji Pokok',
                                'tunjangan_prestasi' => 'Tunjangan Prestasi',
                                'benefit_operasional' => 'Benefit Operasional',
                                'tunjangan_konjungtur' => 'Tunjangan Konjungtur',
                                'benefit_ibadah' => 'Benefit Ibadah',
                                'benefit_komunikasi' => 'Benefit Komunikasi',
                                'reward' => 'Reward'
                            ]; @endphp

                            <table class="w-full">
                                @foreach($pendapatan_fields as $field => $label)
                                    @if($data['hitung_gaji']->$field > 0)
                                    <tr>
                                        <td class="py-2">{{ $label }}</td>
                                        <td class="text-left">
                                            Rp {{ number_format($data['hitung_gaji']->$field,0,',','.') }}
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach

                                <tr class="font-bold">
                                    <td class="pt-4">Total Pendapatan</td>
                                    <td class="text-left pt-4">
                                        Rp {{ number_format($data['hitung_gaji']->total_pendapatan,0,',','.') }}
                                    </td>
                                </tr>
                            </table>
                    </div>

                    <!-- Pengeluaran -->
                    <div class="text-left">
                        <div class="font-bold text-base text-left mb-6">
                            PENGELUARAN
                        </div>
                        @php $pengeluaran_fields = [
                                'bpjs_kesehatan_pengeluaran' => 'BPJS Kesehatan',
                                'bpjs_jht_pengeluaran' => 'BPJS JHT',
                                'bpjs_jp_pengeluaran' => 'BPJS JP',
                                'koperasi' => 'Koperasi',
                                'kasbon' => 'Kasbon',
                                'potongan_absensi' => 'Potongan Absensi',
                                'potongan_kehadiran' => 'Potongan Kehadiran'
                            ]; @endphp

                            <table class="w-full">
                                @foreach($pengeluaran_fields as $field => $label)
                                    @if($data['hitung_gaji']->$field > 0)
                                    <tr>
                                        <td class="py-2">{{ $label }}</td>
                                        <td class="text-left">
                                            Rp {{ number_format($data['hitung_gaji']->$field,0,',','.') }}
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach

                                <tr class="font-bold">
                                    <td class="pt-4">Total Pengeluaran</td>
                                    <td class="text-left pt-4">
                                        Rp {{ number_format($data['hitung_gaji']->total_pengeluaran,0,',','.') }}
                                    </td>
                                </tr>
                            </table>
                    </div>

                </div>

            </div>


            <!-- Gaji Bersih -->
            <div class="bg-gray-50 p-5 mb-8">
                <div class="flex justify-between items-start gap-8">
                    <div class="text-xs text-gray-700 text-left flex-1">
                        <div class="font-bold text-sm mb-3 text-gray-900">CATATAN & KETERANGAN</div>
                        
                        @if($data['pengaturan_gaji'] && $data['pengaturan_gaji']->keterangan)
                        <div class="mb-2">
                            <span class="font-semibold">Pengaturan Gaji:</span>
                            <p class="text-gray-600 mt-0.5">{{ $data['pengaturan_gaji']->keterangan }}</p>
                        </div>
                        @endif
                        
                        @if($data['nki'] && $data['nki']->keterangan)
                        <div class="mb-2">
                            <span class="font-semibold">NKI:</span>
                            <p class="text-gray-600 mt-0.5">{{ $data['nki']->keterangan }}</p>
                        </div>
                        @endif
                        
                        @if($data['absensi'] && $data['absensi']->keterangan)
                        <div class="mb-2">
                            <span class="font-semibold">Absensi:</span>
                            <p class="text-gray-600 mt-0.5">{{ $data['absensi']->keterangan }}</p>
                        </div>
                        @endif
                        
                        @if($data['kasbon'])
                            @if($data['kasbon']->deskripsi)
                            <div class="mb-2">
                                <span class="font-semibold">Kasbon (Deskripsi):</span>
                                <p class="text-gray-600 mt-0.5">{{ $data['kasbon']->deskripsi }}</p>
                            </div>
                            @endif
                            @if($data['kasbon']->keterangan)
                            <div class="mb-2">
                                <span class="font-semibold">Kasbon (Keterangan):</span>
                                <p class="text-gray-600 mt-0.5">{{ $data['kasbon']->keterangan }}</p>
                            </div>
                            @endif
                        @endif
                        
                        @if($data['acuan_gaji'] && $data['acuan_gaji']->keterangan)
                        <div class="mb-2">
                            <span class="font-semibold">Acuan Gaji:</span>
                            <p class="text-gray-600 mt-0.5">{{ $data['acuan_gaji']->keterangan }}</p>
                        </div>
                        @endif
                        
                        @if($data['hitung_gaji']->keterangan)
                        <div class="mb-2">
                            <span class="font-semibold">Hitung Gaji:</span>
                            <p class="text-gray-600 mt-0.5">{{ $data['hitung_gaji']->keterangan }}</p>
                        </div>
                        @endif
                        
                        @if(!$data['pengaturan_gaji']?->keterangan && !$data['nki']?->keterangan && !$data['absensi']?->keterangan && !$data['kasbon']?->deskripsi && !$data['kasbon']?->keterangan && !$data['acuan_gaji']?->keterangan && !$data['hitung_gaji']->keterangan)
                        <p class="text-gray-500 italic">Tidak ada catatan atau keterangan</p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-sm text-gray-600 mb-1">GAJI BERSIH</div>
                        <div class="font-bold text-2xl text-gray-900">
                            Rp {{ number_format($data['hitung_gaji']->gaji_bersih,0,',','.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-xs text-gray-500 text-center pt-4">
                Slip gaji ini dicetak otomatis dan sah tanpa tanda tangan.
                <div class="mt-1">
                    Dicetak pada: {{ now()->format('d F Y H:i') }} WIB
                </div>
            </div>

        </div>
    </div>


<!-- ================= FORMAT TEXT ================= -->
<div x-show="activeTab === 'text'" x-transition>
    <div class="bg-black text-green-400 p-8 font-mono text-xs overflow-x-auto rounded-lg">
<pre id="text-slip" 
     class="whitespace-pre m-0 leading-relaxed font-mono text-sm" style="font-family: 'Courier New', monospace;">
============================================================

PT. PSF Pangestu Suryaning Family
SCBD Ruko, Jl. Sentrakota Timur No.B 16
Jatibening Baru, Kec. Pd. Gede, Kota Bekasi
Jawa Barat 17412

============================================================

SLIP GAJI KARYAWAN
Periode               : {{ $data['periode_formatted'] }}

============================================================
INFORMASI KARYAWAN
============================================================

Nama                   :{{ $data['karyawan']->nama_karyawan ?? '-' }}
Jabatan                :{{ $data['karyawan']->jabatan ?? '-' }}
Jenis Karyawan         :{{ $data['karyawan']->jenis_karyawan ?? '-' }}
Lokasi Kerja           :{{ $data['karyawan']->lokasi_kerja ?? '-' }}
No. Rekening           :{{ $data['karyawan']->no_rekening ?? '-' }}
Bank                   :{{ $data['karyawan']->bank ?? '-' }}

============================================================
RINCIAN GAJI
============================================================

PENDAPATAN
------------------------------------------------------------
Gaji Pokok            : Rp {{ number_format($data['hitung_gaji']->gaji_pokok,0,',','.') }}
Tunjangan Prestasi    : Rp {{ number_format($data['hitung_gaji']->tunjangan_prestasi,0,',','.') }}
Benefit Operasional   : Rp {{ number_format($data['hitung_gaji']->benefit_operasional,0,',','.') }}
Tunjangan Konjungtur  : Rp {{ number_format($data['hitung_gaji']->tunjangan_konjungtur,0,',','.') }}
Benefit Ibadah        : Rp {{ number_format($data['hitung_gaji']->benefit_ibadah,0,',','.') }}
Benefit Komunikasi    : Rp {{ number_format($data['hitung_gaji']->benefit_komunikasi,0,',','.') }}
Reward                : Rp {{ number_format($data['hitung_gaji']->reward,0,',','.') }}

TOTAL PENDAPATAN      : Rp {{ number_format($data['hitung_gaji']->total_pendapatan,0,',','.') }}

------------------------------------------------------------

PENGELUARAN
------------------------------------------------------------
BPJS Kesehatan        : Rp {{ number_format($data['hitung_gaji']->bpjs_kesehatan_pengeluaran,0,',','.') }}
BPJS JHT              : Rp {{ number_format($data['hitung_gaji']->bpjs_jht_pengeluaran,0,',','.') }}
BPJS JP               : Rp {{ number_format($data['hitung_gaji']->bpjs_jp_pengeluaran,0,',','.') }}
Koperasi              : Rp {{ number_format($data['hitung_gaji']->koperasi,0,',','.') }}
Kasbon                : Rp {{ number_format($data['hitung_gaji']->kasbon,0,',','.') }}
Potongan Absensi      : Rp {{ number_format($data['hitung_gaji']->potongan_absensi,0,',','.') }}
Potongan Kehadiran    : Rp {{ number_format($data['hitung_gaji']->potongan_kehadiran,0,',','.') }}

TOTAL PENGELUARAN     : Rp {{ number_format($data['hitung_gaji']->total_pengeluaran,0,',','.') }}

============================================================

GAJI BERSIH           : Rp {{ number_format($data['hitung_gaji']->gaji_bersih,0,',','.') }}

============================================================

Dicetak pada          : {{ now()->format('d F Y H:i') }} WIB
</pre>
    </div>
</div>
<!-- End Content Area -->

</div>
<!-- End Alpine.js wrapper -->



