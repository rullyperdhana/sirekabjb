<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class UploadTransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'operator']);
    }

    public function rules(): array
    {
        return [
            'file_ba_manual' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_buku_kas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_buku_pembantu_bank' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_rekening_koran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
