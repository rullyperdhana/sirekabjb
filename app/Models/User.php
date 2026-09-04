<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

#[Fillable([
    'name', 'email', 'password', 'username', 'skpd_id', 'role', 'status',
    'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at', 'two_factor_enabled'
])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah user telah mengaktifkan dan mengonfirmasi 2FA
     */
    public function hasTwoFactorEnabled(): bool
    {
        return (bool) $this->two_factor_enabled && !empty($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Dapatkan secret key 2FA yang telah didekripsi
     */
    public function getDecryptedTwoFactorSecret(): ?string
    {
        if (empty($this->two_factor_secret)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->two_factor_secret);
        } catch (\Exception $e) {
            return $this->two_factor_secret;
        }
    }

    /**
     * Simpan secret key 2FA secara terenkripsi
     */
    public function setTwoFactorSecret(string $secret): void
    {
        $this->two_factor_secret = Crypt::encryptString($secret);
        $this->save();
    }

    /**
     * Dapatkan daftar kode pemulihan (recovery codes)
     */
    public function getRecoveryCodesArray(): array
    {
        if (empty($this->two_factor_recovery_codes)) {
            return [];
        }

        try {
            $decrypted = Crypt::decryptString($this->two_factor_recovery_codes);
            return json_decode($decrypted, true) ?? [];
        } catch (\Exception $e) {
            return json_decode($this->two_factor_recovery_codes, true) ?? [];
        }
    }

    /**
     * Buat sekumpulan kode pemulihan cadangan (8 kode)
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        }

        $this->two_factor_recovery_codes = Crypt::encryptString(json_encode($codes));
        $this->save();

        return $codes;
    }

    /**
     * Verifikasi kode 6-digit Google Authenticator
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        $secret = $this->getDecryptedTwoFactorSecret();
        if (!$secret) {
            return false;
        }

        $google2fa = new Google2FA();
        // Window = 1 (toleransi 30 detik sebelum/sesudah untuk antisipasi deviasi jam HP)
        return (bool) $google2fa->verifyKey($secret, trim($code), 1);
    }

    /**
     * Verifikasi dan konsumsi satu kode pemulihan
     */
    public function verifyRecoveryCode(string $code): bool
    {
        $cleanCode = strtoupper(trim($code));
        $codes = $this->getRecoveryCodesArray();

        $key = array_search($cleanCode, $codes);
        if ($key !== false) {
            // Hapus kode yang sudah terpakai
            unset($codes[$key]);
            $this->two_factor_recovery_codes = Crypt::encryptString(json_encode(array_values($codes)));
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Reset / Matikan 2FA pengguna (oleh pengguna sendiri atau oleh Admin)
     */
    public function resetTwoFactor(): void
    {
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_confirmed_at = null;
        $this->two_factor_enabled = false;
        $this->save();
    }

    /**
     * Helper Peran Hak Akses Probis 4-Pilar
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBank(): bool
    {
        return $this->role === 'bank';
    }

    public function isKonsolidator(): bool
    {
        return $this->role === 'konsolidator';
    }

    public function isInspektorat(): bool
    {
        return $this->role === 'inspektorat';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    /**
     * Label resmi peran sesuai Alur Proses Bisnis SiReKa Banjarbaru
     */
    public function getRoleTitleAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator Sistem BPKAD',
            'bank' => 'Pilar 2: Verifikator Pihak Bank (Bank Kalsel)',
            'konsolidator' => 'Pilar 3: Konsolidator Kasda (BPKAD)',
            'inspektorat' => 'Pilar 4: Pengawas & Pengesahan (Inspektorat)',
            'operator' => 'Pilar 1: Operator SKPD',
            default => ucfirst($this->role),
        };
    }
}
