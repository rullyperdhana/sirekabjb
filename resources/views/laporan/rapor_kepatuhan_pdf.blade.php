<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Kepatuhan & Kedisiplinan SKPD SiReKa {{ $tahunAktif }}</title>
    <style>
        @page { margin: 15mm 20mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #111;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 15px; padding-bottom: 8px; border-collapse: collapse; }
        .kop-logo { width: 85px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 70px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 85px; } 
        .kop-text h2 { margin: 0; font-size: 16px; font-weight: bold; letter-spacing: 1px; color: #000; }
        .kop-text h1 { margin: 0; font-size: 19px; font-weight: 900; letter-spacing: 1px; color: #000; }
        .kop-text p { margin: 2px 0 0 0; font-size: 11px; color: #333; }
        
        /* Judul */
        .judul-dokumen { margin-top: 10px; margin-bottom: 15px; }
        .judul-dokumen h2 { margin: 0; font-size: 15px; font-weight: bold; text-decoration: underline; color: #000; }
        .judul-dokumen h3 { margin: 4px 0 0 0; font-size: 12px; font-weight: normal; color: #333; }

        /* Kotak Info Eksekuitf */
        .meta-box {
            width: 100%;
            border: 1px solid #94a3b8;
            background-color: #f8fafc;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 16px;
            font-size: 11px;
        }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 3px 0; vertical-align: top; border: none !important; }

        /* Tabel Data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
            border: 1px solid #000;
        }
        table.data th, table.data td {
            padding: 6px 8px;
            border: 1px solid #000;
            vertical-align: middle;
        }
        table.data th {
            background-color: #e2e8f0;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
        }
        .score-box { font-weight: bold; text-align: center; font-size: 11px; }
        .grade-a { background-color: #d1fae5; color: #065f46; font-weight: bold; text-align: center; }
        .grade-b { background-color: #e0f2fe; color: #0369a1; font-weight: bold; text-align: center; }
        .grade-c { background-color: #fef9c3; color: #854d0e; font-weight: bold; text-align: center; }
        .grade-d { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
        .text-alert { color: #dc2626; font-weight: bold; }
        .text-ok { color: #059669; font-weight: bold; }
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
        <h2>RAPOR KEPATUHAN & EVALUASI DISIPLIN REKONSILIASI SKPD</h2>
        <h3>Analisis Ketepatan Waktu (Timeliness Score) & Peringatan Dini SiReKa - Tahun Anggaran {{ $tahunAktif }}</h3>
    </div>

    <!-- Kotak Summary Eksekutif untuk Pimpinan -->
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td style="width: 16%; font-weight: bold;">Tanggal & Jam Cetak</td>
                <td style="width: 2%;">:</td>
                <td style="width: 32%;">{{ $tanggalCetak }}</td>
                <td style="width: 20%; font-weight: bold;">Rata-rata Skor Daerah</td>
                <td style="width: 2%;">:</td>
                <td style="width: 28%; font-weight: bold; font-size: 13px;" class="{{ $avgDaerah >= 75 ? 'text-ok' : 'text-alert' }}">
                    {{ $avgDaerah }} / 100 
                    ({{ $avgDaerah >= 80 ? 'Sangat Baik' : ($avgDaerah >= 60 ? 'Baik / Standar' : 'Perlu Perhatian Khusus') }})
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Dicetak Oleh</td>
                <td>:</td>
                <td>{{ $pencetak }}</td>
                <td style="font-weight: bold;">Total SKPD Evaluasi</td>
                <td>:</td>
                <td style="font-weight: bold;">{{ $totalSkpd }} Instansi</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tujuan Laporan</td>
                <td>:</td>
                <td>Evaluasi Kinerja Pimpinan & Audit BPK</td>
                <td style="font-weight: bold;">Akumulasi Lapor / Selisih</td>
                <td>:</td>
                <td><strong class="text-ok">{{ $totalVerifiedTrx }} Verified</strong> / <strong class="text-alert">{{ $totalSelisihKas }} Kasus Selisih</strong></td>
            </tr>
        </table>
    </div>

    <!-- Tabel Rapor Kepatuhan dan Peringkat -->
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">Rank</th>
                <th style="width: 12%;">Kode SKPD</th>
                <th style="width: 33%;">Nama Instansi / SKPD</th>
                <th style="width: 12%;">Laporan (Verified/Total)</th>
                <th style="width: 12%;">Catatan Selisih Kas</th>
                <th style="width: 12%;">Skor Disiplin Waktu</th>
                <th style="width: 14%;">Kategori Rapor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($raporSkpd as $index => $skpd)
            @php
                $score = $skpd->timeliness_score ?? 0;
                $gradeClass = 'grade-d';
                $gradeText = '🔴 D (Rapor Merah / EWS)';
                if ($score >= 85) {
                    $gradeClass = 'grade-a';
                    $gradeText = '🌟 A (Sangat Disiplin)';
                } elseif ($score >= 65) {
                    $gradeClass = 'grade-b';
                    $gradeText = '🟢 B (Baik / Target)';
                } elseif ($score >= 45) {
                    $gradeClass = 'grade-c';
                    $gradeText = '🟡 C (Perlu Evaluasi)';
                }
            @endphp
            <tr>
                <td class="text-center font-bold" style="{{ $index < 5 ? 'background-color: #fef3c7; color: #b45309;' : '' }}">
                    #{{ $index + 1 }}
                </td>
                <td class="text-center" style="font-family: monospace;">{{ $skpd->kode }}</td>
                <td style="font-weight: 500;">{{ $skpd->nama }}</td>
                <td class="text-center font-bold">
                    <span style="color:#059669;">{{ $skpd->verified_count ?? 0 }}</span> / {{ $skpd->transaksi_count ?? 0 }} Bulan
                </td>
                <td class="text-center">
                    @if(($skpd->selisih_count ?? 0) > 0)
                        <span class="text-alert">⚠️ {{ $skpd->selisih_count }} Kasus Selisih</span>
                    @else
                        <span style="color:#059669; font-weight:bold;">✅ Nihil (Aman)</span>
                    @endif
                </td>
                <td class="score-box">
                    <span style="font-size: 12px; font-weight: 800;">{{ $score }}</span> / 100
                </td>
                <td class="{{ $gradeClass }}">
                    {{ $gradeText }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 15px;">Belum ada data evaluasi SKPD.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Keterangan Penyetaraan Bobot Rapor -->
    <div style="font-size: 9px; color: #475569; margin-top: -10px; margin-bottom: 20px; font-style: italic;">
        *Catatan: Skor Disiplin Waktu (Timeliness Score) dihitung berdasarkan ketepatan hari pelaporan setiap bulannya (Tgl 1-5 = 100 pt, Tgl 6-10 = 85 pt, Tgl 11-15 = 65 pt, > Tgl 15 = 40 pt) dikalikan status keaslian bukti dan keakuratan (tanpa selisih) di sistem SiReKa.
    </div>

    <!-- Tanda Tangan Pengesahan -->
    <div style="width: 100%; margin-top: 15px; page-break-inside: avoid;">
        <table style="width: 100%; border: none; font-size: 11px;">
            <tr>
                <td style="width: 60%; border: none;"></td>
                <td style="width: 40%; text-align: center; border: none;">
                    Banjarbaru, {{ \Carbon\Carbon::now()->format('d-m-Y') }}<br>
                    <strong>Pejabat Penanggung Jawab SiReKa</strong><br>
                    <span>Badan Pengelolaan Keuangan dan Aset Daerah Kota Banjarbaru</span><br><br><br><br>
                    <span style="text-decoration: underline; font-weight: bold;">{{ auth()->user()->name }}</span><br>
                    <span>NIP / Username: {{ auth()->user()->username }}</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
