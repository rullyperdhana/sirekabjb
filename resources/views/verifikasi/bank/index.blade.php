<x-app-layout>
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface p-6 rounded-2xl border border-outline-variant/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">account_balance</span>
                </div>
                <div>
                    <h1 class="text-headline-sm font-bold text-on-surface">Antrean Verifikasi Pihak Bank</h1>
                    <p class="text-body-sm text-on-surface-variant">Pencocokan Nilai Saldo & Mutasi Rekening Koran Bank Kalsel Cabang Banjarbaru</p>
                </div>
            </div>

            <!-- Tab Filter Status -->
            <div class="inline-flex p-1 bg-surface-container rounded-xl border border-outline-variant/40">
                <a href="{{ route('verifikasi.bank.index', ['status' => 'menunggu']) }}" 
                    class="px-3.5 py-1.5 rounded-lg text-label-sm font-semibold transition-all flex items-center gap-1.5 {{ $statusFilter === 'menunggu' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span>Menunggu</span>
                    @if($pendingCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $statusFilter === 'menunggu' ? 'bg-white text-primary' : 'bg-amber-500 text-white' }}">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('verifikasi.bank.index', ['status' => 'valid']) }}" 
                    class="px-3.5 py-1.5 rounded-lg text-label-sm font-semibold transition-all flex items-center gap-1.5 {{ $statusFilter === 'valid' ? 'bg-emerald-600 text-white shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span>Disahkan</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">{{ $validCount }}</span>
                </a>
                <a href="{{ route('verifikasi.bank.index', ['status' => 'revisi']) }}" 
                    class="px-3.5 py-1.5 rounded-lg text-label-sm font-semibold transition-all flex items-center gap-1.5 {{ $statusFilter === 'revisi' ? 'bg-error text-on-error shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span>Revisi</span>
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
                            <th class="py-3.5 px-4">Rekening Bank</th>
                            <th class="py-3.5 px-4 text-center">Periode</th>
                            <th class="py-3.5 px-4 text-right">Saldo Akhir Bank</th>
                            <th class="py-3.5 px-4 text-center">File Koran</th>
                            <th class="py-3.5 px-4 text-center">Status Bank</th>
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
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-on-surface">{{ $item->rekening->nama ?? 'Rekening Kas' }}</div>
                                <div class="text-[11px] font-mono text-primary font-semibold">{{ $item->rekening->nomor ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-surface-container font-semibold text-xs text-on-surface">
                                    {{ date('F', mktime(0, 0, 0, $item->periode_bulan, 10)) }} {{ $item->periode_tahun }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-on-surface">
                                Rp {{ number_format($item->bank_saldo_akhir, 2, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->file_rekening_koran)
                                    <a href="{{ asset('storage/' . $item->file_rekening_koran) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 px-2 py-1 rounded border border-blue-200">
                                        <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-[11px] text-on-surface-variant/60 italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->bank_status === 'valid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        Telah Disahkan
                                    </span>
                                @elseif($item->bank_status === 'revisi')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700">
                                        <span class="material-symbols-outlined text-[14px]">cancel</span>
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">
                                        <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                        Menunggu Cek
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('verifikasi.bank.review', $item->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-transform active:scale-95">
                                    <span class="material-symbols-outlined text-[16px]">fact_check</span>
                                    Periksa Saldo
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center mx-auto mb-3 text-on-surface-variant/40">
                                    <span class="material-symbols-outlined text-[28px]">inbox</span>
                                </div>
                                <p class="font-medium text-body-md">Tidak ada rekonsiliasi kas pada antrean ini.</p>
                                <p class="text-xs text-on-surface-variant/70 mt-1">Saat operator SKPD mengajukan berkas rekonsiliasi, daftar akan otomatis tampil di sini.</p>
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
