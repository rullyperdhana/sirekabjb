<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Selisih Transaksi {{ $tahunAktif }}</title>
    <style>
        @page { margin: 15mm 20mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 15px; padding-bottom: 5px; }
        .kop-logo { width: 80px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 70px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 80px; } 
        .kop-text h2 { margin: 0; font-size: 16px; font-weight: bold; letter-spacing: 1px; }
        .kop-text h1 { margin: 0; font-size: 18px; font-weight: 900; letter-spacing: 1px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 11px; }
        
        /* Judul */
        .judul-dokumen h2 { margin: 0; font-size: 16px; font-weight: bold; text-decoration: underline; }
        .judul-dokumen h3 { margin: 5px 0 15px 0; font-size: 14px; font-weight: normal; }

        /* Tabel Rekap */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
            border: 1px solid #000;
        }
        table.keuangan th, table.keuangan td {
            padding: 5px 8px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        table.keuangan th {
            background-color: #f3f4f6;
        }
        table.keuangan th:last-child, table.keuangan td:last-child {
            border-right: none;
        }
        .text-error { color: #d32f2f; }

        /* Helper Table for Currency */
        .curr-table { width: 100%; border: none !important; border-collapse: collapse; margin: 0; padding: 0; }
        .curr-table td { border: none !important; padding: 0 !important; background: transparent; }
        .curr-symbol { text-align: left; width: 25px; }
        .curr-val { text-align: right; }
        
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .bg-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .bg-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KOTA BANJARBARU|BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH|Jl. Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan 70711|Telp. (0511) 4772545');
        $logoSrc = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
        $base64Logo = null;
        
        if ($logoSrc) {
            $path = storage_path('app/public/' . $logoSrc);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        
        if (!$base64Logo) {
            $path = public_path('images/logo_banjarbaru.png');
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
    @endphp

    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($base64Logo)
                    <img src="{{ $base64Logo }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <h2>{{ $lines[0] ?? '' }}</h2>
                <h1>{{ $lines[1] ?? '' }}</h1>
                <p>{{ $lines[2] ?? '' }}</p>
                <p>{{ $lines[3] ?? '' }}</p>
            </td>
        </tr>
    </table>

    <div class="text-center judul-dokumen">
        <h2>LAPORAN RINGKASAN SELISIH TRANSAKSI</h2>
        <h3>Tahun Anggaran {{ $tahunAktif }}</h3>
    </div>

    @if($skpd || $request->filled('bulan'))
    <table style="width:100%; margin-bottom:10px; font-size:12px; border:none;">
        @if($skpd)
        <tr>
            <td style="width:15%; font-weight:bold; border:none;">SKPD Filter</td>
            <td style="width:2%; border:none;">:</td>
            <td style="border:none;">{{ $skpd->nama }}</td>
        </tr>
        @endif
        @if($request->filled('bulan'))
        <tr>
            <td style="width:15%; font-weight:bold; border:none;">Bulan Filter</td>
            <td style="width:2%; border:none;">:</td>
            <td style="border:none;">Bulan {{ $request->bulan }}</td>
        </tr>
        @endif
    </table>
    @endif

    <table class="keuangan">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 20%;">Instansi (SKPD)</th>
                <th style="width: 8%;">Bulan</th>
                <th style="width: 15%;">Saldo BKU</th>
                <th style="width: 15%;">Saldo Bank</th>
                <th style="width: 15%;">Nilai Selisih</th>
                <th style="width: 15%;">Keterangan</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $trx->skpd->nama ?? '-' }}</td>
                <td class="text-center">{{ $trx->periode_bulan }}</td>
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
                <td class="font-bold text-error">
                    <table class="curr-table">
                        <tr>
                            <td class="curr-symbol">Rp</td>
                            <td class="curr-val">{{ number_format(abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir), 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
                <td>{{ $trx->keterangan_selisih ?: '-' }}</td>
                <td class="text-center">
                    @if($trx->status_verifikasi == 'draft')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-success">Resolved</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 15px;">Tidak ada data transaksi yang memiliki selisih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
