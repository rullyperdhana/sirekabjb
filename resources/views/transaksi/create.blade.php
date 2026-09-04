<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <div class="max-w-[1200px] mx-auto">
        <!-- Page Header -->
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('transaksi.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-md font-headline-md font-bold text-on-surface mb-2">Input Data Transaksi</h2>
                <p class="text-body-md text-on-surface-variant">Rekonsiliasi Bank Bendahara Pengeluaran</p>
            </div>
        </div>
        
        @if ($errors->any())
            <div class="bg-error/10 text-error p-4 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="font-label-sm text-label-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- The Paper Document Container -->
        <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf
            <div class="bg-surface-container-lowest border border-outline-variant rounded shadow-sm p-10 relative">
                <!-- Formal Header Pattern -->
                <div class="border-b-[3px] border-primary pb-6 mb-8 text-center flex flex-col items-center justify-center">
                    <h3 class="text-headline-sm font-headline-sm font-bold uppercase tracking-wide">Pemerintah Kota Banjarbaru</h3>
                    <h2 class="text-headline-lg font-headline-lg font-bold uppercase tracking-tight text-primary">Badan Pengelolaan Keuangan dan Aset Daerah</h2>
                </div>
                
                <!-- General Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">SKPD</label>
                        @if(Auth::user()->role === 'operator' && Auth::user()->skpd_id)
                            <input type="hidden" name="skpd_id" id="skpd_id" value="{{ Auth::user()->skpd_id }}">
                            <input type="text" disabled value="{{ Auth::user()->skpd->nama ?? '-' }}" class="w-full h-10 px-3 rounded border border-outline-variant bg-surface-container-low text-body-md text-on-surface-variant cursor-not-allowed">
                        @else
                            <select id="skpd_id" name="skpd_id" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                <option value="">-- Pilih SKPD --</option>
                                @foreach($skpds as $skpd)
                                    <option value="{{ $skpd->id }}" {{ old('skpd_id') == $skpd->id ? 'selected' : '' }}>{{ $skpd->nama }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Rekening Bank</label>
                        <select id="rekening_id" name="rekening_id" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                            <option value="">-- Pilih Rekening --</option>
                            @foreach($rekenings as $rekening)
                                <option value="{{ $rekening->id }}" data-skpd-id="{{ $rekening->skpd_id }}" {{ old('rekening_id') == $rekening->id ? 'selected' : '' }}>{{ $rekening->nama }} ({{ $rekening->nomor }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Periode Bulan</label>
                        <select name="periode_bulan" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ old('periode_bulan', date('n')) == $i ? 'selected' : '' }}>{{ $namaBulan[$i - 1] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Periode Tahun</label>
                        <input type="number" name="periode_tahun" value="{{ old('periode_tahun', date('Y')) }}" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                </div>

                <!-- Input Section: Split View -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 border border-outline-variant rounded-sm overflow-hidden mb-8 shadow-sm">
                    <!-- Left Column: BKU -->
                    <div class="bg-surface-container-lowest">
                        <div class="bg-surface-container-low border-b border-r border-outline-variant p-4">
                            <h4 class="text-body-lg font-body-lg font-bold text-center">BKU Bendahara Pengeluaran</h4>
                        </div>
                        <div class="p-6 border-r border-outline-variant space-y-6">
                            <div class="space-y-1">
                                <label class="text-label-sm font-label-sm text-on-surface-variant block">Saldo Kas Awal</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-data-tabular">Rp</span>
                                    <input type="hidden" name="bku_saldo_awal" id="bku_saldo_awal" value="{{ old('bku_saldo_awal', 0) }}">
                                    <input id="bku_saldo_awal_display" class="calc-bku w-full h-10 pl-10 pr-4 border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary font-data-tabular {{ !($pengaturanGlobal->allow_edit_saldo_awal ?? false) ? 'bg-surface-container-low cursor-not-allowed text-on-surface-variant' : 'bg-surface-container-lowest' }}" {{ !($pengaturanGlobal->allow_edit_saldo_awal ?? false) ? 'readonly' : '' }} type="text" data-target="bku_saldo_awal" value="{{ old('bku_saldo_awal', 0) }}"/>
                                </div>
                            </div>
                            <div class="pt-2">
                                <span class="text-label-sm font-label-sm text-on-surface block mb-3 font-semibold">Ditambah:</span>
                                <div class="space-y-1 pl-4">
                                    <label class="text-label-sm font-label-sm text-on-surface-variant block">Penerimaan</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-data-tabular">Rp</span>
                                        <input type="hidden" name="bku_penerimaan" id="bku_penerimaan" value="{{ old('bku_penerimaan', 0) }}">
                                        <input id="bku_penerimaan_display" class="calc-bku w-full h-10 pl-10 pr-4 border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest font-data-tabular" type="text" data-target="bku_penerimaan" value="{{ old('bku_penerimaan', 0) }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2">
                                <span class="text-label-sm font-label-sm text-on-surface block mb-3 font-semibold">Dikurang:</span>
                                <div class="space-y-1 pl-4">
                                    <label class="text-label-sm font-label-sm text-on-surface-variant block">Pengeluaran</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-data-tabular">Rp</span>
                                        <input type="hidden" name="bku_pengeluaran" id="bku_pengeluaran" value="{{ old('bku_pengeluaran', 0) }}">
                                        <input id="bku_pengeluaran_display" class="calc-bku w-full h-10 pl-10 pr-4 border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest font-data-tabular" type="text" data-target="bku_pengeluaran" value="{{ old('bku_pengeluaran', 0) }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 mt-6 border-t border-outline-variant">
                                <div class="flex justify-between items-center bg-surface-container-low p-3 rounded">
                                    <span class="text-body-md font-bold">Saldo Akhir Kas</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-data-tabular font-bold text-on-surface-variant">Rp</span>
                                        <input type="hidden" name="bku_saldo_akhir" id="bku_saldo_akhir" value="{{ old('bku_saldo_akhir', 0) }}">
                                        <span id="bku_saldo_akhir_display" class="font-data-tabular font-bold text-[16px]">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Bank Statement -->
                    <div class="bg-surface-container-lowest">
                        <div class="bg-surface-container-low border-b border-outline-variant p-4">
                            <h4 class="text-body-lg font-body-lg font-bold text-center">Rekening Koran Bank</h4>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-1">
                                <label class="text-label-sm font-label-sm text-on-surface-variant block">Saldo Kas Awal</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-data-tabular">Rp</span>
                                    <input type="hidden" name="bank_saldo_awal" id="bank_saldo_awal" value="{{ old('bank_saldo_awal', 0) }}">
                                    <input id="bank_saldo_awal_display" class="calc-bank w-full h-10 pl-10 pr-4 border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary font-data-tabular {{ !($pengaturanGlobal->allow_edit_saldo_awal ?? false) ? 'bg-surface-container-low cursor-not-allowed text-on-surface-variant' : 'bg-surface-container-lowest' }}" {{ !($pengaturanGlobal->allow_edit_saldo_awal ?? false) ? 'readonly' : '' }} type="text" data-target="bank_saldo_awal" value="{{ old('bank_saldo_awal', 0) }}"/>
                                </div>
                            </div>
                            <div class="pt-2">
                                <span class="text-label-sm font-label-sm text-on-surface block mb-3 font-semibold">Ditambah:</span>
                                <div class="space-y-1 pl-4">
                                    <label class="text-label-sm font-label-sm text-on-surface-variant block">Penerimaan</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-data-tabular">Rp</span>
                                        <input type="hidden" name="bank_penerimaan" id="bank_penerimaan" value="{{ old('bank_penerimaan', 0) }}">
                                        <input id="bank_penerimaan_display" class="calc-bank w-full h-10 pl-10 pr-4 border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest font-data-tabular" type="text" data-target="bank_penerimaan" value="{{ old('bank_penerimaan', 0) }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2">
                                <span class="text-label-sm font-label-sm text-on-surface block mb-3 font-semibold">Dikurang:</span>
                                <div class="space-y-1 pl-4">
                                    <label class="text-label-sm font-label-sm text-on-surface-variant block">Pengeluaran</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-data-tabular">Rp</span>
                                        <input type="hidden" name="bank_pengeluaran" id="bank_pengeluaran" value="{{ old('bank_pengeluaran', 0) }}">
                                        <input id="bank_pengeluaran_display" class="calc-bank w-full h-10 pl-10 pr-4 border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest font-data-tabular" type="text" data-target="bank_pengeluaran" value="{{ old('bank_pengeluaran', 0) }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 mt-6 border-t border-outline-variant">
                                <div class="flex justify-between items-center bg-surface-container-low p-3 rounded">
                                    <span class="text-body-md font-bold">Saldo Akhir Kas</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-data-tabular font-bold text-on-surface-variant">Rp</span>
                                        <input type="hidden" name="bank_saldo_akhir" id="bank_saldo_akhir" value="{{ old('bank_saldo_akhir', 0) }}">
                                        <span id="bank_saldo_akhir_display" class="font-data-tabular font-bold text-[16px]">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Difference Calculator Strip -->
                <div class="bg-error-container/20 border border-error/20 rounded p-4 flex items-center justify-between mb-8" id="selisih-container">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">error</span>
                        <span class="text-body-lg font-bold text-error">Selisih (Discrepancy)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-data-tabular font-bold text-error">Rp</span>
                        <span class="font-data-tabular font-bold text-[20px] text-error" id="selisih_value">0.00</span>
                    </div>
                </div>
                
                <!-- Explanation Area -->
                <div class="mb-10 space-y-2">
                    <label class="text-body-md font-bold block text-on-surface">Penjelasan / Keterangan Selisih :</label>
                    <textarea name="keterangan_selisih" maxlength="255" class="w-full h-24 border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary p-3 bg-surface-container-lowest text-body-md resize-none" placeholder="Masukkan penjelasan perbedaan transaksi (maksimal 255 karakter)...">{{ old('keterangan_selisih') }}</textarea>
                    @error('keterangan_selisih')
                        <span class="text-error text-label-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Tanggal Berita Acara <span class="text-error">*</span></label>
                    <input type="date" name="tanggal_ba" required value="{{ old('tanggal_ba', date('Y-m-d')) }}" class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div class="mb-8">
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Status Verifikasi</label>
                    <select name="status_verifikasi" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="draft" {{ old('status_verifikasi') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="verified" {{ old('status_verifikasi') == 'verified' ? 'selected' : '' }}>Verified (Selesai)</option>
                    </select>
                </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end gap-4 border-t border-outline-variant pt-6">
                    <a href="{{ route('transaksi.index') }}" class="px-6 py-2 border border-outline rounded text-on-surface-variant font-label-sm hover:bg-surface-container transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2 bg-primary text-on-primary rounded font-label-sm font-bold shadow hover:bg-primary/90 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Rupiah Formatter ---
            function formatRupiah(angka) {
                if (angka === null || angka === undefined || angka === '') return '0';
                let num = parseFloat(angka);
                if (isNaN(num)) return '0';
                return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(num);
            }
            function parseRupiah(str) {
                if (!str) return 0;
                // Remove dots (thousands), replace comma with dot (decimal)
                let cleaned = String(str).replace(/\./g, '').replace(/,/g, '.');
                return parseFloat(cleaned) || 0;
            }

            // --- Hidden Inputs (raw values) ---
            const bkuAwal = document.getElementById('bku_saldo_awal');
            const bkuTerima = document.getElementById('bku_penerimaan');
            const bkuKeluar = document.getElementById('bku_pengeluaran');
            const bkuAkhir = document.getElementById('bku_saldo_akhir');
            const bankAwal = document.getElementById('bank_saldo_awal');
            const bankTerima = document.getElementById('bank_penerimaan');
            const bankKeluar = document.getElementById('bank_pengeluaran');
            const bankAkhir = document.getElementById('bank_saldo_akhir');

            const selisihValue = document.getElementById('selisih_value');
            const selisihContainer = document.getElementById('selisih-container');

            // --- Attach formatter to all display inputs ---
            document.querySelectorAll('input[data-target]').forEach(displayInput => {
                // Format initial value
                const rawVal = parseFloat(displayInput.value) || 0;
                displayInput.value = formatRupiah(rawVal);

                displayInput.addEventListener('input', function() {
                    const cursorPos = this.selectionStart;
                    const oldLen = this.value.length;

                    let raw = parseRupiah(this.value);
                    // Sync to hidden input
                    document.getElementById(this.dataset.target).value = raw;
                    this.value = formatRupiah(raw);

                    // Adjust cursor position
                    const newLen = this.value.length;
                    const newCursorPos = cursorPos + (newLen - oldLen);
                    this.setSelectionRange(newCursorPos, newCursorPos);
                });

                displayInput.addEventListener('focus', function() {
                    // On focus, allow full editing
                });
            });

            // --- Calculation ---
            function calculateBku() {
                const awal = parseFloat(bkuAwal.value) || 0;
                const terima = parseFloat(bkuTerima.value) || 0;
                const keluar = parseFloat(bkuKeluar.value) || 0;
                const akhir = awal + terima - keluar;
                bkuAkhir.value = akhir;
                document.getElementById('bku_saldo_akhir_display').textContent = formatRupiah(akhir);
                calculateSelisih();
            }

            function calculateBank() {
                const awal = parseFloat(bankAwal.value) || 0;
                const terima = parseFloat(bankTerima.value) || 0;
                const keluar = parseFloat(bankKeluar.value) || 0;
                const akhir = awal + terima - keluar;
                bankAkhir.value = akhir;
                document.getElementById('bank_saldo_akhir_display').textContent = formatRupiah(akhir);
                calculateSelisih();
            }

            function calculateSelisih() {
                const bku = parseFloat(bkuAkhir.value) || 0;
                const bank = parseFloat(bankAkhir.value) || 0;
                const selisih = bku - bank;
                selisihValue.textContent = formatRupiah(selisih);
                selisihContainer.classList.remove('hidden');
            }

            document.querySelectorAll('.calc-bku').forEach(el => el.addEventListener('input', calculateBku));
            document.querySelectorAll('.calc-bank').forEach(el => el.addEventListener('input', calculateBank));

            calculateBku();

            // --- Filter Rekening Dropdown ---
            const skpdSelect = document.getElementById('skpd_id');
            const rekeningSelect = document.getElementById('rekening_id');

            function filterRekenings() {
                const skpdId = skpdSelect ? skpdSelect.value : '';
                if (!rekeningSelect) return;
                const options = rekeningSelect.querySelectorAll('option');
                let hasValidSelection = false;

                options.forEach(option => {
                    if (option.value === "") return;
                    const optionSkpdId = option.getAttribute('data-skpd-id');
                    if (skpdId && optionSkpdId === skpdId) {
                        option.style.display = 'block';
                        if (option.selected) hasValidSelection = true;
                    } else {
                        option.style.display = 'none';
                        if (option.selected) option.selected = false;
                    }
                });
                if (!hasValidSelection && skpdId) {
                    rekeningSelect.value = "";
                }
            }

            function fetchSaldoAwal() {
                const skpdId = skpdSelect ? skpdSelect.value : '';
                const rekeningId = rekeningSelect ? rekeningSelect.value : '';
                const bulanSelect = document.querySelector('select[name="periode_bulan"]');
                const periodeBulan = bulanSelect.value;
                const periodeTahun = document.querySelector('input[name="periode_tahun"]').value;

                if (skpdId && rekeningId) {
                    fetch(`{{ route('transaksi.getSaldoAwal') }}?skpd_id=${skpdId}&rekening_id=${rekeningId}&periode_bulan=${periodeBulan || 1}&periode_tahun=${periodeTahun}`)
                        .then(response => response.json())
                        .then(data => {
                            // Filter disabled months
                            if (data.existing_months) {
                                Array.from(bulanSelect.options).forEach(opt => {
                                    if (data.existing_months.includes(parseInt(opt.value))) {
                                        opt.disabled = true;
                                        opt.text = opt.text.replace(' (Sudah Diinput)', '') + ' (Sudah Diinput)';
                                    } else {
                                        opt.disabled = false;
                                        opt.text = opt.text.replace(' (Sudah Diinput)', '');
                                    }
                                });
                                // Jika bulan yang saat ini dipilih ternyata disabled, otomatis ganti ke bulan pertama yang tidak disabled
                                if (bulanSelect.selectedOptions[0]?.disabled) {
                                    const firstEnabled = Array.from(bulanSelect.options).find(opt => !opt.disabled);
                                    if (firstEnabled) {
                                        bulanSelect.value = firstEnabled.value;
                                    } else {
                                        bulanSelect.value = '';
                                    }
                                }
                            }

                            if(data.bku_saldo_akhir > 0 || data.bank_saldo_akhir > 0) {
                                bkuAwal.value = data.bku_saldo_akhir;
                                bankAwal.value = data.bank_saldo_akhir;
                                // Update display inputs
                                document.getElementById('bku_saldo_awal_display').value = formatRupiah(data.bku_saldo_akhir);
                                document.getElementById('bank_saldo_awal_display').value = formatRupiah(data.bank_saldo_akhir);
                                calculateBku();
                                calculateBank();
                            }
                        })
                        .catch(err => console.error('Error fetching saldo awal:', err));
                }
            }

            if (skpdSelect && skpdSelect.tagName === 'SELECT') {
                new TomSelect(skpdSelect, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    onChange: function() {
                        filterRekenings();
                        fetchSaldoAwal();
                    }
                });
            }
            if (rekeningSelect) rekeningSelect.addEventListener('change', fetchSaldoAwal);
            document.querySelector('select[name="periode_bulan"]').addEventListener('change', fetchSaldoAwal);
            document.querySelector('input[name="periode_tahun"]').addEventListener('change', fetchSaldoAwal);

            // Run on load
            filterRekenings();
            calculateBank();
            fetchSaldoAwal();
        });
    </script>
</x-app-layout>
