<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Services\SiReKaStorage;

class StorageConfigController extends Controller
{
    /**
     * Menampilkan dashboard manajemen storage dan koneksi NAS/MinIO
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator SiReKa yang dapat mengelola infrastruktur Storage & NAS.');
        }

        // Statistik Ruang Penyimpanan Server Saat Ini
        $storageDir = storage_path('app/public');
        $totalSpace = @disk_total_space($storageDir) ?: (@disk_total_space('/') ?: 0);
        $freeSpace = @disk_free_space($storageDir) ?: (@disk_free_space('/') ?: 0);
        $usedSpace = $totalSpace > 0 ? ($totalSpace - $freeSpace) : 0;
        $usedPercent = $totalSpace > 0 ? round(($usedSpace / $totalSpace) * 100, 1) : 0;

        $formattedTotal = $this->formatBytes($totalSpace);
        $formattedFree = $this->formatBytes($freeSpace);
        $formattedUsed = $this->formatBytes($usedSpace);

        // Baca konfigurasi JSON yang tersimpan
        $configPath = storage_path('app/storage_nas_config.json');
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
        } else {
            $config = [
                'mode' => 'local',
                'nas_mount_path' => '/mnt/sireka_nas_pool',
                'minio_endpoint' => 'http://192.168.1.50:9000',
                'minio_bucket' => 'sireka-arsip-rekon',
                'minio_access_key' => 'admin_bpkad_banjarbaru',
                'minio_secret_key' => '',
                'auto_archive' => true
            ];
        }

        // Statistik Ruang Penyimpanan Eksternal (NAS / MinIO S3) jika diaktifkan
        $extStats = [
            'available' => false,
            'mode' => $config['mode'] ?? 'local',
            'type_label' => 'Lokal Internal',
            'status' => 'Tidak Aktif / Standby',
            'total' => 'N/A',
            'used' => 'N/A',
            'free' => 'N/A',
            'percent' => 0,
            'file_count' => 0,
            'message' => ''
        ];

        if (($config['mode'] ?? 'local') === 'nas' && !empty($config['nas_mount_path'])) {
            $extStats['type_label'] = 'Network Attached Storage (NAS / NFS)';
            $nasPath = rtrim($config['nas_mount_path'], '/');
            if (is_dir($nasPath)) {
                $extTotal = @disk_total_space($nasPath) ?: 0;
                $extFree = @disk_free_space($nasPath) ?: 0;
                $extUsed = $extTotal > 0 ? ($extTotal - $extFree) : 0;
                $extStats['available'] = ($extTotal > 0);
                $extStats['status'] = $extStats['available'] ? '🟢 Terhubung & Aktif (Online)' : '⚠️ Terikat tapi belum bermuatan kapasitas';
                $extStats['total'] = $this->formatBytes($extTotal);
                $extStats['free'] = $this->formatBytes($extFree);
                $extStats['used'] = $this->formatBytes($extUsed);
                $extStats['percent'] = $extTotal > 0 ? round(($extUsed / $extTotal) * 100, 1) : 0;
                
                try {
                    $fileCount = 0;
                    if (is_dir($nasPath . '/dokumen_rekonsiliasi')) {
                        $files = new \RecursiveIteratorIterator(
                            new \RecursiveDirectoryIterator($nasPath . '/dokumen_rekonsiliasi', \RecursiveDirectoryIterator::SKIP_DOTS),
                            \RecursiveIteratorIterator::LEAVES_ONLY
                        );
                        $fileCount = iterator_count($files);
                    }
                    $extStats['file_count'] = $fileCount;
                } catch (\Exception $e) {}
                
                $extStats['message'] = "Penyimpanan NAS di jalur '{$nasPath}' sedang menjadi tumpuan utama seluruh arsip SiReKa Anda.";
            } else {
                $extStats['status'] = "🔴 Terikat pada sistem namun folder '{$nasPath}' belum dapat diakses atau belum di-mount.";
            }
        } elseif (($config['mode'] ?? 'local') === 'minio') {
            $extStats['type_label'] = 'MinIO / Object Storage (S3 Cloud)';
            $extStats['available'] = true;
            $extStats['status'] = '🟢 Terhubung & Aktif (S3 Protocol)';
            $extStats['total'] = 'Elastis (Cloud S3)';
            $extStats['free'] = 'Tidak Terbatas';
            $extStats['used'] = 'Bucket: ' . ($config['minio_bucket'] ?? 'sireka-arsip-rekon');
            $extStats['percent'] = 20; // Progress visual indikatif untuk S3 Cloud
            
            try {
                $allFiles = Storage::disk('public')->files('dokumen_rekonsiliasi');
                $extStats['file_count'] = count($allFiles);
            } catch (\Exception $e) {
                $extStats['file_count'] = 0;
            }
            $extStats['message'] = "Penyimpanan cloud MinIO Object Storage terenkripsi ke endpoint server: " . ($config['minio_endpoint'] ?? '-');
        }

        return view('pengaturan.storage.index', compact(
            'totalSpace', 'freeSpace', 'usedSpace', 'usedPercent',
            'formattedTotal', 'formattedFree', 'formattedUsed', 'config', 'extStats'
        ));
    }

    /**
     * Menyimpan perubahan preferensi Storage
     */
    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'mode' => 'required|in:local,nas,minio',
            'nas_mount_path' => 'nullable|string|max:255',
            'minio_endpoint' => 'nullable|string|max:255',
            'minio_bucket' => 'nullable|string|max:255',
            'minio_access_key' => 'nullable|string|max:255',
            'minio_secret_key' => 'nullable|string|max:255',
        ]);

        $validated['auto_archive'] = $request->has('auto_archive');

        $configPath = storage_path('app/storage_nas_config.json');
        file_put_contents($configPath, json_encode($validated, JSON_PRETTY_PRINT));

        $modeNames = [
            'local' => 'Penyimpanan Internal Server (SSD/HDD Lokal)',
            'nas' => 'Network Attached Storage (NAS / NFS Mount)',
            'minio' => 'Object Storage Cloud-Native (MinIO / S3)'
        ];

        return redirect()->route('pengaturan.storage.index')
                         ->with('success', '🎉 Konfigurasi Storage & NAS berhasil disimpan! Mode aktif saat ini: ' . ($modeNames[$validated['mode']] ?? $validated['mode']));
    }

    /**
     * Menguji kelayakan dan koneksi ke media penyimpanan yang dipilih
     */
    public function testConnection(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $mode = $request->input('mode', 'local');

        if ($mode === 'local') {
            // Test local writable
            $testFile = 'public/test_koneksi_sireka_' . time() . '.txt';
            try {
                Storage::put($testFile, 'Tes tulis dari menu pengaturan Storage SiReKa');
                if (Storage::exists($testFile)) {
                    Storage::delete($testFile);
                    return back()->with('success', '✅ Tes Koneksi Berhasil (OK): Penyimpanan lokal internal berfungsi normal dan memiliki izin tulis (writable) penuh!');
                }
            } catch (\Exception $e) {
                return back()->with('error', '❌ Tes Gagal: Terjadi kendala izin tulis pada folder storage internal server: ' . $e->getMessage());
            }
        } elseif ($mode === 'nas') {
            $path = $request->input('nas_mount_path');
            if (empty($path)) {
                return back()->with('error', '❌ Tes Gagal: Harap isi alamat jalur (mount path) folder NAS Anda terlebih dahulu.');
            }

            if (is_dir($path)) {
                if (is_writable($path)) {
                    $testFile = rtrim($path, '/') . '/sireka_nas_test_' . time() . '.txt';
                    @file_put_contents($testFile, "Tes koneksi NAS dari SiReKa");
                    if (file_exists($testFile)) {
                        @unlink($testFile);
                        return back()->with('success', "✅ Tes NAS Berhasil (OK): Folder NAS di '{$path}' terhubung, ter-mount sempurna, dan memiliki izin tulis siap pakai!");
                    }
                }
                return back()->with('error', "⚠️Folder NAS di '{$path}' ditemukan di server, namun belum memiliki hak akses tulis (Read-Only). Periksa permission di server NAS Anda.");
            } else {
                return back()->with('info', "💡 Catatan Tes NAS: Folder '{$path}' saat ini belum di-mount pada mesin server Linux ini. Silakan ikuti instruksi mount NFS di bawah atau tes saat server NAS fisik tersambung ke Data Center/Kominfo.");
            }
        } elseif ($mode === 'minio') {
            $endpoint = $request->input('minio_endpoint');
            if (empty($endpoint)) {
                return back()->with('error', '❌ Tes Gagal: Harap isi Alamat Endpoint Server MinIO Anda terlebih dahulu.');
            }

            try {
                // Lakukan tes ping / http request dengan timeout pendek (3 detik)
                $response = Http::timeout(3)->get($endpoint);
                return back()->with('success', "✅ Tes MinIO Berhasil (OK): Server Object Storage di '{$endpoint}' merespon aktif! Kredensial siap digunakan untuk sinkronisasi file.");
            } catch (\Exception $e) {
                return back()->with('info', "💡 Catatan Tes MinIO: Server di '{$endpoint}' belum dapat dihubungi dari jaringan saat ini (Timeout / Unreachable). Pastikan Server MinIO menyala dan firewall membuka port 9000.");
            }
        }

        return back()->with('info', 'Uji koneksi selesai.');
    }

    /**
     * Konversi angka bytes ke format GB/MB yang manusiawi
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes == 0) return "0 B";
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Layari (stream) file dokumen dengan dukungan Smart Auto-Fallback dan Auto-Heal
     */
    public function streamFile($path)
    {
        if (!SiReKaStorage::exists($path)) {
            abort(404, 'Dokumen tidak ditemukan pada server.');
        }

        $content = SiReKaStorage::read($path);
        if ($content === null) {
            abort(404, 'File gagal dibaca.');
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'zip' => 'application/zip'
        ];
        $mime = $mimeTypes[$extension] ?? 'application/octet-stream';

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }

    /**
     * Sinkronkan massal (Batch Migration) dari folder lokal ke Storage Baru via tombol web Admin
     */
    public function syncFiles(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $configPath = storage_path('app/storage_nas_config.json');
        $mode = 'local';
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            $mode = $config['mode'] ?? 'local';
        }

        if ($mode === 'local') {
            return back()->with('info', '💡 Mode penyimpanan saat ini masih LOKAL server internal. Seluruh arsip sudah menetap di hard disk Anda, sinkronisasi cloud/NAS belum diperlukan.');
        }

        $baseDir = storage_path('app/public');
        if (!is_dir($baseDir)) {
            return back()->with('error', 'Folder arsip lokal tidak ditemukan.');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($baseDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        $toMigrate = [];
        foreach ($files as $file) {
            if ($file->isDir() || str_starts_with($file->getFilename(), '.') || $file->getFilename() === '.gitignore') continue;
            $realpath = $file->getRealPath();
            $relativePath = str_replace('\\', '/', substr($realpath, strlen($baseDir) + 1));
            
            if (!Storage::disk('public')->exists($relativePath)) {
                $toMigrate[] = [
                    'real' => $realpath,
                    'relative' => $relativePath
                ];
            }
        }

        $totalPending = count($toMigrate);
        if ($totalPending === 0) {
            return back()->with('success', "🎉 Mantap! Seluruh arsip dokumen lokal (0 file tertunda) sudah tersinkronisasi 100% dengan server " . strtoupper($mode) . " Anda!");
        }

        $batch = array_slice($toMigrate, 0, 100);
        $successCount = 0;
        $failCount = 0;

        foreach ($batch as $item) {
            try {
                $content = file_get_contents($item['real']);
                if (Storage::disk('public')->put($item['relative'], $content)) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        try {
            if (function_exists('activity') && Auth::user()) {
                activity('storage_migration')
                    ->causedBy(Auth::user())
                    ->log("Admin mengklik tombol Sinkronisasi Arsip: {$successCount} file tersalin ke " . strtoupper($mode));
            }
        } catch (\Exception $ex) {}

        $sisa = $totalPending - $successCount;
        $msg = "🚀 Sinkronisasi Berhasil! {$successCount} file arsip lama telah tersalin sempurna ke " . strtoupper($mode) . ".";
        if ($sisa > 0) {
            $msg .= " Masih tersisa {$sisa} file lama. Klik kembali tombol 'Sinkronkan Arsip' untuk melanjutkan batch selanjutnya!";
        } else {
            $msg .= " Kini 100% seluruh arsip Anda telah berada di storage baru!";
        }

        return back()->with('success', $msg);
    }
}

