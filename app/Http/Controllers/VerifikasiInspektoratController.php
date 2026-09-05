<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\VerifikasiLog;
use App\Services\BaNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiInspektoratController extends Controller
{
    protected $baNumberService;

    public function __construct(BaNumberService $baNumberService)
    {
        $this->baNumberService = $baNumberService;
    }

    private function checkAccess()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['inspektorat', 'admin'])) {
            abort(403, 'Akses terbatas untuk Auditor Inspektorat dan Administrator.');
        }
    }

    /**
     * Tampilkan antrean pengesahan akhir Berita Acara oleh Inspektorat
     */
    public function index(Request $request)
    {
        $this->checkAccess();
        $tahun = session('tahun_login', date('Y'));
        $statusFilter = $request->get('status', 'menunggu');

        $query = Transaksi::with(['skpd', 'rekening', 'user', 'bankChecker', 'checker'])
            ->where('periode_tahun', $tahun);

        if ($statusFilter === 'menunggu') {
            $query->where('tahap_verifikasi', 'menunggu_inspektorat');
        } elseif ($statusFilter === 'disetujui') {
            $query->where('tahap_verifikasi', 'disetujui_final');
        } elseif ($statusFilter === 'revisi') {
            $query->where('tahap_verifikasi', 'revisi_inspektorat');
        }

        $transaksis = $query->orderBy('periode_bulan', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        $pendingCount = Transaksi::where('periode_tahun', $tahun)->where('tahap_verifikasi', 'menunggu_inspektorat')->count();
        $approvedCount = Transaksi::where('periode_tahun', $tahun)->where('tahap_verifikasi', 'disetujui_final')->count();
        $revisiCount = Transaksi::where('periode_tahun', $tahun)->where('tahap_verifikasi', 'revisi_inspektorat')->count();

        return view('verifikasi.inspektorat.index', compact('transaksis', 'pendingCount', 'approvedCount', 'revisiCount', 'statusFilter'));
    }

    /**
     * Review pengawasan internal sebelum penerbitan BA
     */
    public function review(Transaksi $transaksi)
    {
        $this->checkAccess();
        $transaksi->load(['skpd', 'rekening', 'user', 'bankChecker', 'checker', 'verifikasiLogs.user']);
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Siapkan nomor BA usulan jika belum ada
        $suggestedNomorBa = $transaksi->nomor_ba ?? $this->baNumberService->generate($transaksi);

        return view('verifikasi.inspektorat.review', compact('transaksi', 'namaBulan', 'suggestedNomorBa'));
    }

    /**
     * Sahkan & Terbitkan Berita Acara Final
     */
    public function approve(Request $request, Transaksi $transaksi)
    {
        $this->checkAccess();
        $request->validate([
            'nomor_ba' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:500',
        ], [
            'nomor_ba.required' => 'Nomor Berita Acara wajib diisi untuk penerbitan resmi.',
        ]);

        $user = Auth::user();
        $now = now();
        $catatan = $request->catatan ?? 'Telah melalui pengawasan internal dan seluruh tahapan verifikasi SKPD, Bank Kalsel, dan Konsolidator dinyatakan sah.';

        $transaksi->update([
            'nomor_ba' => $request->nomor_ba,
            'inspektorat_verified_by' => $user->id,
            'inspektorat_verified_at' => $now,
            'inspektorat_status' => 'valid',
            'inspektorat_catatan' => $catatan,
            'tahap_verifikasi' => 'disetujui_final',
            'status_verifikasi' => 'verified',
            'status_konsolidator' => 'valid',
        ]);

        // Catat Audit Trail
        VerifikasiLog::create([
            'transaksi_id' => $transaksi->id,
            'user_id' => $user->id,
            'role' => $user->role,
            'stage' => 'pengesahan_inspektorat',
            'aksi' => 'terbitkan_ba',
            'status_sebelum' => 'menunggu_inspektorat',
            'status_sesudah' => 'disetujui_final',
            'catatan' => "Nomor BA: {$request->nomor_ba} | {$catatan}",
            'trace_hash' => VerifikasiLog::createHash($transaksi->id, $user->id, 'pengesahan_inspektorat', 'terbitkan_ba', $now->timestamp),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('verifikasi.inspektorat.index')->with('success', "Berita Acara resmi Nomor {$request->nomor_ba} berhasil diterbitkan dan disahkan!");
    }

    /**
     * Kembalikan dengan Catatan Audit Internal
     */
    public function revisi(Request $request, Transaksi $transaksi)
    {
        $this->checkAccess();
        $request->validate([
            'catatan' => 'required|string|max:500',
        ], [
            'catatan.required' => 'Wajib memberikan alasan atau catatan pengawasan internal.',
        ]);

        $user = Auth::user();
        $now = now();

        $transaksi->update([
            'inspektorat_verified_by' => $user->id,
            'inspektorat_verified_at' => $now,
            'inspektorat_status' => 'revisi',
            'inspektorat_catatan' => $request->catatan,
            'tahap_verifikasi' => 'revisi_inspektorat',
        ]);

        VerifikasiLog::create([
            'transaksi_id' => $transaksi->id,
            'user_id' => $user->id,
            'role' => $user->role,
            'stage' => 'pengesahan_inspektorat',
            'aksi' => 'revisi',
            'status_sebelum' => 'menunggu_inspektorat',
            'status_sesudah' => 'revisi_inspektorat',
            'catatan' => $request->catatan,
            'trace_hash' => VerifikasiLog::createHash($transaksi->id, $user->id, 'pengesahan_inspektorat', 'revisi', $now->timestamp),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('verifikasi.inspektorat.index')->with('warning', 'Berkas dikembalikan dengan catatan pengawasan internal Inspektorat.');
    }
}
