<x-app-layout>
    <div class="max-w-[1100px] mx-auto space-y-6 pb-12">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('transaksi.antrean') }}" class="w-10 h-10 rounded-xl bg-surface-container-low border border-outline-variant flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors shadow-sm" title="Kembali ke Antrean Verifikasi">
                    <span class="material-symbols-outlined text-[22px]">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="font-headline-lg text-headline-lg text-on-surface">Pemeriksaan Rekonsiliasi</h1>
                        @if($transaksi->status_verifikasi === 'verified')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">verified</span> Diverifikasi SKPD
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 border border-amber-500/20 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">edit_note</span> Draft SKPD
                            </span>
                        @endif

                        @if($transaksi->status_konsolidator === 'valid')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-600 text-white flex items-center gap-1 shadow-sm">
                                <span class="material-symbols-outlined text-[15px]">check_circle</span> Valid Konsolidator
                            </span>
                        @elseif($transaksi->status_konsolidator === 'perlu_perbaikan')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-600 text-white flex items-center gap-1 shadow-sm">
                                <span class="material-symbols-outlined text-[15px]">error</span> Perlu Perbaikan
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/10 text-blue-600 border border-blue-500/20 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">pending</span> Menunggu Pemeriksaan
                            </span>
                        @endif
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                        {{ $transaksi->skpd->nama ?? '-' }} &bull; Periode {{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }} &bull; Rekening: {{ $transaksi->rekening->nomor ?? '-' }} ({{ $transaksi->rekening->bank ?? '-' }})
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <!-- Navigasi Cepat Antrean -->
                <div class="flex items-center gap-1 bg-surface-container-low p-1 rounded-xl border border-outline-variant shadow-sm mr-1">
                    @if($prevTrx)
                        <a href="{{ route('transaksi.pemeriksaan', $prevTrx->id) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold text-on-surface-variant hover:text-on-surface hover:bg-surface flex items-center gap-1 transition-colors" title="Sebelumnya: {{ $prevTrx->skpd->nama ?? '' }}">
                            <span class="material-symbols-outlined text-[15px]">arrow_back_ios</span>
                            <span>Prev</span>
                        </a>
                    @else
                        <span class="px-2.5 py-1 text-xs text-on-surface-variant/40 flex items-center gap-1 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[15px]">arrow_back_ios</span>
                            <span>Prev</span>
                        </span>
                    @endif

                    <span class="px-2 py-0.5 text-[11px] font-mono font-bold text-primary bg-primary/10 rounded-md whitespace-nowrap">
                        Sisa: {{ $sisaAntrean }}
                    </span>

                    @if($nextTrx)
                        <a href="{{ route('transaksi.pemeriksaan', $nextTrx->id) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold text-on-surface-variant hover:text-on-surface hover:bg-surface flex items-center gap-1 transition-colors" title="Berikutnya: {{ $nextTrx->skpd->nama ?? '' }}">
                            <span>Next</span>
                            <span class="material-symbols-outlined text-[15px]">arrow_forward_ios</span>
                        </a>
                    @else
                        <span class="px-2.5 py-1 text-xs text-on-surface-variant/40 flex items-center gap-1 cursor-not-allowed">
                            <span>Next</span>
                            <span class="material-symbols-outlined text-[15px]">arrow_forward_ios</span>
                        </span>
                    @endif
                </div>

                @if(Auth::user()->role === 'admin' && $transaksi->status_verifikasi === 'verified')
                <form action="{{ route('transaksi.reset-draft', $transaksi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan status transaksi ini ke DRAFT agar SKPD dapat memperbaikinya?');">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-xl bg-amber-500/10 text-amber-700 hover:bg-amber-500 hover:text-white border border-amber-500/30 transition-all font-label-sm text-xs font-bold flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-outlined text-[17px]">restart_alt</span>
                        Reset Draft
                    </button>
                </form>
                @endif
                <a href="{{ route('ba.show', $transaksi->id) }}" target="_blank" class="px-3 py-2 rounded-xl bg-surface-container-low text-on-surface hover:bg-surface-container border border-outline-variant transition-all font-label-sm text-xs flex items-center gap-1 shadow-sm">
                    <span class="material-symbols-outlined text-[17px]">visibility</span>
                    Lihat BA
                </a>
            </div>
        </div>

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-[24px]">check_circle</span>
                <span class="font-body-md font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-700 p-4 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <span class="material-symbols-outlined text-[20px]">warning</span>
                    <span>Terdapat kendala validasi:</span>
                </div>
                <ul class="list-disc pl-5 text-label-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $selisih = abs($transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir);
            $isBalance = $selisih < 0.01;
        @endphp

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT: 7 Cols - Comparison Data & Difference Analysis -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Status Pengesahan Pilar 2 (Bank Kalsel) -->
                @if($transaksi->bank_status === 'valid')
                <div class="bg-blue-500/10 border border-blue-500/30 text-blue-950 p-4 rounded-2xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-[22px]">verified</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-sm text-blue-900">Pilar 2 Disahkan: Bank Kalsel</h3>
                                <span class="px-2 py-0.5 rounded-full bg-blue-600 text-white font-bold text-[10px] uppercase tracking-wider">Valid</span>
                            </div>
                            <p class="text-xs text-blue-800/80 mt-0.5">
                                Diverifikasi oleh: <span class="font-semibold">{{ $transaksi->bankChecker->name ?? 'Verifikator Bank Kalsel' }}</span> 
                                @if($transaksi->bank_verified_at)
                                    &bull; {{ \Carbon\Carbon::parse($transaksi->bank_verified_at)->format('d/m/Y H:i') }} WITA
                                @endif
                            </p>
                            @if($transaksi->bank_catatan)
                                <p class="text-[11px] text-blue-900 italic mt-1 bg-white/60 p-1.5 rounded-lg border border-blue-200">
                                    "{{ $transaksi->bank_catatan }}"
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($transaksi->tahap_verifikasi === 'menunggu_bank')
                <div class="bg-amber-500/10 border border-amber-500/30 text-amber-950 p-4 rounded-2xl flex items-center gap-3 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <span class="material-symbols-outlined text-[22px]">pending</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-amber-900">Menunggu Pengesahan Bank Kalsel (Pilar 2)</h3>
                        <p class="text-xs text-amber-800/80 mt-0.5">Berkas rekonsiliasi kas ini belum diverifikasi dan disahkan oleh pihak Bank Mitra.</p>
                    </div>
                </div>
                @endif

                <!-- Card Saldo BKU vs Bank -->
                <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="p-5 bg-surface-container-low border-b border-outline-variant flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                            </div>
                            <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Data Saldo Rekonsiliasi</h2>
                        </div>
                        <span class="text-label-sm text-on-surface-variant font-mono">Tahun {{ $transaksi->periode_tahun }}</span>
                    </div>

                    <div class="p-5 space-y-5">
                        <!-- Table Side by Side -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-outline-variant/60 text-[11px] uppercase tracking-wider text-on-surface-variant">
                                        <th class="py-2 px-3">Uraian Transaksi</th>
                                        <th class="py-2 px-3 text-right">BKU Bendahara</th>
                                        <th class="py-2 px-3 text-right">Rekening Bank</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/30 text-body-md font-mono">
                                    <tr>
                                        <td class="py-2.5 px-3 text-on-surface font-sans">Saldo Awal</td>
                                        <td class="py-2.5 px-3 text-right text-on-surface">Rp {{ number_format($transaksi->bku_saldo_awal, 2, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-right text-on-surface">Rp {{ number_format($transaksi->bank_saldo_awal, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 px-3 text-on-surface font-sans">Penerimaan</td>
                                        <td class="py-2.5 px-3 text-right text-emerald-600">+ Rp {{ number_format($transaksi->bku_penerimaan, 2, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-right text-emerald-600">+ Rp {{ number_format($transaksi->bank_penerimaan, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 px-3 text-on-surface font-sans">Pengeluaran</td>
                                        <td class="py-2.5 px-3 text-right text-rose-600">- Rp {{ number_format($transaksi->bku_pengeluaran, 2, ',', '.') }}</td>
                                        <td class="py-2.5 px-3 text-right text-rose-600">- Rp {{ number_format($transaksi->bank_pengeluaran, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-surface-container-lowest font-bold text-on-surface">
                                        <td class="py-3 px-3 font-sans">Saldo Akhir</td>
                                        <td class="py-3 px-3 text-right font-data-tabular">Rp {{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</td>
                                        <td class="py-3 px-3 text-right font-data-tabular">Rp {{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Difference Banner -->
                        @if($isBalance)
                            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow">
                                        <span class="material-symbols-outlined text-[20px]">check</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-emerald-800 text-body-md">Kas Sesuai / Seimbang (Balance)</p>
                                        <p class="text-emerald-700/80 text-label-sm">Saldo BKU cocok 100% dengan Saldo Bank Kalsel.</p>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-emerald-800 text-lg">Rp 0,00</span>
                            </div>
                        @else
                            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-rose-600 text-white flex items-center justify-center shadow">
                                        <span class="material-symbols-outlined text-[20px]">error</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-rose-800 text-body-md">Terdapat Selisih Kas</p>
                                        <p class="text-rose-700/80 text-label-sm">Perbedaan Saldo BKU dengan Rekening Bank:</p>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-rose-700 text-lg">Rp {{ number_format($selisih, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        <!-- Explanation from SKPD -->
                        <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/60">
                            <p class="text-label-sm font-bold text-on-surface-variant uppercase tracking-wide mb-1">Penjelasan / Keterangan Selisih dari SKPD:</p>
                            <p class="text-body-md text-on-surface italic">
                                {{ !empty($transaksi->keterangan_selisih) ? '"' . $transaksi->keterangan_selisih . '"' : 'Tidak ada catatan keterangan selisih yang diisi oleh SKPD.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timeline Riwayat Catatan Konsolidator -->
                <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="p-5 bg-surface-container-low border-b border-outline-variant flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">history_edu</span>
                            </div>
                            <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Riwayat Catatan & Evaluasi ({{ $transaksi->catatans->count() }})</h2>
                        </div>
                    </div>

                    <div class="p-5">
                        @forelse($transaksi->catatans as $index => $c)
                            <div class="relative pl-6 pb-6 last:pb-2 border-l-2 {{ $c->status_pemeriksaan === 'valid' ? 'border-emerald-500' : ($c->status_pemeriksaan === 'reset_draft' ? 'border-amber-500' : 'border-rose-500') }} space-y-1.5">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $c->status_pemeriksaan === 'valid' ? 'bg-emerald-500' : ($c->status_pemeriksaan === 'reset_draft' ? 'bg-amber-500' : 'bg-rose-500') }} ring-4 ring-surface"></div>
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-on-surface text-body-md">{{ $c->user->name ?? 'Petugas' }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase {{ $c->user && $c->user->role === 'admin' ? 'bg-primary/10 text-primary' : 'bg-secondary/10 text-secondary' }}">
                                            {{ $c->user->role ?? 'Petugas' }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-on-surface-variant font-mono">
                                        {{ $c->created_at ? $c->created_at->format('d M Y, H:i') : '-' }} WITA
                                    </span>
                                </div>
                                <div class="bg-surface-container-lowest p-3.5 rounded-xl border border-outline-variant/60">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if($c->status_pemeriksaan === 'valid')
                                            <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[15px]">check_circle</span> Disetujui Valid
                                            </span>
                                        @elseif($c->status_pemeriksaan === 'reset_draft')
                                            <span class="text-xs font-bold text-amber-700 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[15px]">restart_alt</span> Status Di-Reset ke Draft
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-rose-700 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[15px]">error</span> Catatan Koreksi / Kesalahan
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-body-md text-on-surface whitespace-pre-line">{{ $c->catatan }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-4xl text-outline mb-1">chat_bubble_outline</span>
                                <p>Belum ada riwayat catatan pemeriksaan sebelumnya.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- RIGHT: 5 Cols - 4 Bukti Dukung & Action Form -->
            <div class="lg:col-span-5 space-y-6">

                <!-- 4 Bukti Dukung Cards -->
                <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="p-5 bg-surface-container-low border-b border-outline-variant flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">folder_open</span>
                            </div>
                            <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Kelengkapan Bukti Dukung</h2>
                        </div>
                    </div>

                    <div class="p-5 space-y-3.5">
                        @php
                            $docs = [
                                [
                                    'title' => 'Berita Acara Manual',
                                    'sub' => 'Tanda tangan basah instansi',
                                    'field' => 'file_ba_manual',
                                    'icon' => 'description',
                                ],
                                [
                                    'title' => 'Buku Kas Umum (BKU)',
                                    'sub' => 'Penutupan kas bulanan',
                                    'field' => 'file_buku_kas',
                                    'icon' => 'menu_book',
                                ],
                                [
                                    'title' => 'Buku Pembantu Bank',
                                    'sub' => 'Buku rincian bank bendahara',
                                    'field' => 'file_buku_pembantu_bank',
                                    'icon' => 'account_balance',
                                ],
                                [
                                    'title' => 'Rekening Koran Bank',
                                    'sub' => 'Mutasi cetak Bank Kalsel',
                                    'field' => 'file_rekening_koran',
                                    'icon' => 'receipt_long',
                                ],
                            ];
                        @endphp

                        @foreach($docs as $doc)
                            @php
                                $filePath = $transaksi->{$doc['field']};
                                $hasFile = !empty($filePath) && \App\Services\SiReKaStorage::exists($filePath);
                            @endphp
                            <div class="p-3.5 rounded-xl border {{ $hasFile ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-outline-variant bg-surface-container-lowest' }} flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg {{ $hasFile ? 'bg-emerald-500/20 text-emerald-700' : 'bg-outline-variant/30 text-on-surface-variant' }} flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">{{ $doc['icon'] }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-on-surface text-body-md leading-tight">{{ $doc['title'] }}</h4>
                                        <p class="text-[11px] text-on-surface-variant">{{ $doc['sub'] }}</p>
                                    </div>
                                </div>

                                <div>
                                    @if($hasFile)
                                        <a href="{{ \App\Services\SiReKaStorage::url($filePath) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1 transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-[15px]">visibility</span>
                                            Buka
                                        </a>
                                    @else
                                        <span class="px-2 py-1 rounded bg-rose-500/10 text-rose-600 text-[11px] font-bold">
                                            Belum Ada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Keputusan & Catatan Konsolidator -->
                <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="p-5 bg-surface-container-low border-b border-outline-variant flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">fact_check</span>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Keputusan Pemeriksaan</h2>
                    </div>

                    <form action="{{ route('transaksi.pemeriksaan.store', $transaksi->id) }}" method="POST" class="p-5 space-y-5">
                        @csrf

                        <!-- Radio Pilihan Status -->
                        <div>
                            <label class="block text-label-sm font-bold text-on-surface mb-2">Hasil Pemeriksaan Konsolidator:</label>
                            <div class="grid grid-cols-1 gap-2.5">
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant hover:bg-surface-container-lowest cursor-pointer transition-colors">
                                    <input type="radio" name="status_konsolidator" value="valid" {{ old('status_konsolidator', $transaksi->status_konsolidator) === 'valid' ? 'checked' : '' }} class="text-primary focus:ring-primary w-4 h-4">
                                    <div class="flex items-center gap-2 text-emerald-700 font-bold text-body-md">
                                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                        <span>Laporan Sesuai & Valid (Sah)</span>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant hover:bg-surface-container-lowest cursor-pointer transition-colors">
                                    <input type="radio" name="status_konsolidator" value="perlu_perbaikan" {{ old('status_konsolidator', $transaksi->status_konsolidator) === 'perlu_perbaikan' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500 w-4 h-4">
                                    <div class="flex items-center gap-2 text-rose-700 font-bold text-body-md">
                                        <span class="material-symbols-outlined text-[20px]">error</span>
                                        <span>Terdapat Kesalahan / Perlu Perbaikan</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Textarea Catatan -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-label-sm font-bold text-on-surface">Catatan Kesalahan / Catatan Konsolidator:</label>
                                <span class="text-[11px] text-on-surface-variant font-medium">(Bisa multi-catatan/riwayat)</span>
                            </div>
                            <textarea name="catatan" rows="4" maxlength="1000" placeholder="Tuliskan rincian kesalahan saldo, halaman bukti yang buram, atau petunjuk perbaikan bagi SKPD..." class="w-full p-3 text-body-md rounded-xl border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none">{{ old('catatan') }}</textarea>
                            <p class="text-[11px] text-on-surface-variant mt-1">Catatan ini akan otomatis tercatat ke dalam timeline riwayat audit dan tampak pada akun SKPD.</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-1">
                            <button type="submit" name="action" value="save_and_next" class="w-full py-3 px-4 rounded-xl bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container font-label-sm font-bold transition-all flex items-center justify-center gap-2 shadow-md active:scale-95">
                                <span class="material-symbols-outlined text-[20px]">fast_forward</span>
                                <span>Simpan &amp; Lanjut ke BA Berikutnya ⏩</span>
                            </button>
                            <button type="submit" name="action" value="save" class="w-full py-2.5 px-4 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface border border-outline-variant font-label-sm text-xs font-semibold transition-all flex items-center justify-center gap-1.5 active:scale-95">
                                <span class="material-symbols-outlined text-[17px]">save</span>
                                <span>Simpan Saja (Tetap di Halaman Ini)</span>
                            </button>
                        </div>
                    </form>

                    <!-- Tombol Hubungi Admin via WhatsApp jika Perlu Perbaikan -->
                    @if($transaksi->status_konsolidator === 'perlu_perbaikan')
                        @php
                            $pesanWaAdmin = "Halo Admin SiReKa BKAD,\n\nTerdapat laporan rekonsiliasi yang perlu diubah kembali ke status DRAFT untuk diperbaiki ulang oleh SKPD:\n• SKPD: " . ($transaksi->skpd->nama ?? '-') . "\n• Periode: " . $namaBulan[$transaksi->periode_bulan - 1] . " " . $transaksi->periode_tahun . "\n• Rekening: " . ($transaksi->rekening->nomor ?? '-') . " (" . ($transaksi->rekening->bank ?? '-') . ")\n• Catatan Kesalahan Konsolidator:\n\"" . ($transaksi->catatan_konsolidator_terakhir ?? 'Mohon periksa data laporan') . "\"\n\nMohon bantuan Admin Pusat untuk merubah status transaksi ID #" . $transaksi->id . " menjadi DRAFT agar SKPD dapat memperbaikinya. Terima kasih.";
                            
                            $waUrl = !empty($adminWa) 
                                ? 'https://api.whatsapp.com/send?phone=' . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $adminWa)) . '&text=' . urlencode($pesanWaAdmin)
                                : 'https://api.whatsapp.com/send?text=' . urlencode($pesanWaAdmin);
                        @endphp
                        <div class="p-5 border-t border-outline-variant bg-rose-500/5 space-y-3">
                            <div class="flex items-start gap-2.5">
                                <span class="material-symbols-outlined text-rose-600 text-[20px] shrink-0 mt-0.5">contact_support</span>
                                <div class="text-xs text-on-surface">
                                    <p class="font-bold text-rose-800">Langkah Selanjutnya:</p>
                                    <p class="text-on-surface-variant">Hubungi Admin Pusat agar mereset transaksi ini menjadi <strong>Draft</strong> sehingga SKPD dapat memperbaiki data.</p>
                                </div>
                            </div>

                            <a href="{{ $waUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-[#25D366] hover:bg-[#1EBE5D] text-white font-label-sm font-bold transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg active:scale-95">
                                <span class="material-symbols-outlined text-[20px]">chat</span>
                                <span>Hubungi Admin via WhatsApp</span>
                            </a>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
