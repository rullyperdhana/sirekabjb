<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Bukti Pemeriksaan Rekonsiliasi - {{ $transaksi->skpd->nama ?? '-' }}</title>
    <style>
        @page { margin: 12mm 15mm; size: a4 portrait; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            color: #1e293b;
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
        .kop-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 12px; padding-bottom: 5px; }
        .kop-logo { width: 70px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 60px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 70px; } 
        .kop-text h2 { margin: 0; font-size: 14px; font-weight: bold; letter-spacing: 0.5px; }
        .kop-text h1 { margin: 0; font-size: 16px; font-weight: 900; letter-spacing: 0.5px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 9.5px; color: #333; }
        
        /* Judul Dokumen */
        .header-doc {
            text-align: center;
            margin-bottom: 12px;
        }
        .header-doc h2 {
            margin: 0;
            font-size: 13.5px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }
        .register-badge {
            display: inline-block;
            margin-top: 4px;
            padding: 2.5px 10px;
            font-size: 10px;
            font-weight: bold;
            font-family: monospace;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            color: #0f172a;
        }

        /* Tabel Data Info */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 2.5px 0;
            vertical-align: top;
            font-size: 10px;
        }
        .info-label { width: 140px; font-weight: bold; color: #475569; }
        .info-sep { width: 12px; text-align: center; }
        .info-value { font-weight: bold; color: #0f172a; }

        /* Tabel Data Saldo */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
            border: 1px solid #cbd5e1;
        }
        table.keuangan th, table.keuangan td {
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
        }
        table.keuangan th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #334155;
        }
        .curr-table { width: 100%; border: none !important; border-collapse: collapse; margin: 0; padding: 0; }
        .curr-table td { border: none !important; padding: 0 !important; background: transparent; }
        .curr-symbol { text-align: left; width: 20px; }
        .curr-val { text-align: right; }

        /* Checklist Dokumen */
        table.checklist {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9.5px;
            border: 1px solid #cbd5e1;
        }
        table.checklist th, table.checklist td {
            padding: 3.5px 6px;
            border: 1px solid #cbd5e1;
        }
        table.checklist th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #334155;
        }

        /* Statement Box */
        .statement-box {
            background-color: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 5px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 9.5px;
            text-align: justify;
            color: #14532d;
            line-height: 1.35;
        }

        /* 4 Pilar Verification Boxes */
        table.pilar-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .pilar-cell {
            width: 25%;
            vertical-align: top;
            border-radius: 6px;
            padding: 6px 8px;
            text-align: center;
            font-size: 9px;
            border: 1.5px dashed #cbd5e1;
            background-color: #f8fafc;
        }
        .pilar-1 { border-color: #3b82f6; background-color: #eff6ff; }
        .pilar-2 { border-color: #0891b2; background-color: #ecfeff; }
        .pilar-3 { border-color: #059669; background-color: #f0fdf4; }
        .pilar-4 { border-color: #4f46e5; background-color: #eef2ff; }

        .pilar-title { font-size: 9px; font-weight: 900; text-transform: uppercase; margin-bottom: 3px; }
        .pilar-subtitle { font-size: 8px; font-weight: bold; margin-bottom: 4px; }
        .pilar-meta { font-size: 7.5px; line-height: 1.25; margin-top: 4px; }
        .pilar-badge {
            display: inline-block;
            padding: 1.5px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            margin-top: 3px;
        }

        /* Log Table */
        table.log-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8.5px;
            border: 1px solid #cbd5e1;
        }
        table.log-table th, table.log-table td {
            padding: 3px 5px;
            border: 1px solid #cbd5e1;
        }
        table.log-table th { background-color: #f1f5f9; color: #334155; }

        /* Auth Table with QR */
        .auth-table { width: 100%; border: none; margin-top: 8px; page-break-inside: avoid; }
        .auth-table td { border: none; padding: 0; vertical-align: middle; }
    </style>
</head>
<body>

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KOTA BANJARBARU|BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH|Jl. Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan 70711|Telp. (0511) 4772545');
        $logoSrc = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
        $base64Logo = null;
        
        if ($logoSrc && str_starts_with($logoSrc, 'logos/')) {
            $path = storage_path('app/public/' . $logoSrc);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        } elseif ($logoSrc && filter_var($logoSrc, FILTER_VALIDATE_URL)) {
            try {
                $data = @file_get_contents($logoSrc);
                if ($data) {
                    $base64Logo = 'data:image/png;base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {}
        }

        if (!$base64Logo && file_exists(public_path('images/logo_banjarbaru.png'))) {
            $data = file_get_contents(public_path('images/logo_banjarbaru.png'));
            $base64Logo = 'data:image/png;base64,' . base64_encode($data);
        }

        $regNo = 'REG-KONS/BJB/' . $transaksi->periode_tahun . '/' . str_pad($transaksi->periode_bulan, 2, '0', STR_PAD_LEFT) . '/' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT);
        $selisih = abs($transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir);
        
        $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.show', $transaksi->id);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($signedUrl);
        $qrData = @file_get_contents($qrUrl);
        $qrBase64 = $qrData ? base64_encode($qrData) : '';
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
                        <h2>{{ $line }}</h2>
                    @elseif($index === 1)
                        <h1>{{ $line }}</h1>
                    @else
                        <p>{{ $line }}</p>
                    @endif
                @endforeach
            </td>
        </tr>
    </table>

    <!-- Header Dokumen -->
    <div class="header-doc">
        <h2>SURAT TANDA BUKTI PEMERIKSAAN &amp; PENGESAHAN REKONSILIASI KAS DAERAH</h2>
        <div>
            <span class="register-badge">NO. REGISTRASI DIGITAL: {{ $regNo }}</span>
        </div>
    </div>

    <!-- Data Instansi & Rekening -->
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-label">Satuan Kerja (SKPD)</td>
            <td class="info-sep">:</td>
            <td class="info-value">{{ $transaksi->skpd->kode ?? '' }} - {{ $transaksi->skpd->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor Rekening Kas</td>
            <td class="info-sep">:</td>
            <td class="info-value">{{ $transaksi->rekening->nomor ?? '-' }} ({{ $transaksi->rekening->nama ?? '-' }}) &bull; {{ $transaksi->rekening->bank ?? 'Bank Kalsel' }}</td>
        </tr>
        <tr>
            <td class="info-label">Periode Rekonsiliasi</td>
            <td class="info-sep">:</td>
            <td class="info-value">{{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }} (Tahun Anggaran {{ $transaksi->periode_tahun }})</td>
        </tr>
        <tr>
            <td class="info-label">Nomor Berita Acara (BA)</td>
            <td class="info-sep">:</td>
            <td class="info-value">
                @if($transaksi->nomor_ba)
                    <span style="color: #047857; font-weight: 900;">{{ $transaksi->nomor_ba }}</span> (Telah Disahkan Resmi)
                @else
                    <span style="color: #b45309; font-style: italic;">DRAFT - Dalam Alur Verifikasi Berjenjang</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- Ringkasan Saldo Rekonsiliasi -->
    <table class="keuangan" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 40%;">Uraian Saldo Rekonsiliasi</th>
                <th style="width: 35%; text-align: right;">Nilai Rupiah (IDR)</th>
                <th style="width: 25%; text-align: center;">Hasil Uji Kesesuaian</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1. Saldo Kas Buku Kas Umum (BKU SIPANDA)</td>
                <td>
                    <table class="curr-table">
                        <tr>
                            <td class="curr-symbol">Rp</td>
                            <td class="curr-val">{{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
                <td rowspan="2" class="text-center" style="vertical-align: middle; font-weight: bold; background-color: {{ $selisih < 0.01 ? '#f0fdf4' : '#fef2f2' }}; color: {{ $selisih < 0.01 ? '#15803d' : '#b91c1c' }};">
                    @if($selisih < 0.01)
                        SESUAI / KLOP (Rp 0)<br>
                        <span style="font-size: 8px; font-weight: normal; color: #166534;">(BKU = Rekening Koran)</span>
                    @else
                        TERDAPAT SELISIH<br>
                        <span style="font-size: 8.5px; color: #b91c1c;">Rp {{ number_format($selisih, 2, ',', '.') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>2. Saldo Rekening Koran Bank Kalsel</td>
                <td>
                    <table class="curr-table">
                        <tr>
                            <td class="curr-symbol">Rp</td>
                            <td class="curr-val">{{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Checklist Dokumen Bukti Dukung Fisik -->
    <table class="checklist" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th>Jenis Berkas Bukti Dukung yang Diunggah</th>
                <th style="width: 110px; text-align: center;">Ketersediaan File</th>
                <th style="width: 140px; text-align: center;">Status Verifikasi Berkas</th>
            </tr>
        </thead>
        <tbody>
            @php
                $docsCheck = [
                    ['title' => 'Berita Acara (BA) Manual Instansi', 'field' => 'file_ba_manual'],
                    ['title' => 'Buku Kas Umum (BKU) Penutupan Kas', 'field' => 'file_buku_kas'],
                    ['title' => 'Buku Pembantu Bank Bendahara', 'field' => 'file_buku_pembantu_bank'],
                    ['title' => 'Rekening Koran Bank Kalsel', 'field' => 'file_rekening_koran'],
                ];
            @endphp
            @foreach($docsCheck as $idx => $dc)
                @php
                    $filePath = $transaksi->{$dc['field']};
                    $hasFile = !empty($filePath) && \App\Services\SiReKaStorage::exists($filePath);
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $dc['title'] }}</td>
                    <td class="text-center" style="font-weight: bold; color: {{ $hasFile ? '#15803d' : '#b91c1c' }};">
                        {{ $hasFile ? 'Tersedia Lengkap' : 'Tidak Ada' }}
                    </td>
                    <td class="text-center" style="font-weight: bold; color: #15803d;">
                        ✓ Telah Diperiksa
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pernyataan Pengesahan 4-Pilar -->
    <div class="statement-box">
        <strong>PERNYATAAN PENGESAHAN SISTEM 4-PILAR (SiReKa KOTA BANJARBARU):</strong><br>
        Berdasarkan hasil pengujian teknis data saldo, validasi mutasi rekening koran oleh Bank Kalsel, konsolidasi kasda oleh BPKAD, serta pengawasan internal oleh Inspektorat Kota Banjarbaru, pelaksanaan rekonsiliasi kas pada Satuan Kerja bersangkutan telah memenuhi standar tata kelola keuangan daerah dan dinyatakan <strong>TERCATAT, SAH, DAN SAHIH SECARA DIGITAL</strong>.
    </div>

    <!-- 4 PILAR VERIFIKASI BOXES -->
    <table class="pilar-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Pilar 1: SKPD -->
            <td class="pilar-cell pilar-1">
                <div class="pilar-title" style="color: #1d4ed8;">PILAR 1: OPERATOR SKPD</div>
                <div class="pilar-subtitle" style="color: #1e40af;">Pengajuan Rekon</div>
                <span class="pilar-badge" style="background-color: #dbeafe; color: #1e40af;">MANDIRI DISIAPKAN</span>
                <div class="pilar-meta" style="color: #334155;">
                    <strong>{{ Str::limit($transaksi->skpd->nama ?? 'SKPD', 22) }}</strong><br>
                    Tgl: {{ $transaksi->updated_at ? \Carbon\Carbon::parse($transaksi->updated_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : '-' }} WITA
                </div>
            </td>

            <!-- Pilar 2: BANK KALSEL -->
            <td class="pilar-cell pilar-2">
                <div class="pilar-title" style="color: #0e7490;">PILAR 2: BANK KALSEL</div>
                <div class="pilar-subtitle" style="color: #155e75;">Validasi Rekening Koran</div>
                @if($transaksi->bank_status === 'valid')
                    <span class="pilar-badge" style="background-color: #cffafe; color: #155e75;">✓ SALDO BANK COCOK</span>
                @elseif($transaksi->bank_status === 'perbaikan')
                    <span class="pilar-badge" style="background-color: #fef08a; color: #854d0e;">PERBAIKAN</span>
                @else
                    <span class="pilar-badge" style="background-color: #f1f5f9; color: #64748b;">MENUNGGU</span>
                @endif
                <div class="pilar-meta" style="color: #334155;">
                    <strong>{{ $transaksi->bankChecker->name ?? 'Bank Kalsel' }}</strong><br>
                    Tgl: {{ $transaksi->bank_verified_at ? \Carbon\Carbon::parse($transaksi->bank_verified_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : '-' }} WITA
                </div>
            </td>

            <!-- Pilar 3: KONSOLIDATOR BPKAD -->
            <td class="pilar-cell pilar-3">
                <div class="pilar-title" style="color: #047857;">PILAR 3: KONSOLIDATOR</div>
                <div class="pilar-subtitle" style="color: #065f46;">BPKAD Kota Banjarbaru</div>
                @if($transaksi->status_konsolidator === 'valid')
                    <span class="pilar-badge" style="background-color: #d1fae5; color: #065f46;">✓ DIUJI SAH &amp; KLOP</span>
                @elseif($transaksi->status_konsolidator === 'perbaikan')
                    <span class="pilar-badge" style="background-color: #fef08a; color: #854d0e;">PERBAIKAN</span>
                @else
                    <span class="pilar-badge" style="background-color: #f1f5f9; color: #64748b;">MENUNGGU</span>
                @endif
                <div class="pilar-meta" style="color: #334155;">
                    <strong>{{ $transaksi->checker->name ?? 'Konsolidator BPKAD' }}</strong><br>
                    Tgl: {{ $transaksi->checked_at ? \Carbon\Carbon::parse($transaksi->checked_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : '-' }} WITA
                </div>
            </td>

            <!-- Pilar 4: INSPEKTORAT -->
            <td class="pilar-cell pilar-4">
                <div class="pilar-title" style="color: #3730a3;">PILAR 4: INSPEKTORAT</div>
                <div class="pilar-subtitle" style="color: #312e81;">Pengawasan &amp; Terbit BA</div>
                @if($transaksi->inspektorat_status === 'valid')
                    <span class="pilar-badge" style="background-color: #e0e7ff; color: #3730a3;">✓ PENGESAHAN FINAL</span>
                @elseif($transaksi->inspektorat_status === 'perbaikan')
                    <span class="pilar-badge" style="background-color: #fef08a; color: #854d0e;">PERBAIKAN</span>
                @else
                    <span class="pilar-badge" style="background-color: #f1f5f9; color: #64748b;">MENUNGGU</span>
                @endif
                <div class="pilar-meta" style="color: #334155;">
                    <strong>{{ $transaksi->inspektoratChecker->name ?? 'Inspektorat Kota' }}</strong><br>
                    Tgl: {{ $transaksi->inspektorat_verified_at ? \Carbon\Carbon::parse($transaksi->inspektorat_verified_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : '-' }} WITA
                </div>
            </td>
        </tr>
    </table>

    <!-- LOG AUDIT VERIFIKASI DIGITAL -->
    @php
        $logs = $transaksi->verifikasiLogs()->latest()->take(6)->get();
    @endphp
    @if($logs->count() > 0)
    <div style="font-size: 8.5px; font-weight: bold; margin-bottom: 3px; color: #475569;">
        LOG RIWAYAT AUDIT VERIFIKATOR DIGITAL:
    </div>
    <table class="log-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 65px;">Tahap</th>
                <th style="width: 50px;">Role</th>
                <th style="width: 110px;">Nama Verifikator</th>
                <th style="width: 60px;">Aksi</th>
                <th>Catatan / Keterangan</th>
                <th style="width: 80px;">Waktu (WITA)</th>
                <th style="width: 90px;">Digital Seal Hash</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $lIndex => $log)
            <tr>
                <td class="text-center">{{ $lIndex + 1 }}</td>
                <td>{{ strtoupper(str_replace('_', ' ', $log->stage ?? '-')) }}</td>
                <td>{{ strtoupper($log->role) }}</td>
                <td>{{ $log->user->name ?? '-' }}</td>
                <td class="text-center">
                    <strong style="color: {{ in_array($log->aksi, ['approve', 'setuju']) ? '#15803d' : (in_array($log->aksi, ['reject', 'revisi']) ? '#b91c1c' : '#2563eb') }};">
                        {{ strtoupper($log->aksi) }}
                    </strong>
                </td>
                <td>{{ Str::limit($log->catatan ?: '-', 35) }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Makassar')->format('d/m/y H:i') }}</td>
                <td style="font-family: monospace; font-size: 7.5px;">{{ substr($log->trace_hash ?? '-', 0, 14) }}...</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Tanda Tangan & QR Code -->
    <table class="auth-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Kolom QR Code Otentikasi -->
            <td style="width: 25%; text-align: center;">
                @if($qrBase64)
                    <img src="data:image/png;base64,{{ $qrBase64 }}" width="75" height="75" style="border: 1px solid #94a3b8; padding: 2px; border-radius: 4px;">
                @endif
                <div style="font-size: 7.5px; font-weight: bold; color: #475569; margin-top: 3px;">
                    Scan QR Code untuk Validasi
                </div>
            </td>

            <!-- Kolom Info Validasi Digital -->
            <td style="width: 75%; padding-left: 12px;">
                <div style="font-size: 9.5px; color: #0f172a; line-height: 1.4;">
                    Banjarbaru, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                    <strong>Sistem Informasi Rekonsiliasi Kas Daerah (SiReKa)</strong><br>
                    <span style="color: #475569; font-size: 9px;">
                        Pemerintah Kota Banjarbaru &bull; BPKAD &bull; Bank Kalsel &bull; Inspektorat Daerah
                    </span>
                </div>
                <div style="margin-top: 5px; font-size: 8px; color: #64748b; font-family: monospace;">
                    Audit Trace ID: {{ hash('sha256', $transaksi->id . ($transaksi->inspektorat_verified_at ?? '') . ($transaksi->checked_at ?? '') . 'sireka-bjb-seal') }}
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
