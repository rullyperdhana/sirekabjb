<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Top Nav & Breadcrumb -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('verifikasi.inspektorat.index') }}" class="w-9 h-9 rounded-xl bg-surface border border-outline-variant/60 flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-headline-sm font-bold text-on-surface">Pengesahan Akhir Berita Acara</h1>
                    <p class="text-body-sm text-on-surface-variant">{{ $transaksi->skpd->nama }} &bull; Periode {{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }}</p>
                </div>
            </div>

            <!-- Status Badge -->
            <div>
                @if($transaksi->tahap_verifikasi === 'disetujui_final')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-300">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        BA Terbit &amp; Disahkan Sah
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                        <span class="material-symbols-outlined text-[16px]">pending_actions</span>
                        Menunggu Penetapan &amp; Pengesahan BA
                    </span>
                @endif
            </div>
        </div>

        <!-- Ringkasan Jejak Verifikasi 2 Pilar Sebelumnya (Bank & Konsolidator) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Pilar 2: Bank Kalsel -->
            <div class="p-5 rounded-2xl bg-blue-50/70 border border-blue-200/80 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-bold text-blue-900 text-sm">
                        <span class="material-symbols-outlined text-blue-600 text-[20px]">account_balance</span>
                        Pilar 2: Pengesahan Bank Kalsel
                    </div>
                    @if($transaksi->bank_status === 'valid')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-600 text-white">VALID</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white">{{ strtoupper($transaksi->bank_status) }}</span>
                    @endif
                </div>
                <div class="text-xs text-blue-950">
                    <div><b>Verifikator:</b> {{ $transaksi->bankChecker->name ?? 'Petugas Bank' }}</div>
                    <div><b>Waktu:</b> {{ $transaksi->bank_verified_at ? \Carbon\Carbon::parse($transaksi->bank_verified_at)->format('d/m/Y H:i') : '-' }}</div>
                    <div class="mt-1 p-2 bg-white/80 rounded border border-blue-200 font-mono text-[11px]">
                        {{ $transaksi->bank_catatan ?? 'Telah dicocokkan dengan core banking.' }}
                    </div>
                </div>
            </div>

            <!-- Pilar 3: Konsolidator BPKAD -->
            <div class="p-5 rounded-2xl bg-purple-50/70 border border-purple-200/80 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-bold text-purple-900 text-sm">
                        <span class="material-symbols-outlined text-purple-600 text-[20px]">checklist</span>
                        Pilar 3: Konsolidator BPKAD
                    </div>
                    @if($transaksi->status_konsolidator === 'valid')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-600 text-white">LENGKAP &amp; SAH</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white">{{ strtoupper($transaksi->status_konsolidator) }}</span>
                    @endif
                </div>
                <div class="text-xs text-purple-950">
                    <div><b>Konsolidator:</b> {{ $transaksi->checker->name ?? 'Petugas Kasda' }}</div>
                    <div><b>Waktu:</b> {{ $transaksi->checked_at ? \Carbon\Carbon::parse($transaksi->checked_at)->format('d/m/Y H:i') : '-' }}</div>
                    <div class="mt-1 p-2 bg-white/80 rounded border border-purple-200 font-mono text-[11px]">
                        {{ $transaksi->catatan_konsolidator_terakhir ?? 'Dokumen fisik & mutasi kasda lengkap dan valid.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Perbandingan Keuangan SIPANDA vs Bank -->
        <div class="p-6 rounded-2xl bg-surface border border-outline-variant/60 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40">
                <h2 class="text-body-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-600">balance</span>
                    Kesesuaian Angka Buku Kas (SIPANDA) vs Saldo Bank Kalsel
                </h2>
                <span class="font-mono text-xs px-2.5 py-1 rounded bg-surface-container text-on-surface">Rek. {{ $transaksi->rekening->nomor }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Sisi BKU SIPANDA -->
                <div class="p-4 rounded-xl bg-surface-container space-y-2 text-xs">
                    <div class="font-bold text-primary uppercase tracking-wider text-[11px]">Buku Kas Umum (BKU) SIPANDA</div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/30">
                        <span>Saldo Awal BKU:</span>
                        <span class="font-mono font-bold">Rp {{ number_format($transaksi->bku_saldo_awal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/30">
                        <span>Penerimaan BKU:</span>
                        <span class="font-mono font-bold text-emerald-600">Rp {{ number_format($transaksi->bku_penerimaan, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/30">
                        <span>Pengeluaran BKU:</span>
                        <span class="font-mono font-bold text-rose-600">Rp {{ number_format($transaksi->bku_pengeluaran, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 font-bold text-body-md pt-2">
                        <span>Saldo Akhir BKU:</span>
                        <span class="font-mono text-primary">Rp {{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Sisi Bank Kalsel -->
                <div class="p-4 rounded-xl bg-surface-container space-y-2 text-xs">
                    <div class="font-bold text-blue-700 uppercase tracking-wider text-[11px]">Rekening Koran Bank Kalsel</div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/30">
                        <span>Saldo Awal Bank:</span>
                        <span class="font-mono font-bold">Rp {{ number_format($transaksi->bank_saldo_awal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/30">
                        <span>Penerimaan Bank:</span>
                        <span class="font-mono font-bold text-emerald-600">Rp {{ number_format($transaksi->bank_penerimaan, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/30">
                        <span>Pengeluaran Bank:</span>
                        <span class="font-mono font-bold text-rose-600">Rp {{ number_format($transaksi->bank_pengeluaran, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 font-bold text-body-md pt-2">
                        <span>Saldo Akhir Bank:</span>
                        <span class="font-mono text-blue-700">Rp {{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Selisih -->
            @php $selisih = abs($transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir); @endphp
            <div class="p-3 rounded-xl flex items-center justify-between {{ $selisih == 0 ? 'bg-emerald-500/10 text-emerald-800 border border-emerald-500/30' : 'bg-amber-500/10 text-amber-800 border border-amber-500/30' }}">
                <span class="text-xs font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">{{ $selisih == 0 ? 'check_circle' : 'info' }}</span>
                    {{ $selisih == 0 ? 'Saldo BKU dan Saldo Bank Nihil Selisih (Klop / Match)' : 'Terdapat Selisih Saldo Sebesar: Rp ' . number_format($selisih, 2, ',', '.') }}
                </span>
                @if($transaksi->keterangan_selisih)
                    <span class="text-xs font-mono italic">"{{ $transaksi->keterangan_selisih }}"</span>
                @endif
            </div>
        </div>

        <!-- Form Penetapan Nomor BA & Pengesahan Inspektorat -->
        <div class="p-6 rounded-2xl bg-surface border border-outline-variant/60 shadow-sm space-y-4">
            <h2 class="text-body-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600">history_edu</span>
                Penerbitan Nomor &amp; Pengesahan Berita Acara Final
            </h2>
            <p class="text-body-sm text-on-surface-variant">Dengan menekan tombol pengesahan, Nomor Berita Acara resmi akan diterbitkan ke dalam sistem, mengunci seluruh mutasi kas, dan membuka akses unduh dokumen sah bagi SKPD.</p>

            <form action="{{ route('verifikasi.inspektorat.approve', $transaksi->id) }}" method="POST" class="space-y-4 pt-2">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nomor_ba" class="block text-xs font-bold text-on-surface mb-1">
                            Nomor Berita Acara Resmi <span class="text-error">*</span>
                        </label>
                        <input type="text" id="nomor_ba" name="nomor_ba" required
                            value="{{ old('nomor_ba', $suggestedNomorBa) }}"
                            class="w-full h-11 px-3 text-sm font-mono font-bold rounded-xl border border-outline-variant bg-surface focus:outline-none focus:ring-2 focus:ring-amber-500 text-on-surface" />
                        <p class="text-[11px] text-on-surface-variant mt-1">Nomor otomatis terisi dari format setting BPKAD, dapat diedit jika ada penomoran khusus dari Inspektorat.</p>
                    </div>

                    <div>
                        <label for="catatan" class="block text-xs font-bold text-on-surface mb-1">
                            Catatan Pengawasan Inspektorat (Opsional)
                        </label>
                        <input type="text" id="catatan" name="catatan" 
                            value="{{ old('catatan', $transaksi->inspektorat_catatan ?? 'Telah melalui pengawasan internal dan dinyatakan SAH & TUNTAS.') }}"
                            class="w-full h-11 px-3 text-sm rounded-xl border border-outline-variant bg-surface focus:outline-none focus:ring-2 focus:ring-amber-500 text-on-surface" />
                        <p class="text-[11px] text-on-surface-variant mt-1">Akan tertera pada Lembar Bukti Verifikasi Digital 4-Pilar.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 pt-3">
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menerbitkan dan mengesahkan Berita Acara ini secara resmi?')" 
                        class="w-full sm:w-auto flex-1 py-3 px-6 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-md transition-transform active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">verified</span>
                        TERBITKAN &amp; SAHKAN BERITA ACARA RESMI
                    </button>

                    <!-- Tombol Preview BA Draft -->
                    <a href="{{ route('ba.pdf', $transaksi->id) }}" target="_blank" 
                        class="w-full sm:w-auto py-3 px-4 rounded-xl bg-surface border border-outline-variant/60 text-on-surface font-semibold text-sm hover:bg-surface-container transition-all flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                        Pratinjau Draft BA
                    </a>
                </div>
            </form>

            <!-- Opsi Kembalikan Revisi -->
            <div class="pt-4 border-t border-outline-variant/40">
                <details class="group">
                    <summary class="cursor-pointer text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center gap-1.5 select-none">
                        <span class="material-symbols-outlined text-[18px] group-open:rotate-90 transition-transform">chevron_right</span>
                        Kembalikan Berkas (Catatan Pengawasan Khusus)
                    </summary>
                    <form action="{{ route('verifikasi.inspektorat.revisi', $transaksi->id) }}" method="POST" class="mt-3 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 space-y-3">
                        @csrf
                        <label for="catatan_revisi_inspektorat" class="block text-xs font-bold text-rose-900">Catatan Temuan / Perbaikan dari Inspektorat:</label>
                        <textarea id="catatan_revisi_inspektorat" name="catatan" rows="2" required placeholder="Tuliskan catatan audit internal jika ada data yang perlu dikoreksi..." 
                            class="w-full p-2.5 text-xs rounded-lg border border-rose-300 bg-white focus:outline-none focus:ring-2 focus:ring-rose-500 resize-none"></textarea>
                        
                        <button type="submit" onclick="return confirm('Kembalikan transaksi ini dengan catatan audit internal?')" 
                            class="py-2 px-4 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow transition-transform active:scale-95 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">reply</span>
                            KEMBALIKAN KE KONSOLIDATOR / SKPD
                        </button>
                    </form>
                </details>
            </div>
        </div>

        <!-- Riwayat Log Audit Verifikasi -->
        @if($transaksi->verifikasiLogs && $transaksi->verifikasiLogs->count() > 0)
        <div class="p-6 rounded-2xl bg-surface border border-outline-variant/60 shadow-sm space-y-3">
            <h3 class="text-body-md font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Rekam Jejak Audit Verifikasi Lengkap (4-Pilar)
            </h3>
            <div class="divide-y divide-outline-variant/40 text-xs">
                @foreach($transaksi->verifikasiLogs as $log)
                <div class="py-2.5 flex items-start justify-between gap-4">
                    <div>
                        <span class="font-bold text-on-surface uppercase">{{ $log->role }}</span> &bull; 
                        <span class="font-medium text-primary">{{ $log->aksi }}</span>
                        <div class="text-on-surface-variant mt-0.5">{{ $log->catatan }}</div>
                        <div class="text-[10px] text-on-surface-variant/60 font-mono mt-0.5">Hash Seal: {{ $log->trace_hash }}</div>
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
