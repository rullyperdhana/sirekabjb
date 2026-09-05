<section>
    <header class="border-b border-outline-variant/60 pb-4 mb-6">
        <div class="flex items-center gap-2.5 text-primary mb-1">
            <span class="material-symbols-outlined text-[24px]">badge</span>
            <h2 class="text-title-lg font-title-lg font-bold text-on-surface">
                Informasi Profil & Data Akun
            </h2>
        </div>
        <p class="text-body-md font-body-md text-on-surface-variant">
            Perbarui nama lengkap dan alamat email kedinasan Anda yang terdaftar di basis data Sistem Rekonsiliasi Kas.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-label-md font-bold text-on-surface mb-2">
                    Nama Lengkap <span class="text-error">*</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                    </div>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        class="block w-full pl-11 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all placeholder:text-on-surface-variant/40"
                        placeholder="Masukkan nama lengkap Anda..."
                    />
                </div>
                @if($errors->get('name'))
                    <p class="mt-1.5 text-xs text-error font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-label-md font-bold text-on-surface mb-2">
                    Alamat Email Kedinasan <span class="text-error">*</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">mail</span>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        autocomplete="username"
                        class="block w-full pl-11 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all placeholder:text-on-surface-variant/40"
                        placeholder="nama@banjarbarukota.go.id"
                    />
                </div>
                @if($errors->get('email'))
                    <p class="mt-1.5 text-xs text-error font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <!-- Username (Read-Only) -->
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-2">
                    Username Login
                    <span class="text-xs font-normal text-on-surface-variant ml-1">(Ditetapkan Administrator)</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">alternate_email</span>
                    </div>
                    <input 
                        type="text" 
                        value="{{ $user->username }}" 
                        disabled 
                        class="block w-full pl-11 pr-10 py-2.5 bg-surface-container-low border border-outline-variant/60 rounded-xl text-on-surface-variant/80 font-mono text-body-md cursor-not-allowed select-none"
                    />
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-on-surface-variant/50">
                        <span class="material-symbols-outlined text-[18px]">lock</span>
                    </div>
                </div>
                <p class="mt-1 text-[11px] text-on-surface-variant">
                    Username digunakan sebagai identitas autentikasi permanen di SiReKa.
                </p>
            </div>

            <!-- SKPD / Unit Kerja (Read-Only) -->
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-2">
                    Instansi / Unit Kerja
                    <span class="text-xs font-normal text-on-surface-variant ml-1">(Penugasan)</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">corporate_fare</span>
                    </div>
                    <input 
                        type="text" 
                        value="{{ $user->skpd->nama ?? 'Pemerintah Kota Banjarbaru (Pusat)' }}" 
                        disabled 
                        class="block w-full pl-11 pr-10 py-2.5 bg-surface-container-low border border-outline-variant/60 rounded-xl text-on-surface-variant/80 text-body-md cursor-not-allowed select-none truncate"
                    />
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-on-surface-variant/50">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                    </div>
                </div>
                <p class="mt-1 text-[11px] text-on-surface-variant">
                    Entitas SKPD yang berwenang Anda kelola dalam siklus rekonsiliasi kas.
                </p>
            </div>

        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-800 flex items-start gap-3">
                <span class="material-symbols-outlined text-amber-600 mt-0.5">mark_email_unread</span>
                <div>
                    <p class="text-sm font-medium">Alamat email kedinasan Anda belum diverifikasi.</p>
                    <button form="send-verification" class="mt-1 text-xs font-bold text-primary hover:underline focus:outline-none">
                        Klik di sini untuk mengirim ulang tautan verifikasi ke email Anda.
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-semibold text-emerald-700 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4 pt-2 border-t border-outline-variant/40">
            <button 
                type="submit" 
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-on-primary font-semibold text-label-md rounded-xl shadow-sm transition-all duration-200 active:scale-95 cursor-pointer"
            >
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</section>
