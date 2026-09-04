<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Rekonsiliasi - {{ $transaksi->skpd->nama }}</title>
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
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .italic { font-style: italic; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 12px; }
        .indent { text-indent: 40px; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 10px; padding-bottom: 5px; }
        .kop-logo { width: 80px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 70px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 80px; } 
        .kop-text h2 { margin: 0; font-size: 16px; font-weight: bold; letter-spacing: 1px; }
        .kop-text h1 { margin: 0; font-size: 18px; font-weight: 900; letter-spacing: 1px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 11px; }
        
        /* Judul */
        .judul-dokumen h2 { margin: 0; font-size: 16px; font-weight: bold; text-decoration: underline; }
        .judul-dokumen h3 { margin: 5px 0 0 0; font-size: 14px; font-weight: bold; }

        /* Tabel Keuangan */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 12px;
            border: 1px solid #000;
        }
        table.keuangan th, table.keuangan td {
            padding: 3px 5px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        table.keuangan th:last-child, table.keuangan td:last-child {
            border-right: none;
        }
        .pl-4 { padding-left: 20px; }

        /* Tanda Tangan */
        .ttd-table { width: 100%; margin-top: 10px; font-size: 12px; page-break-inside: avoid; border: none; }
        .ttd-table td { border: none; padding: 1px; }
        .ttd-cell { width: 50%; text-align: center; vertical-align: top; }
        .ttd-space { height: 50px; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-bottom: 2px; }
        .ttd-nip { margin-top: 0; }
        
        /* Helper Table for Currency */
        .curr-table { width: 100%; border: none !important; border-collapse: collapse; margin: 0; padding: 0; }
        .curr-table td { border: none !important; padding: 0 !important; background: transparent; }
        .curr-symbol { text-align: left; width: 25px; }
        .curr-val { text-align: right; }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 35%;
            left: 10%;
            font-size: 140px;
            color: rgba(255, 0, 0, 0.15);
            font-weight: bold;
            transform: rotate(-45deg);
            z-index: -1000;
            user-select: none;
            pointer-events: none;
        }
    </style>
</head>
<body>

    @if($transaksi->tahap_verifikasi !== 'disetujui_final')
        <div class="watermark">DRAFT</div>
    @endif

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KOTA BANJARBARU|BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH|Jl. Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan 70711|Telp. (0511) 4772545');
        
        $logoSrc = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
        $base64Logo = null;
        
        if($logoSrc && str_starts_with($logoSrc, 'logos/')) {
            $path = storage_path('app/public/' . $logoSrc);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        } elseif ($logoSrc && filter_var($logoSrc, FILTER_VALIDATE_URL)) {
            // If it's a URL (like from old config)
            try {
                $data = @file_get_contents($logoSrc);
                if ($data) {
                    $type = 'png';
                    $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {}
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
                @foreach($lines as $index => $line)
                    @if($index === 0)
                        <h2 class="uppercase">{{ $line }}</h2>
                    @elseif($index === 1)
                        <h1 class="uppercase">{{ $line }}</h1>
                    @elseif($index === 2)
                        <p style="margin-top:5px;">{{ $line }}</p>
                    @else
                        <p>{{ $line }}</p>
                    @endif
                @endforeach
            </td>
        </tr>
    </table>

    <!-- Judul -->
    <div class="text-center judul-dokumen mb-4">
        <h2 class="uppercase">BERITA ACARA REKONSILIASI</h2>
        @if($transaksi->nomor_ba)
            <div style="font-size: 12px; font-weight: bold; margin-top: 3px;">Nomor : {{ $transaksi->nomor_ba }}</div>
        @else
            <div style="font-size: 10.5px; font-style: italic; color: #666; margin-top: 3px;">Nomor : DRAFT (Menunggu Pengesahan Inspektorat)</div>
        @endif
        <h3 class="uppercase" style="margin-top: 4px;">Bulan : {{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }}</h3>
    </div>

    <!-- Intro Text -->
    @php
        $tglSumber = $transaksi->tanggal_ba ? \Carbon\Carbon::parse($transaksi->tanggal_ba) : \Carbon\Carbon::parse($transaksi->updated_at);
        $tanggal = $tglSumber->locale('id')->isoFormat('dddd');
        $tglNum = $tglSumber->format('d');
        $bulanLengkap = $tglSumber->locale('id')->isoFormat('MMMM');
        $tahunLengkap = $tglSumber->format('Y');
        $akhirBulan = \Carbon\Carbon::createFromDate($transaksi->periode_tahun, $transaksi->periode_bulan, 1)->endOfMonth()->locale('id')->isoFormat('D MMMM YYYY');
        $namaInstansi = $lines[1] ?? 'Badan Pengelolaan Keuangan dan Aset Daerah';
        $namaPemda = $lines[0] ?? 'Kota Banjarbaru';
    @endphp

    @php
        // Ambil template pengantar (Prioritas: Snapshot -> Pengaturan Global -> Default)
        $templatePengantar = $transaksi->snapshot_pengantar_ba 
            ?? $pengaturan->teks_pengantar_ba 
            ?? 'Pada hari ini [HARI] Tanggal [TANGGAL] Bulan [BULAN] Tahun [TAHUN], telah dilakukan rekonsiliasi Saldo Kas Bendahara Pengeluaran per [AKHIR_BULAN] pada [NAMA_INSTANSI] [NAMA_PEMDA].<br><br>Dengan mencocokkan BKU Bendahara Pengeluaran per [AKHIR_BULAN] pada Aplikasi SIPANDA dengan Rekening Koran Bank Kalsel per [AKHIR_BULAN] dengan hasil sebagai berikut :';
        
        // Parse placeholders
        $templatePengantar = str_replace(
            ['[HARI]', '[TANGGAL]', '[BULAN]', '[TAHUN]', '[AKHIR_BULAN]', '[NAMA_INSTANSI]', '[NAMA_PEMDA]'],
            [$tanggal, $tglNum, $bulanLengkap, $tahunLengkap, $akhirBulan, ucwords(strtolower($namaInstansi)), ucwords(strtolower($namaPemda))],
            $templatePengantar
        );
        
        $templatePenutup = $transaksi->snapshot_penutup_ba 
            ?? $pengaturan->teks_penutup_ba 
            ?? '** Rincian terlampir';
            
        // Pisahkan paragraf jika ada <br><br> agar indentasi p bekerja
        $paragraphs = explode('<br><br>', $templatePengantar);
    @endphp

    @foreach($paragraphs as $idx => $p)
        <p class="text-justify indent {{ $idx == count($paragraphs)-1 ? 'mb-4' : 'mb-2' }}">
            {!! str_replace('<br>', '<br/>', $p) !!}
        </p>
    @endforeach

    <!-- Tabel Keuangan -->
    <table class="keuangan" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th colspan="2" class="text-center font-bold">BKU Bendahara Pengeluaran</th>
                <th colspan="2" class="text-center font-bold">Rekening Koran Bank Kalsel</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">Saldo Kas Awal</td>
                <td class="font-bold">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_saldo_awal, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="font-bold">Saldo Kas Awal</td>
                <td class="font-bold">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_saldo_awal, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <tr>
                <td>Ditambah:</td>
                <td></td>
                <td>Ditambah:</td>
                <td></td>
            </tr>
            <tr>
                <td class="pl-4">Penerimaan</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_penerimaan, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="pl-4">Penerimaan</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_penerimaan, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <tr>
                <td>Dikurang:</td>
                <td></td>
                <td>Dikurang:</td>
                <td></td>
            </tr>
            <tr>
                <td class="pl-4 pb-2">Pengeluaran</td>
                <td class="pb-2">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_pengeluaran, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="pl-4 pb-2">Pengeluaran</td>
                <td class="pb-2">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_pengeluaran, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <tr class="font-bold">
                <td>Saldo Akhir Kas</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</td></tr></table>
                </td>
                <td>Saldo Akhir Kas</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <!-- Selisih -->
            @php $selisih = $transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir; @endphp
            <tr class="font-bold" style="{{ abs($selisih) > 0 ? 'color: #d32f2f; background-color: #fee2e2;' : '' }}">
                <td colspan="2" class="text-center italic">Selisih</td>
                <td colspan="2">
                    <table class="curr-table" style="{{ abs($selisih) > 0 ? 'color: #d32f2f;' : '' }}">
                        <tr>
                            <td class="curr-symbol">Rp</td>
                            <td class="curr-val">{{ number_format($selisih, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Penjelasan -->
    @if(abs($selisih) > 0)
    <div class="mb-4 text-justify">
        <span class="font-bold">Penjelasan :</span><br>
        {{ $transaksi->keterangan_selisih ?: '-' }}
    </div>
    @endif

    <div class="mb-4 text-sm font-bold">
        {!! nl2br(str_replace('<br>', "\n", $templatePenutup)) !!}
    </div>

    <!-- Tanda Tangan -->
    @php
        $kotaFallback = 'Banjarbaru';
        $lastLine = end($lines);
        if(stripos($lastLine, 'Banjarbaru') !== false) {
            $kotaFallback = 'Banjarbaru';
        }
    @endphp

    <table class="ttd-table text-center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="ttd-cell">
                Pembuatan Laporan,<br>
                {{ $pengaturan->jabatan_bendahara ?? 'Bendahara Pengeluaran' }}
                <div class="ttd-space"></div>
                <div class="ttd-name uppercase">{{ $pengaturan->nama_bendahara ?? '.........................' }}</div>
                <div class="ttd-nip">{{ $pengaturan->pangkat_bendahara ?? '.........................' }}</div>
                <div class="ttd-nip">NIP. {{ $pengaturan->nip_bendahara ?? '.........................' }}</div>
            </td>
            <td class="ttd-cell">
                Menyetujui,<br>
                {{ $pengaturan->jabatan_kasubag ?? 'Kasubag Keuangan' }}
                <div class="ttd-space"></div>
                <div class="ttd-name uppercase">{{ $pengaturan->nama_kasubag ?? '.........................' }}</div>
                <div class="ttd-nip">{{ $pengaturan->pangkat_kasubag ?? '.........................' }}</div>
                <div class="ttd-nip">NIP. {{ $pengaturan->nip_kasubag ?? '.........................' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="ttd-cell" style="padding-top: 10px;">
                {{ $kotaFallback }}, {{ $tglSumber->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                <span class="font-bold">Mengetahui,</span><br>
                <span class="font-bold">{{ $pengaturan->jabatan_kepala ?? 'Pengguna Anggaran / Kuasa Pengguna Anggaran' }}</span>
                <div class="ttd-space"></div>
                <div class="ttd-name uppercase">{{ $pengaturan->nama_kepala ?? '.........................' }}</div>
                <div class="ttd-nip">{{ $pengaturan->pangkat_kepala ?? '.........................' }}</div>
                <div class="ttd-nip">NIP. {{ $pengaturan->nip_kepala ?? '.........................' }}</div>
            </td>
        </tr>
    </table>

    <!-- Footer Lampiran & Stempel Verifikasi Berjenjang -->
    <table width="100%" style="margin-top: 10px; border: none; padding: 0;">
        <tr>
            <td style="vertical-align: bottom; border: none;">
                <span class="font-bold italic">Lampiran Berkas:</span>
                <ol class="italic" style="margin-top: 3px; padding-left: 20px; font-size: 11px; margin-bottom: 0;">
                    <li>Buku Kas Pengeluaran (BKU)</li>
                    <li>Buku Pembantu Bank</li>
                    <li>Rekening Koran Bank Kalsel</li>
                </ol>
            </td>
            @if($transaksi->status_konsolidator === 'valid')
            <td style="vertical-align: bottom; text-align: center; width: 145px; border: none; padding-right: 6px;">
                <div style="border: 1.5px dashed #059669; background-color: #f0fdf4; border-radius: 6px; padding: 4px 6px; text-align: center;">
                    <div style="font-size: 8px; font-weight: 900; color: #047857; text-transform: uppercase;">
                        TELAH DIUJI SAH
                    </div>
                    <div style="font-size: 7.5px; font-weight: bold; color: #065f46; margin-top: 1px;">
                        KONSOLIDATOR BPKAD
                    </div>
                    <div style="font-size: 7px; color: #047857; font-family: monospace; margin-top: 2px; line-height: 1.1;">
                        {{ $transaksi->checked_at ? \Carbon\Carbon::parse($transaksi->checked_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : 'VALID' }}<br>
                        {{ Str::limit($transaksi->checker->name ?? 'BPKAD', 16) }}
                    </div>
                </div>
            </td>
            @endif
            @if($transaksi->inspektorat_status === 'valid')
            <td style="vertical-align: bottom; text-align: center; width: 145px; border: none; padding-right: 6px;">
                <div style="border: 1.5px dashed #4338ca; background-color: #eef2ff; border-radius: 6px; padding: 4px 6px; text-align: center;">
                    <div style="font-size: 8px; font-weight: 900; color: #3730a3; text-transform: uppercase;">
                        PENGESAHAN AKHIR
                    </div>
                    <div style="font-size: 7.5px; font-weight: bold; color: #312e81; margin-top: 1px;">
                        INSPEKTORAT BANJARBARU
                    </div>
                    <div style="font-size: 7px; color: #3730a3; font-family: monospace; margin-top: 2px; line-height: 1.1;">
                        {{ $transaksi->inspektorat_checked_at ? \Carbon\Carbon::parse($transaksi->inspektorat_checked_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : 'TERCATAT' }}<br>
                        {{ Str::limit($transaksi->inspektoratChecker->name ?? 'Inspektorat', 16) }}
                    </div>
                </div>
            </td>
            @endif
            @if($transaksi->tahap_verifikasi === 'disetujui_final' || $transaksi->status_verifikasi === 'verified')
            <td style="vertical-align: bottom; text-align: center; width: 85px; border: none;">
                @php
                    $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.show', $transaksi->id);
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($signedUrl);
                    $qrData = @file_get_contents($qrUrl);
                    $qrBase64 = $qrData ? base64_encode($qrData) : '';
                @endphp
                @if($qrBase64)
                    <img src="data:image/png;base64,{{ $qrBase64 }}" width="65" height="65" style="border: 1px solid #000; padding: 2px;">
                @else
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->generate($signedUrl)) !!}" width="65" height="65" style="border: 1px solid #000; padding: 2px;">
                @endif
                <div style="font-size: 8px; font-style: italic; font-weight: bold; margin-top: 2px;">Segel Digital</div>
            </td>
            @endif
        </tr>
    </table>

</body>
</html>
