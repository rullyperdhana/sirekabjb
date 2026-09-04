<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\VerifikasiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiBankController extends Controller
{
    public function __construct()
    {
        // Hanya role bank dan admin yang berhak mengakses
        $this->middleware(function ($request, $next) {
            if (!in_array(Auth::user()->role, ['bank', 'admin'])) {
                abort(403, 'Akses terbatas untuk Verifikator Pihak Bank dan Administrator.');
            }
            return $next($request);
        });
    }

    /**
     * Tampilkan antrean verifikasi rekening koran oleh Bank
     */
    public function index(Request $request)
    {
        $tahun = session('tahun_login', date('Y'));
        $statusFilter = $request->get('status', 'menunggu');

        $query = Transaksi::with(['skpd', 'rekening', 'user'])
            ->where('periode_tahun', $tahun);

        if ($statusFilter === 'menunggu') {
            $query->where(function ($q) {
                $q->where('tahap_verifikasi', 'menunggu_bank')
                  ->orWhere(function ($sub) {
                      // Kompatibilitas jika ada data lama yang baru diajukan
                      $sub->where('tahap_verifikasi', 'skpd_draft')
                          ->where('status_verifikasi', 'verified');
                  });
            });
        } elseif ($statusFilter === 'valid') {
            $query->where('bank_status', 'valid');
        } elseif ($statusFilter === 'revisi') {
            $query->where('bank_status', 'revisi');
        }

        $transaksis = $query->orderBy('periode_bulan', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        $pendingCount = Transaksi::where('periode_tahun', $tahun)
            ->where(function ($q) {
                $q->where('tahap_verifikasi', 'menunggu_bank')
                  ->orWhere(function ($sub) {
                      $sub->where('tahap_verifikasi', 'skpd_draft')
                          ->where('status_verifikasi', 'verified');
                  });
            })->count();

        $validCount = Transaksi::where('periode_tahun', $tahun)->where('bank_status', 'valid')->count();
        $revisiCount = Transaksi::where('periode_tahun', $tahun)->where('bank_status', 'revisi')->count();

        return view('verifikasi.bank.index', compact('transaksis', 'pendingCount', 'validCount', 'revisiCount', 'statusFilter'));
    }

    /**
     * Review perbandingan saldo & rekening koran
     */
    public function review(Transaksi $transaksi)
    {
        $transaksi->load(['skpd', 'rekening', 'user', 'bankChecker', 'verifikasiLogs.user']);
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('verifikasi.bank.review', compact('transaksi', 'namaBulan'));
    }

    /**
     * Sahkan Saldo Bank (Pilar 2 Valid)
     */
    public function approve(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $catatan = $request->catatan ?? 'Nilai mutasi dan saldo rekening koran telah dicocokkan dan sesuai dengan core banking Bank Kalsel.';
        $now = now();
        $user = Auth::user();

        $transaksi->update([
            'bank_verified_by' => $user->id,
            'bank_verified_at' => $now,
            'bank_status' => 'valid',
            'bank_catatan' => $catatan,
            'tahap_verifikasi' => 'menunggu_konsolidator',
        ]);

        // Catat Audit Trail
        VerifikasiLog::create([
            'transaksi_id' => $transaksi->id,
            'user_id' => $user->id,
            'role' => $user->role,
            'stage' => 'verifikasi_bank',
            'aksi' => 'setuju',
            'status_sebelum' => 'menunggu_bank',
            'status_sesudah' => 'menunggu_konsolidator',
            'catatan' => $catatan,
            'trace_hash' => VerifikasiLog::createHash($transaksi->id, $user->id, 'verifikasi_bank', 'setuju', $now->timestamp),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('verifikasi.bank.index')->with('success', 'Rekonsiliasi rekening koran berhasil disahkan oleh Bank Kalsel. Berkas diteruskan ke Konsolidator BPKAD.');
    }

    /**
     * Kembalikan Revisi Bank
     */
    public function revisi(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ], [
            'catatan.required' => 'Wajib memberikan catatan perbaikan atau rincian selisih untuk SKPD bersangkutan.',
        ]);

        $user = Auth::user();
        $now = now();

        $transaksi->update([
            'bank_verified_by' => $user->id,
            'bank_verified_at' => $now,
            'bank_status' => 'revisi',
            'bank_catatan' => $request->catatan,
            'tahap_verifikasi' => 'revisi_bank',
            'status_verifikasi' => 'draft', // Buka kunci agar SKPD bisa memperbaiki inputan
        ]);

        VerifikasiLog::create([
            'transaksi_id' => $transaksi->id,
            'user_id' => $user->id,
            'role' => $user->role,
            'stage' => 'verifikasi_bank',
            'aksi' => 'revisi',
            'status_sebelum' => 'menunggu_bank',
            'status_sesudah' => 'revisi_bank',
            'catatan' => $request->catatan,
            'trace_hash' => VerifikasiLog::createHash($transaksi->id, $user->id, 'verifikasi_bank', 'revisi', $now->timestamp),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('verifikasi.bank.index')->with('warning', 'Berkas dikembalikan ke Operator SKPD dengan catatan revisi pihak bank.');
    }
}
