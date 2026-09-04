<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Verifikasi Konsolidator Kas Daerah - {{ $tahunAktif }}</title>
    <style>
        @page { margin: 12mm 15mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 2.5px solid #000; margin-bottom: 12px; padding-bottom: 4px; }
        .kop-logo { width: 65px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 55px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 65px; } 
        .kop-text h2 { margin: 0; font-size: 13px; font-weight: bold; letter-spacing: 0.5px; }
        .kop-text h1 { margin: 0; font-size: 15px; font-weight: 900; letter-spacing: 0.5px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 9.5px; }
        
        /* Judul */
        .judul-dokumen h2 { margin: 0; font-size: 14px; font-weight: bold; text-decoration: underline; }
        .judul-dokumen h3 { margin: 3px 0 10px 0; font-size: 11px; font-weight: normal; }

        /* Tabel Rekap */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9.5px;
            border: 1px solid #000;
        }
        table.keuangan th, table.keuangan td {
            padding: 4px 6px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        table.keuangan th {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        table.keuangan th:last-child, table.keuangan td:last-child {
            border-right: none;
        }
        .text-error { color: #dc2626; }
        .text-success { color: #16a34a; }

        /* Currency helper */
        .curr-table { width: 100%; border: none !important; border-collapse: collapse; margin: 0; padding: 0; }
        .curr-table td { border: none !important; padding: 0 !important; background: transparent; }
        .curr-symbol { text-align: left; width: 18px; }
        .curr-val { text-align: right; }

        /* Tanda Tangan */
        .ttd-table { width: 100%; margin-top: 15px; font-size: 10px; page-break-inside: avoid; border: none; }
        .ttd-table td { border: none; padding: 2px; }
        .ttd-cell { width: 45%; text-align: center; vertical-align: top; }
        .ttd-space { height: 45px; }
        .ttd-name { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KOTA BANJARBARU|BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH|Jl. Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan 70711|Telp. (0511) 4772545');
        $logoSrc = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
        $base64Logo = null;
        if ($logoSrc) {
            $path = public_path('storage/' . $logoSrc);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        if (!$base64Logo && file_exists(public_path('images/logo_banjarbaru.png'))) {
            $data = file_get_contents(public_path('images/logo_banjarbaru.png'));
            $base64Logo = 'data:image/png;base64,' . base64_encode($data);
        }
    @endphp

    <!-- KOP Surat -->
    <table class="kop-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="kop-logo">
                @if($base64Logo)
                    <img src="{{ $base64Logo }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                @if(count($lines) >= 2)
                    <h2>{{ $lines[0] }}</h2>
                    <h1>{{ $lines[1] }}</h1>
                    @for($i = 2; $i < count($lines); $i++)
                        <p>{{ $lines[$i] }}</p>
                    @endfor
                @else
                    <h2>PEMERINTAH KOTA BANJARBARU</h2>
                    <h1>BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH</h1>
                    <p>Jl. Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan 70711</p>
                @endif
            </td>
        </tr>
    </table>

    <!-- Judul Dokumen -->
    <div class="judul-dokumen text-center">
        <h2>REGISTER HASIL PEMERIKSAAN & VERIFIKASI KONSOLIDATOR KAS DAERAH</h2>
        <h3>
            Tahun Anggaran {{ $tahunAktif }} &bull; 
            Periode: <strong>{{ $selectedBulan ? $namaBulan[$selectedBulan - 1] : 'Semua Bulan' }}</strong> &bull; 
            Status: <strong>{{ $selectedStatus ? strtoupper(str_replace('_', ' ', $selectedStatus)) : 'Semua Status' }}</strong>
        </h3>
    </div>

    <!-- Tabel Data Register -->
    <table class="keuangan" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 55px;" class="text-center">Periode</th>
                <th>Instansi (SKPD)</th>
                <th>Nomor Rekening &amp; Bank</th>
                <th style="width: 95px;" class="text-right">Saldo BKU (Rp)</th>
                <th style="width: 95px;" class="text-right">Saldo Bank (Rp)</th>
                <th style="width: 75px;" class="text-right">Selisih (Rp)</th>
                <th style="width: 60px;" class="text-center">Bukti</th>
                <th style="width: 70px;" class="text-center">Status</th>
                <th style="width: 80px;" class="text-center">Diperiksa (WITA)</th>
                <th style="width: 90px;">Konsolidator</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $trx)
                @php
                    $selisih = abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir);
                    $docsCount = 0;
                    if (!empty($trx->file_ba_manual) && \App\Services\SiReKaStorage::exists($trx->file_ba_manual)) $docsCount++;
                    if (!empty($trx->file_buku_kas) && \App\Services\SiReKaStorage::exists($trx->file_buku_kas)) $docsCount++;
                    if (!empty($trx->file_buku_pembantu_bank) && \App\Services\SiReKaStorage::exists($trx->file_buku_pembantu_bank)) $docsCount++;
                    if (!empty($trx->file_rekening_koran) && \App\Services\SiReKaStorage::exists($trx->file_rekening_koran)) $docsCount++;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $namaBulan[$trx->periode_bulan - 1] }}</td>
                    <td class="font-bold">{{ $trx->skpd->nama ?? '-' }}</td>
                    <td>{{ $trx->rekening->nomor ?? '-' }} <br><span style="font-size: 8.5px; color: #555;">{{ $trx->rekening->bank ?? '-' }}</span></td>
                    <td>
                        <table class="curr-table">
                            <tr>
                                <td class="curr-symbol">Rp</td>
                                <td class="curr-val">{{ number_format($trx->bku_saldo_akhir, 2, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="curr-table">
                            <tr>
                                <td class="curr-symbol">Rp</td>
                                <td class="curr-val">{{ number_format($trx->bank_saldo_akhir, 2, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="{{ $selisih > 0 ? 'text-error font-bold' : 'text-success' }}">
                        <table class="curr-table">
                            <tr>
                                <td class="curr-symbol">Rp</td>
                                <td class="curr-val">{{ number_format($selisih, 2, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="text-center font-bold">{{ $docsCount }}/4</td>
                    <td class="text-center font-bold">
                        @if($trx->status_konsolidator === 'valid')
                            <span class="text-success">VALID</span>
                        @elseif($trx->status_konsolidator === 'perlu_perbaikan')
                            <span class="text-error">PERBAIKAN</span>
                        @else
                            <span style="color: #64748b;">MENUNGGU</span>
                        @endif
                    </td>
                    <td class="text-center" style="font-size: 8.5px;">
                        {{ $trx->checked_at ? \Carbon\Carbon::parse($trx->checked_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : '-' }}
                    </td>
                    <td style="font-size: 9px;">{{ $trx->checker->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 12px;">Tidak ada data rekonsiliasi yang sesuai kriteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan BKAD -->
    <table class="ttd-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 55%;"></td>
            <td class="ttd-cell">
                Banjarbaru, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                <strong>Mengetahui / Mengesahkan,</strong><br>
                {{ $pengaturan->jabatan_kepala ?? 'Kepala Badan Pengelolaan Keuangan dan Aset Daerah' }}
                <div class="ttd-space"></div>
                <div class="ttd-name uppercase">{{ $pengaturan->nama_kepala ?? '.........................' }}</div>
                <div>{{ $pengaturan->pangkat_kepala ?? '.........................' }}</div>
                <div>NIP. {{ $pengaturan->nip_kepala ?? '.........................' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
