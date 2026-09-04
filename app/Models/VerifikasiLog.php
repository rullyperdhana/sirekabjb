<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiLog extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_logs';

    protected $fillable = [
        'transaksi_id',
        'user_id',
        'role',
        'stage',
        'aksi',
        'status_sebelum',
        'status_sesudah',
        'catatan',
        'trace_hash',
        'ip_address',
        'user_agent',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate trace hash SHA-256 digital signature
     */
    public static function createHash($transaksiId, $userId, $stage, $aksi, $timestamp): string
    {
        $payload = implode('|', [
            $transaksiId,
            $userId,
            $stage,
            $aksi,
            $timestamp,
            config('app.key'),
            'sireka-bjb-seal'
        ]);

        return hash('sha256', $payload);
    }
}
