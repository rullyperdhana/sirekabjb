<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skpd;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    public function tree(Request $request)
    {
        // Hanya Admin dan Konsolidator yang boleh mengakses
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator', 'inspektorat'])) {
            abort(403);
        }

        $tahunAktif = session('tahun_login') ?? date('Y');

        $query = Skpd::whereHas('transaksis', function ($q) use ($tahunAktif) {
            $q->where('periode_tahun', $tahunAktif);
        })->with(['transaksis' => function ($q) use ($tahunAktif) {
            $q->where('periode_tahun', $tahunAktif)
              ->orderBy('periode_bulan', 'asc');
        }, 'transaksis.rekening']);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->has('filter_status') && $request->filter_status != '') {
            if ($request->filter_status === 'kurang') {
                $query->whereHas('transaksis', function ($q) use ($tahunAktif) {
                    $q->where('periode_tahun', $tahunAktif)
                      ->where(function($sub) {
                          $sub->whereNull('file_ba_manual')
                              ->orWhereNull('file_buku_kas')
                              ->orWhereNull('file_buku_pembantu_bank')
                              ->orWhereNull('file_rekening_koran');
                      });
                });
            } elseif ($request->filter_status === 'lengkap') {
                $query->whereDoesntHave('transaksis', function ($q) use ($tahunAktif) {
                    $q->where('periode_tahun', $tahunAktif)
                      ->where(function($sub) {
                          $sub->whereNull('file_ba_manual')
                              ->orWhereNull('file_buku_kas')
                              ->orWhereNull('file_buku_pembantu_bank')
                              ->orWhereNull('file_rekening_koran');
                      });
                });
            }
        }

        // Mengambil SKPD yang memiliki transaksi di tahun aktif dengan paginasi
        $skpds = $query->orderBy('kode')->paginate(10)->withQueryString();

        // Kita akan melakukan grouping di Controller agar View lebih ringan.
        $treeData = [];

        foreach ($skpds as $skpd) {
            $totalTransaksi = 0;
            $totalDokumenMissing = 0;
            $totalDraft = 0;
            $totalVerified = 0;

            $treeData[$skpd->id] = [
                'nama' => $skpd->kode . ' - ' . $skpd->nama,
                'rekenings' => [],
                'stats' => []
            ];

            foreach ($skpd->transaksis as $trx) {
                if (!$trx->rekening) continue;
                
                $totalTransaksi++;

                if ($trx->status_verifikasi == 'verified') {
                    $totalVerified++;
                } else {
                    $totalDraft++;
                }

                // Cek dokumen yang belum di-upload
                $docMissing = 0;
                if (!$trx->file_ba_manual) $docMissing++;
                if (!$trx->file_buku_kas) $docMissing++;
                if (!$trx->file_buku_pembantu_bank) $docMissing++;
                if (!$trx->file_rekening_koran) $docMissing++;
                
                $totalDokumenMissing += $docMissing;
                
                $rekId = $trx->rekening_id;
                if (!isset($treeData[$skpd->id]['rekenings'][$rekId])) {
                    $treeData[$skpd->id]['rekenings'][$rekId] = [
                        'nama' => $trx->rekening->nama . ' (' . $trx->rekening->nomor . ') - ' . $trx->rekening->bank,
                        'transaksis' => []
                    ];
                }

                $treeData[$skpd->id]['rekenings'][$rekId]['transaksis'][$trx->periode_bulan] = $trx;
            }

            // Simpan ringkasan status di level SKPD
            $treeData[$skpd->id]['stats'] = [
                'transaksi' => $totalTransaksi,
                'missing_docs' => $totalDokumenMissing,
                'draft' => $totalDraft,
                'verified' => $totalVerified
            ];
        }

        $namaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Meneruskan variabel $skpds untuk memunculkan link pagination di view
        $allSkpdList = Skpd::where('status', true)->orderBy('kode')->get();
        return view('dokumen.tree', compact('treeData', 'tahunAktif', 'namaBulan', 'skpds', 'allSkpdList'));
    }

    public function eksporExcel(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator', 'inspektorat'])) {
            abort(403);
        }

        $tahunAktif = session('tahun_login') ?? date('Y');
        $skpds = Skpd::with(['transaksis' => function ($q) use ($tahunAktif) {
            $q->where('periode_tahun', $tahunAktif);
        }, 'transaksis.rekening'])->orderBy('kode')->get();

        $skpdData = [];
        foreach ($skpds as $skpd) {
            $trxPerBulan = [];
            
            foreach ($skpd->transaksis as $trx) {
                if (!$trx->rekening) continue;
                $bulan = $trx->periode_bulan;
                
                if (!isset($trxPerBulan[$bulan])) {
                    $trxPerBulan[$bulan] = ['total' => 0, 'missing' => 0, 'has_draft' => false];
                }
                
                $trxPerBulan[$bulan]['total']++;
                
                if ($trx->status_verifikasi != 'verified') {
                    $trxPerBulan[$bulan]['has_draft'] = true;
                }
                
                $docMissing = 0;
                if (!$trx->file_ba_manual) $docMissing++;
                if (!$trx->file_buku_kas) $docMissing++;
                if (!$trx->file_buku_pembantu_bank) $docMissing++;
                if (!$trx->file_rekening_koran) $docMissing++;
                
                if ($docMissing > 0) {
                    $trxPerBulan[$bulan]['missing']++;
                }
            }

            $bulanStatus = [];
            for ($i = 1; $i <= 12; $i++) {
                if (isset($trxPerBulan[$i])) {
                    $isLengkap = ($trxPerBulan[$i]['missing'] == 0 && $trxPerBulan[$i]['total'] > 0);
                    $rekonStatus = $trxPerBulan[$i]['has_draft'] ? 'Draft' : 'Verified';

                    if ($isLengkap) {
                        $bulanStatus[$i] = $rekonStatus . '|Lengkap';
                    } else {
                        $bulanStatus[$i] = $rekonStatus . '|Kurang';
                    }
                } else {
                    $bulanStatus[$i] = '-';
                }
            }

            $skpdData[] = [
                'skpd' => $skpd,
                'bulan_status' => $bulanStatus,
            ];
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ArsipExport($skpdData, $tahunAktif), 'Laporan_Kelengkapan_Arsip_' . $tahunAktif . '.xlsx');
    }

    public function cetakPdf(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator', 'inspektorat'])) {
            abort(403);
        }

        $tahunAktif = session('tahun_login') ?? date('Y');
        $pengaturan = \App\Models\Pengaturan::first();
        $skpds = Skpd::with(['transaksis' => function ($q) use ($tahunAktif) {
            $q->where('periode_tahun', $tahunAktif);
        }, 'transaksis.rekening'])->orderBy('kode')->get();

        $skpdData = [];
        foreach ($skpds as $skpd) {
            $trxPerBulan = [];
            
            foreach ($skpd->transaksis as $trx) {
                if (!$trx->rekening) continue;
                $bulan = $trx->periode_bulan;
                
                if (!isset($trxPerBulan[$bulan])) {
                    $trxPerBulan[$bulan] = ['total' => 0, 'missing' => 0, 'has_draft' => false];
                }
                
                $trxPerBulan[$bulan]['total']++;
                
                if ($trx->status_verifikasi != 'verified') {
                    $trxPerBulan[$bulan]['has_draft'] = true;
                }
                
                $docMissing = 0;
                if (!$trx->file_ba_manual) $docMissing++;
                if (!$trx->file_buku_kas) $docMissing++;
                if (!$trx->file_buku_pembantu_bank) $docMissing++;
                if (!$trx->file_rekening_koran) $docMissing++;
                
                if ($docMissing > 0) {
                    $trxPerBulan[$bulan]['missing']++;
                }
            }

            $bulanStatus = [];
            for ($i = 1; $i <= 12; $i++) {
                if (isset($trxPerBulan[$i])) {
                    $isLengkap = ($trxPerBulan[$i]['missing'] == 0 && $trxPerBulan[$i]['total'] > 0);
                    $rekonStatus = $trxPerBulan[$i]['has_draft'] ? 'Draft' : 'Verified';

                    if ($isLengkap) {
                        $bulanStatus[$i] = $rekonStatus . '|Lengkap';
                    } else {
                        $bulanStatus[$i] = $rekonStatus . '|Kurang';
                    }
                } else {
                    $bulanStatus[$i] = '-';
                }
            }

            $skpdData[] = [
                'skpd' => $skpd,
                'bulan_status' => $bulanStatus,
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.arsip_pdf', compact('skpdData', 'tahunAktif', 'pengaturan'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Kelengkapan_Arsip_' . $tahunAktif . '.pdf');
    }

    public function downloadZip(Transaksi $transaksi)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator', 'inspektorat'])) {
            abort(403);
        }

        $zip = new \ZipArchive();
        
        $skpdName = ($transaksi->skpd->kode ?? '') . '_' . ($transaksi->skpd->nama ?? 'SKPD');
        $bulan = str_pad($transaksi->periode_bulan, 2, '0', STR_PAD_LEFT);
        $tahun = $transaksi->periode_tahun;
        $fileName = 'Dokumen_' . \Illuminate\Support\Str::slug($skpdName) . '_' . $bulan . '_' . $tahun . '.zip';
        $zipPath = storage_path('app/public/' . $fileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $files = [
                'BA_Manual' => $transaksi->file_ba_manual,
                'Buku_Kas' => $transaksi->file_buku_kas,
                'Buku_Pembantu_Bank' => $transaksi->file_buku_pembantu_bank,
                'Rekening_Koran' => $transaksi->file_rekening_koran,
            ];

            $hasFiles = false;
            foreach ($files as $name => $path) {
                if ($path && \App\Services\SiReKaStorage::exists($path)) {
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                    $content = \App\Services\SiReKaStorage::read($path);
                    if ($content !== null) {
                        $zip->addFromString($name . '.' . $extension, $content);
                        $hasFiles = true;
                    }
                }
            }

            $zip->close();

            if ($hasFiles) {
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }
        }

        return back()->with('error', 'Tidak ada dokumen yang bisa didownload atau zip gagal dibuat.');
    }

    /**
     * Ekspor Massal Arsip Dokumen SiReKa (Bulk ZIP Exporter Skala Kabupaten)
     */
    public function bulkDownloadZip(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator', 'inspektorat'])) {
            abort(403, 'Akses khusus Admin dan Konsolidator');
        }

        $tahunAktif = session('tahun_login') ?? date('Y');
        $bulan = $request->input('bulan', 'all');
        $skpdId = $request->input('skpd_id', 'all');

        $query = Transaksi::with(['skpd', 'rekening'])->where('periode_tahun', $tahunAktif);

        if ($bulan && $bulan !== 'all') {
            $query->where('periode_bulan', $bulan);
        }

        if ($skpdId && $skpdId !== 'all') {
            $query->where('skpd_id', $skpdId);
        }

        $transaksis = $query->get();

        if ($transaksis->isEmpty()) {
            return back()->with('error', 'Tidak ada data transaksi yang cocok dengan filter yang Anda pilih.');
        }

        $zip = new \ZipArchive();
        $namaBulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $labelWaktu = ($bulan !== 'all' && is_numeric($bulan)) ? 'Bulan_' . $namaBulanList[$bulan - 1] : 'Full_Tahun_' . $tahunAktif;
        $fileName = 'Paket_Audit_SiReKa_' . $labelWaktu . '_' . date('His') . '.zip';
        $zipPath = storage_path('app/public/' . $fileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $hasFiles = false;

            foreach ($transaksis as $trx) {
                $skpdSlug = \Illuminate\Support\Str::slug(($trx->skpd->kode ?? '') . ' ' . ($trx->skpd->nama ?? 'Instansi_Tanpa_Nama'));
                $bulanName = str_pad($trx->periode_bulan, 2, '0', STR_PAD_LEFT) . '_' . ($namaBulanList[$trx->periode_bulan - 1] ?? 'Bulan');
                $folder = $skpdSlug . '/' . $bulanName . '/';

                $files = [
                    'BA_Manual' => $trx->file_ba_manual,
                    'Buku_Kas' => $trx->file_buku_kas,
                    'Buku_Pembantu_Bank' => $trx->file_buku_pembantu_bank,
                    'Rekening_Koran' => $trx->file_rekening_koran,
                ];

                foreach ($files as $name => $path) {
                    if ($path && \App\Services\SiReKaStorage::exists($path)) {
                        $extension = pathinfo($path, PATHINFO_EXTENSION);
                        $content = \App\Services\SiReKaStorage::read($path);
                        if ($content !== null) {
                            $zip->addFromString($folder . $name . '.' . $extension, $content);
                            $hasFiles = true;
                        }
                    }
                }
            }

            $zip->close();

            if ($hasFiles) {
                return response()->download($zipPath)->deleteFileAfterSend(true);
            } else {
                if (file_exists($zipPath)) {
                    @unlink($zipPath);
                }
            }
        }

        return back()->with('error', 'Tidak ditemukan fisik file dokumen lampiran di dalam rentang waktu atau instansi yang Anda pilih.');
    }
}
