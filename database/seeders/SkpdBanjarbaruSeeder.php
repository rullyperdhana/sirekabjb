<?php

namespace Database\Seeders;

use App\Models\Skpd;
use Illuminate\Database\Seeder;

class SkpdBanjarbaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skpds = require __DIR__ . '/data_skpd_bjb.php';

        foreach ($skpds as $item) {
            Skpd::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'status' => true,
                ]
            );
        }
    }
}
