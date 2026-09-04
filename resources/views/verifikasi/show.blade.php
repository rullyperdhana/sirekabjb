<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen Rekonsiliasi - SiReKa Banjarbaru</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 py-8">
    @php
        $selisih = abs($transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir);
        $hasSelisih = $selisih > 0.01;
        $isFinal = ($transaksi->tahap_verifikasi === 'disetujui_final');
        $isValidKonsolidator = ($transaksi->status_konsolidator === 'valid');
        $isValidBank = ($transaksi->bank_status === 'valid');
        
        $regNo = 'REG-KONS/BJB/' . $transaksi->periode_tahun . '/' . str_pad($transaksi->periode_bulan, 2, '0', STR_PAD_LEFT) . '/' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT);
    @endphp

    <div class="bg-white max-w-xl w-full rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        <!-- Banner Header -->
        <div class="{{ $isFinal ? 'bg-gradient-to-r from-emerald-600 to-teal-700' : ($isValidKonsolidator ? 'bg-gradient-to-r from-blue-600 to-indigo-700' : ($hasSelisih ? 'bg-gradient-to-r from-rose-600 to-red-700' : 'bg-gradient-to-r from-slate-700 to-slate-800')) }} p-6 text-center text-white relative">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-inner mb-3">
                <span class="material-symbols-outlined text-[36px] text-white">
                    {{ $isFinal ? 'verified' : ($isValidKonsolidator ? 'fact_check' : ($hasSelisih ? 'warning' : 'task_alt')) }}
                </span>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight">
                @if($isFinal)
                    Dokumen Sah &amp; Berita Acara Terbit
                @elseif($isValidKonsolidator)
                    Diverifikasi Konsolidator BPKAD
                @elseif($isValidBank)
                    Divalidasi Bank Kalsel
                @else
                    Draf Pengajuan Rekonsiliasi SKPD
                @endif
            </h1>
            <p class="text-white/80 text-xs mt-1 max-w-sm mx-auto">
                @if($isFinal)
                    Telah melalui seluruh 4 pilar verifikasi (SKPD, Bank Kalsel, Konsolidator BPKAD, &amp; Inspektorat Kota Banjarbaru).
                @elseif($isValidKonsolidator)
                    Sedang menunggu pengesahan akhir dan penerbitan nomor BA oleh Inspektorat Kota Banjarbaru.
                @elseif($isValidBank)
                    Saldo bank tervalidasi cocok. Menunggu pemeriksaan fisik oleh Konsolidator BPKAD.
                @else
                    Tercatat resmi dalam database SiReKa Pemerintah Kota Banjarbaru.
                @endif
            </p>

            <div class="mt-3 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-3.5 py-1 text-[11px] font-mono font-bold tracking-wider">
                <span>{{ $regNo }}</span>
            </div>
            @if($transaksi->nomor_ba)
            <div class="mt-2 text-xs font-semibold bg-emerald-900/40 text-emerald-200 border border-emerald-400/30 rounded-lg py-1 px-3 inline-block">
                No. BA: {{ $transaksi->nomor_ba }}
            </div>
            @endif
        </div>
        
        <div class="p-6 space-y-5">
            <!-- 4-Pilar Progress Status -->
            <div>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-2.5">Progres Alur Verifikasi 4-Pilar</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <!-- Pilar 1: SKPD -->
                    <div class="p-2.5 rounded-xl border border-blue-200 bg-blue-50/60 flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-blue-600 text-[20px]">check_circle</span>
                        <div>
                            <p class="font-bold text-blue-950 text-[11px]">1. Operator SKPD</p>
                            <p class="text-blue-700 text-[10px]">Data &amp; Berkas Diajukan</p>
                        </div>
                    </div>

                    <!-- Pilar 2: Bank -->
                    <div class="p-2.5 rounded-xl border {{ $isValidBank ? 'border-cyan-200 bg-cyan-50/60' : 'border-slate-200 bg-slate-50' }} flex items-center gap-2.5">
                        <span class="material-symbols-outlined {{ $isValidBank ? 'text-cyan-600' : 'text-slate-400' }} text-[20px]">
                            {{ $isValidBank ? 'check_circle' : 'pending' }}
                        </span>
                        <div>
                            <p class="font-bold {{ $isValidBank ? 'text-cyan-950' : 'text-slate-700' }} text-[11px]">2. Bank Kalsel</p>
                            <p class="{{ $isValidBank ? 'text-cyan-700' : 'text-slate-400' }} text-[10px]">
                                {{ $isValidBank ? 'Rekening Koran Cocok' : 'Menunggu Validasi' }}
                            </p>
                        </div>
                    </div>

                    <!-- Pilar 3: Konsolidator -->
                    <div class="p-2.5 rounded-xl border {{ $isValidKonsolidator ? 'border-emerald-200 bg-emerald-50/60' : 'border-slate-200 bg-slate-50' }} flex items-center gap-2.5">
                        <span class="material-symbols-outlined {{ $isValidKonsolidator ? 'text-emerald-600' : 'text-slate-400' }} text-[20px]">
                            {{ $isValidKonsolidator ? 'check_circle' : 'pending' }}
                        </span>
                        <div>
                            <p class="font-bold {{ $isValidKonsolidator ? 'text-emerald-950' : 'text-slate-700' }} text-[11px]">3. Konsolidator BPKAD</p>
                            <p class="{{ $isValidKonsolidator ? 'text-emerald-700' : 'text-slate-400' }} text-[10px]">
                                {{ $isValidKonsolidator ? 'Diuji Sah & Lengkap' : 'Menunggu Uji Kasda' }}
                            </p>
                        </div>
                    </div>

                    <!-- Pilar 4: Inspektorat -->
                    <div class="p-2.5 rounded-xl border {{ $isFinal ? 'border-indigo-200 bg-indigo-50/60' : 'border-slate-200 bg-slate-50' }} flex items-center gap-2.5">
                        <span class="material-symbols-outlined {{ $isFinal ? 'text-indigo-600' : 'text-slate-400' }} text-[20px]">
                            {{ $isFinal ? 'verified' : 'pending' }}
                        </span>
                        <div>
                            <p class="font-bold {{ $isFinal ? 'text-indigo-950' : 'text-slate-700' }} text-[11px]">4. Inspektorat</p>
                            <p class="{{ $isFinal ? 'text-indigo-700' : 'text-slate-400' }} text-[10px]">
                                {{ $isFinal ? 'Disahkan & BA Terbit' : 'Pengawasan Akhir' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instansi -->
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Satuan Kerja (SKPD)</p>
                <p class="text-base font-bold text-slate-800 mt-0.5">{{ $transaksi->skpd->nama ?? '-' }}</p>
            </div>
            
            <!-- Grid Info -->
            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                <div>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Periode Rekonsiliasi</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Rekening Kas Bank</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $transaksi->rekening->nomor ?? '-' }}</p>
                    <p class="text-[11px] text-slate-500 font-medium">{{ $transaksi->rekening->bank ?? 'Bank Kalsel' }}</p>
                </div>
            </div>

            <!-- Rincian Saldo -->
            <div class="pt-1 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-2.5">Data Saldo Akhir Kas</p>
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-600 font-medium">Saldo Kas BKU (SIPANDA):</span>
                        <span class="font-bold text-slate-900 font-mono text-sm">Rp {{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-600 font-medium">Saldo Rekening Koran Bank:</span>
                        <span class="font-bold text-slate-900 font-mono text-sm">Rp {{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</span>
                    </div>
                    @if($hasSelisih)
                    <div class="flex justify-between items-center p-2.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700">
                        <span class="font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">warning</span> Selisih Kas:
                        </span>
                        <span class="font-extrabold font-mono text-sm">Rp {{ number_format($selisih, 2, ',', '.') }}</span>
                    </div>
                    @else
                    <div class="flex justify-between items-center p-2 bg-emerald-50 text-emerald-700 rounded-xl font-semibold text-[11px]">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">check_circle</span> Status Selisih:
                        </span>
                        <span class="font-bold">SESUAI / KLOP (Rp 0)</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="pt-4 flex flex-col gap-2">
                @if($isValidKonsolidator || $isFinal)
                <a href="{{ route('transaksi.bukti-digital-pdf', $transaksi->id) }}" target="_blank" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    <span>Unduh Surat Tanda Bukti Digital (PDF)</span>
                </a>
                @endif
                @if($isFinal)
                <a href="{{ route('ba.pdf', $transaksi->id) }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">description</span>
                    <span>Unduh Berita Acara Resmi (PDF)</span>
                </a>
                @endif
                <a href="{{ route('landing') }}" class="w-full py-2 px-4 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-semibold text-xs text-center transition-colors">
                    Kembali ke Beranda SiReKa
                </a>
            </div>
        </div>
    </div>
</body>
</html>
