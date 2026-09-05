<section>
    <header class="border-b border-outline-variant/60 pb-4 mb-6">
        <div class="flex items-center gap-2.5 text-primary mb-1">
            <span class="material-symbols-outlined text-[24px]">lock_reset</span>
            <h2 class="text-title-lg font-title-lg font-bold text-on-surface">
                Perbarui Kata Sandi Akun
            </h2>
        </div>
        <p class="text-body-md font-body-md text-on-surface-variant">
            Gunakan kombinasi kata sandi yang kuat dan unik demi menjaga keamanan hak akses pelaporan dan pengesahan kas daerah.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Kata Sandi Saat Ini -->
            <div x-data="{ show: false }">
                <label for="update_password_current_password" class="block text-label-md font-bold text-on-surface mb-2">
                    Kata Sandi Saat Ini <span class="text-error">*</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">key</span>
                    </div>
                    <input 
                        :type="show ? 'text' : 'password'"
                        id="update_password_current_password" 
                        name="current_password" 
                        required
                        autocomplete="current-password"
                        class="block w-full pl-11 pr-11 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all placeholder:text-on-surface-variant/40"
                        placeholder="••••••••"
                    />
                    <button 
                        type="button" 
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant hover:text-primary transition-colors cursor-pointer"
                        title="Tampilkan / Sembunyikan Kata Sandi"
                    >
                        <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
                @if($errors->updatePassword->get('current_password'))
                    <p class="mt-1.5 text-xs text-error font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $errors->updatePassword->first('current_password') }}
                    </p>
                @endif
            </div>

            <!-- Kata Sandi Baru -->
            <div x-data="{ show: false }">
                <label for="update_password_password" class="block text-label-md font-bold text-on-surface mb-2">
                    Kata Sandi Baru <span class="text-error">*</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                    </div>
                    <input 
                        :type="show ? 'text' : 'password'"
                        id="update_password_password" 
                        name="password" 
                        required
                        autocomplete="new-password"
                        class="block w-full pl-11 pr-11 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all placeholder:text-on-surface-variant/40"
                        placeholder="Minimal 8 karakter"
                    />
                    <button 
                        type="button" 
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant hover:text-primary transition-colors cursor-pointer"
                        title="Tampilkan / Sembunyikan Kata Sandi"
                    >
                        <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
                @if($errors->updatePassword->get('password'))
                    <p class="mt-1.5 text-xs text-error font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $errors->updatePassword->first('password') }}
                    </p>
                @endif
            </div>

            <!-- Konfirmasi Kata Sandi Baru -->
            <div x-data="{ show: false }">
                <label for="update_password_password_confirmation" class="block text-label-md font-bold text-on-surface mb-2">
                    Ulangi Kata Sandi Baru <span class="text-error">*</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">lock_clock</span>
                    </div>
                    <input 
                        :type="show ? 'text' : 'password'"
                        id="update_password_password_confirmation" 
                        name="password_confirmation" 
                        required
                        autocomplete="new-password"
                        class="block w-full pl-11 pr-11 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all placeholder:text-on-surface-variant/40"
                        placeholder="Ulangi kata sandi baru"
                    />
                    <button 
                        type="button" 
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant hover:text-primary transition-colors cursor-pointer"
                        title="Tampilkan / Sembunyikan Kata Sandi"
                    >
                        <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
                @if($errors->updatePassword->get('password_confirmation'))
                    <p class="mt-1.5 text-xs text-error font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $errors->updatePassword->first('password_confirmation') }}
                    </p>
                @endif
            </div>

        </div>

        <div class="p-3 bg-surface-container-low rounded-xl text-xs text-on-surface-variant flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[18px]">info</span>
            <span>Gunakan kombinasi minimal 8 karakter yang terdiri atas huruf besar, huruf kecil, angka, atau simbol untuk keamanan maksimal.</span>
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-outline-variant/40">
            <button 
                type="submit" 
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-on-primary font-semibold text-label-md rounded-xl shadow-sm transition-all duration-200 active:scale-95 cursor-pointer"
            >
                <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                <span>Perbarui Kata Sandi</span>
            </button>
        </div>
    </form>
</section>
