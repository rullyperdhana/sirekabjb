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
        // 1. Tambah format_nomor_ba pada pengaturans
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->string('format_nomor_ba')->default('900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}')->after('isi_kop');
        });

        // 2. Tambah kolom alur verifikasi berjenjang pada transaksis
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('nomor_ba')->nullable()->after('tanggal_ba');
            $table->string('tahap_verifikasi')->default('skpd_draft')->after('status_verifikasi');
            
            // Pilar 2: Pihak Bank
            $table->foreignId('bank_verified_by')->nullable()->constrained('users')->nullOnDelete()->after('tahap_verifikasi');
            $table->timestamp('bank_verified_at')->nullable()->after('bank_verified_by');
            $table->string('bank_status')->default('menunggu')->after('bank_verified_at'); // menunggu, valid, revisi
            $table->text('bank_catatan')->nullable()->after('bank_status');

            // Pilar 4: Inspektorat
            $table->foreignId('inspektorat_verified_by')->nullable()->constrained('users')->nullOnDelete()->after('catatan_konsolidator_terakhir');
            $table->timestamp('inspektorat_verified_at')->nullable()->after('inspektorat_verified_by');
            $table->string('inspektorat_status')->default('menunggu')->after('inspektorat_verified_at'); // menunggu, valid, revisi
            $table->text('inspektorat_catatan')->nullable()->after('inspektorat_status');
        });

        // 3. Tabel log bukti verifikasi digital (Audit Trail 4-Pilar)
        Schema::create('verifikasi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role', 50); // operator, bank, konsolidator, inspektorat, admin
            $table->string('stage', 50); // skpd_submit, verifikasi_bank, verifikasi_konsolidator, pengesahan_inspektorat
            $table->string('aksi', 50); // submit, setuju, revisi, terbitkan_ba, reset
            $table->string('status_sebelum', 50)->nullable();
            $table->string('status_sesudah', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->string('trace_hash', 64)->nullable(); // SHA-256 digital signature
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['transaksi_id', 'stage']);
            $table->index('trace_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_logs');

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['bank_verified_by']);
            $table->dropForeign(['inspektorat_verified_by']);
            $table->dropColumn([
                'nomor_ba',
                'tahap_verifikasi',
                'bank_verified_by',
                'bank_verified_at',
                'bank_status',
                'bank_catatan',
                'inspektorat_verified_by',
                'inspektorat_verified_at',
                'inspektorat_status',
                'inspektorat_catatan',
            ]);
        });

        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn('format_nomor_ba');
        });
    }
};
