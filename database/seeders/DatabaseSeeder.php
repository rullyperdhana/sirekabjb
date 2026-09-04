<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skpd;
use App\Models\TahunAnggaran;
use App\Models\Rekening;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tahun Anggaran
        TahunAnggaran::firstOrCreate(['tahun' => 2024], ['is_active' => false]);
        TahunAnggaran::firstOrCreate(['tahun' => 2025], ['is_active' => false]);
        TahunAnggaran::firstOrCreate(['tahun' => 2026], ['is_active' => true]);

        // 2. Pengaturan Global BPKAD Banjarbaru
        $this->call(PengaturanSeeder::class);

        // 3. 87 SKPD Kota Banjarbaru dari kodeskpdbjb.xlsx
        $this->call(SkpdBanjarbaruSeeder::class);

        // 4. Rekening Kas Daerah Bank Kalsel Cabang Banjarbaru
        $bpkadSkpd = Skpd::where('kode', 'like', '%4.04.01%')->orWhere('nama', 'like', '%PENGELOLAAN KEUANGAN%')->first();
        $disdikSkpd = Skpd::where('kode', '1.01.01.0')->first();
        $dinkesSkpd = Skpd::where('kode', '1.02.01.0')->first();
        $dpuprSkpd = Skpd::where('kode', '1.03.01.0')->first();
        $bappedaSkpd = Skpd::where('kode', 'like', '%4.03.01%')->first();

        Rekening::firstOrCreate(
            ['nomor' => '001.03.01.00001.1'],
            [
                'skpd_id' => $bpkadSkpd ? $bpkadSkpd->id : null,
                'nama' => 'Kas Daerah Pemerintah Kota Banjarbaru',
                'bank' => 'BANK KALSEL CABANG BANJARBARU',
                'status' => true,
            ]
        );

        if ($disdikSkpd) {
            Rekening::firstOrCreate(
                ['nomor' => '001.03.02.10010.1'],
                [
                    'skpd_id' => $disdikSkpd->id,
                    'nama' => 'Bendahara Pengeluaran Dinas Pendidikan',
                    'bank' => 'BANK KALSEL CABANG BANJARBARU',
                    'status' => true,
                ]
            );
        }

        if ($dinkesSkpd) {
            Rekening::firstOrCreate(
                ['nomor' => '001.03.02.10020.1'],
                [
                    'skpd_id' => $dinkesSkpd->id,
                    'nama' => 'Bendahara Pengeluaran Dinas Kesehatan',
                    'bank' => 'BANK KALSEL CABANG BANJARBARU',
                    'status' => true,
                ]
            );
        }

        if ($dpuprSkpd) {
            Rekening::firstOrCreate(
                ['nomor' => '001.03.02.10030.1'],
                [
                    'skpd_id' => $dpuprSkpd->id,
                    'nama' => 'Bendahara Pengeluaran Dinas PUPR',
                    'bank' => 'BANK KALSEL CABANG BANJARBARU',
                    'status' => true,
                ]
            );
        }

        // 5. Akun Pengguna 4-Pilar & Operator Percontohan
        // Admin BPKAD
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator BPKAD Kota Banjarbaru',
                'email' => 'admin@bpkad.banjarbarukota.go.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'skpd_id' => null,
            ]
        );

        // Pihak BANK Kalsel (Pilar 2)
        User::updateOrCreate(
            ['username' => 'bank'],
            [
                'name' => 'Verifikator Bank Kalsel Cabang Banjarbaru',
                'email' => 'bank@bankkalsel.co.id',
                'password' => Hash::make('password'),
                'role' => 'bank',
                'skpd_id' => null,
            ]
        );

        // Konsolidator Kasda BPKAD (Pilar 3)
        User::updateOrCreate(
            ['username' => 'konsolidator'],
            [
                'name' => 'Konsolidator Kas Daerah BPKAD Kota Banjarbaru',
                'email' => 'konsolidator@bpkad.banjarbarukota.go.id',
                'password' => Hash::make('password'),
                'role' => 'konsolidator',
                'skpd_id' => $bpkadSkpd ? $bpkadSkpd->id : null,
            ]
        );

        // Inspektorat Kota Banjarbaru (Pilar 4)
        User::updateOrCreate(
            ['username' => 'inspektorat'],
            [
                'name' => 'Auditor Pengawas Inspektorat Kota Banjarbaru',
                'email' => 'inspektorat@banjarbarukota.go.id',
                'password' => Hash::make('password'),
                'role' => 'inspektorat',
                'skpd_id' => null,
            ]
        );

        // Operator SKPD Disdik
        if ($disdikSkpd) {
            User::updateOrCreate(
                ['username' => 'disdik'],
                [
                    'name' => 'Operator Dinas Pendidikan',
                    'email' => 'operator.disdik@banjarbarukota.go.id',
                    'password' => Hash::make('password'),
                    'role' => 'operator',
                    'skpd_id' => $disdikSkpd->id,
                ]
            );
        }

        // Operator SKPD Dinkes
        if ($dinkesSkpd) {
            User::updateOrCreate(
                ['username' => 'dinkes'],
                [
                    'name' => 'Operator Dinas Kesehatan',
                    'email' => 'operator.dinkes@banjarbarukota.go.id',
                    'password' => Hash::make('password'),
                    'role' => 'operator',
                    'skpd_id' => $dinkesSkpd->id,
                ]
            );
        }

        // Operator SKPD DPUPR
        if ($dpuprSkpd) {
            User::updateOrCreate(
                ['username' => 'dpupr'],
                [
                    'name' => 'Operator Dinas PUPR',
                    'email' => 'operator.dpupr@banjarbarukota.go.id',
                    'password' => Hash::make('password'),
                    'role' => 'operator',
                    'skpd_id' => $dpuprSkpd->id,
                ]
            );
        }

        // Operator SKPD BPKAD
        if ($bpkadSkpd) {
            User::updateOrCreate(
                ['username' => 'bpkad'],
                [
                    'name' => 'Operator BPKAD',
                    'email' => 'operator.bpkad@banjarbarukota.go.id',
                    'password' => Hash::make('password'),
                    'role' => 'operator',
                    'skpd_id' => $bpkadSkpd->id,
                ]
            );
        }
    }
}
