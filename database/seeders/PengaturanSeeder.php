<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengaturan::updateOrCreate(
            ['skpd_id' => null],
            [
                'isi_kop' => 'PEMERINTAH KOTA BANJARBARU|BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH|Jalan Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan|Telp. (0511) 4782098 Email: bpkad@banjarbarukota.go.id',
                'logo' => 'images/logo_banjarbaru.png',
                'nama_kepala' => 'Drs. H. JAINUDIN, M.Si',
                'nip_kepala' => '19680512 199303 1 005',
                'pangkat_kepala' => 'Pembina Utama Muda (IV/c)',
                'jabatan_kepala' => 'Kepala BPKAD Kota Banjarbaru',
                'nama_bendahara' => 'BENDAHARA UMUM DAERAH',
                'nip_bendahara' => '19820415 200604 1 008',
                'pangkat_bendahara' => 'Penata Tk. I (III/d)',
                'jabatan_bendahara' => 'Kuasa BUD Kota Banjarbaru',
                'nama_kasubag' => 'KASUBAG KEUANGAN',
                'nip_kasubag' => '19850920 200902 2 003',
                'pangkat_kasubag' => 'Penata (III/c)',
                'jabatan_kasubag' => 'Kasubag Keuangan & Aset',
                'is_registration_open' => true,
                'allow_operator_reupload' => true,
                'is_livelog_active' => true,
                'allow_edit_saldo_awal' => true,
                'allow_skpd_download_bukti_digital' => true,
                'format_nomor_ba' => '900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}',
            ]
        );
    }
}
