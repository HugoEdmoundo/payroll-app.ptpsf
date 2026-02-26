<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $data['karyawan']->nama_karyawan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            padding: 15px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .company-address {
            font-size: 9px;
            color: #666;
        }
        .title {
            font-size: 13px;
            font-weight: bold;
            margin: 10px 0 3px 0;
        }
        .periode {
            font-size: 10px;
            color: #666;
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin: 12px 0 8px 0;
            padding: 5px;
            background-color: #f0f0f0;
            border-left: 3px solid #333;
        }
        table {
            width: 100%;
            margin-bottom: 10px;
        }
        table td {
            padding: 3px 5px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 120px;
            font-weight: 500;
        }
        .info-table td:nth-child(2) {
            width: 5px;
        }
        .two-column {
            width: 100%;
            margin-bottom: 15px;
        }
        .two-column td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        .amount-table {
            width: 100%;
            border-collapse: collapse;
        }
        .amount-table td {
            padding: 4px 5px;
            border-bottom: 1px solid #eee;
        }
        .amount-table td:first-child {
            width: 60%;
        }
        .amount-table td:last-child {
            text-align: right;
            width: 40%;
        }
        .total-row td {
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            padding-top: 6px !important;
            padding-bottom: 6px !important;
            background-color: #f9f9f9;
        }
        .gaji-bersih-section {
            margin: 15px 0;
            padding: 12px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .gaji-bersih-section table {
            width: 100%;
        }
        .gaji-bersih-section td {
            vertical-align: top;
            padding: 5px;
        }
        .gaji-bersih-section .left-col {
            width: 65%;
            border-right: 1px solid #ddd;
            padding-right: 15px;
        }
        .gaji-bersih-section .right-col {
            width: 35%;
            text-align: right;
            padding-left: 15px;
        }
        .catatan-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
        }
        .catatan-item {
            margin-bottom: 6px;
            font-size: 9px;
        }
        .catatan-label {
            font-weight: bold;
            color: #000;
        }
        .catatan-text {
            color: #555;
            margin-top: 2px;
        }
        .gaji-bersih-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        .gaji-bersih-amount {
            font-size: 20px;
            font-weight: bold;
            color: #000;
        }
        .footer {
            text-align: center;
            font-size: 8px;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>

    
    <!-- Header -->
    <div class="header">
        <table style="width: 100%; margin-bottom: 10px;">
            <tr>
                <td style="width: 80px; vertical-align: middle;">
                    <img src="{{ public_path('images/LOGOPSF_v4fq0w.jpg') }}" style="width: 60px; height: auto;">
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <div class="company-name">PT. PSF Pangestu Suryaning Family</div>
                    <div class="company-address">
                        SCBD Ruko, Jl. Sentrakota Timur No.B 16, Jatibening Baru, Kec. Pd. Gede, Kota Bekasi, Jawa Barat 17412
                    </div>
                </td>
                <td style="width: 80px;"></td>
            </tr>
        </table>
    </div>

    <div class="title" style="text-align: center;">SLIP GAJI KARYAWAN</div>
    <div class="periode" style="text-align: center;">Periode: {{ $data['periode_formatted'] }}</div>

    <!-- Informasi Karyawan -->
    <div class="section-title">INFORMASI KARYAWAN</div>
    <table class="info-table">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $data['karyawan']->nama_karyawan ?? '-' }}</td>
            <td style="width: 120px; padding-left: 20px; font-weight: 500;">Lokasi Kerja</td>
            <td style="width: 5px;">:</td>
            <td>{{ $data['karyawan']->lokasi_kerja ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $data['karyawan']->jabatan ?? '-' }}</td>
            <td style="padding-left: 20px; font-weight: 500;">No. Rekening</td>
            <td>:</td>
            <td>{{ $data['karyawan']->no_rekening ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Karyawan</td>
            <td>:</td>
            <td>{{ $data['karyawan']->jenis_karyawan ?? '-' }}</td>
            <td style="padding-left: 20px; font-weight: 500;">Bank</td>
            <td>:</td>
            <td>{{ $data['karyawan']->bank ?? '-' }}</td>
        </tr>
    </table>

    <!-- Pendapatan & Pengeluaran -->
    <table class="two-column">
        <tr>
            <!-- Pendapatan (TANPA BPJS) -->
            <td>
                <div class="section-title">PENDAPATAN</div>
                <table class="amount-table">
                    @php 
                    $pendapatan_fields = [
                        'gaji_pokok' => 'Gaji Pokok',
                        'tunjangan_prestasi' => 'Tunjangan Prestasi',
                        'tunjangan_konjungtur' => 'Tunjangan Konjungtur',
                        'benefit_operasional' => 'Benefit Operasional',
                        'benefit_ibadah' => 'Benefit Ibadah',
                        'benefit_komunikasi' => 'Benefit Komunikasi',
                        'reward' => 'Reward'
                    ];
                    
                    $total_pendapatan_display = 0;
                    @endphp
                    
                    @foreach($pendapatan_fields as $field => $label)
                        @php $finalValue = $data['hitung_gaji']->getFinalValue($field); @endphp
                        @if($finalValue > 0)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>Rp {{ number_format($finalValue,0,',','.') }}</td>
                        </tr>
                        @php $total_pendapatan_display += $finalValue; @endphp
                        @endif
                    @endforeach
                    
                    <tr class="total-row">
                        <td>TOTAL PENDAPATAN</td>
                        <td>Rp {{ number_format($total_pendapatan_display,0,',','.') }}</td>
                    </tr>
                </table>
            </td>

            <!-- Pengeluaran (TANPA BPJS) -->
            <td>
                <div class="section-title">PENGELUARAN</div>
                <table class="amount-table">
                    @php 
                    $pengeluaran_fields = [
                        'koperasi' => 'Koperasi',
                        'kasbon' => 'Kasbon',
                        'umroh' => 'Umroh',
                        'kurban' => 'Kurban',
                        'mutabaah' => 'Mutabaah',
                        'potongan_absensi' => 'Potongan Absensi',
                        'potongan_kehadiran' => 'Potongan Kehadiran'
                    ];
                    
                    $total_pengeluaran_display = 0;
                    @endphp
                    
                    @foreach($pengeluaran_fields as $field => $label)
                        @php $finalValue = $data['hitung_gaji']->getFinalValue($field); @endphp
                        @if($finalValue > 0)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>Rp {{ number_format($finalValue,0,',','.') }}</td>
                        </tr>
                        @php $total_pengeluaran_display += $finalValue; @endphp
                        @endif
                    @endforeach
                    
                    <tr class="total-row">
                        <td>TOTAL PENGELUARAN</td>
                        <td>Rp {{ number_format($total_pengeluaran_display,0,',','.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Gaji Bersih & Catatan -->
    <div class="gaji-bersih-section">
        <table>
            <tr>
                <td class="left-col">
                    <div class="catatan-title">RINCIAN LENGKAP (TOTAL AKHIR)</div>
                    
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px; color: #000;">PENDAPATAN:</div>
                        @php 
                        $all_pendapatan = [
                            'gaji_pokok' => 'Gaji Pokok',
                            'bpjs_kesehatan_pendapatan' => 'BPJS Kesehatan (P)',
                            'bpjs_kecelakaan_kerja_pendapatan' => 'BPJS Kecelakaan Kerja (P)',
                            'bpjs_kematian_pendapatan' => 'BPJS Kematian (P)',
                            'bpjs_jht_pendapatan' => 'BPJS JHT (P)',
                            'bpjs_jp_pendapatan' => 'BPJS JP (P)',
                            'tunjangan_prestasi' => 'Tunjangan Prestasi',
                            'tunjangan_konjungtur' => 'Tunjangan Konjungtur',
                            'benefit_ibadah' => 'Benefit Ibadah',
                            'benefit_komunikasi' => 'Benefit Komunikasi',
                            'benefit_operasional' => 'Benefit Operasional',
                            'reward' => 'Reward'
                        ];
                        @endphp
                        @foreach($all_pendapatan as $field => $label)
                            @php $finalValue = $data['hitung_gaji']->getFinalValue($field); @endphp
                            @if($finalValue > 0)
                            <div style="font-size: 8px; padding: 2px 0; display: flex; justify-content: space-between;">
                                <span>{{ $label }}</span>
                                <span>Rp {{ number_format($finalValue,0,',','.') }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px; color: #000;">PENGELUARAN:</div>
                        @php 
                        $all_pengeluaran = [
                            'bpjs_kesehatan_pengeluaran' => 'BPJS Kesehatan (D)',
                            'bpjs_kecelakaan_kerja_pengeluaran' => 'BPJS Kecelakaan Kerja (D)',
                            'bpjs_kematian_pengeluaran' => 'BPJS Kematian (D)',
                            'bpjs_jht_pengeluaran' => 'BPJS JHT (D)',
                            'bpjs_jp_pengeluaran' => 'BPJS JP (D)',
                            'koperasi' => 'Koperasi',
                            'kasbon' => 'Kasbon',
                            'umroh' => 'Umroh',
                            'kurban' => 'Kurban',
                            'mutabaah' => 'Mutabaah',
                            'potongan_absensi' => 'Potongan Absensi',
                            'potongan_kehadiran' => 'Potongan Kehadiran'
                        ];
                        @endphp
                        @foreach($all_pengeluaran as $field => $label)
                            @php $finalValue = $data['hitung_gaji']->getFinalValue($field); @endphp
                            @if($finalValue > 0)
                            <div style="font-size: 8px; padding: 2px 0; display: flex; justify-content: space-between;">
                                <span>{{ $label }}</span>
                                <span>Rp {{ number_format($finalValue,0,',','.') }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <div style="border-top: 1px solid #ddd; padding-top: 8px; margin-top: 8px;">
                        <div style="font-size: 8px; padding: 2px 0; display: flex; justify-content: space-between; font-weight: bold;">
                            <span>TOTAL PENDAPATAN</span>
                            <span>Rp {{ number_format($data['hitung_gaji']->total_pendapatan,0,',','.') }}</span>
                        </div>
                        <div style="font-size: 8px; padding: 2px 0; display: flex; justify-content: space-between; font-weight: bold;">
                            <span>TOTAL PENGELUARAN</span>
                            <span>Rp {{ number_format($data['hitung_gaji']->total_pengeluaran,0,',','.') }}</span>
                        </div>
                    </div>
                </td>
                <td class="right-col">
                    <div class="gaji-bersih-label">GAJI BERSIH</div>
                    <div class="gaji-bersih-amount">Rp {{ number_format($data['hitung_gaji']->gaji_bersih,0,',','.') }}</div>
                    
                    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                        <div class="catatan-title" style="font-size: 9px;">CATATAN</div>
                        
                        @php $hasKeterangan = false; @endphp
                        
                        @if($data['pengaturan_gaji'] && $data['pengaturan_gaji']->keterangan)
                            @php $hasKeterangan = true; @endphp
                            <div class="catatan-item" style="font-size: 8px;">
                                <div class="catatan-label">Pengaturan Gaji:</div>
                                <div class="catatan-text">{{ $data['pengaturan_gaji']->keterangan }}</div>
                            </div>
                        @endif
                        
                        @if($data['kasbon'])
                            @php 
                            $statusInfo = $data['kasbon']->getPaymentStatusInfo();
                            $kasbonAmountInSlip = $data['hitung_gaji']->getFinalValue('kasbon');
                            $showKasbon = $data['kasbon']->deskripsi || $data['kasbon']->keterangan || $data['kasbon']->total_paid != 0;
                            @endphp
                            @if($showKasbon)
                                @php $hasKeterangan = true; @endphp
                                <div class="catatan-item" style="font-size: 8px;">
                                    <div class="catatan-label">Kasbon:</div>
                                    <div class="catatan-text">
                                        @if($data['kasbon']->total_paid != 0 || $kasbonAmountInSlip > 0)
                                        Dibayar bulan ini: Rp {{ number_format($kasbonAmountInSlip,0,',','.') }}<br>
                                        Status: {{ $statusInfo['message'] }}<br>
                                        Total Dibayar: Rp {{ number_format($data['kasbon']->total_paid,0,',','.') }} / Rp {{ number_format($data['kasbon']->nominal,0,',','.') }}
                                        @endif
                                        @if($data['kasbon']->deskripsi)
                                        <br>Deskripsi: {{ $data['kasbon']->deskripsi }}
                                        @endif
                                        @if($data['kasbon']->keterangan)
                                        <br>Keterangan: {{ $data['kasbon']->keterangan }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        @if($data['nki'] && $data['nki']->keterangan)
                            @php $hasKeterangan = true; @endphp
                            <div class="catatan-item" style="font-size: 8px;">
                                <div class="catatan-label">NKI:</div>
                                <div class="catatan-text">{{ $data['nki']->keterangan }}</div>
                            </div>
                        @endif
                        
                        @if($data['absensi'] && $data['absensi']->keterangan)
                            @php $hasKeterangan = true; @endphp
                            <div class="catatan-item" style="font-size: 8px;">
                                <div class="catatan-label">Absensi:</div>
                                <div class="catatan-text">{{ $data['absensi']->keterangan }}</div>
                            </div>
                        @endif
                        
                        @if($data['acuan_gaji'] && $data['acuan_gaji']->keterangan)
                            @php $hasKeterangan = true; @endphp
                            <div class="catatan-item" style="font-size: 8px;">
                                <div class="catatan-label">Acuan Gaji:</div>
                                <div class="catatan-text">{{ $data['acuan_gaji']->keterangan }}</div>
                            </div>
                        @endif
                        
                        @if($data['hitung_gaji']->keterangan)
                            @php $hasKeterangan = true; @endphp
                            <div class="catatan-item" style="font-size: 8px;">
                                <div class="catatan-label">Hitung Gaji:</div>
                                <div class="catatan-text">{{ $data['hitung_gaji']->keterangan }}</div>
                            </div>
                        @endif
                        
                        @if(!$hasKeterangan)
                            <div style="color: #999; font-style: italic; font-size: 8px;">-</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Slip gaji ini dicetak otomatis dan sah tanpa tanda tangan.<br>
        Dicetak pada: {{ now()->format('d F Y H:i') }} WIB
    </div>
</body>
</html>
