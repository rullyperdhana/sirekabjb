<x-app-layout>
<style>
    #appMain { max-width: 100% !important; }
</style>
    <div class="space-y-6" x-data="{ modalOpen: false, modalUrl: '', modalTitle: '', bulkZipOpen: false }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Arsip Dokumen SKPD</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Struktur hirarki dokumen rekonsiliasi tahun {{ $tahunAktif }}.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
                <a href="{{ route('dokumen.tree.excel') }}" class="bg-[#107c41] text-white font-bold px-3 py-2 rounded flex items-center space-x-1.5 hover:bg-[#0c5e31] transition-colors shadow-md font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[18px]">table</span>
                    <span>Unduh Excel</span>
                </a>
                <a href="{{ route('dokumen.tree.pdf') }}" target="_blank" class="bg-rose-600 text-white font-bold px-3 py-2 rounded flex items-center space-x-1.5 hover:bg-rose-700 transition-colors shadow-md font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    <span>Cetak PDF</span>
                </a>
                <button type="button" @click="bulkZipOpen = true" class="bg-amber-500 text-slate-950 font-bold px-3.5 py-2 rounded flex items-center space-x-1.5 hover:bg-amber-400 transition-colors shadow-md font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[18px]" data-weight="fill">archive</span>
                    <span>Unduh Paket Audit BPK (.ZIP)</span>
                </button>
                <button type="button" @click="$dispatch('expand-all')" class="bg-surface text-on-surface border border-outline-variant px-3 py-2 rounded flex items-center space-x-1 hover:bg-surface-container-low transition-colors shadow-sm font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[18px]">unfold_more</span>
                    <span>Buka Semua</span>
                </button>
                <button type="button" @click="$dispatch('collapse-all')" class="bg-surface text-on-surface border border-outline-variant px-3 py-2 rounded flex items-center space-x-1 hover:bg-surface-container-low transition-colors shadow-sm font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[18px]">unfold_less</span>
                    <span>Tutup Semua</span>
                </button>
                <a href="{{ route('transaksi.index') }}" class="bg-surface text-on-surface border border-outline-variant px-3 py-2 rounded flex items-center space-x-1 hover:bg-surface-container-low transition-colors shadow-sm font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[18px]">table_rows</span>
                    <span>Tampilan Tabel</span>
                </a>
            </div>
        </div>

        @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-rose-600" data-weight="fill">error</span>
            <span class="font-body-md text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif
        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-emerald-600" data-weight="fill">check_circle</span>
            <span class="font-body-md text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Search Filter -->
        <form action="{{ route('dokumen.tree') }}" method="GET" class="bg-surface p-4 rounded border border-outline-variant shadow-sm flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block font-body-md font-bold text-on-surface mb-1">Cari SKPD</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama SKPD..." class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
            </div>
            <div class="w-full sm:w-64">
                <label class="block font-body-md font-bold text-on-surface mb-1">Status Kelengkapan</label>
                <select name="filter_status" class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua SKPD</option>
                    <option value="lengkap" {{ request('filter_status') == 'lengkap' ? 'selected' : '' }}>Dokumen Lengkap</option>
                    <option value="kurang" {{ request('filter_status') == 'kurang' ? 'selected' : '' }}>Dokumen Kurang</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="h-10 px-4 w-full sm:w-auto border border-outline-variant rounded bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-sm text-label-sm flex items-center justify-center space-x-2">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request('search'))
                <a href="{{ route('dokumen.tree') }}" class="h-10 px-4 w-full sm:w-auto border border-outline-variant rounded bg-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm flex items-center justify-center space-x-2 text-on-surface-variant">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>

        <div class="bg-surface rounded border border-outline-variant shadow-sm p-4 md:p-6">
            @if(empty($treeData))
                <div class="text-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl mb-3">folder_off</span>
                    <p class="font-body-lg">Belum ada data dokumen di tahun {{ $tahunAktif }}.</p>
                </div>
            @else
                <ul class="space-y-3">
                    @foreach($treeData as $skpdId => $skpd)
                        <!-- SKPD Level -->
                        <li x-data="{ open: false }" @expand-all.window="open = true" @collapse-all.window="open = false" class="border border-outline-variant rounded-lg overflow-hidden bg-surface shadow-sm">
                            <button @click="open = !open" class="w-full flex flex-col md:flex-row md:items-center justify-between p-4 hover:bg-surface-container-low transition-colors outline-none focus:bg-surface-container-low gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary" x-text="open ? 'folder_open' : 'folder'">folder</span>
                                    <span class="font-headline-sm text-on-surface text-left">{{ $skpd['nama'] }}</span>
                                </div>
                                <div class="flex items-center gap-3 self-start md:self-auto ml-9 md:ml-0 flex-wrap">
                                    <span class="bg-surface-container-highest text-on-surface text-xs font-bold px-2 py-1 rounded">{{ $skpd['stats']['transaksi'] }} Transaksi</span>
                                    
                                    @if($skpd['stats']['draft'] > 0)
                                        <span class="bg-secondary-container text-on-secondary-container text-xs font-bold px-2 py-1 rounded">{{ $skpd['stats']['draft'] }} Draft</span>
                                    @endif
                                    
                                    @if($skpd['stats']['verified'] > 0)
                                        <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-1 rounded">{{ $skpd['stats']['verified'] }} Verified</span>
                                    @endif

                                    @if($skpd['stats']['missing_docs'] > 0)
                                        <span class="bg-error-container text-error text-xs font-bold px-2 py-1 rounded border border-error/20 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">warning</span>
                                            {{ $skpd['stats']['missing_docs'] }} Dokumen Kurang
                                        </span>
                                    @else
                                        <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-1 rounded border border-primary/20 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            Lengkap
                                        </span>
                                    @endif
                                    <span class="material-symbols-outlined text-on-surface-variant transition-transform duration-200 hidden md:block" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </div>
                            </button>
                            
                            <!-- Rekening Level -->
                            <ul x-show="open" x-collapse class="bg-surface border-t border-outline-variant p-3 md:p-4 space-y-3 md:pl-10">
                                @forelse($skpd['rekenings'] as $rekId => $rek)
                                    <li x-data="{ openRek: false }" @expand-all.window="openRek = true" @collapse-all.window="openRek = false" class="border border-outline-variant rounded-lg overflow-hidden bg-surface">
                                        <button @click="openRek = !openRek" class="w-full flex items-center justify-between p-3 bg-surface-container-lowest hover:bg-surface-container-low transition-colors outline-none focus:bg-surface-container-low">
                                            <div class="flex items-center gap-3">
                                                <span class="material-symbols-outlined text-secondary" x-text="openRek ? 'folder_open' : 'folder'">folder</span>
                                                <span class="font-label-sm text-on-surface text-left">{{ $rek['nama'] }}</span>
                                            </div>
                                            <span class="material-symbols-outlined text-on-surface-variant transition-transform duration-200" :class="openRek ? 'rotate-180' : ''">expand_more</span>
                                        </button>

                                        <!-- Bulan / Transaksi Level -->
                                        <ul x-show="openRek" x-collapse class="bg-surface border-t border-outline-variant p-3 md:p-4 space-y-3 md:pl-10">
                                            @foreach($rek['transaksis'] as $bulan => $trx)
                                                <li x-data="{ openBulan: false }" @expand-all.window="openBulan = true" @collapse-all.window="openBulan = false" class="border border-outline-variant rounded-lg overflow-hidden bg-surface">
                                                    <button @click="openBulan = !openBulan" class="w-full flex items-center justify-between p-3 bg-surface-container-lowest hover:bg-surface-container-low transition-colors outline-none focus:bg-surface-container-low">
                                                        <div class="flex items-center gap-3">
                                                            <span class="material-symbols-outlined text-tertiary" x-text="openBulan ? 'folder_open' : 'folder'">folder</span>
                                                            <span class="font-body-md text-on-surface font-bold text-left">Bulan {{ $namaBulan[$bulan - 1] }}</span>
                                                            @if($trx->status_verifikasi == 'verified')
                                                                <span class="px-2 py-0.5 text-xs bg-primary/10 text-primary rounded border border-primary/20">Terverifikasi</span>
                                                            @else
                                                                <span class="px-2 py-0.5 text-xs bg-secondary/10 text-secondary rounded border border-secondary/20">Draft</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ route('dokumen.zip', $trx->id) }}" @click.stop class="bg-tertiary-container text-on-tertiary-container px-2 py-1 rounded text-xs flex items-center gap-1 shadow-sm hover:brightness-95 transition-all">
                                                                <span class="material-symbols-outlined text-[14px]">archive</span>
                                                                ZIP
                                                            </a>
                                                            <span class="material-symbols-outlined text-on-surface-variant transition-transform duration-200" :class="openBulan ? 'rotate-180' : ''">expand_more</span>
                                                        </div>
                                                    </button>
                                                    
                                                    <!-- Documents Level -->
                                                    <div x-show="openBulan" x-collapse class="bg-surface-container-lowest border-t border-outline-variant p-4 md:pl-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        @php
                                                            $docs = [
                                                                'file_ba_manual' => ['title' => 'BA Manual', 'icon' => 'description'],
                                                                'file_buku_kas' => ['title' => 'Buku Kas', 'icon' => 'book'],
                                                                'file_buku_pembantu_bank' => ['title' => 'Buku Pembantu Bank', 'icon' => 'menu_book'],
                                                                'file_rekening_koran' => ['title' => 'Rekening Koran', 'icon' => 'receipt_long'],
                                                            ];
                                                        @endphp
                                                        @foreach($docs as $field => $doc)
                                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-outline-variant rounded bg-surface shadow-sm gap-3">
                                                                <div class="flex items-center gap-3">
                                                                    <div class="w-10 h-10 rounded bg-primary-container/20 flex items-center justify-center shrink-0">
                                                                        <span class="material-symbols-outlined text-primary">{{ $doc['icon'] }}</span>
                                                                    </div>
                                                                    <span class="font-label-sm text-on-surface">{{ $doc['title'] }}</span>
                                                                </div>
                                                                @if($trx->$field)
                                                                    <button type="button" @click="modalOpen = true; modalUrl = '{{ \App\Services\SiReKaStorage::url($trx->$field) }}'; modalTitle = '{{ $doc['title'] }} - {{ $skpd['nama'] }}'" class="px-4 py-2 bg-primary text-on-primary rounded text-sm hover:bg-primary-container transition-colors flex items-center justify-center gap-2 whitespace-nowrap shadow-sm">
                                                                        <span class="material-symbols-outlined text-[18px]">visibility</span> Lihat Dokumen
                                                                    </button>
                                                                @else
                                                                    <span class="text-sm text-error flex items-center gap-1 bg-error-container/50 px-3 py-1.5 rounded w-fit sm:w-auto mt-2 sm:mt-0">
                                                                        <span class="material-symbols-outlined text-sm">close</span> Belum Upload
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @empty
                                    <li class="p-4 text-center text-on-surface-variant border border-dashed border-outline-variant rounded">Belum ada Rekening</li>
                                @endforelse
                            </ul>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination Links -->
                <div class="mt-6 border-t border-outline-variant pt-4">
                    {{ $skpds->links() }}
                </div>
            @endif
        </div>
        
        <!-- Preview Modal -->
        <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 md:p-6" x-transition.opacity>
            <div class="bg-surface rounded-xl shadow-2xl w-full max-w-5xl h-full max-h-[90vh] flex flex-col overflow-hidden" @click.away="modalOpen = false">
                <div class="flex items-center justify-between p-4 border-b border-outline-variant bg-surface-container-lowest">
                    <h3 class="font-headline-sm text-on-surface" x-text="modalTitle">Pratinjau Dokumen</h3>
                    <div class="flex items-center gap-2">
                        <a :href="modalUrl" target="_blank" class="p-2 text-primary hover:bg-primary/10 rounded transition-colors" title="Buka di Tab Baru">
                            <span class="material-symbols-outlined">open_in_new</span>
                        </a>
                        <button @click="modalOpen = false; modalUrl = ''" class="p-2 text-on-surface-variant hover:bg-surface-container rounded transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
                <div class="flex-1 bg-surface-container-lowest p-0 relative overflow-hidden">
                    <iframe :src="modalUrl" class="w-full h-full border-none"></iframe>
                </div>
            </div>
        </div>

        <!-- Bulk ZIP Export Modal (Paket Audit BPK) -->
        <div x-show="bulkZipOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 md:p-6" x-transition.opacity>
            <div class="bg-surface rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-outline-variant" @click.away="bulkZipOpen = false">
                <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-5 border-b border-outline-variant flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-400" data-weight="fill">folder_zip</span>
                            <span>Unduh Paket Bukti Audit SiReKa</span>
                        </h3>
                        <p class="text-xs text-slate-300 mt-0.5">Kompresi massal file Rekening Koran, BKU, & BA ke format ZIP untuk pemeriksaan BPK.</p>
                    </div>
                    <button @click="bulkZipOpen = false" type="button" class="text-slate-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form action="{{ route('dokumen.bulk_zip') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block font-bold text-body-sm text-on-surface mb-1.5">Filter Bulan (Tahun {{ $tahunAktif }})</label>
                        <select name="bulan" class="w-full h-10 border border-outline-variant rounded-lg px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none text-on-surface font-body-sm">
                            <option value="all">📁 Semua Bulan (Full Tahun Anggaran {{ $tahunAktif }})</option>
                            @foreach($namaBulan as $mIdx => $mName)
                                <option value="{{ $mIdx + 1 }}">Bulan {{ $mName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-body-sm text-on-surface mb-1.5">Filter Instansi / SKPD</label>
                        <select name="skpd_id" class="w-full h-10 border border-outline-variant rounded-lg px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none text-on-surface font-body-sm">
                            <option value="all">🏛️ Seluruh SKPD Kota Banjarbaru</option>
                            @foreach($allSkpdList ?? [] as $sItem)
                                <option value="{{ $sItem->id }}">{{ $sItem->kode }} - {{ $sItem->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bg-amber-50 border border-amber-200 p-3 rounded-lg flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-amber-600 shrink-0 text-xl" data-weight="fill">info</span>
                        <p class="text-[11px] text-amber-900 leading-normal">
                            <strong>Otomatis Disusun:</strong> File yang diunduh akan dikelompokkan ke dalam sub-folder nama SKPD & Bulan di dalam file `.ZIP` secara otomatis.
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-outline-variant/60">
                        <button type="button" @click="bulkZipOpen = false" class="px-4 py-2 bg-surface text-on-surface hover:bg-surface-container rounded-lg border border-outline-variant font-medium text-body-sm transition-colors">
                            Batal
                        </button>
                        <button type="submit" @click="bulkZipOpen = false" class="px-5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-lg shadow transition-all flex items-center gap-1.5 text-body-sm">
                            <span class="material-symbols-outlined text-base" data-weight="fill">download</span>
                            <span>Mulai Unduh ZIP Massal</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
