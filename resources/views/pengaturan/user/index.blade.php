<x-app-layout>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <div class="max-w-[1200px] mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Manajemen Pengguna & Hak Akses 4-Pilar</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola otorisasi akun Operator SKPD, Pihak Bank Kalsel, Konsolidator BPKAD, Inspektorat, dan Administrator sesuai Proses Bisnis SiReKa.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('user.cetak_laporan') }}" target="_blank" class="bg-tertiary text-on-tertiary hover:bg-tertiary/90 px-4 py-2.5 rounded-lg flex items-center space-x-2 transition-colors shadow-sm font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[18px]" data-weight="fill">print</span>
                    <span>Cetak Laporan Audit (PDF)</span>
                </a>
                @endif
                <a href="{{ route('user.create') }}" class="bg-primary text-on-primary hover:bg-primary/90 px-4 py-2.5 rounded-lg flex items-center space-x-2 transition-colors shadow-sm font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>
        
        <!-- Matriks Hak Akses Alur 4-Pilar (Collapsible Info) -->
        <div x-data="{ openMatrix: false }" class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
            <button @click="openMatrix = !openMatrix" class="w-full px-5 py-3.5 flex items-center justify-between bg-surface-container-low hover:bg-surface-container transition-colors text-left">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-[22px]">account_tree</span>
                    <span class="font-bold text-sm text-on-surface">Matriks Hak Akses & Otoritas Alur 4-Pilar SiReKa Banjarbaru</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-primary/10 text-primary font-semibold">Panduan Otoritas</span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant transition-transform" :class="openMatrix ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="openMatrix" class="p-5 border-t border-outline-variant bg-surface space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 text-xs">
                    <!-- Pilar 1 -->
                    <div class="p-3 rounded-lg bg-blue-50/70 border border-blue-200">
                        <div class="font-bold text-blue-900 flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-[16px]">badge</span>
                            Pilar 1: Operator
                        </div>
                        <p class="text-blue-800 leading-relaxed">Entri mutasi kas, isi saldo BKU, upload 5 dokumen rekon, ajukan ke Bank Kalsel. <em>Lingkup: 1 SKPD terikat.</em></p>
                    </div>
                    <!-- Pilar 2 -->
                    <div class="p-3 rounded-lg bg-amber-50/70 border border-amber-200">
                        <div class="font-bold text-amber-900 flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-[16px]">account_balance</span>
                            Pilar 2: Bank Kalsel
                        </div>
                        <p class="text-amber-800 leading-relaxed">Verifikasi rekening koran bank, cek mutasi kasda, sahkan atau minta revisi bank. <em>Lingkup: Seluruh SKPD.</em></p>
                    </div>
                    <!-- Pilar 3 -->
                    <div class="p-3 rounded-lg bg-purple-50/70 border border-purple-200">
                        <div class="font-bold text-purple-900 flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-[16px]">checklist</span>
                            Pilar 3: Konsolidator
                        </div>
                        <p class="text-purple-800 leading-relaxed">Verifikasi teknis 5 berkas fisik kasda, checklist kelengkapan berkas, setujui/revisi kasda. <em>Lingkup: Seluruh SKPD.</em></p>
                    </div>
                    <!-- Pilar 4 -->
                    <div class="p-3 rounded-lg bg-rose-50/70 border border-rose-200">
                        <div class="font-bold text-rose-900 flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-[16px]">policy</span>
                            Pilar 4: Inspektorat
                        </div>
                        <p class="text-rose-800 leading-relaxed">Pengawasan internal tingkat akhir, terbitkan & sahkan Nomor Berita Acara Final. <em>Lingkup: Seluruh SKPD.</em></p>
                    </div>
                    <!-- Admin -->
                    <div class="p-3 rounded-lg bg-slate-100 border border-slate-300">
                        <div class="font-bold text-slate-900 flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-[16px]">shield_person</span>
                            Admin BPKAD
                        </div>
                        <p class="text-slate-800 leading-relaxed">Pengelolaan master data, pengguna, format nomor BA, 2FA, jejak audit & maintenance. <em>Lingkup: Akses Penuh.</em></p>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
        @php
            $totalSkpdCount = \App\Models\Skpd::where('status', true)->count();
            $skpdWithUserCount = \App\Models\Skpd::where('status', true)->whereHas('users')->count();
            $skpdWithoutUserCount = $totalSkpdCount - $skpdWithUserCount;

            $countOperator = \App\Models\User::where('role', 'operator')->count();
            $countBank = \App\Models\User::where('role', 'bank')->count();
            $countKonsolidator = \App\Models\User::where('role', 'konsolidator')->count();
            $countInspektorat = \App\Models\User::where('role', 'inspektorat')->count();
            $countAdmin = \App\Models\User::where('role', 'admin')->count();
        @endphp
        <!-- Executive Audit Banner untuk Internal Admin -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-xl p-6 shadow-md border border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
            <!-- Decoration background -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex items-start md:items-center gap-4 z-10">
                <div class="p-3 bg-white/10 backdrop-blur rounded-xl text-amber-400 shrink-0 border border-white/10">
                    <span class="material-symbols-outlined text-3xl" data-weight="fill">shield_person</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-white tracking-wide flex items-center gap-2">
                        <span>Pengecekan Internal Akun SKPD & Otoritas 4-Pilar</span>
                        <span class="text-[11px] font-mono px-2 py-0.5 bg-amber-400/20 text-amber-300 rounded-full border border-amber-400/30">Admin Only</span>
                    </h3>
                    <p class="text-body-sm text-slate-300 mt-1 leading-relaxed">
                        SKPD Aktif: <strong class="text-white">{{ $totalSkpdCount }} SKPD</strong> (<span class="text-emerald-400 font-bold">{{ $skpdWithUserCount }} Berakun</span>, <span class="text-rose-400 font-bold">{{ $skpdWithoutUserCount }} Belum Berakun</span>).
                    </p>
                    <!-- Quick Pill Badges for All 4 Pillars -->
                    <div class="flex flex-wrap items-center gap-2 mt-2.5 text-xs">
                        <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 font-semibold">Pilar 1 (Operator): {{ $countOperator }}</span>
                        <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30 font-semibold">Pilar 2 (Bank): {{ $countBank }}</span>
                        <span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-300 border border-purple-500/30 font-semibold">Pilar 3 (Konsolidator): {{ $countKonsolidator }}</span>
                        <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30 font-semibold">Pilar 4 (Inspektorat): {{ $countInspektorat }}</span>
                        <span class="px-2 py-0.5 rounded bg-slate-500/30 text-slate-200 border border-slate-500/40 font-semibold">Admin: {{ $countAdmin }}</span>
                    </div>
                </div>
            </div>
            <div class="z-10 shrink-0">
                <a href="{{ route('user.cetak_laporan') }}" target="_blank" class="px-5 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl flex items-center justify-center gap-2 shadow-lg hover:shadow-amber-500/20 transition-all active:scale-95 text-body-sm">
                    <span class="material-symbols-outlined text-lg" data-weight="fill">print</span>
                    <span>Cetak Rekapan Audit (PDF)</span>
                </a>
            </div>
        </div>
        @endif
        
        <!-- Filters -->
        <form method="GET" action="{{ route('user.index') }}" class="bg-surface p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-body-md font-bold text-on-surface mb-1">Cari Nama / Username</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau username pengguna..." class="w-full h-10 border border-outline-variant rounded-lg pl-9 pr-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                </div>
            </div>
            <div class="w-full md:w-64">
                <label class="block font-body-md font-bold text-on-surface mb-1">Filter SKPD</label>
                <select id="skpd_id" name="skpd_id" class="w-full h-10 border border-outline-variant rounded-lg px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua SKPD</option>
                    @foreach($skpds as $skpd)
                    <option value="{{ $skpd->id }}" {{ request('skpd_id') == $skpd->id ? 'selected' : '' }}>{{ $skpd->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-60">
                <label class="block font-body-md font-bold text-on-surface mb-1">Filter Peran (Otoritas)</label>
                <select name="role" class="w-full h-10 border border-outline-variant rounded-lg px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua Peran (5 Otoritas)</option>
                    <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>🔹 Pilar 1: Operator SKPD</option>
                    <option value="bank" {{ request('role') == 'bank' ? 'selected' : '' }}>🏛️ Pilar 2: Pihak Bank (Bank Kalsel)</option>
                    <option value="konsolidator" {{ request('role') == 'konsolidator' ? 'selected' : '' }}>📑 Pilar 3: Konsolidator BPKAD</option>
                    <option value="inspektorat" {{ request('role') == 'inspektorat' ? 'selected' : '' }}>🛡️ Pilar 4: Inspektorat Banjarbaru</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>⚙️ Administrator Pusat</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-10 px-4 rounded-lg bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-sm text-label-sm font-bold flex items-center space-x-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request('search') || request('skpd_id') || request('role'))
                <a href="{{ route('user.index') }}" class="h-10 px-3.5 rounded-lg border border-outline-variant bg-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm flex items-center space-x-1.5 text-on-surface-variant">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
        
        <!-- Table Data -->
        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[860px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Nama Lengkap & Info Akun</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Instansi / SKPD</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Tingkat Peran (Probis)</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Status</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Keamanan 2FA</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($users as $user)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-body-md font-semibold text-on-surface">{{ $user->name }}</div>
                                <div class="text-xs text-on-surface-variant flex items-center gap-2 mt-0.5">
                                    <span class="font-data-tabular font-bold text-primary">{{ $user->username }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                @if($user->skpd)
                                    <span class="font-medium text-slate-900 block">{{ $user->skpd->nama }}</span>
                                    <span class="text-xs text-slate-500 font-mono">Kode: {{ $user->skpd->kode }}</span>
                                @elseif($user->role === 'bank')
                                    <span class="text-amber-800 font-semibold flex items-center gap-1.5 text-xs bg-amber-50 px-2 py-1 rounded border border-amber-200">
                                        <span class="material-symbols-outlined text-[15px]">account_balance</span>
                                        Bank Kalsel (Seluruh SKPD)
                                    </span>
                                @elseif($user->role === 'konsolidator')
                                    <span class="text-purple-800 font-semibold flex items-center gap-1.5 text-xs bg-purple-50 px-2 py-1 rounded border border-purple-200">
                                        <span class="material-symbols-outlined text-[15px]">account_balance_wallet</span>
                                        BPKAD Kasda (Seluruh SKPD)
                                    </span>
                                @elseif($user->role === 'inspektorat')
                                    <span class="text-rose-800 font-semibold flex items-center gap-1.5 text-xs bg-rose-50 px-2 py-1 rounded border border-rose-200">
                                        <span class="material-symbols-outlined text-[15px]">verified</span>
                                        Inspektorat (Pengawasan Kota)
                                    </span>
                                @else
                                    <span class="text-slate-700 font-semibold flex items-center gap-1.5 text-xs bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                        <span class="material-symbols-outlined text-[15px]">shield_person</span>
                                        BPKAD Pusat (Administrator)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($user->role === 'operator')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        <span class="material-symbols-outlined text-[14px]">badge</span>
                                        Pilar 1: Operator
                                    </span>
                                @elseif($user->role === 'bank')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                        <span class="material-symbols-outlined text-[14px]">account_balance</span>
                                        Pilar 2: Bank Kalsel
                                    </span>
                                @elseif($user->role === 'konsolidator')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-900 border border-purple-200">
                                        <span class="material-symbols-outlined text-[14px]">checklist</span>
                                        Pilar 3: Konsolidator
                                    </span>
                                @elseif($user->role === 'inspektorat')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-900 border border-rose-200">
                                        <span class="material-symbols-outlined text-[14px]">policy</span>
                                        Pilar 4: Inspektorat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-900 text-white border border-slate-700">
                                        <span class="material-symbols-outlined text-[14px]">shield_person</span>
                                        Admin BPKAD
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($user->status)
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-secondary/10 text-secondary font-label-sm text-xs font-semibold">
                                    <span class="material-symbols-outlined text-[14px] mr-1">check_circle</span>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-surface-variant text-on-surface-variant font-label-sm text-xs font-semibold">
                                    <span class="material-symbols-outlined text-[14px] mr-1">block</span>
                                    Non-Aktif
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($user->hasTwoFactorEnabled())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300" title="Google Authenticator 2FA Aktif">
                                    <span class="material-symbols-outlined text-[14px] mr-1" data-weight="fill">verified_user</span>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-slate-500 bg-slate-100 border border-slate-200" title="Two-Factor Authentication Standby (Belum diaktifkan)">
                                    <span class="material-symbols-outlined text-[14px] mr-1">shield</span>
                                    Standby
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('user.edit', $user->id) }}" class="inline-block text-primary hover:text-primary-container p-1 mx-1 transition-colors" title="Edit Pengguna">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                @if($user->hasTwoFactorEnabled() && auth()->user()->role === 'admin')
                                <form action="{{ route('user.reset-2fa', $user->id) }}" method="POST" onsubmit="return confirm('Peringatan: Reset 2FA untuk {{ $user->name }}? Gunakan ini jika perangkat pengguna hilang/rusak.');" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-amber-600 hover:text-amber-700 p-1 mx-1 transition-colors" title="Reset 2FA (Darurat jika HP hilang)">
                                        <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                                    </button>
                                </form>
                                @endif
                                @if($user->id !== auth()->id())
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:text-error-container p-1 mx-1 transition-colors" title="Hapus Pengguna">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-on-surface-variant font-medium">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-outline">search_off</span>
                                    <span>Belum ada data Pengguna sesuai kriteria pencarian atau filter peran.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="border-t border-outline-variant p-4 bg-surface-container-lowest">
                {{ $users->links() }}
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('skpd_id')) {
                new TomSelect("#skpd_id", {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            }
        });
    </script>
</x-app-layout>
