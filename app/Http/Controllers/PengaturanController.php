<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    public function edit()
    {
        $skpdId = auth()->user()->skpd_id;
        $skpdName = auth()->user()->skpd ? auth()->user()->skpd->nama : 'BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH';
        
        $pengaturan = Pengaturan::firstOrCreate(
            ['skpd_id' => $skpdId],
            [
                'isi_kop' => "PEMERINTAH KOTA BANJARBARU|{$skpdName}|Jalan Panglima Batur No. 1|Kelurahan Komet, Kecamatan Banjarbaru Utara Telp. (0511) 4782098 Email: bpkad@banjarbarukota.go.id",
                'format_nomor_ba' => '900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}',
                'nama_kepala' => 'Drs. H. JAINUDIN, M.Si',
                'nip_kepala' => '19680512 199303 1 005',
                'pangkat_kepala' => 'PEMBINA UTAMA MUDA (IV/c)',
                'jabatan_kepala' => 'KEPALA BADAN',
                'nama_bendahara' => 'BENDAHARA UMUM DAERAH',
                'nip_bendahara' => '19820415 200604 1 008',
                'pangkat_bendahara' => 'PENATA TK. I (III/d)',
                'jabatan_bendahara' => 'KUASA BUD',
                'nama_kasubag' => 'KASUBAG KEUANGAN',
                'nip_kasubag' => '19850920 200902 2 003',
                'pangkat_kasubag' => 'PENATA (III/c)',
                'jabatan_kasubag' => 'KASUBAG KEUANGAN & ASET',
            ]
        );

        $previewNomorBa = app(\App\Services\BaNumberService::class)->preview($pengaturan->format_nomor_ba ?? '900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}');

        return view('pengaturan.instansi.edit', compact('pengaturan', 'previewNomorBa'));
    }

    public function update(Request $request)
    {
        $skpdId = auth()->user()->skpd_id;
        $pengaturan = Pengaturan::firstOrCreate(['skpd_id' => $skpdId]);
        
        $validated = $request->validate([
            'isi_kop' => 'required|string',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|extensions:jpeg,png,jpg,svg,webp|max:2048',
            'format_nomor_ba' => 'nullable|string|max:255',
            'nama_kepala' => 'required|string|max:255',
            'nip_kepala' => 'required|string|max:255',
            'pangkat_kepala' => 'required|string|max:255',
            'jabatan_kepala' => 'required|string|max:255',
            'nama_bendahara' => 'required|string|max:255',
            'nip_bendahara' => 'required|string|max:255',
            'pangkat_bendahara' => 'required|string|max:255',
            'jabatan_bendahara' => 'required|string|max:255',
            'nama_kasubag' => 'required|string|max:255',
            'nip_kasubag' => 'required|string|max:255',
            'pangkat_kasubag' => 'required|string|max:255',
            'jabatan_kasubag' => 'required|string|max:255',
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('logos', 'public');
            $validated['logo'] = $path;
        }
        unset($validated['logo_file']);

        if (auth()->user()->role === 'admin') {
            $validated['is_registration_open'] = $request->has('is_registration_open') ? true : false;
            $validated['allow_operator_reupload'] = $request->has('allow_operator_reupload') ? true : false;
            $validated['is_livelog_active'] = $request->has('is_livelog_active') ? true : false;
            $validated['allow_edit_saldo_awal'] = $request->has('allow_edit_saldo_awal') ? true : false;
            $validated['allow_skpd_download_bukti_digital'] = $request->has('allow_skpd_download_bukti_digital') ? true : false;
            $validated['is_2fa_active'] = $request->has('is_2fa_active') ? true : false;
            $validated['is_2fa_mandatory_for_critical_roles'] = $request->has('is_2fa_mandatory_for_critical_roles') ? true : false;
            
            if ($request->filled('format_nomor_ba')) {
                $validated['format_nomor_ba'] = $request->format_nomor_ba;
            }
            if ($request->has('teks_pengantar_ba')) {
                $validated['teks_pengantar_ba'] = $request->teks_pengantar_ba;
            }
            if ($request->has('teks_penutup_ba')) {
                $validated['teks_penutup_ba'] = $request->teks_penutup_ba;
            }
        }

        $pengaturan->update($validated);

        return redirect()->route('pengaturan.instansi.edit')->with('success', 'Pengaturan instansi dan format Berita Acara berhasil diperbarui.');
    }
}
