<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Rekonsiliasi Tahunan - {{ $skpd->nama }}</title>
    <style>
        @page { margin: 15mm 20mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
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
            font-size: 12px;
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

        /* Tanda Tangan */
        .ttd-table { width: 100%; margin-top: 30px; font-size: 12px; page-break-inside: avoid; border: none; }
        .ttd-table td { border: none; padding: 1px; }
        .ttd-cell { width: 50%; text-align: center; vertical-align: top; }
        .ttd-space { height: 60px; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-bottom: 2px; }
        .ttd-nip { margin-top: 0; }
        
        /* Helper Table for Currency */
        .curr-table { width: 100%; border: none !important; border-collapse: collapse; margin: 0; padding: 0; }
        .curr-table td { border: none !important; padding: 0 !important; background: transparent; }
        .curr-symbol { text-align: left; width: 25px; }
        .curr-val { text-align: right; }
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
        <h2>REKAPITULASI REKONSILIASI KAS</h2>
        <h3>Tahun Anggaran {{ $tahunAktif }}</h3>
    </div>

    <table style="width:100%; margin-bottom:10px; font-size:12px; border:none;">
        <tr>
            <td style="width:20%; font-weight:bold; border:none;">SKPD</td>
            <td style="width:2%; border:none;">:</td>
            <td style="width:78%; border:none;">{{ $skpd->kode }} - {{ $skpd->nama }}</td>
        </tr>
    </table>

    <table class="keuangan">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th class="text-center" style="width: 15%">Bulan</th>
                <th class="text-center" style="width: 25%">Saldo BKU</th>
                <th class="text-center" style="width: 25%">Saldo Bank</th>
                <th class="text-center" style="width: 30%">Selisih / Discrepancy</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $data)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $namaBulan[$data['bulan'] - 1] }}</td>
                @if(isset($data['bku']))
                    <td>
                        <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($data['bku'], 2, ',', '.') }}</td></tr></table>
                    </td>
                    <td>
                        <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($data['bank'], 2, ',', '.') }}</td></tr></table>
                    </td>
                    <td>
                        <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($data['selisih'], 2, ',', '.') }}</td></tr></table>
                    </td>
                @else
                    <td colspan="3" class="text-center italic" style="color: #666;">Belum ada laporan rekonsiliasi</td>
                @endif
            </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-center font-bold">TOTAL SALDO AKHIR</td>
                <td class="font-bold">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($totalBku, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="font-bold">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($totalBank, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="font-bold">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format(abs($totalBku - $totalBank), 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="ttd-table">
        <tr>
            <td class="ttd-cell">
            </td>
            <td class="ttd-cell">
                Banjarbaru, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                <strong>Pejabat Penatausahaan Keuangan (PPK) SKPD</strong><br>
                <div class="ttd-space"></div>
                <div class="ttd-name">...................................................</div>
                <div class="ttd-nip">NIP. ...............................................</div>
            </td>
        </tr>
    </table>

</body>
</html>
