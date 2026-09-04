<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    /**
     * Tampilkan halaman tantangan 2FA saat proses login
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('login.2fa.user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('login.2fa.user_id'));
        if (!$user) {
            $request->session()->forget(['login.2fa.user_id', 'login.2fa.remember', 'login.2fa.tahun_login']);
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge', compact('user'));
    }

    /**
     * Verifikasi kode 2FA (TOTP atau Recovery Code) saat login
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->session()->has('login.2fa.user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('login.2fa.user_id'));
        if (!$user) {
            $request->session()->forget(['login.2fa.user_id', 'login.2fa.remember', 'login.2fa.tahun_login']);
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $isValid = false;

        // 1. Verifikasi via 6-digit TOTP
        if ($request->filled('code')) {
            $isValid = $user->verifyTwoFactorCode($request->code);
        }
        // 2. Verifikasi via Recovery Code
        elseif ($request->filled('recovery_code')) {
            $isValid = $user->verifyRecoveryCode($request->recovery_code);
        }

        if (!$isValid) {
            return back()->withErrors([
                'code' => 'Kode verifikasi Google Authenticator atau kode pemulihan tidak valid.',
            ]);
        }

        // Ambil data sesi sementara
        $remember = $request->session()->get('login.2fa.remember', false);
        $tahunLogin = $request->session()->get('login.2fa.tahun_login', date('Y'));

        // Bersihkan sesi sementara
        $request->session()->forget(['login.2fa.user_id', 'login.2fa.remember', 'login.2fa.tahun_login']);

        // Login pengguna
        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('tahun_login', $tahunLogin);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Halaman manajemen 2FA di Profil Pengguna
     */
    public function show(): View
    {
        $user = Auth::user();
        $pengaturan = Pengaturan::whereNull('skpd_id')->first();
        $google2fa = new Google2FA();

        $secret = null;
        $qrCodeSvg = null;
        $recoveryCodes = [];

        if (!$user->hasTwoFactorEnabled()) {
            // Siapkan secret key baru untuk inisialisasi setup
            $secret = $user->getDecryptedTwoFactorSecret();
            if (!$secret) {
                $secret = $google2fa->generateSecretKey();
                $user->setTwoFactorSecret($secret);
            }

            $company = 'SiReKa Kota Banjarbaru';
            $holder = $user->username . ' (' . ($user->email ?: 'banjarbaru') . ')';
            $qrUrl = $google2fa->getQRCodeUrl($company, $holder, $secret);

            // Generate SVG QR Code
            $qrCodeSvg = QrCode::size(190)->margin(1)->generate($qrUrl);
        } else {
            $recoveryCodes = $user->getRecoveryCodesArray();
        }

        $secret2fa = $secret;

        return view('profile.two-factor', compact('user', 'pengaturan', 'secret', 'secret2fa', 'qrCodeSvg', 'recoveryCodes'));
    }

    /**
     * Konfirmasi dan aktifkan 2FA pertama kali dengan 6-digit OTP
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();
        if ($user->verifyTwoFactorCode($request->code)) {
            $user->two_factor_enabled = true;
            $user->two_factor_confirmed_at = now();
            $recoveryCodes = $user->generateRecoveryCodes();
            $user->save();

            return redirect()->route('profile.two-factor')
                ->with('status', '2fa-enabled')
                ->with('new_recovery_codes', $recoveryCodes);
        }

        return back()->withErrors([
            'code' => 'Kode autentikasi tidak sesuai. Pastikan jam pada smartphone Anda sinkron dengan waktu internet.',
        ]);
    }

    /**
     * Nonaktifkan 2FA oleh pengguna sendiri (wajib konfirmasi kata sandi)
     */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Kata sandi konfirmasi tidak sesuai.',
            ]);
        }

        $user->resetTwoFactor();

        return redirect()->route('profile.two-factor')->with('status', '2fa-disabled');
    }

    /**
     * Regenerasi kode pemulihan cadangan
     */
    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.two-factor');
        }

        $recoveryCodes = $user->generateRecoveryCodes();

        return redirect()->route('profile.two-factor')
            ->with('status', 'recovery-codes-regenerated')
            ->with('new_recovery_codes', $recoveryCodes);
    }

    /**
     * Reset 2FA akun pengguna oleh Admin Pusat (Fail-safe Emergency)
     */
    public function adminReset(User $user): RedirectResponse
    {
        $user->resetTwoFactor();

        return back()->with('success', "Two-Factor Authentication (2FA) untuk pengguna {$user->name} ({$user->username}) berhasil dinonaktifkan.");
    }
}
