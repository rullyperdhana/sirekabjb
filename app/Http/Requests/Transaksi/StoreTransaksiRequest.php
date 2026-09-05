<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'operator']);
    }

    public function rules(): array
    {
        $bku = (float) $this->bku_saldo_akhir;
        $bank = (float) $this->bank_saldo_akhir;
        $isSelisih = abs($bku - $bank) > 0;

        return [
            'skpd_id' => 'required|exists:skpds,id',
            'rekening_id' => 'required|exists:rekenings,id',
            'periode_bulan' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('transaksis')->where(function ($query) {
                    return $query->where('skpd_id', $this->skpd_id)
                                 ->where('rekening_id', $this->rekening_id)
                                 ->where('periode_tahun', $this->periode_tahun)
                                 ->whereNull('deleted_at');
                }),
            ],
            'periode_tahun' => 'required|integer|min:2000|max:2099',
            'bku_saldo_awal' => 'nullable|numeric',
            'bku_penerimaan' => 'nullable|numeric',
            'bku_pengeluaran' => 'nullable|numeric',
            'bku_saldo_akhir' => 'required|numeric',
            'bank_saldo_awal' => 'required|numeric',
            'bank_penerimaan' => 'required|numeric',
            'bank_pengeluaran' => 'required|numeric',
            'bank_saldo_akhir' => 'required|numeric',
            'keterangan_selisih' => $isSelisih ? 'required|string|max:255' : 'nullable|string|max:255',
            'tanggal_ba' => 'nullable|date',
            'status_verifikasi' => 'nullable|in:draft,verified',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|extensions:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'periode_bulan.unique' => 'Data rekonsiliasi untuk Rekening, Bulan, dan Tahun ini sudah terdaftar dan aktif. Jika sebelumnya pernah dihapus, sekarang Anda dapat membuatnya kembali tanpa gangguan.',
            'keterangan_selisih.required' => 'Penjelasan / Keterangan Selisih wajib diisi karena terdapat selisih Kas.',
            'keterangan_selisih.max' => 'Penjelasan / Keterangan Selisih maksimal 255 karakter.',
        ];
    }
}
