<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable([
    'skpd_id', 'rekening_id', 'periode_bulan', 'periode_tahun',
    'bku_saldo_awal', 'bku_penerimaan', 'bku_pengeluaran', 'bku_saldo_akhir',
    'bank_saldo_awal', 'bank_penerimaan', 'bank_pengeluaran', 'bank_saldo_akhir',
    'keterangan_selisih', 'tanggal_ba', 'nomor_ba', 'status_verifikasi', 'tahap_verifikasi', 'file_bukti', 'user_id',
    'file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran',
    'snapshot_pengantar_ba', 'snapshot_penutup_ba',
    'bank_verified_by', 'bank_verified_at', 'bank_status', 'bank_catatan',
    'status_konsolidator', 'catatan_konsolidator_terakhir', 'checked_by', 'checked_at',
    'inspektorat_verified_by', 'inspektorat_verified_at', 'inspektorat_status', 'inspektorat_catatan'
])]
class Transaksi extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function bankChecker()
    {
        return $this->belongsTo(User::class, 'bank_verified_by');
    }

    public function inspektoratChecker()
    {
        return $this->belongsTo(User::class, 'inspektorat_verified_by');
    }

    public function catatans()
    {
        return $this->hasMany(TransaksiCatatan::class)->orderBy('created_at', 'desc');
    }

    public function verifikasiLogs()
    {
        return $this->hasMany(VerifikasiLog::class)->orderBy('created_at', 'asc');
    }
}

