<x-app-layout>
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface p-6 rounded-2xl border border-outline-variant/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-600/10 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">verified</span>
                </div>
                <div>
                    <h1 class="text-headline-sm font-bold text-on-surface">Pengawasan &amp; Pengesahan Inspektorat</h1>
                    <p class="text-body-sm text-on-surface-variant">Penerbitan Nomor Berita Acara Rekonsiliasi Kas Pemerintah Kota Banjarbaru</p>
                </div>
            </div>

            <!-- Tab Filter Status -->
            <div class="inline-flex p-1 bg-surface-container rounded-xl border border-outline-variant/40">
                <a href="{{ route('verifikasi.inspektorat.index', ['status' => 'menunggu']) }}" 
                    class="px-3.5 py-1.5 rounded-lg text-label-sm font-semibold transition-all flex items-center gap-1.5 {{ $statusFilter === 'menunggu' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span>Menunggu Pengesahan</span>
                    @if($pendingCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $statusFilter === 'menunggu' ? 'bg-white text-primary' : 'bg-amber-500 text-white' }}">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('verifikasi.inspektorat.index', ['status' => 'disetujui']) }}" 
                    class="px-3.5 py-1.5 rounded-lg text-label-sm font-semibold transition-all flex items-center gap-1.5 {{ $statusFilter === 'disetujui' ? 'bg-emerald-600 text-white shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span>BA Terbit Sah</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">{{ $approvedCount }}</span>
                </a>
                <a href="{{ route('verifikasi.inspektorat.index', ['status' => 'revisi']) }}" 
                    class="px-3.5 py-1.5 rounded-lg text-label-sm font-semibold transition-all flex items-center gap-1.5 {{ $statusFilter === 'revisi' ? 'bg-error text-on-error shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span>Catatan Audit</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">{{ $revisiCount }}</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-body-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-800 flex items-center gap-3">
                <span class="material-symbols-outlined">warning</span>
                <span class="text-body-sm font-medium">{{ session('warning') }}</span>
            </div>
        @endif

        <!-- Table Container -->
        <div class="bg-surface rounded-2xl border border-outline-variant/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/60 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">
                            <th class="py-3.5 px-4">SKPD / Unit Kerja</th>
                            <th class="py-3.5 px-4">Periode</th>
                            <th class="py-3.5 px-4">Nomor Berita Acara</th>
                            <th class="py-3.5 px-4 text-center">Status Bank</th>
                            <th class="py-3.5 px-4 text-center">Status Konsolidator</th>
                            <th class="py-3.5 px-4 text-center">Status Inspektorat</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/40 text-body-sm">
                        @forelse($transaksis as $item)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-on-surface">{{ $item->skpd->nama ?? 'SKPD Tidak Ditemukan' }}</div>
                                <div class="text-[11px] font-mono text-on-surface-variant">{{ $item->skpd->kode ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-surface-container font-semibold text-xs text-on-surface">
                                    {{ date('F', mktime(0, 0, 0, $item->periode_bulan, 10)) }} {{ $item->periode_tahun }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->nomor_ba)
                                    <span class="font-mono text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">{{ $item->nomor_ba }}</span>
                                @else
                                    <span class="text-xs text-on-surface-variant italic">Belum Diterbitkan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->bank_status === 'valid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">
                                        <span class="material-symbols-outlined text-[14px]">check</span> Bank Sah
                                    </span>
                                @else
                                    <span class="text-xs text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->status_konsolidator === 'valid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800">
                                        <span class="material-symbols-outlined text-[14px]">check</span> Kasda Sah
                                    </span>
                                @else
                                    <span class="text-xs text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->tahap_verifikasi === 'disetujui_final')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">
                                        <span class="material-symbols-outlined text-[14px]">verified</span>
                                        BA Terbit Sah
                                    </span>
                                @elseif($item->tahap_verifikasi === 'revisi_inspektorat')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700">
                                        <span class="material-symbols-outlined text-[14px]">cancel</span>
                                        Catatan Audit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">
                                        <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                        Perlu Disahkan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('verifikasi.inspektorat.review', $item->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-sm transition-transform active:scale-95">
                                    <span class="material-symbols-outlined text-[16px]">approval</span>
                                    {{ $item->tahap_verifikasi === 'disetujui_final' ? 'Detail Pengesahan' : 'Sahkan & Terbitkan BA' }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center mx-auto mb-3 text-on-surface-variant/40">
                                    <span class="material-symbols-outlined text-[28px]">fact_check</span>
                                </div>
                                <p class="font-medium text-body-md">Tidak ada transaksi yang menunggu pengesahan Inspektorat.</p>
                                <p class="text-xs text-on-surface-variant/70 mt-1">Transaksi akan muncul setelah berhasil diverifikasi oleh Bank Kalsel dan Konsolidator BPKAD.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transaksis->hasPages())
                <div class="p-4 border-t border-outline-variant/40">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
