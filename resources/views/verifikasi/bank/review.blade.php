<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Top Nav & Breadcrumb -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('verifikasi.bank.index') }}" class="w-9 h-9 rounded-xl bg-surface border border-outline-variant/60 flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-headline-sm font-bold text-on-surface">Pemeriksaan Rekening Koran</h1>
                    <p class="text-body-sm text-on-surface-variant">{{ $transaksi->skpd->nama }} &bull; Periode {{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }}</p>
                </div>
            </div>

            <!-- Status Badge -->
            <div>
                @if($transaksi->bank_status === 'valid')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-300">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        Telah Disahkan Bank
                    </span>
                @elseif($transaksi->bank_status === 'revisi')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-300">
                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                        Catatan Revisi Bank
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-300">
                        <span class="material-symbols-outlined text-[16px]">hourglass_top</span>
                        Menunggu Verifikasi Bank
                    </span>
                @endif
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-surface border border-outline-variant/60">
                <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">SKPD Pengaju</div>
                <div class="font-bold text-on-surface text-body-md mt-1">{{ $transaksi->skpd->nama }}</div>
                <div class="text-xs text-on-surface-variant font-mono mt-0.5">Kode: {{ $transaksi->skpd->kode }}</div>
            </div>

            <div class="p-4 rounded-xl bg-surface border border-outline-variant/60">
                <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Nomor Rekening Bank</div>
                <div class="font-bold text-blue-600 font-mono text-body-md mt-1">{{ $transaksi->rekening->nomor }}</div>
                <div class="text-xs text-on-surface-variant mt-0.5">{{ $transaksi->rekening->nama }} &bull; {{ $transaksi->rekening->bank }}</div>
            </div>

            <div class="p-4 rounded-xl bg-surface border border-outline-variant/60">
                <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Petugas Penginput SKPD</div>
                <div class="font-bold text-on-surface text-body-md mt-1">{{ $transaksi->user->name ?? '-' }}</div>
                <div class="text-xs text-on-surface-variant mt-0.5">{{ $transaksi->user->email ?? '-' }} &bull; Diinput: {{ $transaksi->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <!-- Perbandingan Saldo & Mutasi Bank -->
        <div class="p-6 rounded-2xl bg-surface border border-outline-variant/60 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600">account_balance_wallet</span>
                    <h2 class="text-body-lg font-bold text-on-surface">Data Saldo &amp; Mutasi Kas Bank Kalsel</h2>
                </div>
                <span class="text-xs text-on-surface-variant">Data inputan dari Bendahara SKPD</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-surface-container">
                    <div class="text-xs text-on-surface-variant font-medium">Saldo Awal Bank</div>
                    <div class="text-body-lg font-mono font-bold text-on-surface mt-1">
                        Rp {{ number_format($transaksi->bank_saldo_awal, 2, ',', '.') }}
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-surface-container">
                    <div class="text-xs text-emerald-700 font-medium">Penerimaan Bank</div>
                    <div class="text-body-lg font-mono font-bold text-emerald-700 mt-1">
                        Rp {{ number_format($transaksi->bank_penerimaan, 2, ',', '.') }}
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-surface-container">
                    <div class="text-xs text-rose-700 font-medium">Pengeluaran Bank</div>
                    <div class="text-body-lg font-mono font-bold text-rose-700 mt-1">
                        Rp {{ number_format($transaksi->bank_pengeluaran, 2, ',', '.') }}
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
                    <div class="text-xs text-blue-800 font-bold">Saldo Akhir Bank</div>
                    <div class="text-headline-sm font-mono font-black text-blue-900 mt-1">
                        Rp {{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            @if($transaksi->keterangan_selisih)
            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-900 text-xs">
                <b>Catatan Keterangan Selisih dari SKPD:</b><br>
                <p class="mt-1 font-mono leading-relaxed">{{ $transaksi->keterangan_selisih }}</p>
            </div>
            @endif
        </div>

        <!-- Berkas Rekening Koran Fisik -->
        <div class="p-6 rounded-2xl bg-surface border border-outline-variant/60 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">description</span>
                    <h2 class="text-body-lg font-bold text-on-surface">Berkas Rekening Koran Asli Bank</h2>
                </div>
                @if($transaksi->file_rekening_koran)
                    <a href="{{ asset('storage/' . $transaksi->file_rekening_koran) }}" target="_blank" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        Buka di Tab Baru
                    </a>
                @endif
            </div>

            @if($transaksi->file_rekening_koran)
                <div class="aspect-[16/9] w-full rounded-xl overflow-hidden border border-outline-variant/40 bg-surface-container">
                    <iframe src="{{ asset('storage/' . $transaksi->file_rekening_koran) }}" class="w-full h-full"></iframe>
                </div>
            @else
                <div class="py-10 text-center text-on-surface-variant bg-surface-container rounded-xl">
                    <span class="material-symbols-outlined text-[36px] text-amber-500 mb-2">warning</span>
                    <p class="font-bold">Operator SKPD belum mengunggah file rekening koran untuk transaksi ini.</p>
                </div>
            @endif
        </div>

        <!-- Form Aksi Verifikasi Pihak Bank -->
        <div class="p-6 rounded-2xl bg-surface border border-outline-variant/60 shadow-sm space-y-4">
            <h2 class="text-body-lg font-bold text-on-surface">Keputusan Verifikasi Bank Kalsel</h2>
            <p class="text-body-sm text-on-surface-variant">Silakan berikan pengesahan jika nilai saldo dan mutasi pada aplikasi sesuai dengan core banking, atau kembalikan dengan catatan revisi jika ada ketidakcocokan.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <!-- Box Sahkan -->
                <form action="{{ route('verifikasi.bank.approve', $transaksi->id) }}" method="POST" class="p-5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex flex-col justify-between space-y-4">
                    @csrf
                    <div>
                        <div class="flex items-center gap-2 font-bold text-emerald-800 text-body-md">
                            <span class="material-symbols-outlined text-emerald-600">verified</span>
                            Sahkan Saldo &amp; Mutasi Bank
                        </div>
                        <p class="text-xs text-emerald-700 mt-1">Data rekening koran valid dan sesuai. Status akan berlanjut ke <b>Pilar 3: Konsolidator BPKAD</b>.</p>
                        
                        <label for="catatan_setuju" class="block text-xs font-semibold text-emerald-900 mt-3 mb-1">Catatan Tambahan Bank (Opsional):</label>
                        <input type="text" id="catatan_setuju" name="catatan" value="Nilai saldo dan mutasi rekening koran telah dicocokkan dan sesuai dengan core banking Bank Kalsel." 
                            class="w-full h-10 px-3 text-xs rounded-lg border border-emerald-300 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>

                    <button type="submit" onclick="return confirm('Apakah Anda yakin data saldo rekening koran ini telah diverifikasi dan sah?')" 
                        class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-transform active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check</span>
                        SAHKAN SALDO BANK KALSEL
                    </button>
                </form>

                <!-- Box Revisi -->
                <form action="{{ route('verifikasi.bank.revisi', $transaksi->id) }}" method="POST" class="p-5 rounded-xl bg-rose-500/10 border border-rose-500/30 flex flex-col justify-between space-y-4">
                    @csrf
                    <div>
                        <div class="flex items-center gap-2 font-bold text-rose-800 text-body-md">
                            <span class="material-symbols-outlined text-rose-600">cancel</span>
                            Kembalikan (Perlu Revisi SKPD)
                        </div>
                        <p class="text-xs text-rose-700 mt-1">Terdapat selisih nominal saldo, mutasi belum tercatat, atau file rekening koran tidak terbaca.</p>
                        
                        <label for="catatan_revisi" class="block text-xs font-semibold text-rose-900 mt-3 mb-1">Catatan Revisi Bank (Wajib):</label>
                        <textarea id="catatan_revisi" name="catatan" rows="2" required placeholder="Jelaskan butir selisih atau kesalahan rekening koran yang harus diperbaiki operator SKPD..." 
                            class="w-full p-2.5 text-xs rounded-lg border border-rose-300 bg-white focus:outline-none focus:ring-2 focus:ring-rose-500 resize-none"></textarea>
                    </div>

                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengembalikan berkas ini ke SKPD untuk revisi?')" 
                        class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-md transition-transform active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">reply</span>
                        KEMBALIKAN KE SKPD (REVISI)
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Log Audit Verifikasi -->
        @if($transaksi->verifikasiLogs && $transaksi->verifikasiLogs->count() > 0)
        <div class="p-6 rounded-2xl bg-surface border border-outline-variant/60 shadow-sm space-y-3">
            <h3 class="text-body-md font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Rekam Jejak Audit Verifikasi (Audit Trail)
            </h3>
            <div class="divide-y divide-outline-variant/40 text-xs">
                @foreach($transaksi->verifikasiLogs as $log)
                <div class="py-2.5 flex items-start justify-between gap-4">
                    <div>
                        <span class="font-bold text-on-surface uppercase">{{ $log->role }}</span> &bull; 
                        <span class="font-medium text-primary">{{ $log->aksi }}</span>
                        <div class="text-on-surface-variant mt-0.5">{{ $log->catatan }}</div>
                        <div class="text-[10px] text-on-surface-variant/60 font-mono mt-0.5">Hash Seal: {{ substr($log->trace_hash, 0, 16) }}...</div>
                    </div>
                    <div class="text-right text-[11px] text-on-surface-variant whitespace-nowrap">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
