<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-8 pb-16">
        
        <!-- Header Halaman -->
        <div class="border-b border-outline-variant/60 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5 text-primary mb-1">
                    <span class="material-symbols-outlined text-[28px]">manage_accounts</span>
                    <h1 class="font-headline-lg text-headline-lg font-bold text-on-surface tracking-tight">Profil & Keamanan Akun</h1>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Kelola data identitas pengguna, pembaharuan kata sandi, dan proteksi autentikasi Sistem Rekonsiliasi Kas (SiReKa) Kota Banjarbaru.
                </p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sesi Aktif (TA {{ session('tahun_login') ?? date('Y') }})
                </span>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <p class="text-body-md font-semibold">Data profil Anda berhasil diperbarui dan disimpan ke sistem.</p>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="material-symbols-outlined text-emerald-600">lock</span>
                <p class="text-body-md font-semibold">Kata sandi akun berhasil diperbarui. Silakan gunakan kata sandi baru untuk login selanjutnya.</p>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-800 flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <p class="text-body-md font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- HERO CARD: Ringkasan Identitas Pegawai / User Kedinasan -->
        <div class="relative overflow-hidden bg-gradient-to-br from-[#00244f] via-[#003875] to-[#001938] rounded-2xl border border-white/10 p-6 sm:p-8 text-white shadow-lg">
            <!-- Background Watermark Pattern -->
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none text-white">
                <span class="material-symbols-outlined text-[200px]">shield_person</span>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <!-- Avatar Inisial Bulat -->
                @php
                    $initials = collect(explode(' ', $user->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                    if (empty($initials)) $initials = strtoupper(substr($user->username, 0, 2));

                    $roleConfig = match($user->role) {
                        'admin' => [
                            'title' => 'Administrator Sistem',
                            'pilar' => 'BPKAD Kota Banjarbaru',
                            'badge_bg' => 'bg-amber-400/20 text-amber-300 border-amber-400/30',
                            'icon' => 'admin_panel_settings',
                        ],
                        'operator' => [
                            'title' => 'Operator SKPD',
                            'pilar' => 'Pilar 1: Pelaporan Kas SKPD',
                            'badge_bg' => 'bg-blue-400/20 text-blue-300 border-blue-400/30',
                            'icon' => 'badge',
                        ],
                        'bank' => [
                            'title' => 'Verifikator Bank Kalsel',
                            'pilar' => 'Pilar 2: Verifikasi Rekening Koran',
                            'badge_bg' => 'bg-cyan-400/20 text-cyan-300 border-cyan-400/30',
                            'icon' => 'account_balance',
                        ],
                        'konsolidator' => [
                            'title' => 'Konsolidator Kas Daerah',
                            'pilar' => 'Pilar 3: Verifikasi Kasda & Dokumen',
                            'badge_bg' => 'bg-purple-400/20 text-purple-300 border-purple-400/30',
                            'icon' => 'fact_check',
                        ],
                        'inspektorat' => [
                            'title' => 'Auditor Inspektorat',
                            'pilar' => 'Pilar 4: Pengawasan & Pengesahan BA',
                            'badge_bg' => 'bg-emerald-400/20 text-emerald-300 border-emerald-400/30',
                            'icon' => 'verified',
                        ],
                        default => [
                            'title' => ucfirst($user->role),
                            'pilar' => 'Pengguna Terdaftar',
                            'badge_bg' => 'bg-slate-400/20 text-slate-300 border-slate-400/30',
                            'icon' => 'person',
                        ]
                    };
                @endphp

                <div class="relative shrink-0">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-br from-amber-400 via-yellow-500 to-amber-600 flex items-center justify-center text-slate-950 font-black text-2xl sm:text-3xl shadow-md border-2 border-white/20">
                        {{ $initials }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 border-2 border-[#00244f] flex items-center justify-center text-white" title="Akun Aktif">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                    </div>
                </div>

                <!-- Info Identitas -->
                <div class="flex-1 space-y-2">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">{{ $user->name }}</h2>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $roleConfig['badge_bg'] }}">
                            <span class="material-symbols-outlined text-[15px]">{{ $roleConfig['icon'] }}</span>
                            {{ $roleConfig['title'] }}
                        </span>
                    </div>

                    <p class="text-white/80 text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-300 text-[18px]">account_tree</span>
                        <span>{{ $roleConfig['pilar'] }}</span>
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-1 text-xs text-white/70">
                        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg">
                            <span class="material-symbols-outlined text-[14px]">alternate_email</span>
                            <span>{{ $user->username }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg">
                            <span class="material-symbols-outlined text-[14px]">corporate_fare</span>
                            <span>{{ $user->skpd->nama ?? 'Pemerintah Kota Banjarbaru' }}</span>
                        </span>
                        @if($user->hasTwoFactorEnabled())
                            <span class="inline-flex items-center gap-1 bg-emerald-400/20 text-emerald-300 px-2.5 py-1 rounded-lg border border-emerald-400/30 font-medium">
                                <span class="material-symbols-outlined text-[14px]">shield_check</span>
                                2FA Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-white/10 text-white/60 px-2.5 py-1 rounded-lg border border-white/10">
                                <span class="material-symbols-outlined text-[14px]">shield</span>
                                2FA Belum Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- GRID KARTU PENGATURAN -->
        <div class="grid grid-cols-1 gap-8">
            
            <!-- 1. DATA DIRI & INFORMASI PROFIL -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 sm:p-8 shadow-sm transition-all hover:shadow-md">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- 2. PERBARUI KATA SANDI -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 sm:p-8 shadow-sm transition-all hover:shadow-md">
                @include('profile.partials.update-password-form')
            </div>

            <!-- 3. AUTENTIKASI DUA LANGKAH (2FA / GOOGLE AUTHENTICATOR) -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 sm:p-8 shadow-sm transition-all hover:shadow-md" id="two-factor">
                @include('profile.partials.two-factor-authentication-form', [
                    'user' => $user,
                    'pengaturan' => $pengaturan,
                    'secret2fa' => $secret2fa,
                    'qrCodeSvg' => $qrCodeSvg,
                    'recoveryCodes' => $recoveryCodes
                ])
            </div>

            <!-- 4. KEBIJAKAN TATA KELOLA AKUN KEDINASAN -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 sm:p-8 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>

        </div>

    </div>
</x-app-layout>
