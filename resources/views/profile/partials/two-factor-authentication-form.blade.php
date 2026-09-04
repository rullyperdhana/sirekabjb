<section>
    <header>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Autentikasi Dua Langkah (2FA / Google Authenticator)</span>
            </h2>
            @if($user->hasTwoFactorEnabled())
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-300">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    Belum Diaktifkan
                </span>
            @endif
        </div>

        <p class="mt-1 text-sm text-gray-600">
            Tambahkan perlindungan keamanan ekstra pada akun Anda menggunakan kode OTP dari aplikasi Google Authenticator, Microsoft Authenticator, atau Authy di smartphone.
        </p>

        @if(!$pengaturan || !$pengaturan->is_2fa_active)
            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <b>Status Kebijakan: Mode Standby.</b> 2FA secara global saat ini belum diwajibkan oleh Admin Pusat. Anda tetap dapat menyiapkan dan mengaktifkannya sekarang untuk proteksi mandiri akun Anda.
                </div>
            </div>
        @endif
    </header>

    @if (session('status') === '2fa-enabled')
        <div class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800 font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Google Authenticator (2FA) berhasil diaktifkan untuk akun Anda!</span>
        </div>
    @elseif (session('status') === '2fa-disabled')
        <div class="mt-4 p-4 rounded-xl bg-slate-100 border border-slate-300 text-sm text-slate-700 font-semibold">
            Google Authenticator (2FA) telah dinonaktifkan.
        </div>
    @endif

    {{-- KONDISI 1: 2FA SUDAH AKTIF --}}
    @if ($user->hasTwoFactorEnabled())
        <div class="mt-6 space-y-6">
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Perangkat Autentikator Terhubung</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Dikonfirmasi pada: {{ $user->two_factor_confirmed_at?->format('d M Y, H:i') }} WITA</p>
                    </div>
                </div>

                {{-- Recovery Codes --}}
                <div x-data="{ showCodes: {{ session('new_recovery_codes') ? 'true' : 'false' }} }" class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-700">Kode Pemulihan Cadangan (Recovery Codes)</h4>
                            <p class="text-[11px] text-slate-500">Simpan kode ini di tempat aman jika smartphone Anda hilang atau rusak.</p>
                        </div>
                        <button type="button" @click="showCodes = !showCodes" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 underline">
                            <span x-show="!showCodes">Tampilkan Kode</span>
                            <span x-show="showCodes">Sembunyikan Kode</span>
                        </button>
                    </div>

                    <div x-show="showCodes" class="mt-3 p-3 bg-white border border-slate-300 rounded-lg">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 font-mono text-xs text-slate-800 text-center font-bold">
                            @foreach ($user->getRecoveryCodesArray() as $code)
                                <div class="p-1.5 bg-slate-100 rounded border border-slate-200">{{ $code }}</div>
                            @endforeach
                        </div>
                        <div class="mt-3 flex items-center justify-between pt-2 border-t border-slate-100">
                            <span class="text-[11px] text-slate-400">Tiap kode hanya dapat digunakan 1 kali.</span>
                            <form method="POST" action="{{ route('profile.two-factor.recovery-codes') }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-amber-700 hover:text-amber-900 underline">
                                    Buat Ulang Kode Pemulihan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Nonaktifkan 2FA --}}
            <div x-data="{ confirmingDisable: false }" class="pt-2">
                <button type="button" @click="confirmingDisable = true" x-show="!confirmingDisable" class="px-4 py-2 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold hover:bg-rose-100 transition">
                    Nonaktifkan Google Authenticator
                </button>

                <div x-show="confirmingDisable" class="p-4 bg-rose-50/70 border border-rose-200 rounded-xl space-y-3">
                    <h4 class="text-xs font-bold text-rose-800">Konfirmasi Penonaktifan 2FA</h4>
                    <p class="text-xs text-slate-600">Masukkan kata sandi Anda saat ini untuk mengonfirmasi penonaktifan 2FA.</p>
                    <form method="POST" action="{{ route('profile.two-factor.disable') }}" class="space-y-3 max-w-sm">
                        @csrf
                        @method('DELETE')
                        <div>
                            <input type="password" name="password" required placeholder="Kata Sandi Akun" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:outline-none">
                            @error('password') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700">
                                Ya, Nonaktifkan
                            </button>
                            <button type="button" @click="confirmingDisable = false" class="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-300">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    {{-- KONDISI 2: 2FA BELUM AKTIF --}}
    @else
        <div x-data="{ setupOpen: false }" class="mt-6">
            <div x-show="!setupOpen">
                <button type="button" @click="setupOpen = true" class="px-4 py-2.5 bg-gradient-to-r from-emerald-700 to-teal-700 hover:from-emerald-600 hover:to-teal-600 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Mulai Pasang Google Authenticator</span>
                </button>
            </div>

            <div x-show="setupOpen" class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <h3 class="text-sm font-bold text-slate-900">Langkah Pengaturan Google Authenticator</h3>
                    <button type="button" @click="setupOpen = false" class="text-xs text-slate-400 hover:text-slate-600">&times; Tutup</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    {{-- Kolom Kiri: QR Code --}}
                    <div class="flex flex-col items-center p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
                        <div class="p-2 border border-slate-100 rounded-lg bg-white">
                            {!! $qrCodeSvg !!}
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2 text-center">Pindai QR Code ini menggunakan aplikasi Google Authenticator di HP Anda.</p>
                        
                        <div class="mt-3 w-full text-center">
                            <span class="text-[10.5px] text-slate-400 block mb-0.5">Atau masukkan Secret Key secara manual:</span>
                            <code class="text-xs font-mono font-bold bg-slate-100 text-emerald-800 px-2 py-1 rounded border border-slate-200 select-all">{{ $secret2fa }}</code>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Panduan & Konfirmasi OTP --}}
                    <div class="space-y-4">
                        <ol class="text-xs text-slate-600 space-y-2 list-decimal list-inside">
                            <li>Buka aplikasi <b>Google Authenticator</b> (atau Microsoft Authenticator) di smartphone.</li>
                            <li>Tekan tombol <b>+ (Tambah)</b> lalu pilih <b>Pindai kode QR</b>.</li>
                            <li>Arahkan kamera ke gambar QR di sebelah kiri.</li>
                            <li>Ketik 6 digit angka yang muncul ke kotak di bawah untuk mengaktifkan:</li>
                        </ol>

                        <form method="POST" action="{{ route('profile.two-factor.confirm') }}" class="space-y-3 pt-2">
                            @csrf
                            <div>
                                <label for="confirm_code" class="block text-xs font-bold text-slate-700 mb-1">Kode 6-Digit dari Aplikasi</label>
                                <input id="confirm_code" type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" required placeholder="Contoh: 123456" 
                                    class="w-full text-base font-bold tracking-widest text-center py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-900">
                                @error('code') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow transition">
                                Konfirmasi & Aktifkan 2FA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
