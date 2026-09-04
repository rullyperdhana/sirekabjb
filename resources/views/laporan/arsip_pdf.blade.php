<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kelengkapan Arsip Dokumen SKPD - Tahun {{ $tahunAktif }}</title>
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
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 15px; padding-bottom: 5px; }
        .kop-logo { width: 80px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 70px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 80px; } 
        .kop-text h2 { margin: 0; font-size: 16px; font-weight: bold; letter-spacing: 1px; }
        .kop-text h1 { margin: 0; font-size: 18px; font-weight: 900; letter-spacing: 1px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 11px; }
        
        /* Judul */
        .judul-dokumen h2 { margin: 0; font-size: 14px; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        .judul-dokumen h3 { margin: 5px 0 15px 0; font-size: 12px; font-weight: normal; }

        /* Tabel Rekap */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
            border: 1px solid #000;
        }
        table.keuangan th, table.keuangan td {
            padding: 4px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        table.keuangan th {
            background-color: #f3f4f6;
        }
        table.keuangan th:last-child, table.keuangan td:last-child {
            border-right: none;
        }
        .status-danger { color: #ba1a1a; }
        .status-success { color: #1b6d24; }

        /* Tanda Tangan */
        .ttd-table { width: 100%; margin-top: 30px; font-size: 12px; page-break-inside: avoid; border: none; }
        .ttd-table td { border: none; padding: 1px; }
        .ttd-cell { width: 50%; text-align: center; vertical-align: top; }
        .ttd-space { height: 60px; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-bottom: 2px; }
        .ttd-nip { margin-top: 0; }
    </style>
</head>
<body>

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KOTA BANJARBARU|BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH|Jl. Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan 70711|Telp. (0511) 4772545');
        $logoSrc = $pengaturan->logo ?? null;
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
        
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
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
        <h2>Laporan Kelengkapan Arsip Dokumen SKPD</h2>
        <h3>Tahun Anggaran {{ $tahunAktif }}</h3>
    </div>

    <table class="keuangan">
        <thead>
            <tr>
                <th class="text-center" style="width: 3%">No</th>
                <th class="text-center" style="width: 8%">Kode SKPD</th>
                <th class="text-center" style="width: 23%">Nama SKPD</th>
                @foreach($namaBulan as $bulan)
                    <th class="text-center" style="width: 5.5%">{{ substr($bulan, 0, 3) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($skpdData as $data)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $data['skpd']->kode }}</td>
                <td>{{ $data['skpd']->nama }}</td>
                @for($i = 1; $i <= 12; $i++)
                    @php
                        $statusParts = explode('|', $data['bulan_status'][$i]);
                        $rekonStatus = $statusParts[0] ?? '-';
                        $docStatus = $statusParts[1] ?? '-';

                        $class = '';
                        $textRekon = '';
                        $textDoc = '';
                        
                        if ($docStatus == 'Lengkap') { 
                            $class = 'status-success font-bold'; 
                            $textDoc = 'V'; 
                        }
                        if ($docStatus == 'Kurang') { 
                            $class = 'status-danger font-bold'; 
                            $textDoc = 'X'; 
                        }
                        
                        if ($rekonStatus == 'Verified') $textRekon = 'Verif';
                        if ($rekonStatus == 'Draft') $textRekon = 'Draft';
                    @endphp
                    <td class="text-center {{ $class }}">
                        @if($rekonStatus != '-')
                            {{ $textRekon }}<br>({{ $textDoc }})
                        @else
                            -
                        @endif
                    </td>
                @endfor
            </tr>
            @endforeach
            @if(empty($skpdData))
            <tr>
                <td colspan="15" class="text-center" style="padding: 10px; color: #666;">Belum ada data SKPD.</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div style="margin-top: 10px; font-size: 10px;">
        <strong>Keterangan (Status Rekon):</strong><br>
        <strong>Verif</strong> : Transaksi sudah Diverifikasi &nbsp;&nbsp;&nbsp;
        <strong>Draft</strong> : Transaksi masih Draft<br><br>
        <strong>Keterangan (Kelengkapan Dokumen):</strong><br>
        <span class="status-success font-bold">V</span> : Lengkap (Sudah diunggah semua) &nbsp;&nbsp;&nbsp;
        <span class="status-danger font-bold">X</span> : Kurang (Ada dokumen yang belum diunggah) &nbsp;&nbsp;&nbsp;
        <span>-</span> : Belum ada laporan/transaksi
    </div>

    <table class="ttd-table">
        <tr>
            <td class="ttd-cell">
            </td>
            <td class="ttd-cell">
                Banjarbaru, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                <strong>Admin SiReKa</strong><br>
                <div class="ttd-space"></div>
                <div class="ttd-name">{{ Auth::user()->name }}</div>
                <div class="ttd-nip">Role: {{ ucfirst(Auth::user()->role) }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
