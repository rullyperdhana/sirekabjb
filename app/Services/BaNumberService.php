<?php

namespace App\Services;

use App\Models\Pengaturan;
use App\Models\Transaksi;

class BaNumberService
{
    /**
     * Romawi converter
     */
    public static function bulanRomawi(int $bulan): string
    {
        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return $romawi[$bulan] ?? 'I';
    }

    /**
     * Ambil template format nomor BA dari pengaturan global
     */
    public function getFormatPattern(): string
    {
        $pengaturan = Pengaturan::whereNull('skpd_id')->first();
        if ($pengaturan && !empty($pengaturan->format_nomor_ba)) {
            return $pengaturan->format_nomor_ba;
        }

        return '900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}';
    }

    /**
     * Generate Nomor BA resmi berdasarkan template format
     */
    public function generate(Transaksi $transaksi, ?string $customPattern = null): string
    {
        $pattern = $customPattern ?? $this->getFormatPattern();

        // Tentukan nomor urut
        // Urutan per SKPD & per tahun, atau ID transaksi
        $nomorUrut = Transaksi::where('skpd_id', $transaksi->skpd_id)
            ->where('periode_tahun', $transaksi->periode_tahun)
            ->whereNotNull('nomor_ba')
            ->count() + 1;

        $nomorPadded = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        $kodeSkpd = $transaksi->skpd ? $transaksi->skpd->kode : '0.00';
        $namaSkpd = $transaksi->skpd ? $transaksi->skpd->nama : 'SKPD';
        $bulan = str_pad($transaksi->periode_bulan, 2, '0', STR_PAD_LEFT);
        $bulanRomawi = self::bulanRomawi($transaksi->periode_bulan);
        $tahun = $transaksi->periode_tahun ?? date('Y');

        $replacements = [
            '{NOMOR}' => $nomorPadded,
            '{KODE_SKPD}' => $kodeSkpd,
            '{BULAN}' => $bulan,
            '{BULAN_ROMAWI}' => $bulanRomawi,
            '{TAHUN}' => $tahun,
            '{NAMA_SKPD}' => $namaSkpd,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $pattern);
    }

    /**
     * Preview format nomor dengan contoh data
     */
    public function preview(string $pattern): string
    {
        $replacements = [
            '{NOMOR}' => '001',
            '{KODE_SKPD}' => '1.01.01.0',
            '{BULAN}' => '09',
            '{BULAN_ROMAWI}' => 'IX',
            '{TAHUN}' => date('Y'),
            '{NAMA_SKPD}' => 'DINAS PENDIDIKAN',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $pattern);
    }
}
