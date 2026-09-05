<x-app-layout>
<style>
    #appMain { max-width: 100% !important; }
</style>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Data Transaksi Rekonsiliasi</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola data input rekonsiliasi bulanan SKPD.</p>
            </div>
            @if(in_array(Auth::user()->role, ['admin', 'operator']))
            <a href="{{ route('transaksi.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded flex items-center space-x-2 hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm self-start md:self-auto font-label-sm text-label-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Input Transaksi Baru</span>
            </a>
            @endif
        </div>
        
        <!-- Filters -->
        <form action="{{ route('transaksi.index') }}" method="GET" class="bg-surface p-4 rounded border border-outline-variant shadow-sm flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-body-md font-bold text-on-surface mb-1">Cari SKPD / Rekening</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama SKPD atau rekening..." class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
            </div>
            <div class="w-full sm:w-48">
                <label class="block font-body-md font-bold text-on-surface mb-1">Bulan</label>
                <select name="bulan" class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ $namaBulan[$i - 1] }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-10 px-4 border border-outline-variant rounded bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-sm text-label-sm flex items-center space-x-2">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request('search') || request('bulan'))
                <a href="{{ route('transaksi.index') }}" class="h-10 px-4 border border-outline-variant rounded bg-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm flex items-center space-x-2 text-on-surface-variant">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
        
        <!-- Table Data -->
        <div class="bg-surface rounded border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Periode</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">SKPD</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Rekening</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-right">Saldo BKU</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-right">Saldo Bank</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Status</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Dokumen</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transaksis as $trx)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ str_pad($trx->periode_bulan, 2, '0', STR_PAD_LEFT) }} / {{ $trx->periode_tahun }}
                            </td>
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ $trx->skpd->nama ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ $trx->rekening->nama ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-data-tabular text-on-surface text-right">
                                Rp {{ number_format($trx->bku_saldo_akhir, 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 font-data-tabular text-on-surface text-right">
                                Rp {{ number_format($trx->bank_saldo_akhir, 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="space-y-1.5">
                                    <!-- Tahapan Verifikasi 4-Pilar -->
                                    <div>
                                        @if($trx->tahap_verifikasi === 'disetujui_final')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-label-sm text-[11px] font-black border border-emerald-300">
                                                <span class="material-symbols-outlined text-[13px]">verified</span> SAH FINAL (BA TERBIT)
                                            </span>
                                            @if($trx->nomor_ba)
                                                <div class="text-[10px] font-mono text-emerald-800 font-bold mt-0.5">No: {{ $trx->nomor_ba }}</div>
                                            @endif
                                        @elseif($trx->tahap_verifikasi === 'menunggu_inspektorat')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-label-sm text-[10px] font-bold border border-amber-300">
                                                <span class="material-symbols-outlined text-[13px]">hourglass_top</span> 4. Cek Inspektorat
                                            </span>
                                        @elseif($trx->tahap_verifikasi === 'revisi_inspektorat')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-rose-100 text-rose-800 font-label-sm text-[10px] font-bold">
                                                <span class="material-symbols-outlined text-[13px]">error</span> 4. Catatan Inspektorat
                                            </span>
                                        @elseif($trx->tahap_verifikasi === 'menunggu_konsolidator')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 font-label-sm text-[10px] font-bold border border-purple-300">
                                                <span class="material-symbols-outlined text-[13px]">checklist</span> 3. Cek Konsolidator
                                            </span>
                                        @elseif($trx->tahap_verifikasi === 'revisi_konsolidator' || $trx->status_konsolidator === 'perlu_perbaikan')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-rose-100 text-rose-800 font-label-sm text-[10px] font-bold">
                                                <span class="material-symbols-outlined text-[13px]">cancel</span> 3. Revisi Kasda
                                            </span>
                                        @elseif($trx->tahap_verifikasi === 'menunggu_bank')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 font-label-sm text-[10px] font-bold border border-blue-300">
                                                <span class="material-symbols-outlined text-[13px]">account_balance</span> 2. Cek Bank Kalsel
                                            </span>
                                        @elseif($trx->tahap_verifikasi === 'revisi_bank' || $trx->bank_status === 'revisi')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-rose-100 text-rose-800 font-label-sm text-[10px] font-bold">
                                                <span class="material-symbols-outlined text-[13px]">error</span> 2. Revisi Bank
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-label-sm text-[10px] font-medium border border-slate-300">
                                                <span class="material-symbols-outlined text-[13px]">edit_note</span> 1. Draf SKPD
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Catatan Terakhir -->
                                    @if($trx->bank_catatan && in_array($trx->tahap_verifikasi, ['revisi_bank']))
                                        <div class="text-[10px] text-rose-700 font-medium italic p-1 bg-rose-50 rounded border border-rose-200">
                                            <b>Bank:</b> {{ \Illuminate\Support\Str::limit($trx->bank_catatan, 35) }}
                                        </div>
                                    @elseif($trx->catatan_konsolidator_terakhir && in_array($trx->tahap_verifikasi, ['revisi_konsolidator']))
                                        <div class="text-[10px] text-rose-700 font-medium italic p-1 bg-rose-50 rounded border border-rose-200">
                                            <b>Kasda:</b> {{ \Illuminate\Support\Str::limit($trx->catatan_konsolidator_terakhir, 35) }}
                                        </div>
                                    @elseif($trx->inspektorat_catatan && in_array($trx->tahap_verifikasi, ['revisi_inspektorat']))
                                        <div class="text-[10px] text-rose-700 font-medium italic p-1 bg-rose-50 rounded border border-rose-200">
                                            <b>Audit:</b> {{ \Illuminate\Support\Str::limit($trx->inspektorat_catatan, 35) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $docCount = 0;
                                    if($trx->file_ba_manual) $docCount++;
                                    if($trx->file_buku_kas) $docCount++;
                                    if($trx->file_buku_pembantu_bank) $docCount++;
                                    if($trx->file_rekening_koran) $docCount++;
                                @endphp
                                <a href="{{ route('transaksi.upload', $trx->id) }}" class="inline-flex items-center gap-1 {{ $docCount >= 4 ? 'text-emerald-600 font-bold' : ($docCount > 0 ? 'text-secondary hover:text-secondary-container' : 'text-primary hover:text-primary-container') }} transition-colors text-label-sm font-label-sm" title="Lihat / Kelola Berkas Dokumen">
                                    <span class="material-symbols-outlined text-[18px]">folder_open</span>
                                    <span>{{ $docCount }}/4</span>
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Tombol Kirim ke Bank untuk Operator jika masih draft atau setelah revisi -->
                                    @if(Auth::user()->role === 'operator' && in_array($trx->tahap_verifikasi, ['skpd_draft', 'revisi_bank', 'revisi_konsolidator']))
                                    <form action="{{ route('transaksi.submit-bank', $trx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Kirimkan berkas rekonsiliasi kas ini ke pihak Bank Kalsel untuk diverifikasi?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-xs font-bold transition-all shadow-xs gap-1" title="Kirim ke Bank Kalsel">
                                            <span class="material-symbols-outlined text-[16px]">send</span>
                                            Kirim Bank
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Tombol Unduh Berita Acara (Resmi jika final, Draft jika belum) -->
                                    <a href="{{ route('ba.pdf', $trx->id) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $trx->tahap_verifikasi === 'disetujui_final' ? 'bg-emerald-600 text-white' : 'bg-surface-container text-on-surface hover:bg-primary hover:text-white' }} transition-all" title="{{ $trx->tahap_verifikasi === 'disetujui_final' ? 'Cetak Berita Acara Rekonsiliasi Resmi (PDF)' : 'Cetak Draft Berita Acara (Watermark Draft)' }}">
                                        <span class="material-symbols-outlined text-[18px]">description</span>
                                    </a>

                                    <!-- Tombol Unduh Tanda Bukti Digital 4-Pilar -->
                                    @if(in_array($trx->tahap_verifikasi, ['disetujui_final', 'menunggu_inspektorat']) || $trx->status_konsolidator === 'valid')
                                    <a href="{{ route('transaksi.bukti-digital-pdf', $trx->id) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all" title="Unduh Lembar Bukti Verifikasi Digital (PDF)">
                                        <span class="material-symbols-outlined text-[18px]">verified</span>
                                    </a>
                                    @endif

                                    <!-- Tombol Reset ke Draft Khusus Admin -->
                                    @if(Auth::user()->role === 'admin' && $trx->tahap_verifikasi !== 'skpd_draft')
                                    <form action="{{ route('transaksi.reset-draft', $trx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Kembalikan transaksi {{ $trx->skpd->nama ?? '' }} ke status DRAFT?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 hover:bg-amber-500 hover:text-white transition-all" title="Kembalikan ke Draft">
                                            <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Tombol Edit & Hapus untuk Operator / Admin -->
                                    @if(in_array(Auth::user()->role, ['admin', 'operator']))
                                        @if(in_array($trx->tahap_verifikasi, ['skpd_draft', 'revisi_bank', 'revisi_konsolidator']) || Auth::user()->role === 'admin')
                                        <a href="{{ route('transaksi.edit', $trx->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-primary hover:bg-primary/10 transition-colors" title="Edit Transaksi">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" class="inline-block form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-error hover:bg-error/10 transition-colors" title="Hapus Transaksi">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                        @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-on-surface-variant/40" title="Terkunci (Sedang dalam proses verifikasi)">
                                            <span class="material-symbols-outlined text-[18px]">lock</span>
                                        </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-on-surface-variant">Belum ada data Transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="border-t border-outline-variant p-4 bg-surface-container-lowest">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
