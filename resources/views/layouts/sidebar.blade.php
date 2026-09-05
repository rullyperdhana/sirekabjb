<nav id="appSidebar" class="fixed top-0 left-0 h-screen flex flex-col py-6 bg-primary dark:bg-primary-container w-72 shadow-2xl dark:shadow-none z-50 transform -translate-x-[120%] lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
    @php
        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
        $logoApp = ($pengaturanGlobal && $pengaturanGlobal->logo) 
            ? (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http') ? $pengaturanGlobal->logo : asset('storage/' . $pengaturanGlobal->logo)) 
            : null;
    @endphp
    <div class="px-6 mb-8 flex items-center gap-4">
        @if($logoApp)
            <img src="{{ $logoApp }}" alt="Logo" class="w-10 h-10 object-contain rounded bg-white p-1">
        @else
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-primary" data-weight="fill">account_balance</span>
            </div>
        @endif
        <div>
            <h1 class="text-headline-md font-headline-md font-bold text-on-primary dark:text-primary-fixed">BPKAD</h1>
            <p class="text-label-sm font-label-sm text-on-primary/80">Kota Banjarbaru</p>
        </div>
    </div>
    
    @if(in_array(auth()->user()->role, ['admin', 'operator']))
    <div class="px-4 mb-6">
        <a href="{{ route('transaksi.create') }}" class="w-full bg-secondary-container text-on-secondary-container hover:bg-secondary-container/90 py-3 rounded-lg text-label-sm font-label-sm flex items-center justify-center gap-2 shadow-sm transition-transform scale-95 active:scale-90">
            <span class="material-symbols-outlined" data-weight="fill">add_circle</span>
            Rekonsiliasi Baru
        </a>
    </div>
    @endif

    <ul class="flex-1 space-y-1.5 px-3">
        <!-- Dashboard -->
        <li>
            <a class="group relative rounded-xl flex items-center gap-3 px-4 py-3 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" href="{{ route('dashboard') }}">
                @if(request()->routeIs('dashboard'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-on-secondary-container rounded-r-full"></div>
                @endif
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300" data-weight="{{ request()->routeIs('dashboard') ? 'fill' : '300' }}">dashboard</span>
                <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Dashboard</span>
            </a>
        </li>

        <!-- Master Data (Admin & Operator SKPD Saja) -->
        @if(in_array(auth()->user()->role, ['admin', 'operator']))
        <li class="group/menu">
            @php $isMasterData = request()->routeIs('skpd.*', 'rekening.*', 'tahun.*'); @endphp
            <button class="w-full relative rounded-xl flex items-center justify-between px-4 py-3 transition-all duration-300 {{ $isMasterData ? 'bg-primary-container/30 text-on-primary' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined group-hover/menu:scale-110 transition-transform duration-300" data-weight="{{ $isMasterData ? 'fill' : '300' }}">database</span>
                    <span class="text-label-sm font-label-sm group-hover/menu:translate-x-1 transition-transform duration-300">Master Data</span>
                </div>
                <span class="material-symbols-outlined text-[18px] arrow transition-transform duration-300 {{ $isMasterData ? 'rotate-180' : '' }}">expand_more</span>
            </button>
            <ul class="{{ $isMasterData ? '' : 'hidden' }} mt-1 mb-2 space-y-1 relative before:absolute before:inset-y-0 before:left-[1.35rem] before:w-[1px] before:bg-on-primary/20">
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('skpd.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Master SKPD</span>
                    </a>
                </li>
                @endif
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('rekening.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Master Rekening</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('tahun.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Tahun Anggaran</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        <!-- Data Entri / Monitoring Transaksi -->
        <li>
            <a class="group relative rounded-xl flex items-center gap-3 px-4 py-3 transition-all duration-300 {{ request()->routeIs('transaksi.*') && !request()->routeIs('transaksi.create') && !request()->routeIs('transaksi.antrean') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" href="{{ route('transaksi.index') }}">
                @if(request()->routeIs('transaksi.*') && !request()->routeIs('transaksi.create') && !request()->routeIs('transaksi.antrean'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-on-secondary-container rounded-r-full"></div>
                @endif
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300" data-weight="300">swap_horiz</span>
                <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">{{ in_array(auth()->user()->role, ['admin', 'operator']) ? 'Data Entri' : 'Monitoring Transaksi' }}</span>
            </a>
        </li>

        <!-- Pilar 2: Antrean Verifikasi Bank -->
        @if(in_array(auth()->user()->role, ['admin', 'bank']))
        @php
            $pendingBankCount = \App\Models\Transaksi::where('periode_tahun', session('tahun_login') ?? date('Y'))
                ->where('tahap_verifikasi', 'menunggu_bank')
                ->count();
        @endphp
        <li>
            <a class="group relative rounded-xl flex items-center justify-between px-4 py-3 transition-all duration-300 {{ request()->routeIs('verifikasi.bank.*') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" href="{{ route('verifikasi.bank.index') }}">
                <div class="flex items-center gap-3">
                    @if(request()->routeIs('verifikasi.bank.*'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-on-secondary-container rounded-r-full"></div>
                    @endif
                    <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300" data-weight="{{ request()->routeIs('verifikasi.bank.*') ? 'fill' : '300' }}">account_balance</span>
                    <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Verifikasi Bank</span>
                </div>
                @if($pendingBankCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-400 text-slate-900 shadow-sm animate-pulse">
                        {{ $pendingBankCount }}
                    </span>
                @endif
            </a>
        </li>
        @endif

        <!-- Pilar 3: Antrean Verifikasi Konsolidator Kasda -->
        @if(in_array(auth()->user()->role, ['admin', 'konsolidator']))
        @php
            $pendingVerifikasiCount = \App\Models\Transaksi::where('periode_tahun', session('tahun_login') ?? date('Y'))
                ->where('status_verifikasi', 'verified')
                ->where('status_konsolidator', 'menunggu')
                ->count();
        @endphp
        <li>
            <a class="group relative rounded-xl flex items-center justify-between px-4 py-3 transition-all duration-300 {{ request()->routeIs('transaksi.antrean') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" href="{{ route('transaksi.antrean') }}">
                <div class="flex items-center gap-3">
                    @if(request()->routeIs('transaksi.antrean'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-on-secondary-container rounded-r-full"></div>
                    @endif
                    <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300" data-weight="{{ request()->routeIs('transaksi.antrean') ? 'fill' : '300' }}">fact_check</span>
                    <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Verifikasi Kasda</span>
                </div>
                @if($pendingVerifikasiCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-purple-400 text-slate-900 shadow-sm animate-pulse">
                        {{ $pendingVerifikasiCount }}
                    </span>
                @endif
            </a>
        </li>
        @endif

        <!-- Pilar 4: Pengesahan & Penerbitan BA Inspektorat -->
        @if(in_array(auth()->user()->role, ['admin', 'inspektorat']))
        @php
            $pendingInspektoratCount = \App\Models\Transaksi::where('periode_tahun', session('tahun_login') ?? date('Y'))
                ->where('tahap_verifikasi', 'menunggu_inspektorat')
                ->count();
        @endphp
        <li>
            <a class="group relative rounded-xl flex items-center justify-between px-4 py-3 transition-all duration-300 {{ request()->routeIs('verifikasi.inspektorat.*') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" href="{{ route('verifikasi.inspektorat.index') }}">
                <div class="flex items-center gap-3">
                    @if(request()->routeIs('verifikasi.inspektorat.*'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-on-secondary-container rounded-r-full"></div>
                    @endif
                    <span class="material-symbols-outlined group-hover:scale-110 transition-transform duration-300" data-weight="{{ request()->routeIs('verifikasi.inspektorat.*') ? 'fill' : '300' }}">verified</span>
                    <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Pengesahan Inspektorat</span>
                </div>
                @if($pendingInspektoratCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-400 text-slate-900 shadow-sm animate-pulse">
                        {{ $pendingInspektoratCount }}
                    </span>
                @endif
            </a>
        </li>
        @endif

        <!-- Laporan -->
        <li class="group/menu">
            @php $isLaporan = request()->routeIs('ba.*', 'laporan.*', 'dokumen.*'); @endphp
            <button class="w-full relative rounded-xl flex items-center justify-between px-4 py-3 transition-all duration-300 {{ $isLaporan ? 'bg-primary-container/30 text-on-primary' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined group-hover/menu:scale-110 transition-transform duration-300" data-weight="{{ $isLaporan ? 'fill' : '300' }}">assessment</span>
                    <span class="text-label-sm font-label-sm group-hover/menu:translate-x-1 transition-transform duration-300">Laporan</span>
                </div>
                <span class="material-symbols-outlined text-[18px] arrow transition-transform duration-300 {{ $isLaporan ? 'rotate-180' : '' }}">expand_more</span>
            </button>
            <ul class="{{ $isLaporan ? '' : 'hidden' }} mt-1 mb-2 space-y-1 relative before:absolute before:inset-y-0 before:left-[1.35rem] before:w-[1px] before:bg-on-primary/20">
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('ba.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Berita Acara (Bulanan)</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('laporan.rekap') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Rekapitulasi Tahunan</span>
                    </a>
                </li>
                @if(in_array(auth()->user()->role, ['admin', 'konsolidator']))
                <li>
                    <a class="relative text-on-primary/70 hover:text-emerald-400 rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1 {{ request()->routeIs('laporan.verifikasi-konsolidator*') ? 'text-emerald-300 font-bold' : '' }}" href="{{ route('laporan.verifikasi-konsolidator') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Verifikasi Konsolidator</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-emerald-400 rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('laporan.rekap-wa') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Broadcast Rekap WA</span>
                    </a>
                </li>
                @endif
                @if(in_array(auth()->user()->role, ['admin', 'konsolidator', 'inspektorat']))
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('dokumen.tree') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Arsip Dokumen (Tree)</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('laporan.konsolidasi') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Konsolidasi Daerah</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('laporan.tunggakan') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Tunggakan Kepatuhan</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('laporan.ringkasan-selisih') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Ringkasan Selisih Kas</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <!-- Pengaturan -->
        <li class="group/menu">
            @php $isPengaturan = request()->routeIs('user.*', 'pengaturan.*', 'password.*', 'log.*', 'pengumuman.*', 'profile.*'); @endphp
            <button class="w-full relative rounded-xl flex items-center justify-between px-4 py-3 transition-all duration-300 {{ $isPengaturan ? 'bg-primary-container/30 text-on-primary' : 'text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50' }}" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined group-hover/menu:scale-110 transition-transform duration-300" data-weight="{{ $isPengaturan ? 'fill' : '300' }}">settings</span>
                    <span class="text-label-sm font-label-sm group-hover/menu:translate-x-1 transition-transform duration-300">Pengaturan</span>
                </div>
                <span class="material-symbols-outlined text-[18px] arrow transition-transform duration-300 {{ $isPengaturan ? 'rotate-180' : '' }}">expand_more</span>
            </button>
            <ul class="{{ $isPengaturan ? '' : 'hidden' }} mt-1 mb-2 space-y-1 relative before:absolute before:inset-y-0 before:left-[1.35rem] before:w-[1px] before:bg-on-primary/20">
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('user.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Pengaturan Pengguna</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-error rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('pengaturan.maintenance.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Maintenance Sistem</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-emerald-300 rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1 {{ request()->routeIs('pengaturan.storage.*') ? 'text-emerald-300' : '' }}" href="{{ route('pengaturan.storage.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Manajemen Storage & NAS</span>
                    </a>
                </li>
                @endif
                @if(in_array(auth()->user()->role, ['admin', 'operator']))
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('pengaturan.instansi.edit') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Pengaturan Instansi (Kop)</span>
                    </a>
                </li>
                @endif
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('password.edit') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Ubah Password</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1 {{ request()->routeIs('profile.two-factor') ? 'text-white font-semibold' : '' }}" href="{{ route('profile.two-factor') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Keamanan 2FA</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('log.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Jejak Audit</span>
                    </a>
                </li>
                <li>
                    <a class="relative text-on-primary/70 hover:text-on-primary rounded-lg flex items-center gap-3 px-4 py-2 ml-8 transition-all duration-300 group-hover:translate-x-1" href="{{ route('pengumuman.index') }}">
                        <div class="absolute left-[-1.15rem] top-1/2 -translate-y-1/2 w-3 h-[1px] bg-on-primary/20"></div>
                        <span class="text-label-sm font-label-sm group-hover:translate-x-1 transition-transform duration-300">Pengumuman</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
    </ul>

    <!-- Bottom Actions -->
    <div class="mt-auto px-4 space-y-2 pb-4">
        <a class="text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg flex items-center gap-3 px-4 py-3 scale-95 active:scale-90 transition-transform" href="#">
            <span class="material-symbols-outlined">help</span>
            <span class="text-label-sm font-label-sm">Bantuan</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg flex items-center gap-3 px-4 py-3 scale-95 active:scale-90 transition-transform">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-label-sm font-label-sm">Logout</span>
            </button>
        </form>
        <div class="pt-4 mt-2 border-t border-on-primary/10 text-center">
            <p class="text-[10px] text-on-primary/40 font-mono tracking-wider">SiReKa v2.4.0</p>
        </div>
    </div>
</nav>
