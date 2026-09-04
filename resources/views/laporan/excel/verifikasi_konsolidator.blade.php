<table>
    <tr>
        <td colspan="10" style="font-weight: bold; font-size: 14px; text-align: center;">REGISTER HASIL PEMERIKSAAN & VERIFIKASI KONSOLIDATOR KAS DAERAH</td>
    </tr>
    <tr>
        <td colspan="10" style="font-weight: bold; text-align: center;">BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH (BPKAD) KOTA BANJARBARU</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center;">
            Tahun Anggaran: {{ $tahunAktif }} | 
            Periode: {{ $selectedBulan ? $namaBulan[$selectedBulan - 1] : 'Semua Bulan' }} | 
            Status: {{ $selectedStatus ? strtoupper(str_replace('_', ' ', $selectedStatus)) : 'SEMUA STATUS' }}
        </td>
    </tr>
    <tr></tr>
    <thead>
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <th style="border: 1px solid #000; text-align: center;">No</th>
            <th style="border: 1px solid #000; text-align: center;">Bulan</th>
            <th style="border: 1px solid #000;">Instansi (SKPD)</th>
            <th style="border: 1px solid #000;">Rekening Bank</th>
            <th style="border: 1px solid #000; text-align: right;">Saldo BKU (Rp)</th>
            <th style="border: 1px solid #000; text-align: right;">Saldo Bank (Rp)</th>
            <th style="border: 1px solid #000; text-align: right;">Selisih Kas (Rp)</th>
            <th style="border: 1px solid #000; text-align: center;">Bukti Dukung</th>
            <th style="border: 1px solid #000; text-align: center;">Status Verifikasi</th>
            <th style="border: 1px solid #000;">Tanggal Diperiksa (WITA)</th>
            <th style="border: 1px solid #000;">Konsolidator Pemeriksa</th>
            <th style="border: 1px solid #000;">Catatan / Evaluasi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transaksis as $trx)
            @php
                $selisih = abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir);
                $docsCount = 0;
                if (!empty($trx->file_ba_manual) && \App\Services\SiReKaStorage::exists($trx->file_ba_manual)) $docsCount++;
                if (!empty($trx->file_buku_kas) && \App\Services\SiReKaStorage::exists($trx->file_buku_kas)) $docsCount++;
                if (!empty($trx->file_buku_pembantu_bank) && \App\Services\SiReKaStorage::exists($trx->file_buku_pembantu_bank)) $docsCount++;
                if (!empty($trx->file_rekening_koran) && \App\Services\SiReKaStorage::exists($trx->file_rekening_koran)) $docsCount++;
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $namaBulan[$trx->periode_bulan - 1] }}</td>
                <td style="border: 1px solid #000;">{{ $trx->skpd->nama ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $trx->rekening->nomor ?? '-' }} ({{ $trx->rekening->bank ?? '-' }})</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $trx->bku_saldo_akhir }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $trx->bank_saldo_akhir }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $selisih }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $docsCount }}/4 Berkas</td>
                <td style="border: 1px solid #000; text-align: center;">
                    {{ $trx->status_konsolidator === 'valid' ? 'VALID (SAH)' : ($trx->status_konsolidator === 'perlu_perbaikan' ? 'PERLU PERBAIKAN' : 'MENUNGGU CEK') }}
                </td>
                <td style="border: 1px solid #000;">
                    {{ $trx->checked_at ? \Carbon\Carbon::parse($trx->checked_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') . ' WITA' : '-' }}
                </td>
                <td style="border: 1px solid #000;">{{ $trx->checker->name ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $trx->catatan_konsolidator_terakhir ?: '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12" style="border: 1px solid #000; text-align: center;">Tidak ada data rekonsiliasi yang sesuai.</td>
            </tr>
        @endforelse
    </tbody>
</table>
