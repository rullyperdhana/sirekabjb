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
        // 1. Kolom 2FA pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')->nullable()->after('password');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_confirmed_at');
        });

        // 2. Master switch 2FA pada tabel pengaturans (default: false / nonaktif)
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->boolean('is_2fa_active')->default(false)->after('allow_skpd_download_bukti_digital');
            $table->boolean('is_2fa_mandatory_for_critical_roles')->default(false)->after('is_2fa_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn(['is_2fa_active', 'is_2fa_mandatory_for_critical_roles']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'two_factor_enabled',
            ]);
        });
    }
};
