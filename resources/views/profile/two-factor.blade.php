<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="border-b-[3px] border-primary pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Keamanan Akun & Two-Factor Authentication (2FA)</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola proteksi ganda akun Anda menggunakan Google Authenticator atau aplikasi TOTP lainnya.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-3.5 py-2 border border-outline-variant bg-surface hover:bg-surface-container-low rounded-lg text-label-sm font-semibold text-on-surface-variant flex items-center gap-1.5 self-start sm:self-auto transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Profil</span>
            </a>
        </div>

        <!-- Two-Factor Authentication Form Card -->
        <div class="p-6 sm:p-8 bg-surface rounded-xl border border-outline-variant shadow-sm">
            @include('profile.partials.two-factor-authentication-form', [
                'user' => $user,
                'pengaturan' => $pengaturan,
                'secret2fa' => $secret2fa ?? $secret,
                'qrCodeSvg' => $qrCodeSvg,
                'recoveryCodes' => $recoveryCodes
            ])
        </div>
    </div>
</x-app-layout>
