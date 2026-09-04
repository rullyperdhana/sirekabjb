<x-app-layout>
    <div class="space-y-6" x-data="{ 
        selectedMode: '{{ $config['mode'] ?? 'local' }}', 
        showTestModal: false,
        testMode: '{{ $config['mode'] ?? 'local' }}'
    }">
        <!-- Page Header -->
        <div class="border-b-[3px] border-primary pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Manajemen Storage & Koneksi NAS SiReKa</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Pusat kontrol penyimpanan file bukti dukung (Rekening Koran & BKU). Pengaturan ini <strong class="text-primary">bersifat fleksibel (bukan permanen)</strong> dan dapat diubah atau beralih mode sewaktu-waktu sesuai kebutuhan instansi.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold font-mono border {{ $config['mode'] == 'local' ? 'bg-slate-100 text-slate-800 border-slate-300' : ($config['mode'] == 'nas' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-blue-100 text-blue-800 border-blue-300') }}">
                    <span class="w-2 h-2 rounded-full {{ $config['mode'] == 'local' ? 'bg-slate-500' : ($config['mode'] == 'nas' ? 'bg-emerald-500 animate-pulse' : 'bg-blue-500 animate-pulse') }}"></span>
                    MODE AKTIF: {{ strtoupper($config['mode'] ?? 'LOCAL') }}
                </span>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl flex items-center gap-3 shadow-sm font-body-md text-sm font-medium">
            <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0" data-weight="fill">check_circle</span>
            <div class="flex-grow">{{ session('success') }}</div>
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-xl flex items-center gap-3 shadow-sm font-body-md text-sm font-medium">
            <span class="material-symbols-outlined text-rose-600 text-2xl shrink-0" data-weight="fill">error</span>
            <div class="flex-grow">{{ session('error') }}</div>
        </div>
        @endif
        @if(session('info'))
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl flex items-center gap-3 shadow-sm font-body-md text-sm font-medium">
            <span class="material-symbols-outlined text-amber-600 text-2xl shrink-0" data-weight="fill">lightbulb</span>
            <div class="flex-grow">{{ session('info') }}</div>
        </div>
        @endif

        <!-- Diagnostik Kapasitas Ruang Simpan EKSTERNAL (NAS / MinIO - ACTIVE STORAGE) -->
        @if(($extStats['mode'] ?? 'local') !== 'local')
        <div class="bg-gradient-to-r from-teal-950 via-slate-900 to-emerald-950 text-white p-6 rounded-2xl shadow-2xl border-2 border-emerald-500/60 relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-72 h-72 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute left-1/3 bottom-0 w-64 h-32 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 relative z-10">
                <div class="space-y-2 max-w-xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl animate-pulse" data-weight="fill">dns</span>
                        <h2 class="text-lg font-black tracking-tight text-white">Status Penyimpanan Eksternal ({{ strtoupper($extStats['mode']) }})</h2>
                        <span class="text-[11px] px-3 py-0.5 bg-emerald-500 text-slate-950 rounded-full font-black tracking-wider uppercase shadow-[0_0_15px_rgba(16,185,129,0.5)]">⭐ ACTIVE SIREKA STORAGE</span>
                    </div>
                    <p class="text-xs text-emerald-100/80 font-medium">
                        {{ $extStats['status'] }} &mdash; {{ $extStats['message'] }}
                    </p>
                    @if(($extStats['file_count'] ?? 0) > 0)
                    <div class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1.5 rounded-lg text-emerald-300 text-xs font-mono font-bold mt-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px] text-emerald-400">folder_special</span>
                        <span>Terindeks di {{ strtoupper($extStats['mode']) }}: <strong class="text-white text-sm">{{ number_format($extStats['file_count'], 0, ',', '.') }}</strong> Dokumen & Arsip</span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-between xl:justify-end gap-4 md:gap-6 bg-slate-950/90 backdrop-blur border border-emerald-500/30 p-4 rounded-xl shrink-0 shadow-inner">
                    <div class="text-center px-2">
                        <span class="block text-[10px] text-emerald-400/80 font-extrabold uppercase tracking-wider">Total Kapasitas NAS/S3</span>
                        <span class="text-lg md:text-xl font-black text-white font-mono">{{ $extStats['total'] }}</span>
                    </div>
                    <div class="w-px h-12 bg-slate-800"></div>
                    <div class="text-center px-2">
                        <span class="block text-[10px] text-amber-400/80 font-extrabold uppercase tracking-wider">Terpakai</span>
                        <span class="text-lg md:text-xl font-black text-amber-300 font-mono">{{ $extStats['used'] }} @if(is_numeric($extStats['percent'])) <span class="text-xs text-amber-400">({{ $extStats['percent'] }}%)</span> @endif</span>
                    </div>
                    <div class="w-px h-12 bg-slate-800"></div>
                    <div class="text-center px-2">
                        <span class="block text-[10px] text-emerald-400/80 font-extrabold uppercase tracking-wider">Sisa Ruang Bebas</span>
                        <span class="text-lg md:text-xl font-black text-emerald-400 font-mono">{{ $extStats['free'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-5 w-full bg-slate-950 h-3.5 rounded-full overflow-hidden p-0.5 border border-emerald-500/40 shadow-inner">
                <div class="h-full rounded-full transition-all duration-1000 {{ ($extStats['percent'] ?? 0) < 65 ? 'bg-gradient-to-r from-emerald-500 to-teal-400 shadow-[0_0_12px_rgba(52,211,153,0.8)]' : 'bg-gradient-to-r from-amber-500 to-rose-500' }}" style="width: {{ min(100, max(8, $extStats['percent'] ?? 15)) }}%"></div>
            </div>
        </div>
        @endif

        <!-- Storage Health Diagnostics (Gauge Banner untuk Lokal) -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 rounded-2xl shadow-lg border border-slate-800 relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="space-y-2 max-w-xl">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400 text-2xl" data-weight="fill">hard_drive</span>
                        <h2 class="text-lg font-bold">Diagnostik Kapasitas Ruang Simpan @if(($extStats['mode'] ?? 'local') !== 'local') Lokal VPS (Internal Fallback) @else Server Utama (Lokal) @endif</h2>
                        @if(($extStats['mode'] ?? 'local') !== 'local')
                            <span class="text-[11px] px-2.5 py-0.5 bg-indigo-500/20 text-indigo-300 rounded-full font-bold border border-indigo-500/40">🛡️ Standby & Auto-Fallback</span>
                        @elseif($usedPercent < 75)
                            <span class="text-[11px] px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 rounded-full font-bold border border-emerald-500/40">🟢 Kondisi Aman</span>
                        @else
                            <span class="text-[11px] px-2.5 py-0.5 bg-rose-500/20 text-rose-300 rounded-full font-bold border border-rose-500/40 animate-pulse">⚠️ Kapasitas Menipis</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Pantau kapasitas harddisk server SiReKa saat ini. Jika ruang tersisa di bawah 25%, sangat disukai untuk segera menyambungkan sistem ke perangkat <strong>NAS Synology/QNAP</strong> atau <strong>MinIO Object Storage</strong> agar performa server tetap maksimal.
                    </p>
                </div>

                <div class="flex items-center gap-6 bg-slate-800/80 backdrop-blur border border-slate-700/80 p-4 rounded-xl shrink-0">
                    <div class="text-center px-2">
                        <span class="block text-[11px] text-slate-400 font-semibold uppercase">Total Kapasitas</span>
                        <span class="text-lg font-black text-white font-mono">{{ $formattedTotal }}</span>
                    </div>
                    <div class="w-px h-10 bg-slate-700"></div>
                    <div class="text-center px-2">
                        <span class="block text-[11px] text-slate-400 font-semibold uppercase">Terpakai</span>
                        <span class="text-lg font-black {{ $usedPercent >= 80 ? 'text-rose-400' : 'text-amber-400' }} font-mono">{{ $formattedUsed }} <span class="text-xs">({{ $usedPercent }}%)</span></span>
                    </div>
                    <div class="w-px h-10 bg-slate-700"></div>
                    <div class="text-center px-2">
                        <span class="block text-[11px] text-slate-400 font-semibold uppercase">Sisa Ruang Bebas</span>
                        <span class="text-lg font-black text-emerald-400 font-mono">{{ $formattedFree }}</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-5 w-full bg-slate-800 h-3 rounded-full overflow-hidden p-0.5 border border-slate-700/60">
                <div class="h-full rounded-full transition-all duration-1000 {{ $usedPercent < 65 ? 'bg-emerald-500' : ($usedPercent < 85 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ min(100, max(5, $usedPercent)) }}%"></div>
            </div>
        </div>

        <!-- Panel Migrasi & Sinkronisasi Arsip Massal -->
        @if(($config['mode'] ?? 'local') !== 'local')
        <div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-teal-950 border-2 border-emerald-600/60 p-6 rounded-2xl text-white shadow-xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl" data-weight="fill">cloud_sync</span>
                        <h3 class="text-lg font-black tracking-tight">Sinkronisasi & Migrasi Massal Ke {{ strtoupper($config['mode']) }}</h3>
                        <span class="px-2.5 py-0.5 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 font-extrabold text-[11px] rounded-full">⚡ SIAP SINKRON</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Saat ini Anda telah mengaktifkan mode <strong>{{ strtoupper($config['mode']) }}</strong>. SiReKa telah dilengkapi teknologi <strong>Smart Auto-Fallback</strong> (membaca file lokal lama secara otomatis). Namun jika Anda ingin memindahkan seluruh arsip fisik lama ke storage baru secara langsung sekarang juga, cukup klik tombol sinkronisasi di sebelah kanan.
                    </p>
                    <div class="p-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-xs font-mono text-slate-400 flex items-center justify-between gap-3 shadow-inner">
                        <span>💻 Untuk sinkronisasi berskala besar ribuan file di terminal server tanpa batas waktu browser, gunakan perintah: <strong class="text-emerald-400 font-bold">php artisan sireka:sync-storage</strong></span>
                    </div>
                </div>

                <form action="{{ route('pengaturan.storage.sync') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" onclick="return confirm('Mulai proses penyalinan batch file dari hard disk lokal ke {{ strtoupper($config['mode']) }}? Proses ini akan mengkopi hingga 100 dokumen per klik secara aman.')" class="w-full md:w-auto px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black rounded-xl shadow-[0_0_25px_-5px_rgba(16,185,129,0.4)] transition-all flex items-center justify-center gap-2 active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-xl font-extrabold">cloud_upload</span>
                        <span>Sinkronkan Arsip Lama Sekarang</span>
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl text-slate-300 text-xs flex flex-col md:flex-row items-center justify-between gap-4 shadow-md">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-indigo-400 text-2xl shrink-0" data-weight="fill">shield_with_heart</span>
                <div>
                    <strong class="text-white block font-semibold text-sm">Proteksi SiReKa Storage Engine: Smart Auto-Fallback & Auto-Heal Aktif</strong>
                    <span>Jika kelak sewaktu-waktu Anda mengaktifkan NAS atau MinIO S3 di bawah, seluruh file lokal lama Anda di hard disk tidak perlu terburu-buru dimigrasi manual! SiReKa akan otomatis membacakan file asli di hard disk lama (Auto-Fallback) dan mengkopikannya perlahan (Auto-Heal) setiap diakses, atau dapat dipacu massal melalui tombol sinkron yang akan muncul saat NAS/MinIO aktif.</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Konfigurasi Utama -->
        <form action="{{ route('pengaturan.storage.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Pilihan Mode Storage (Cards) -->
            <div class="space-y-3">
                <h3 class="font-headline-sm text-base text-on-surface font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">dns</span>
                    <span>Pilih Mode Penyimpanan Arsip Dokumen</span>
                </h3>
                <p class="text-xs text-on-surface-variant">Klik pada salah satu mode penyimpanan di bawah untuk mengatur parameter yang sesuai:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- MODE 1: LOCAL -->
                    <label @click="selectedMode = 'local'" :class="selectedMode == 'local' ? 'border-primary ring-2 ring-primary/20 bg-primary-container/10 shadow-md' : 'border-outline-variant hover:border-primary/50 bg-surface-container-lowest'" class="border-2 rounded-xl p-5 cursor-pointer transition-all flex flex-col justify-between relative">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="p-2.5 bg-slate-100 text-slate-800 rounded-lg flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined text-2xl">storage</span>
                                </span>
                                <input type="radio" name="mode" value="local" x-model="selectedMode" class="w-5 h-5 text-primary focus:ring-primary">
                            </div>
                            <h4 class="font-bold text-base text-on-surface">Penyimpanan Internal Server</h4>
                            <span class="inline-block px-2 py-0.5 bg-slate-200/80 text-slate-700 font-semibold rounded text-[10px] mt-1 mb-2">Default / Standar</span>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Menyimpan seluruh file PDF langsung pada hard disk/SSD lokal mesin server di dalam folder <code>storage/app/public</code>. Sangat stabil dan tanpa pengaturan tambahan.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-outline-variant/50 text-[11px] font-semibold text-slate-600 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">verified</span>
                            <span>Siap Pakai / Tanpa Perangkat Tambahan</span>
                        </div>
                    </label>

                    <!-- MODE 2: NAS / NFS MOUNT -->
                    <label @click="selectedMode = 'nas'" :class="selectedMode == 'nas' ? 'border-emerald-500 ring-2 ring-emerald-500/20 bg-emerald-50/20 shadow-md' : 'border-outline-variant hover:border-emerald-500/50 bg-surface-container-lowest'" class="border-2 rounded-xl p-5 cursor-pointer transition-all flex flex-col justify-between relative">
                        <div class="absolute -top-3 right-4 px-2.5 py-0.5 bg-emerald-600 text-white font-black text-[10px] rounded-full uppercase shadow-sm">
                            ⭐ Pilihan Favorit
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="p-2.5 bg-emerald-100 text-emerald-800 rounded-lg flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined text-2xl" data-weight="fill">cloud_sync</span>
                                </span>
                                <input type="radio" name="mode" value="nas" x-model="selectedMode" class="w-5 h-5 text-emerald-600 focus:ring-emerald-500">
                            </div>
                            <h4 class="font-bold text-base text-on-surface">Network Attached Storage (NAS)</h4>
                            <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-800 font-semibold rounded text-[10px] mt-1 mb-2">NFS / CIFS Network Mount</span>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Menyalurkan file fisik langsung ke mesin <strong>Synology / QNAP NAS</strong> di Data Center melalui jaringan LAN. Aplikasi tidak dibebani ruang disk sedikit pun (Zero-Code Scaling).
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-outline-variant/50 text-[11px] font-bold text-emerald-700 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">speed</span>
                            <span>Kapasitas Terbesar & Akses Stabil</span>
                        </div>
                    </label>

                    <!-- MODE 3: MINIO / S3 -->
                    <label @click="selectedMode = 'minio'" :class="selectedMode == 'minio' ? 'border-blue-500 ring-2 ring-blue-500/20 bg-blue-50/20 shadow-md' : 'border-outline-variant hover:border-blue-500/50 bg-surface-container-lowest'" class="border-2 rounded-xl p-5 cursor-pointer transition-all flex flex-col justify-between relative">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="p-2.5 bg-blue-100 text-blue-800 rounded-lg flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined text-2xl" data-weight="fill">cloud</span>
                                </span>
                                <input type="radio" name="mode" value="minio" x-model="selectedMode" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                            </div>
                            <h4 class="font-bold text-base text-on-surface">MinIO / Object Storage (S3)</h4>
                            <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 font-semibold rounded text-[10px] mt-1 mb-2">Enterprise / Cloud Native</span>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Penyimpanan berorientasi objek kelas enterprise setara AWS S3 namun dipasang pada jaringan lokal instansi (Private Cloud). Menyediakan perlindungan enkripsi & replikasi tinggi.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-outline-variant/50 text-[11px] font-bold text-blue-700 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">security</span>
                            <span>Terisolasi & Keamanan Terbaik</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Panel Khusus Mode NAS -->
            <div x-show="selectedMode == 'nas'" style="display: none;" class="bg-surface-container-lowest border border-emerald-200 rounded-2xl p-6 shadow-sm space-y-4 transition-all">
                <div class="border-b border-outline-variant/50 pb-3 flex items-center justify-between">
                    <h4 class="font-bold text-base text-emerald-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">settings_ethernet</span>
                        <span>Konfigurasi Mount Point NAS (NFS / CIFS)</span>
                    </h4>
                    <span class="text-[11px] font-mono bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded border border-emerald-200">Port Default: 2049</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block font-bold text-body-sm text-on-surface mb-1">Jalur Folder NAS (Mount Path di Server Linux) <span class="text-rose-500">*</span></label>
                            <input type="text" name="nas_mount_path" value="{{ old('nas_mount_path', $config['nas_mount_path'] ?? '/mnt/sireka_nas_pool') }}" placeholder="Contoh: /mnt/sireka_nas atau //192.168.1.50/share" class="w-full h-11 border border-outline-variant rounded-xl px-4 bg-surface focus:ring-2 focus:ring-emerald-500 outline-none font-mono text-sm text-on-surface">
                            <p class="text-[11px] text-on-surface-variant mt-1.5">
                                Masukkan jalur absolute path di mana folder NAS Synology/QNAP Anda di-mount pada mesin server.
                            </p>
                        </div>
                        
                        <div class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-xl space-y-2">
                            <span class="font-bold text-xs text-emerald-900 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base">verified_user</span>
                                <span>Keuntungan Mode NAS:</span>
                            </span>
                            <ul class="text-xs text-emerald-900 space-y-1 pl-4 list-disc">
                                <li><strong>Tanpa Beban Prosesor:</strong> File langsung disalurkan lewat jaringan LAN Gigabit.</li>
                                <li><strong>Fleksibilitas Penuh:</strong> Jika NAS dalam perawatan, Anda bisa klik balik ke Mode Lokal kapan pun tanpa kehilangan data lama.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-slate-900 text-slate-100 p-5 rounded-xl font-mono text-xs space-y-3 shadow-inner">
                        <span class="text-amber-400 font-bold flex items-center gap-1.5 text-xs">
                            <span class="material-symbols-outlined text-base">terminal</span>
                            <span>Panduan Cepat Mount di Server Linux / Kominfo:</span>
                        </span>
                        <div class="space-y-2 text-[11px] leading-relaxed">
                            <p class="text-slate-400">// 1. Hubungkan NAS Synology/QNAP ke server SiReKa via terminal:</p>
                            <div class="bg-slate-950 p-2.5 rounded border border-slate-800 text-emerald-400 font-bold overflow-x-auto">
                                sudo mount -t nfs 192.168.1.50:/volume1/sireka_storage /var/www/sireke/storage/app/public
                            </div>
                            <p class="text-slate-400 pt-1">// 2. Agar otomatis terhubung kembali jika server reboot (di /etc/fstab):</p>
                            <div class="bg-slate-950 p-2.5 rounded border border-slate-800 text-blue-300 overflow-x-auto">
                                192.168.1.50:/volume1/sireka_storage  /var/www/sireke/storage/app/public  nfs  defaults,nofails  0  0
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Khusus Mode MinIO -->
            <div x-show="selectedMode == 'minio'" style="display: none;" class="bg-surface-container-lowest border border-blue-200 rounded-2xl p-6 shadow-sm space-y-4 transition-all">
                <div class="border-b border-outline-variant/50 pb-3 flex items-center justify-between">
                    <h4 class="font-bold text-base text-blue-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">api</span>
                        <span>Konfigurasi MinIO / S3 Object Storage</span>
                    </h4>
                    <span class="text-[11px] font-mono bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-200">Port Default: 9000</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-body-sm text-on-surface mb-1">Alamat Endpoint Server MinIO <span class="text-rose-500">*</span></label>
                        <input type="text" name="minio_endpoint" value="{{ old('minio_endpoint', $config['minio_endpoint'] ?? 'http://192.168.1.50:9000') }}" placeholder="http://192.168.1.50:9000" class="w-full h-11 border border-outline-variant rounded-xl px-4 bg-surface focus:ring-2 focus:ring-blue-500 outline-none font-mono text-sm text-on-surface">
                    </div>
                    <div>
                        <label class="block font-bold text-body-sm text-on-surface mb-1">Nama Bucket (Wadah File) <span class="text-rose-500">*</span></label>
                        <input type="text" name="minio_bucket" value="{{ old('minio_bucket', $config['minio_bucket'] ?? 'sireka-arsip-rekon') }}" placeholder="sireka-arsip-rekon" class="w-full h-11 border border-outline-variant rounded-xl px-4 bg-surface focus:ring-2 focus:ring-blue-500 outline-none font-mono text-sm text-on-surface">
                    </div>
                    <div>
                        <label class="block font-bold text-body-sm text-on-surface mb-1">Access Key ID (Username MinIO)</label>
                        <input type="text" name="minio_access_key" value="{{ old('minio_access_key', $config['minio_access_key'] ?? '') }}" placeholder="admin_bpkad_banjarbaru" class="w-full h-11 border border-outline-variant rounded-xl px-4 bg-surface focus:ring-2 focus:ring-blue-500 outline-none font-mono text-sm text-on-surface">
                    </div>
                    <div>
                        <label class="block font-bold text-body-sm text-on-surface mb-1">Secret Access Key (Password MinIO)</label>
                        <input type="password" name="minio_secret_key" value="{{ old('minio_secret_key', $config['minio_secret_key'] ?? '') }}" placeholder="••••••••••••••••••••" class="w-full h-11 border border-outline-variant rounded-xl px-4 bg-surface focus:ring-2 focus:ring-blue-500 outline-none font-mono text-sm text-on-surface">
                    </div>
                </div>
            </div>

            <!-- Opsi Tambahan Tata Kelola Arsip -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm space-y-3">
                <h4 class="font-bold text-base text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">rule_folder</span>
                    <span>Kebijakan Pengarsipan & Siklus Kerja (Lifecycle Governance)</span>
                </h4>
                <div class="flex items-start gap-3 pt-2">
                    <input type="checkbox" id="auto_archive" name="auto_archive" value="1" {{ !empty($config['auto_archive']) ? 'checked' : '' }} class="w-5 h-5 mt-0.5 rounded text-primary focus:ring-primary border-outline-variant">
                    <label for="auto_archive" class="text-xs text-on-surface cursor-pointer leading-relaxed">
                        <strong class="text-sm block text-on-surface">Aktifkan Pengingat Pengarsipan Cold Storage Tahunan</strong>
                        Jika dicentang, pada setiap awal Tahun Anggaran baru (Januari/Februari), SiReKa akan memunculkan pengingat kepada Administrator untuk mengunduh <strong>Paket Audit BPK (.ZIP)</strong> tahun sebelumnya dan memindahkan master arsip tersebut ke Cold Storage (NAS/Hardisk Eksternal), guna menjaga kapasitas dan laju kecepatan database server utama tetap optimal.
                    </label>
                </div>
            </div>

            <!-- Action Button Bar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 bg-surface-container rounded-2xl border border-outline-variant shadow-md">
                <div class="flex items-center gap-2 text-on-surface-variant text-xs">
                    <span class="material-symbols-outlined text-amber-600 text-lg">touch_app</span>
                    <span>Anda dapat menguji koneksi terlebih dahulu sebelum menyimpan preferensi.</span>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <!-- Tombol Uji Koneksi -->
                    <button type="submit" formaction="{{ route('pengaturan.storage.test') }}" formmethod="POST" class="px-5 py-2.5 bg-surface hover:bg-surface-container-low text-on-surface border border-outline-variant rounded-xl font-bold text-body-sm flex items-center gap-2 shadow-sm transition-all active:scale-95 whitespace-nowrap">
                        <span class="material-symbols-outlined text-primary">power_input</span>
                        <span>Uji Koneksi Storage</span>
                    </button>
                    <!-- Tombol Simpan & Aktifkan -->
                    <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container rounded-xl font-bold text-body-sm flex items-center gap-2 shadow-lg transition-all active:scale-95 whitespace-nowrap">
                        <span class="material-symbols-outlined" data-weight="fill">save</span>
                        <span>Simpan & Aktifkan Mode ini</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
