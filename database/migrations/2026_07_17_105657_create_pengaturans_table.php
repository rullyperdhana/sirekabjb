<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemerintah')->default('PEMERINTAH KOTA BANJARBARU');
            $table->string('nama_instansi')->default('BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH');
            $table->string('jalan')->default('Jl. Panglima Batur No. 1');
            $table->string('kecamatan')->default('Kelurahan Loktabat Utara, Kecamatan Banjarbaru Utara');
            $table->string('kontak')->default('Kode Pos 70711 Telp. (0511) 4772545');
            $table->string('kota')->default('BANJARBARU');
            $table->text('logo')->nullable();
            
            // Penandatangan (Mengetahui)
            $table->string('jabatan_penandatangan')->default('Pengguna Anggaran / Kuasa Pengguna Anggaran');
            $table->string('nama_penandatangan')->nullable();
            $table->string('nip_penandatangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
