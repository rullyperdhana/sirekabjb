<x-app-layout>
@section('title', 'Broadcast Rekap WhatsApp')
<style>
    #appMain { max-width: 100% !important; }
</style>

<div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h2 class="text-headline-sm font-headline-sm text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-[#25D366] text-3xl" data-weight="fill">chat</span>
            Broadcast Rekap Rekonsiliasi (Group WA)
        </h2>
        <p class="text-body-md font-body-md text-on-surface-variant">
            Generator teks rekapitulasi cepat untuk dikirimkan ke Group WhatsApp SKPD pada Bulan {{ $namaBulanTerpilih }} Tahun {{ $tahunAktif }}.
        </p>
    </div>
    <div class="flex gap-2 mt-2 md:mt-0">
        <a href="{{ route('laporan.konsolidasi', ['bulan' => $selectedBulan]) }}" class="h-10 px-4 bg-surface-variant text-on-surface-variant hover:bg-surface-variant/80 rounded-lg flex items-center gap-2 text-label-md transition-colors shadow-sm font-semibold">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Kembali ke Konsolidasi
        </a>
    </div>
</div>

<!-- Filter Box -->
<div class="bg-surface rounded-xl shadow-sm border border-outline-variant p-6 mb-8">
    <form action="{{ route('laporan.rekap-wa') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/4">
            <label for="bulan" class="block text-label-md font-label-md text-on-surface mb-1 font-semibold">Pilih Bulan</label>
            <select name="bulan" id="bulan" class="w-full h-11 px-3 rounded-lg border border-outline bg-surface text-body-md focus:ring-2 focus:ring-[#25D366]/30 focus:border-[#25D366] outline-none transition-all">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $selectedBulan == $i ? 'selected' : '' }}>{{ $namaBulan[$i - 1] }}</option>
                @endfor
            </select>
        </div>
        
        <div class="w-full md:w-1/3">
            <label for="kriteria" class="block text-label-md font-label-md text-on-surface mb-1 font-semibold">Kriteria "Sudah Rekonsiliasi"</label>
            <select name="kriteria" id="kriteria" class="w-full h-11 px-3 rounded-lg border border-outline bg-surface text-body-md focus:ring-2 focus:ring-[#25D366]/30 focus:border-[#25D366] outline-none transition-all">
                <option value="all" {{ $kriteria === 'all' ? 'selected' : '' }}>Semua yang Sudah Input Laporan (Draft + Verified)</option>
                <option value="verified" {{ $kriteria === 'verified' ? 'selected' : '' }}>Khusus yang Sudah Diverifikasi Selesai (Verified Only)</option>
                <option value="uploaded_ba" {{ $kriteria === 'uploaded_ba' ? 'selected' : '' }}>Khusus yang Sudah Upload Berita Acara (BA Manual)</option>
                <option value="uploaded_all" {{ $kriteria === 'uploaded_all' ? 'selected' : '' }}>Khusus yang Sudah Upload Lengkap (4 Dokumen)</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="h-11 px-6 bg-primary text-on-primary hover:bg-primary/90 rounded-lg flex items-center gap-2 font-label-md transition-colors shadow-sm">
                <span class="material-symbols-outlined" data-weight="fill">filter_alt</span>
                Tampilkan Rekap
            </button>
        </div>
    </form>
</div>

@php
    $totalSkpd = count($sudahRekon) + count($belumRekon);
    $persenSelesai = $totalSkpd > 0 ? round((count($sudahRekon) / $totalSkpd) * 100) : 0;
@endphp

<!-- Main Content Split -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- Left Column: Summary & Interactive SKPD Lists (5 Columns) -->
    <div class="lg:col-span-5 space-y-6">
        <!-- Progress Summary Card -->
        <div class="bg-surface rounded-xl shadow-sm border border-outline-variant p-6 relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-title-md font-title-md font-bold text-on-surface">Statistik Rekonsiliasi</h3>
                    <p class="text-body-sm text-on-surface-variant">Periode: {{ $namaBulanTerpilih }} {{ $tahunAktif }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#25D366]/10 text-[#25D366] flex items-center justify-center font-bold text-lg">
                    {{ $persenSelesai }}%
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-surface-container-highest h-3 rounded-full overflow-hidden mb-6">
                <div class="bg-[#25D366] h-full transition-all duration-500 rounded-full" style="width: {{ $persenSelesai }}%"></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-lg bg-emerald-50 text-emerald-900 border border-emerald-200">
                    <span class="text-xs font-semibold text-emerald-700 uppercase block mb-1">Sudah Rekonsiliasi</span>
                    <span class="text-2xl font-bold font-data-tabular flex items-center gap-1.5 text-emerald-700">
                        <span class="material-symbols-outlined text-xl" data-weight="fill">check_circle</span>
                        {{ count($sudahRekon) }} <span class="text-sm font-normal">SKPD</span>
                    </span>
                </div>
                <div class="p-4 rounded-lg bg-rose-50 text-rose-900 border border-rose-200">
                    <span class="text-xs font-semibold text-rose-700 uppercase block mb-1">Belum Rekonsiliasi</span>
                    <span class="text-2xl font-bold font-data-tabular flex items-center gap-1.5 text-rose-600">
                        <span class="material-symbols-outlined text-xl" data-weight="fill">cancel</span>
                        {{ count($belumRekon) }} <span class="text-sm font-normal">SKPD</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Daftar SKPD Belum Rekon (Prioritas Pantau) -->
        <div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="p-4 bg-rose-500 text-white flex justify-between items-center font-semibold">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">warning</span>
                    <span>Daftar Belum Rekonsiliasi ({{ count($belumRekon) }})</span>
                </div>
            </div>
            <div class="max-h-64 overflow-y-auto divide-y divide-outline-variant/50 p-2">
                @forelse($belumRekon as $idx => $item)
                <div class="p-2.5 flex items-center justify-between hover:bg-surface-container-lowest transition-colors">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <span class="text-xs font-bold text-on-surface-variant w-6">{{ $idx + 1 }}.</span>
                        <span class="text-body-sm font-medium text-on-surface truncate" title="{{ $item['nama'] }}">{{ $item['nama'] }}</span>
                    </div>
                    @if($item['no_wa'])
                        @php
                            $pesanWa = "Yth. Admin SKPD {$item['nama']}.\nMohon segera menyelesaikan dan mengirimkan laporan rekonsiliasi kas untuk Bulan {$namaBulanTerpilih} {$tahunAktif} di aplikasi SiReKa. Terima kasih.";
                        @endphp
                        <a href="{{ $item['skpd']->getWhatsappUrl($pesanWa) }}" target="_blank" class="p-1.5 rounded bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors text-xs flex items-center gap-1 shrink-0" title="Chat Individual">
                            <span class="material-symbols-outlined text-sm" data-weight="fill">send</span>
                            <span>WA</span>
                        </a>
                    @else
                        <span class="text-[10px] text-outline italic shrink-0">No WA</span>
                    @endif
                </div>
                @empty
                <div class="p-4 text-center text-secondary font-medium">
                    🎉 Luar biasa! Semua SKPD sudah melakukan rekonsiliasi bulan ini.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Daftar SKPD Sudah Rekon -->
        <div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="p-4 bg-emerald-600 text-white flex justify-between items-center font-semibold">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">verified</span>
                    <span>Daftar Sudah Rekonsiliasi ({{ count($sudahRekon) }})</span>
                </div>
            </div>
            <div class="max-h-64 overflow-y-auto divide-y divide-outline-variant/50 p-2">
                @forelse($sudahRekon as $idx => $item)
                <div class="p-2.5 flex items-center justify-between hover:bg-surface-container-lowest transition-colors">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <span class="text-xs font-bold text-on-surface-variant w-6">{{ $idx + 1 }}.</span>
                        <span class="text-body-sm font-medium text-on-surface truncate" title="{{ $item['nama'] }}">{{ $item['nama'] }}</span>
                    </div>
                    <div>
                        @if($item['status'] === 'verified')
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase">Verified</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold uppercase">Draft</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-error italic">
                    Belum ada SKPD yang melakukan rekonsiliasi.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: WhatsApp Broadcast Generator (7 Columns) -->
    <div class="lg:col-span-7">
        <div class="bg-surface rounded-xl shadow-md border border-outline-variant overflow-hidden">
            <!-- Header Card -->
            <div class="p-6 bg-[#25D366]/10 border-b border-[#25D366]/20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-md">
                        <span class="material-symbols-outlined text-2xl" data-weight="fill">campaign</span>
                    </div>
                    <div>
                        <h3 class="text-title-lg font-title-lg font-bold text-on-surface">Generator Teks Group WA</h3>
                        <p class="text-body-sm text-on-surface-variant">Sesuaikan opsi dan format pesan di bawah ini sebelum disalin/dikirim.</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Toggles & Options -->
                <div class="bg-surface-container-low p-4 rounded-lg border border-outline-variant flex flex-wrap gap-4 items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer text-body-sm font-medium text-on-surface">
                        <input type="checkbox" id="toggleNomor" checked class="w-4 h-4 text-[#25D366] rounded focus:ring-[#25D366]">
                        <span>Sertakan Nomor Urut (1, 2, 3...)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-body-sm font-medium text-on-surface">
                        <input type="checkbox" id="toggleStatus" class="w-4 h-4 text-[#25D366] rounded focus:ring-[#25D366]">
                        <span>Sertakan Status (Verified/Draft)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-body-sm font-medium text-on-surface">
                        <input type="checkbox" id="togglePeringatan" checked class="w-4 h-4 text-[#25D366] rounded focus:ring-[#25D366]">
                        <span>Sertakan Pesan Penutup & Imbauan</span>
                    </label>
                </div>

                <!-- Textarea Editor -->
                <div>
                    <label for="waTextarea" class="block text-label-md font-bold text-on-surface mb-2 flex justify-between items-center">
                        <span>Preview & Edit Teks Siap Kirim:</span>
                        <span class="text-xs font-normal text-on-surface-variant italic">(Anda dapat mengedit teks ini secara langsung)</span>
                    </label>
                    <textarea id="waTextarea" rows="18" class="w-full p-4 rounded-xl border-2 border-outline-variant focus:border-[#25D366] focus:ring-4 focus:ring-[#25D366]/20 font-mono text-body-sm leading-relaxed outline-none bg-surface-container-lowest transition-all text-on-surface shadow-inner"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button id="btnCopy" type="button" class="flex-1 h-13 py-3 px-6 bg-surface-variant text-on-surface hover:bg-surface-variant/80 active:scale-[0.98] rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-sm border border-outline">
                        <span class="material-symbols-outlined text-lg">content_copy</span>
                        <span>Salin Teks ke Clipboard</span>
                    </button>
                    <button id="btnSendWa" type="button" class="flex-1 h-13 py-3 px-6 bg-[#25D366] text-white hover:bg-[#1ebd5a] active:scale-[0.98] rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-[#25D366]/30 text-base">
                        <span class="material-symbols-outlined text-xl" data-weight="fill">send</span>
                        <span>Buka di WhatsApp Group (Web/App)</span>
                    </button>
                </div>

                <!-- Toast/Feedback -->
                <div id="copyToast" class="hidden bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-md flex items-center gap-2 justify-center transition-all animate-bounce">
                    <span class="material-symbols-outlined text-white" data-weight="fill">check_circle</span>
                    <span class="font-semibold text-sm">Teks berhasil disalin! Siap ditempel (paste) di aplikasi WhatsApp Anda.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sudahRekonData = @json($sudahRekon);
        const belumRekonData = @json($belumRekon);
        const bulanText = "{{ $namaBulanTerpilih }}";
        const tahunText = "{{ $tahunAktif }}";
        
        const toggleNomor = document.getElementById("toggleNomor");
        const toggleStatus = document.getElementById("toggleStatus");
        const togglePeringatan = document.getElementById("togglePeringatan");
        const waTextarea = document.getElementById("waTextarea");
        const btnCopy = document.getElementById("btnCopy");
        const btnSendWa = document.getElementById("btnSendWa");
        const copyToast = document.getElementById("copyToast");

        function generateWaText() {
            let useNomor = toggleNomor.checked;
            let useStatus = toggleStatus.checked;
            let usePeringatan = togglePeringatan.checked;

            let text = `📊 *REKAP REKONSILIASI KEUANGAN* 📊\n`;
            text += `🏢 *BPKAD KOTA BANJARBARU*\n`;
            text += `🗓️ *Bulan : ${bulanText} ${tahunText}*\n`;
            text += `━━━━━━━━━━━━━━━━━━━━━\n\n`;
            
            text += `✅ *Sudah Rekonsiliasi (${sudahRekonData.length} SKPD)* :\n`;

            if (sudahRekonData.length === 0) {
                text += `_ (Belum ada SKPD yang melaporkan)_\n`;
            } else {
                sudahRekonData.forEach((item, index) => {
                    let prefix = useNomor ? `${index + 1}. ` : `🔸 `;
                    let statusSuffix = "";
                    if (useStatus) {
                        statusSuffix = item.status === 'verified' ? ` 🟢 [Verified]` : ` 🟡 [Draft]`;
                    }
                    text += `${prefix}${item.nama}${statusSuffix}\n`;
                });
            }

            text += `\n━━━━━━━━━━━━━━━━━━━━━\n\n`;
            text += `❌ *Belum Rekonsiliasi (${belumRekonData.length} SKPD)* :\n`;

            if (belumRekonData.length === 0) {
                text += `🎉 _ (Nihil / Semua SKPD sudah rekonsiliasi!)_\n`;
            } else {
                belumRekonData.forEach((item, index) => {
                    let prefix = useNomor ? `${index + 1}. ` : `▪️ `;
                    text += `${prefix}${item.nama}\n`;
                });
            }

            if (usePeringatan) {
                text += `\n━━━━━━━━━━━━━━━━━━━━━\n`;
                text += `⚠️ *INFORMASI & IMBAUAN* ⚠️\n`;
                text += `Mohon kepada Bapak/Ibu Admin & Operator SKPD yang *Belum Rekonsiliasi* agar segera menyelesaikan pelaporan dan melengkapi dokumen pendukung pada aplikasi *SiReKa*.\n\n`;
                text += `🙏 _Terima kasih atas kerja sama dan keterbataswaktuan Anda._ ✨`;
            }

            waTextarea.value = text;
        }

        // Generate initial text
        generateWaText();

        // Listen for toggle changes
        toggleNomor.addEventListener("change", generateWaText);
        toggleStatus.addEventListener("change", generateWaText);
        togglePeringatan.addEventListener("change", generateWaText);

        // Copy button handler
        btnCopy.addEventListener("click", function () {
            waTextarea.select();
            waTextarea.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(waTextarea.value).then(function() {
                showToast();
            }).catch(function(err) {
                // Fallback execCommand
                document.execCommand('copy');
                showToast();
            });
        });

        function showToast() {
            copyToast.classList.remove("hidden");
            setTimeout(function () {
                copyToast.classList.add("hidden");
            }, 4000);
        }

        // Send WA button handler
        btnSendWa.addEventListener("click", function () {
            const textToShare = encodeURIComponent(waTextarea.value);
            const waUrl = `https://api.whatsapp.com/send?text=${textToShare}`;
            window.open(waUrl, "_blank");
        });
    });
</script>
</x-app-layout>
