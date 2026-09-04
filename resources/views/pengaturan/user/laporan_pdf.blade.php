<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Audit Kepemilikan Akun SKPD</title>
    <style>
        @page { margin: 15mm 20mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 15px; padding-bottom: 8px; border-collapse: collapse; }
        .kop-logo { width: 85px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 72px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 85px; } 
        .kop-text h2 { margin: 0; font-size: 16px; font-weight: bold; letter-spacing: 1px; color: #000; }
        .kop-text h1 { margin: 0; font-size: 19px; font-weight: 900; letter-spacing: 1px; color: #000; }
        .kop-text p { margin: 2px 0 0 0; font-size: 11px; color: #333; }
        
        /* Judul */
        .judul-dokumen { margin-top: 10px; margin-bottom: 15px; }
        .judul-dokumen h2 { margin: 0; font-size: 15px; font-weight: bold; text-decoration: underline; color: #000; }
        .judul-dokumen h3 { margin: 4px 0 0 0; font-size: 12px; font-weight: normal; color: #444; }

        /* Kotak Info / Audit Metadata */
        .meta-box {
            width: 100%;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 18px;
            font-size: 11px;
        }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 3px 0; vertical-align: top; border: none !important; }

        /* Tabel Data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            font-size: 10px;
            border: 1px solid #000;
        }
        table.data th, table.data td {
            padding: 6px 8px;
            border: 1px solid #000;
            vertical-align: top;
        }
        table.data th {
            background-color: #e2e8f0;
            color: #000;
            font-weight: bold;
            text-align: center;
        }
        .bg-ok { background-color: #d1fae5; color: #065f46; font-weight: bold; text-align: center; }
        .bg-alert { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
        .text-alert { color: #dc2626; font-weight: bold; }
        .text-ok { color: #059669; font-weight: bold; }
        .user-item { margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed #e2e8f0; }
        .user-item:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        
        /* Section Title */
        .section-title { font-size: 12px; font-weight: bold; margin-bottom: 6px; color: #1e293b; text-transform: uppercase; border-left: 4px solid #3b82f6; padding-left: 6px; }
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
        <h2>LAPORAN REKAPITULASI & AUDIT KEPEMILIKAN AKUN SKPD</h2>
        <h3>Pengecekan Internal Status Pengguna SiReKa (Sistem Rekonsiliasi Keuangan Daerah)</h3>
    </div>

    <!-- Kotak Metadata Pengecekan Internal Admin -->
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td style="width: 14%; font-weight: bold;">Tanggal & Waktu Cetak</td>
                <td style="width: 2%;">:</td>
                <td style="width: 36%;">{{ $tanggalCetak }}</td>
                <td style="width: 16%; font-weight: bold;">Total SKPD Aktif</td>
                <td style="width: 2%;">:</td>
                <td style="width: 30%; font-weight: bold;">{{ $totalSkpd }} Instansi</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Dicetak Oleh (Internal)</td>
                <td>:</td>
                <td>{{ $adminPencetak }}</td>
                <td style="font-weight: bold;">SKPD Sudah Berakun</td>
                <td>:</td>
                <td class="text-ok">{{ $skpdSudahAdaUser }} SKPD (Sudah Siap Rekon)</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tujuan Pengecekan</td>
                <td>:</td>
                <td>Audit Ketersediaan & Pemantauan Akun Operator</td>
                <td style="font-weight: bold;">SKPD Belum Berakun</td>
                <td>:</td>
                <td class="text-alert">{{ $skpdBelumAdaUser }} SKPD (Perlu Dibuatkan Akun)</td>
            </tr>
        </table>
    </div>

    <!-- Bagian I: Daftar Kepemilikan Akun per SKPD -->
    <div class="section-title">I. STATUS KEPEMILIKAN AKUN OPERATOR DAN ADMIN PER SKPD</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 14%;">Kode SKPD</th>
                <th style="width: 32%;">Nama SKPD / Instansi</th>
                <th style="width: 16%;">Status Ketersediaan Akun</th>
                <th style="width: 34%;">Daftar Pengguna Terdaftar (Nama - Username - Status)</th>
            </tr>
        </tr>
        </thead>
        <tbody>
            @forelse($skpds as $index => $skpd)
            @php
                $userCount = $skpd->users->count();
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center" style="font-family: monospace;">{{ $skpd->kode }}</td>
                <td style="font-weight: 500;">{{ $skpd->nama }}</td>
                @if($userCount > 0)
                    <td class="bg-ok">✅ Sudah Ada ({{ $userCount }} Akun)</td>
                @else
                    <td class="bg-alert">❌ BELUM ADA AKUN</td>
                @endif
                <td>
                    @if($userCount > 0)
                        @foreach($skpd->users as $user)
                        <div class="user-item">
                            <strong>{{ $user->name }}</strong> 
                            (<span style="color:#2563eb;">{{ $user->username }}</span>) 
                            - <em>{{ ucfirst($user->role) }}</em> 
                            [<span>{{ $user->status ? 'Aktif' : 'Non-Aktif' }}</span>]
                        </div>
                        @endforeach
                    @else
                        <span class="text-alert" style="font-style: italic;">- Belum ada pengguna/operator terdaftar di instansi ini -</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 15px;">Belum ada data SKPD.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Bagian II: Daftar Pengguna Non-SKPD (Pusat / 4-Pilar) -->
    <div class="section-title" style="margin-top: 10px;">II. DAFTAR PENGGUNA TINGKAT PUSAT / 4-PILAR (ADMIN, BANK KALSEL, KONSOLIDATOR BPKAD & INSPEKTORAT)</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Lengkap</th>
                <th style="width: 20%;">Username</th>
                <th style="width: 30%;">Peran & Otoritas (Probis)</th>
                <th style="width: 15%;">Status Akun</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nonSkpdUsers as $idx => $usr)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $usr->name }}</td>
                <td style="font-family: monospace; color: #2563eb;">{{ $usr->username }}</td>
                <td class="text-center" style="font-size: 9.5px; font-weight: bold;">
                    @if($usr->role === 'bank')
                        <span style="color: #b45309;">Pilar 2: Bank Kalsel</span>
                    @elseif($usr->role === 'konsolidator')
                        <span style="color: #6b21a8;">Pilar 3: Konsolidator BPKAD</span>
                    @elseif($usr->role === 'inspektorat')
                        <span style="color: #9f1239;">Pilar 4: Inspektorat</span>
                    @else
                        <span style="color: #0f172a;">Administrator BPKAD</span>
                    @endif
                </td>
                <td class="text-center font-bold {{ $usr->status ? 'text-ok' : 'text-alert' }}">
                    {{ $usr->status ? 'Aktif' : 'Non-Aktif' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 10px;">Tidak ada pengguna non-SKPD.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Penandatangan / Footer Audit -->
    <div style="width: 100%; margin-top: 25px; page-break-inside: avoid;">
        <table style="width: 100%; border: none; font-size: 11px;">
            <tr>
                <td style="width: 65%; border: none;"></td>
                <td style="width: 35%; text-align: center; border: none;">
                    Banjarbaru, {{ \Carbon\Carbon::now()->format('d-m-Y') }}<br>
                    <strong>Administrator Sistem SiReKa</strong><br><br><br><br>
                    <span style="text-decoration: underline; font-weight: bold;">{{ auth()->user()->name }}</span><br>
                    <span>NIP / Username: {{ auth()->user()->username }}</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
