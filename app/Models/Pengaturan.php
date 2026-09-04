<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Pengaturan extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = [
        'skpd_id',
        'logo',
        'isi_kop',
        'nama_kepala', 'nip_kepala', 'pangkat_kepala', 'jabatan_kepala',
        'nama_bendahara', 'nip_bendahara', 'pangkat_bendahara', 'jabatan_bendahara',
        'nama_kasubag', 'nip_kasubag', 'pangkat_kasubag', 'jabatan_kasubag',
        'is_registration_open', 'allow_operator_reupload', 'is_livelog_active', 'allow_edit_saldo_awal',
        'allow_skpd_download_bukti_digital', 'format_nomor_ba',
        'is_2fa_active', 'is_2fa_mandatory_for_critical_roles',
    ];

    protected $casts = [
        'is_2fa_active' => 'boolean',
        'is_2fa_mandatory_for_critical_roles' => 'boolean',
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }
}
