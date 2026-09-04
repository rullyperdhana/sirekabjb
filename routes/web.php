<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\SkpdController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\BaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikasiController;

// Public Routes
Route::get('/storage-stream/{path}', [\App\Http\Controllers\StorageConfigController::class, 'streamFile'])->where('path', '.*')->name('storage.stream');
Route::get('/maintenance-notice', [\App\Http\Controllers\MaintenanceController::class, 'notice'])->name('maintenance.notice');
Route::get('/verifikasi/{id}', [VerifikasiController::class, 'show'])->name('verifikasi.show')->middleware('signed');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Two-Factor Authentication Profile Routes
    Route::get('/profile/two-factor', [\App\Http\Controllers\Auth\TwoFactorController::class, 'show'])->name('profile.two-factor');
    Route::post('/profile/two-factor/confirm', [\App\Http\Controllers\Auth\TwoFactorController::class, 'confirm'])->name('profile.two-factor.confirm');
    Route::delete('/profile/two-factor/disable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'disable'])->name('profile.two-factor.disable');
    Route::post('/profile/two-factor/recovery-codes', [\App\Http\Controllers\Auth\TwoFactorController::class, 'regenerateRecoveryCodes'])->name('profile.two-factor.recovery-codes');

    // Master Data (Admin Only)
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        Route::resource('master/skpd', SkpdController::class);
        Route::resource('master/tahun', \App\Http\Controllers\TahunAnggaranController::class)->except(['create', 'show', 'edit']);
        Route::get('pengaturan/user/cetak-laporan', [UserController::class, 'cetakLaporan'])->name('user.cetak_laporan');
        Route::post('pengaturan/user/{user}/reset-2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'adminReset'])->name('user.reset-2fa');
        Route::resource('pengaturan/user', UserController::class);
        Route::resource('pengaturan/pengumuman', \App\Http\Controllers\PengumumanController::class);
        Route::get('pengaturan/log', [\App\Http\Controllers\LogController::class, 'index'])->name('log.index');
        
        // Maintenance System
        Route::get('pengaturan/maintenance', [\App\Http\Controllers\MaintenanceController::class, 'index'])->name('pengaturan.maintenance.index');
        Route::post('pengaturan/maintenance/backup', [\App\Http\Controllers\MaintenanceController::class, 'backup'])->name('pengaturan.maintenance.backup');
        Route::post('pengaturan/maintenance/restore', [\App\Http\Controllers\MaintenanceController::class, 'restore'])->name('pengaturan.maintenance.restore');
        Route::delete('pengaturan/maintenance/reset', [\App\Http\Controllers\MaintenanceController::class, 'reset'])->name('pengaturan.maintenance.reset');
        Route::post('pengaturan/maintenance/lockdown', [\App\Http\Controllers\MaintenanceController::class, 'toggleLockdown'])->name('pengaturan.maintenance.lockdown');

        // Storage & NAS Management
        Route::get('pengaturan/storage', [\App\Http\Controllers\StorageConfigController::class, 'index'])->name('pengaturan.storage.index');
        Route::post('pengaturan/storage', [\App\Http\Controllers\StorageConfigController::class, 'update'])->name('pengaturan.storage.update');
        Route::post('pengaturan/storage/test', [\App\Http\Controllers\StorageConfigController::class, 'testConnection'])->name('pengaturan.storage.test');
        Route::post('pengaturan/storage/sync', [\App\Http\Controllers\StorageConfigController::class, 'syncFiles'])->name('pengaturan.storage.sync');

        // Reset Status Transaksi ke Draft oleh Admin Pusat
        Route::post('/transaksi/{transaksi}/reset-draft', [\App\Http\Controllers\TransaksiController::class, 'resetToDraft'])->name('transaksi.reset-draft');
    });

    // Laporan & Verifikasi (Admin & Konsolidator)
    Route::middleware(['admin.konsolidator'])->group(function () {
        Route::get('/transaksi/antrean', [\App\Http\Controllers\TransaksiController::class, 'antrean'])->name('transaksi.antrean');
        Route::get('/transaksi/{transaksi}/pemeriksaan', [\App\Http\Controllers\TransaksiController::class, 'pemeriksaanForm'])->name('transaksi.pemeriksaan');
        Route::post('/transaksi/{transaksi}/pemeriksaan', [\App\Http\Controllers\TransaksiController::class, 'pemeriksaanStore'])->name('transaksi.pemeriksaan.store');
        Route::get('/laporan/verifikasi-konsolidator', [\App\Http\Controllers\LaporanController::class, 'verifikasiKonsolidator'])->name('laporan.verifikasi-konsolidator');
        Route::get('/laporan/verifikasi-konsolidator/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakVerifikasiKonsolidatorPdf'])->name('laporan.verifikasi-konsolidator.pdf');
        Route::get('/laporan/verifikasi-konsolidator/excel', [\App\Http\Controllers\LaporanController::class, 'exportVerifikasiKonsolidatorExcel'])->name('laporan.verifikasi-konsolidator.excel');
        Route::get('/laporan/rekap-wa', [\App\Http\Controllers\LaporanController::class, 'rekapWa'])->name('laporan.rekap-wa');
        Route::get('/laporan/tunggakan', [\App\Http\Controllers\LaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
        Route::get('/laporan/tunggakan/excel', [\App\Http\Controllers\LaporanController::class, 'eksporTunggakan'])->name('laporan.tunggakan.excel');
        Route::get('/laporan/konsolidasi', [\App\Http\Controllers\LaporanController::class, 'konsolidasi'])->name('laporan.konsolidasi');
        Route::get('/laporan/konsolidasi/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakKonsolidasi'])->name('laporan.konsolidasi.pdf');
        Route::get('/laporan/konsolidasi/excel', [\App\Http\Controllers\LaporanController::class, 'eksporKonsolidasi'])->name('laporan.konsolidasi.excel');
        Route::get('/dashboard/cetak-rapor-kepatuhan', [\App\Http\Controllers\DashboardController::class, 'cetakRaporKepatuhan'])->name('dashboard.cetak-rapor-kepatuhan');
        Route::get('/dokumen/tree', [\App\Http\Controllers\DokumenController::class, 'tree'])->name('dokumen.tree');
        Route::get('/dokumen/tree/excel', [\App\Http\Controllers\DokumenController::class, 'eksporExcel'])->name('dokumen.tree.excel');
        Route::get('/dokumen/tree/pdf', [\App\Http\Controllers\DokumenController::class, 'cetakPdf'])->name('dokumen.tree.pdf');
        Route::get('/dokumen/tree/{transaksi}/zip', [\App\Http\Controllers\DokumenController::class, 'downloadZip'])->name('dokumen.zip');
        Route::post('/dokumen/bulk-zip', [\App\Http\Controllers\DokumenController::class, 'bulkDownloadZip'])->name('dokumen.bulk_zip');
    });

    // Pilar 1 -> Pilar 2: Pengajuan SKPD ke Bank
    Route::post('/transaksi/{transaksi}/submit-bank', [\App\Http\Controllers\TransaksiController::class, 'submitToBank'])->name('transaksi.submit-bank');

    // Pilar 2: Verifikasi Rekening Koran (Pihak Bank & Admin)
    Route::get('/verifikasi/bank', [\App\Http\Controllers\VerifikasiBankController::class, 'index'])->name('verifikasi.bank.index');
    Route::get('/verifikasi/bank/{transaksi}', [\App\Http\Controllers\VerifikasiBankController::class, 'review'])->name('verifikasi.bank.review');
    Route::post('/verifikasi/bank/{transaksi}/approve', [\App\Http\Controllers\VerifikasiBankController::class, 'approve'])->name('verifikasi.bank.approve');
    Route::post('/verifikasi/bank/{transaksi}/revisi', [\App\Http\Controllers\VerifikasiBankController::class, 'revisi'])->name('verifikasi.bank.revisi');

    // Pilar 4: Pengawasan & Pengesahan BA (Inspektorat & Admin)
    Route::get('/verifikasi/inspektorat', [\App\Http\Controllers\VerifikasiInspektoratController::class, 'index'])->name('verifikasi.inspektorat.index');
    Route::get('/verifikasi/inspektorat/{transaksi}', [\App\Http\Controllers\VerifikasiInspektoratController::class, 'review'])->name('verifikasi.inspektorat.review');
    Route::post('/verifikasi/inspektorat/{transaksi}/approve', [\App\Http\Controllers\VerifikasiInspektoratController::class, 'approve'])->name('verifikasi.inspektorat.approve');
    Route::post('/verifikasi/inspektorat/{transaksi}/revisi', [\App\Http\Controllers\VerifikasiInspektoratController::class, 'revisi'])->name('verifikasi.inspektorat.revisi');

    // Master Data (All Users)
    Route::resource('master/rekening', RekeningController::class);
    
    // Transaksi (All Users)
    Route::get('transaksi/get-saldo-awal', [TransaksiController::class, 'getSaldoAwal'])->name('transaksi.getSaldoAwal');
    Route::get('transaksi/{transaksi}/upload', [TransaksiController::class, 'uploadForm'])->name('transaksi.upload');
    Route::post('transaksi/{transaksi}/upload', [TransaksiController::class, 'uploadStore'])->name('transaksi.upload.store');
    Route::delete('transaksi/{transaksi}/hapus-dokumen/{field}', [TransaksiController::class, 'hapusDokumen'])->name('transaksi.hapus-dokumen');
    Route::get('transaksi/{transaksi}/bukti-digital-pdf', [TransaksiController::class, 'cetakBuktiDigitalPdf'])->name('transaksi.bukti-digital-pdf');
    Route::resource('transaksi', TransaksiController::class)->where(['transaksi' => '[0-9]+'])->except(['show']);
    
    // Catch-all untuk transaksi yang tidak valid (misal: /transaksi/upload tanpa ID)
    Route::any('transaksi/{any}', function () {
        return redirect()->route('transaksi.index');
    })->where('any', '.*');
    
    // Laporan (All Users)
    Route::get('/laporan/ba', [BaController::class, 'index'])->name('ba.index');
    Route::get('/laporan/ba/{transaksi}', [BaController::class, 'show'])->name('ba.show');
    Route::get('/laporan/ba/{transaksi}/pdf', [BaController::class, 'pdf'])->name('ba.pdf');
    Route::get('/laporan/rekap', [\App\Http\Controllers\LaporanController::class, 'rekapTahunan'])->name('laporan.rekap');
    Route::get('/laporan/rekap/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakRekapTahunan'])->name('laporan.rekap.pdf');
    Route::get('/laporan/rekap/excel', [\App\Http\Controllers\LaporanController::class, 'eksporRekapTahunan'])->name('laporan.rekap.excel');
    Route::get('/laporan/ringkasan-selisih', [\App\Http\Controllers\LaporanController::class, 'ringkasanSelisih'])->name('laporan.ringkasan-selisih');
    Route::get('/laporan/ringkasan-selisih/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakRingkasanSelisih'])->name('laporan.ringkasan-selisih.pdf');
    Route::get('/laporan/ringkasan-selisih/excel', [\App\Http\Controllers\LaporanController::class, 'eksporRingkasanSelisih'])->name('laporan.ringkasan-selisih.excel');

    // Pengaturan Instansi (All Users)
    Route::get('pengaturan/instansi', [\App\Http\Controllers\PengaturanController::class, 'edit'])->name('pengaturan.instansi.edit');
    Route::put('pengaturan/instansi', [\App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.instansi.update');

    // Pengaturan Password (All Users)
    Route::get('pengaturan/password', [\App\Http\Controllers\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('pengaturan/password', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');
});

require __DIR__.'/auth.php';
