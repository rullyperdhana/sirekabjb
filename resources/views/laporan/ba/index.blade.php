<x-app-layout>
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: 215mm 330mm; /* F4 / Folio Size */
                margin: 25mm 20mm;
            }
            .max-w-\[215mm\] {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    <!-- Print Action Bar -->
    <div class="no-print bg-surface-container-lowest border-b border-outline-variant sticky top-0 z-10 p-4 shadow-sm flex items-center justify-between">
        <a href="{{ route('ba.index') }}" class="text-primary hover:bg-primary/10 px-4 py-2 rounded-lg font-label-sm text-label-sm transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
            Kembali
        </a>
        <a href="{{ route('ba.pdf', $transaksi->id) }}" target="_blank" class="bg-primary text-on-primary hover:bg-primary/90 px-6 py-2 rounded-lg font-label-sm text-label-sm transition-colors shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">print</span>
            Cetak Berita Acara (PDF)
        </a>
    </div>

    <!-- Paper Container -->
    <div class="max-w-[215mm] mx-auto bg-white p-[20mm] md:shadow-md md:my-8 min-h-[330mm] relative overflow-hidden">
        
        @if($transaksi->tahap_verifikasi !== 'disetujui_final')
        <!-- Watermark Draft -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0 opacity-10 rotate-[-45deg]">
            <span class="text-9xl font-black text-red-600 tracking-widest border-8 border-red-600 px-12 py-4 rounded-3xl">DRAFT</span>
        </div>
        @endif

        <!-- Document Content -->
        <div class="max-w-3xl mx-auto relative z-10">
            
            <!-- KOP Surat (Formal Header) -->
            <div class="flex items-center gap-6 border-b-[3px] border-black pb-4 mb-8">
                <div class="w-24 h-24 shrink-0 flex items-center justify-center">
                    @php 
                        $globalLogo = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
                        if ($globalLogo && str_starts_with($globalLogo, 'logos/')) {
                            $logoUrl = asset('storage/' . $globalLogo);
                        } elseif ($globalLogo && filter_var($globalLogo, FILTER_VALIDATE_URL)) {
                            $logoUrl = $globalLogo;
                        } elseif (file_exists(public_path('images/logo_banjarbaru.png'))) {
                            $logoUrl = asset('images/logo_banjarbaru.png');
                        } else {
                            $logoUrl = null;
                        }
                    @endphp
                    @if($logoUrl)
                        <img class="object-contain h-full w-full" data-alt="Logo" src="{{ $logoUrl }}"/>
                    @else
                        <!-- No logo placeholder -->
                        <div class="w-full h-full flex items-center justify-center border border-dashed border-gray-300 rounded text-gray-400 text-xs text-center p-2">
                            Pemerintah Kota Banjarbaru
                        </div>
                    @endif
                </div>
                <div class="flex-1 text-center text-black">
                    @php
                        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KOTA BANJARBARU|BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH|Jl. Panglima Batur No. 1 Kota Banjarbaru, Kalimantan Selatan 70711|Telp. (0511) 4772545');
                    @endphp
                    @foreach($lines as $index => $line)
                        @if($index === 0)
                            <h2 class="text-xl font-bold uppercase leading-tight tracking-wide">{{ $line }}</h2>
                        @elseif($index === 1)
                            <h1 class="text-2xl font-black uppercase leading-tight tracking-wide">{{ $line }}</h1>
                        @elseif($index === 2)
                            <p class="text-sm mt-1">{{ $line }}</p>
                        @else
                            <p class="text-sm">{{ $line }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Document Title -->
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold uppercase underline underline-offset-4 decoration-2 text-black">BERITA ACARA REKONSILIASI</h2>
                @if($transaksi->nomor_ba)
                    <div class="text-sm font-bold text-black mt-1">Nomor : {{ $transaksi->nomor_ba }}</div>
                @else
                    <div class="text-xs italic text-gray-500 mt-1">Nomor : DRAFT (Menunggu Pengesahan Akhir Inspektorat)</div>
                @endif
                <h3 class="text-lg font-bold mt-2 text-black">Bulan : {{ strtoupper($namaBulan[$transaksi->periode_bulan - 1]) }} {{ $transaksi->periode_tahun }}</h3>
            </div>
            
            <!-- Introductory Text -->
            <div class="text-base text-justify space-y-4 mb-8 leading-relaxed text-black">
                @php
                    $tglSumber = $transaksi->tanggal_ba ? \Carbon\Carbon::parse($transaksi->tanggal_ba) : \Carbon\Carbon::parse($transaksi->updated_at);
                    
                    $tanggal = $tglSumber->locale('id')->isoFormat('dddd');
                    $tglNum = $tglSumber->format('d');
                    $bulanLengkap = $tglSumber->locale('id')->isoFormat('MMMM');
                    $tahunLengkap = $tglSumber->format('Y');
                    
                    $akhirBulan = \Carbon\Carbon::createFromDate($transaksi->periode_tahun, $transaksi->periode_bulan, 1)->endOfMonth()->locale('id')->isoFormat('D MMMM YYYY');
                    
                    $namaInstansi = $lines[1] ?? 'Badan Pengelolaan Keuangan dan Aset Daerah';
                    $namaPemda = $lines[0] ?? 'Kota Banjarbaru';
                    
                    // Ambil template pengantar (Prioritas: Snapshot -> Pengaturan Global -> Default)
                    $templatePengantar = $transaksi->snapshot_pengantar_ba 
                        ?? $pengaturan->teks_pengantar_ba 
                        ?? 'Pada hari ini [HARI] Tanggal [TANGGAL] Bulan [BULAN] Tahun [TAHUN], telah dilakukan rekonsiliasi Saldo Kas Bendahara Pengeluaran per [AKHIR_BULAN] pada [NAMA_INSTANSI] [NAMA_PEMDA].<br><br>Dengan mencocokkan BKU Bendahara Pengeluaran per [AKHIR_BULAN] pada Aplikasi SIPANDA dengan Rekening Koran Bank Kalsel per [AKHIR_BULAN] dengan hasil sebagai berikut :';
                    
                    // Parse placeholders
                    $templatePengantar = str_replace(
                        ['[HARI]', '[TANGGAL]', '[BULAN]', '[TAHUN]', '[AKHIR_BULAN]', '[NAMA_INSTANSI]', '[NAMA_PEMDA]'],
                        [$tanggal, $tglNum, $bulanLengkap, $tahunLengkap, $akhirBulan, ucwords(strtolower($namaInstansi)), ucwords(strtolower($namaPemda))],
                        $templatePengantar
                    );
                    
                    $templatePenutup = $transaksi->snapshot_penutup_ba 
                        ?? $pengaturan->teks_penutup_ba 
                        ?? '** Rincian terlampir';
                        
                    $paragraphs = explode('<br><br>', $templatePengantar);
                @endphp
                
                @foreach($paragraphs as $p)
                <p class="indent-10">
                    {!! str_replace('<br>', '<br/>', $p) !!}
                </p>
                @endforeach
            </div>
            
            <!-- Financial Table -->
            <div class="border border-black mb-8 overflow-hidden rounded-sm text-black">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-black">
                            <th class="py-2 px-3 text-center border-r-[1px] border-black font-bold" colspan="2">BKU Bendahara Pengeluaran</th>
                            <th class="py-2 px-3 text-center font-bold" colspan="2">Rekening Koran Bank Kalsel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-black/30">
                            <td class="py-2 px-3 font-semibold">Saldo Kas Awal</td>
                            <td class="py-2 px-3 text-right font-data-tabular font-bold border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_saldo_awal, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-2 px-3 font-semibold">Saldo Kas Awal</td>
                            <td class="py-2 px-3 text-right font-data-tabular font-bold">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_saldo_awal, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Ditambah -->
                        <tr>
                            <td class="py-2 px-3">Ditambah:</td>
                            <td class="py-2 px-3 border-r-[1px] border-black"></td>
                            <td class="py-2 px-3">Ditambah:</td>
                            <td class="py-2 px-3"></td>
                        </tr>
                        <tr>
                            <td class="py-1 px-3 pl-8">Penerimaan</td>
                            <td class="py-1 px-3 text-right font-data-tabular border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_penerimaan, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-1 px-3 pl-8">Penerimaan</td>
                            <td class="py-1 px-3 text-right font-data-tabular">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_penerimaan, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Dikurang -->
                        <tr>
                            <td class="py-2 px-3 mt-2">Dikurang:</td>
                            <td class="py-2 px-3 border-r-[1px] border-black"></td>
                            <td class="py-2 px-3 mt-2">Dikurang:</td>
                            <td class="py-2 px-3"></td>
                        </tr>
                        <tr class="border-b border-black">
                            <td class="py-1 px-3 pl-8 pb-3">Pengeluaran</td>
                            <td class="py-1 px-3 pb-3 text-right font-data-tabular border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_pengeluaran, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-1 px-3 pl-8 pb-3">Pengeluaran</td>
                            <td class="py-1 px-3 pb-3 text-right font-data-tabular">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_pengeluaran, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Saldo Akhir -->
                        <tr class="font-bold border-b border-black">
                            <td class="py-2 px-3">Saldo Akhir Kas</td>
                            <td class="py-2 px-3 text-right font-data-tabular border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-2 px-3">Saldo Akhir Kas</td>
                            <td class="py-2 px-3 text-right font-data-tabular">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Selisih -->
                        @php
                            $selisih = $transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir;
                        @endphp
                        <tr class="font-bold bg-gray-100 print:bg-transparent">
                            <td class="py-2 px-3 text-center border-r-[1px] border-black italic" colspan="2">Selisih</td>
                            <td class="py-2 px-3 text-right font-data-tabular" colspan="2">
                                <div class="flex justify-center w-full gap-4 {{ abs($selisih) > 0 ? 'text-red-600 print:text-black' : '' }}"><span>Rp</span><span>{{ number_format($selisih, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Penjelasan -->
            @if(abs($selisih) > 0)
            <div class="mb-8 text-black">
                <h4 class="text-base font-bold mb-1">Penjelasan :</h4>
                <p class="text-sm leading-relaxed text-justify">
                    {{ $transaksi->keterangan_selisih ?: '-' }}
                </p>
            </div>
            @endif
            
            <!-- Lampiran Note -->
            <div class="mb-10 text-sm font-medium text-black">
                {!! nl2br(str_replace('<br>', "\n", $templatePenutup)) !!}
            </div>
            
            <!-- Signatures Section -->
            <div class="grid grid-cols-2 gap-8 mb-12 text-black text-sm">
                <div class="text-center">
                    <p class="mb-20">Pembuatan Laporan,<br>{{ $pengaturan->jabatan_bendahara ?? 'Bendahara Pengeluaran' }}</p>
                    <p class="font-bold underline underline-offset-2 uppercase">{{ $pengaturan->nama_bendahara ?? '.........................' }}</p>
                    <p>{{ $pengaturan->pangkat_bendahara ?? '.........................' }}</p>
                    <p>NIP. {{ $pengaturan->nip_bendahara ?? '.........................' }}</p>
                </div>
                <div class="text-center">
                    <p class="mb-20">Menyetujui,<br>{{ $pengaturan->jabatan_kasubag ?? 'Kasubag Keuangan' }}</p>
                    <p class="font-bold underline underline-offset-2 uppercase">{{ $pengaturan->nama_kasubag ?? '.........................' }}</p>
                    <p>{{ $pengaturan->pangkat_kasubag ?? '.........................' }}</p>
                    <p>NIP. {{ $pengaturan->nip_kasubag ?? '.........................' }}</p>
                </div>
                
                <!-- Bawah Tengah: Kepala SKPD -->
                <div class="col-span-2 text-center mt-12">
                    @php
                        // Coba cari kota dari isi_kop
                        $kotaFallback = 'Banjarbaru';
                        $lastLine = end($lines);
                        if(stripos($lastLine, 'Banjarbaru') !== false) {
                            $kotaFallback = 'Banjarbaru';
                        }
                    @endphp
                    <p class="mb-1">{{ $kotaFallback }}, {{ $tglSumber->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    <p class="font-bold mb-16">Mengetahui,<br>{{ $pengaturan->jabatan_kepala ?? 'Pengguna Anggaran / Kuasa Pengguna Anggaran' }}</p>
                    <p class="font-bold underline underline-offset-2 uppercase">{{ $pengaturan->nama_kepala ?? '.........................' }}</p>
                    <p>{{ $pengaturan->pangkat_kepala ?? '.........................' }}</p>
                    <p>NIP. {{ $pengaturan->nip_kepala ?? '.........................' }}</p>
                </div>
            </div>
            
            <div class="flex flex-wrap justify-between items-end text-black text-sm gap-4 pt-4 border-t border-gray-200">
                <!-- Lampiran List -->
                <div>
                    <p class="font-bold italic mb-1">Lampiran Berkas:</p>
                    <ol class="list-decimal list-inside italic text-xs space-y-0.5">
                        <li>Buku Kas Pengeluaran (BKU)</li>
                        <li>Buku Pembantu Bank</li>
                        <li>Rekening Koran Bank Kalsel</li>
                    </ol>
                </div>

                <!-- Verification Badges -->
                <div class="flex items-center gap-3">
                    @if($transaksi->status_konsolidator === 'valid')
                    <div class="border border-emerald-600 border-dashed bg-emerald-50 rounded-lg p-2 text-center text-xs">
                        <div class="font-bold text-emerald-800 text-[10px]">TELAH DIUJI SAH</div>
                        <div class="font-bold text-emerald-700 text-[9px]">KONSOLIDATOR BPKAD</div>
                        <div class="text-[8px] text-emerald-600 font-mono mt-0.5">
                            {{ $transaksi->checked_at ? \Carbon\Carbon::parse($transaksi->checked_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : 'VALID' }}
                        </div>
                    </div>
                    @endif

                    @if($transaksi->inspektorat_status === 'valid')
                    <div class="border border-indigo-600 border-dashed bg-indigo-50 rounded-lg p-2 text-center text-xs">
                        <div class="font-bold text-indigo-800 text-[10px]">PENGESAHAN AKHIR</div>
                        <div class="font-bold text-indigo-700 text-[9px]">INSPEKTORAT BANJARBARU</div>
                        <div class="text-[8px] text-indigo-600 font-mono mt-0.5">
                            {{ $transaksi->inspektorat_checked_at ? \Carbon\Carbon::parse($transaksi->inspektorat_checked_at)->timezone('Asia/Makassar')->format('d/m/y H:i') : 'TERCATAT' }}
                        </div>
                    </div>
                    @endif

                    @if($transaksi->tahap_verifikasi === 'disetujui_final' || $transaksi->status_verifikasi === 'verified')
                    <div class="flex flex-col items-center justify-center">
                        <div class="p-1 border border-black inline-block bg-white shadow-sm">
                            @php
                                $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.show', $transaksi->id);
                                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($signedUrl);
                                $qrData = @file_get_contents($qrUrl);
                                $qrBase64 = $qrData ? base64_encode($qrData) : '';
                            @endphp
                            @if($qrBase64)
                                <img src="data:image/png;base64,{{ $qrBase64 }}" width="70" height="70">
                            @else
                                <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->generate($signedUrl)) !!}" width="70" height="70">
                            @endif
                        </div>
                        <span class="text-[9px] mt-1 italic font-bold">Segel Digital SiReKa</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body {
                background: white !important;
            }
            .max-w-\[1000px\] {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            header, nav, aside, .mb-6 {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
