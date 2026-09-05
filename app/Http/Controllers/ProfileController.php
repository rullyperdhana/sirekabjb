<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load('skpd');
        $pengaturan = \App\Models\Pengaturan::whereNull('skpd_id')->first();
        $google2fa = new \PragmaRX\Google2FA\Google2FA();

        $secret2fa = null;
        $qrCodeSvg = null;
        $recoveryCodes = [];

        if (!$user->hasTwoFactorEnabled()) {
            $secret2fa = $user->getDecryptedTwoFactorSecret();
            if (!$secret2fa) {
                $secret2fa = $google2fa->generateSecretKey();
                $user->setTwoFactorSecret($secret2fa);
            }

            $company = 'SiReKa Kota Banjarbaru';
            $holder = $user->username . ' (' . ($user->email ?: 'banjarbaru') . ')';
            $qrUrl = $google2fa->getQRCodeUrl($company, $holder, $secret2fa);
            $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->generate($qrUrl);
        } else {
            $recoveryCodes = $user->getRecoveryCodesArray();
        }

        return view('profile.edit', [
            'user' => $user,
            'pengaturan' => $pengaturan,
            'secret2fa' => $secret2fa,
            'qrCodeSvg' => $qrCodeSvg,
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account (Disabled for government audit compliance).
     */
    public function destroy(Request $request): RedirectResponse
    {
        return Redirect::route('profile.edit')->with('error', 'Penghapusan akun kedinasan secara mandiri dinonaktifkan demi integritas jejak audit sistem rekonsiliasi kas daerah. Hubungi Administrator BPKAD untuk penonaktifan akun.');
    }
}
