<x-app-layout>
    <div class="mb-6 pb-4 border-b border-outline-variant flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-primary mb-1">
                <span class="material-symbols-outlined">edit_document</span>
                <h2 class="text-headline-md font-headline-md text-on-surface">Edit Pengaturan Instansi</h2>
            </div>
            <p class="text-body-md font-body-md text-on-surface-variant">Perbarui informasi kop surat dan penanda tangan laporan</p>
        </div>
    </div>

    <form action="{{ route('pengaturan.instansi.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col mb-24">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col mb-8">
            <div class="p-6 grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Bagian Form -->
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">domain</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">INFORMASI SKPD & KOP SURAT</h3>
                    </div>
                    <div class="space-y-4">
                        @if(auth()->user()->role === 'admin')
                        <div class="flex flex-col gap-1.5 p-4 bg-primary/5 rounded-lg border border-primary/20">
                            <label class="text-label-sm font-bold text-primary flex items-center justify-between cursor-pointer" for="is_registration_open">
                                Buka Pendaftaran Mandiri OP
                                <div class="relative inline-flex items-center">
                                  <input type="checkbox" id="is_registration_open" name="is_registration_open" class="sr-only peer" value="1" {{ old('is_registration_open', $pengaturan->is_registration_open) ? 'checked' : '' }}>
                                  <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </div>
                            </label>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed mt-1">Jika diaktifkan, link <b>Daftar Akun Baru</b> akan muncul di halaman login. Operator dapat mendaftar mandiri (hanya 1 per SKPD).</p>
                        </div>

                        <div class="flex flex-col gap-1.5 p-4 bg-amber-500/10 rounded-lg border border-amber-500/30 mt-2">
                            <label class="text-label-sm font-bold text-amber-700 flex items-center justify-between cursor-pointer" for="allow_operator_reupload">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-amber-600">shield_lock</span>
                                    Izin Re-Upload & Timpa Dokumen (Operator SKPD)
                                </span>
                                <div class="relative inline-flex items-center">
                                  <input type="checkbox" id="allow_operator_reupload" name="allow_operator_reupload" class="sr-only peer" value="1" {{ old('allow_operator_reupload', $pengaturan->allow_operator_reupload ?? false) ? 'checked' : '' }}>
                                  <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                                </div>
                            </label>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed mt-1">
                                <b>Proteksi Keamanan Audit:</b> Jika dinonaktifkan (<b>OFF</b> - Default Disarankan), operator SKPD tidak berhak mengganti/menimpa dokumen yang sudah diunggah demi menghindari penyamaran bukti atau ketidaksesuaian berkas paska rekon. Aktifkan (<b>ON</b>) sementara hanya saat masa pembenahan data atau migrasi arsip.
                            </p>
                        </div>
                        
                        <div class="flex flex-col gap-1.5 p-4 bg-blue-500/10 rounded-lg border border-blue-500/30 mt-2">
                            <label class="text-label-sm font-bold text-blue-700 flex items-center justify-between cursor-pointer" for="is_livelog_active">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-blue-600">sensors</span>
                                    Aktifkan Live Log (Teks Berjalan di Footer)
                                </span>
                                <div class="relative inline-flex items-center">
                                  <input type="checkbox" id="is_livelog_active" name="is_livelog_active" class="sr-only peer" value="1" {{ old('is_livelog_active', $pengaturan->is_livelog_active ?? true) ? 'checked' : '' }}>
                                  <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </div>
                            </label>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed mt-1">
                                Jika diaktifkan, teks animasi aktivitas transaksi terbaru akan muncul dan berjalan di bagian bawah layar (*footer*). Nonaktifkan jika mengganggu pemandangan layar.
                            </p>
                        </div>
                        
                        <div class="flex flex-col gap-1.5 p-4 bg-emerald-500/10 rounded-lg border border-emerald-500/30 mt-2">
                            <label class="text-label-sm font-bold text-emerald-700 flex items-center justify-between cursor-pointer" for="allow_edit_saldo_awal">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">lock_open</span>
                                    Izinkan Edit Saldo Kas Awal
                                </span>
                                <div class="relative inline-flex items-center">
                                  <input type="checkbox" id="allow_edit_saldo_awal" name="allow_edit_saldo_awal" class="sr-only peer" value="1" {{ old('allow_edit_saldo_awal', $pengaturan->allow_edit_saldo_awal ?? false) ? 'checked' : '' }}>
                                  <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                </div>
                            </label>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed mt-1">
                                Secara default (<b>OFF</b>), sistem akan otomatis mengisi dan mengunci Saldo Kas Awal berdasarkan Saldo Akhir bulan sebelumnya demi mencegah kesalahan input. Aktifkan (<b>ON</b>) jika ada penyesuaian khusus atau saat entri bulan Januari.
                            </p>
                        </div>
                        
                        <div class="flex flex-col gap-1.5 p-4 bg-indigo-500/10 rounded-lg border border-indigo-500/30 mt-2">
                            <label class="text-label-sm font-bold text-indigo-700 flex items-center justify-between cursor-pointer" for="allow_skpd_download_bukti_digital">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-indigo-600">verified</span>
                                    Izin Unduh Tanda Bukti Verifikasi Digital (Operator SKPD)
                                </span>
                                <div class="relative inline-flex items-center">
                                  <input type="checkbox" id="allow_skpd_download_bukti_digital" name="allow_skpd_download_bukti_digital" class="sr-only peer" value="1" {{ old('allow_skpd_download_bukti_digital', $pengaturan->allow_skpd_download_bukti_digital ?? true) ? 'checked' : '' }}>
                                  <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </div>
                            </label>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed mt-1">
                                <b>Kontrol Akses Pengunduhan:</b> Jika diaktifkan (<b>ON</b>), Operator SKPD dapat mengunduh Surat Tanda Bukti Pemeriksaan Rekonsiliasi Digital (PDF) setelah seluruh tahapan verifikasi disahkan.
                            </p>
                        </div>

                        <!-- Setting Keamanan: Two-Factor Authentication (2FA) -->
                        <div class="flex flex-col gap-2 p-4 bg-teal-500/10 rounded-xl border border-teal-500/30 mt-2">
                            <label class="text-label-sm font-bold text-teal-800 flex items-center justify-between cursor-pointer" for="is_2fa_active">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-teal-700">security</span>
                                    Fitur Two-Factor Authentication / 2FA (Google Authenticator)
                                </span>
                                <div class="relative inline-flex items-center">
                                  <input type="checkbox" id="is_2fa_active" name="is_2fa_active" class="sr-only peer" value="1" {{ old('is_2fa_active', $pengaturan->is_2fa_active ?? false) ? 'checked' : '' }}>
                                  <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-teal-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-700"></div>
                                </div>
                            </label>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed">
                                <b>Status Saat Ini:</b> Sistem 2FA dalam posisi <b>Nonaktif (Standby)</b>. Pengguna dapat menyiapkan perangkat di menu Profil, namun verifikasi 2FA saat login hanya akan diwajibkan jika opsi ini dinyalakan (<b>ON</b>).
                            </p>
                            
                            <div class="pt-2 border-t border-teal-500/20">
                                <label class="text-[11px] font-semibold text-teal-900 flex items-center justify-between cursor-pointer" for="is_2fa_mandatory_for_critical_roles">
                                    <span>Wajibkan 2FA untuk Verifikator Kritis (Admin, Bank Kalsel, BPKAD, Inspektorat)</span>
                                    <input type="checkbox" id="is_2fa_mandatory_for_critical_roles" name="is_2fa_mandatory_for_critical_roles" class="rounded text-teal-700 focus:ring-teal-600 w-4 h-4" value="1" {{ old('is_2fa_mandatory_for_critical_roles', $pengaturan->is_2fa_mandatory_for_critical_roles ?? false) ? 'checked' : '' }}>
                                </label>
                            </div>
                        </div>

                        <!-- Setting Format Nomor BA Dinamis -->
                        <div class="flex flex-col gap-2 p-4 bg-primary/5 rounded-xl border border-primary/20 mt-2">
                            <label class="text-label-sm font-bold text-primary flex items-center justify-between" for="format_nomor_ba">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-primary">tag</span>
                                    Format Penomoran Berita Acara (BA)
                                </span>
                                <span class="text-[11px] font-semibold text-primary bg-primary/10 px-2 py-0.5 rounded-full">Dinamis</span>
                            </label>
                            <input type="text" id="format_nomor_ba" name="format_nomor_ba" 
                                value="{{ old('format_nomor_ba', $pengaturan->format_nomor_ba ?? '900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}') }}" 
                                class="h-11 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-mono w-full transition-all outline-none" 
                                placeholder="Contoh: 900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}" />
                            
                            <div class="p-3 bg-surface-container rounded-lg border border-outline-variant/60 flex flex-col gap-1.5 text-[11px]">
                                <div class="flex items-center justify-between font-semibold text-on-surface">
                                    <span>Pratinjau Hasil Format:</span>
                                    <span class="font-mono text-primary bg-primary/10 px-2 py-0.5 rounded" id="previewNomorBaBadge">{{ $previewNomorBa ?? '900/001/BA-REKON/1.01.01.0/IX/2026' }}</span>
                                </div>
                                <p class="text-on-surface-variant leading-relaxed">
                                    <b>Tag Otomatis yang Tersedia:</b><br>
                                    <code class="bg-surface px-1 py-0.5 rounded border text-primary font-mono">{NOMOR}</code> (Urutan 3 digit: 001) &bull;
                                    <code class="bg-surface px-1 py-0.5 rounded border text-primary font-mono">{KODE_SKPD}</code> (Kode: 1.01.01.0) &bull;
                                    <code class="bg-surface px-1 py-0.5 rounded border text-primary font-mono">{BULAN}</code> (Angka 01-12) &bull;
                                    <code class="bg-surface px-1 py-0.5 rounded border text-primary font-mono">{BULAN_ROMAWI}</code> (Romawi: I s/d XII) &bull;
                                    <code class="bg-surface px-1 py-0.5 rounded border text-primary font-mono">{TAHUN}</code> (Tahun anggaran) &bull;
                                    <code class="bg-surface px-1 py-0.5 rounded border text-primary font-mono">{NAMA_SKPD}</code> (Nama Instansi)
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-1.5 mt-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="logo_file">Logo Aplikasi (Hanya Admin) <span class="text-error">*</span></label>
                            <input class="h-10 p-1.5 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-sm font-body-md w-full transition-all outline-none" 
                                id="logo_file" name="logo_file" type="file" accept="image/*" />
                            <p class="text-[11px] text-on-surface-variant mt-1">Kosongkan jika tidak ingin mengubah logo.</p>
                            @error('logo_file') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5 mt-4 p-4 bg-surface border border-outline-variant rounded-lg">
                            <label class="text-label-sm font-bold text-primary flex items-center gap-1.5" for="teks_pengantar_ba">
                                <span class="material-symbols-outlined text-[18px]">format_align_justify</span>
                                Template Kata Pengantar BA (Hanya Admin)
                            </label>
                            <textarea class="p-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none resize-y" 
                                id="teks_pengantar_ba" name="teks_pengantar_ba" rows="4">{{ old('teks_pengantar_ba', $pengaturan->teks_pengantar_ba ?? 'Pada hari ini [HARI] Tanggal [TANGGAL] Bulan [BULAN] Tahun [TAHUN], telah dilakukan rekonsiliasi Saldo Kas Bendahara Pengeluaran per [AKHIR_BULAN] pada [NAMA_INSTANSI] [NAMA_PEMDA].<br><br>Dengan mencocokkan BKU Bendahara Pengeluaran per [AKHIR_BULAN] pada Aplikasi SIPANDA dengan Rekening Koran Bank Kalsel per [AKHIR_BULAN] dengan hasil sebagai berikut :') }}</textarea>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed mt-1">
                                <b>Variabel yang bisa digunakan:</b> <code class="bg-surface-container-high px-1 rounded text-primary">[HARI]</code>, <code class="bg-surface-container-high px-1 rounded text-primary">[TANGGAL]</code>, <code class="bg-surface-container-high px-1 rounded text-primary">[BULAN]</code>, <code class="bg-surface-container-high px-1 rounded text-primary">[TAHUN]</code>, <code class="bg-surface-container-high px-1 rounded text-primary">[AKHIR_BULAN]</code>, <code class="bg-surface-container-high px-1 rounded text-primary">[NAMA_INSTANSI]</code>, <code class="bg-surface-container-high px-1 rounded text-primary">[NAMA_PEMDA]</code>. Gunakan tag <code>&lt;br&gt;</code> untuk enter/baris baru.
                            </p>
                        </div>
                        
                        <div class="flex flex-col gap-1.5 p-4 bg-surface border border-outline-variant rounded-lg">
                            <label class="text-label-sm font-bold text-primary flex items-center gap-1.5" for="teks_penutup_ba">
                                <span class="material-symbols-outlined text-[18px]">format_align_center</span>
                                Template Kata Penutup BA (Hanya Admin)
                            </label>
                            <textarea class="p-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none resize-y" 
                                id="teks_penutup_ba" name="teks_penutup_ba" rows="2">{{ old('teks_penutup_ba', $pengaturan->teks_penutup_ba ?? '** Rincian terlampir') }}</textarea>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed mt-1">
                                Teks yang muncul di bagian paling bawah tabel sebelum tanda tangan.
                            </p>
                        </div>
                        @endif
                        <div class="flex flex-col gap-1.5 mt-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="isi_kop">Isi Kop Surat <span class="text-error">*</span></label>
                            <textarea class="p-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none resize-y" 
                                id="isi_kop" name="isi_kop" rows="5">{{ old('isi_kop', $pengaturan->isi_kop) }}</textarea>
                            <p class="text-[11px] text-on-surface-variant mt-1">Gunakan tanda pemisah <code class="text-error bg-error-container/30 px-1 rounded">|</code> untuk ganti baris.</p>
                            @error('isi_kop') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Bagian Preview -->
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col h-full">
                        <div class="flex items-center justify-between border-b border-outline-variant/50 pb-2 mb-2">
                            <h3 class="text-label-sm font-label-sm text-on-surface-variant">Preview Tampilan</h3>
                            <span class="text-[10px] font-semibold tracking-wider uppercase text-outline bg-surface-container-high px-2 py-0.5 rounded">Live Preview</span>
                        </div>
                        <div class="flex-1 bg-surface border border-outline-variant rounded-lg p-6 flex flex-col items-center shadow-sm relative overflow-hidden group">
                            <div class="w-full max-w-[500px] flex gap-4 items-center border-b-[3px] border-black pb-4">
                                <div class="w-20 h-20 shrink-0 flex items-center justify-center grayscale opacity-80">
                                    @php
                                        $logoAppPreview = ($pengaturan && $pengaturan->logo) 
                                            ? (Str::startsWith($pengaturan->logo, 'http') ? $pengaturan->logo : asset('storage/' . $pengaturan->logo)) 
                                            : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGQglX4a91lGBKJ3x84BjayBzB86CFjav3SqOK5oE63MWbYO2Qcazq0aldyUiq4O4QUHgyHX3dIYsy_YZxQrgNA3gnZu-9IDh5PBQyqlamviMO9EYFfXzj-ZmB1cLlx2nTyOGUzDWwaUmkCW2sxkgnhAFG2520U_AyWNIov7XjxkjfYKcEDsZudVlfdUva_l58gAIdKZlkfCSf_qyyKiJjlMlPtKy6VdEbjqUDxlo92seLSowz38NN';
                                    @endphp
                                    <img id="preview_logo" class="max-w-full max-h-full object-contain" src="{{ $logoAppPreview }}">
                                </div>
                                <div id="preview_kop" class="flex-1 text-center font-serif flex flex-col text-black">
                                    <!-- Diisi oleh Javascript -->
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none flex items-center justify-center backdrop-blur-[1px]">
                                <span class="material-symbols-outlined text-primary/40" style="font-size: 48px;">visibility</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-2">
                <div class="h-px w-full bg-outline-variant border-dashed"></div>
            </div>

            <!-- Lower Forms Grid -->
            <div class="p-6 grid grid-cols-1 xl:grid-cols-2 gap-8 gap-y-12">
                <!-- Section 2: DATA KEPALA SKPD -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">badge</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">DATA KEPALA SKPD (MENGETAHUI)</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nama_kepala">Nama Lengkap <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="nama_kepala" name="nama_kepala" type="text" value="{{ old('nama_kepala', $pengaturan->nama_kepala) }}">
                            @error('nama_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nip_kepala">NIP <span class="text-error">*</span></label>
                            <input class="font-data-tabular text-data-tabular h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 w-full transition-all outline-none" 
                                id="nip_kepala" name="nip_kepala" type="text" value="{{ old('nip_kepala', $pengaturan->nip_kepala) }}">
                            @error('nip_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="pangkat_kepala">Pangkat <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="pangkat_kepala" name="pangkat_kepala" type="text" value="{{ old('pangkat_kepala', $pengaturan->pangkat_kepala) }}">
                            @error('pangkat_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="jabatan_kepala">Jabatan <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="jabatan_kepala" name="jabatan_kepala" type="text" value="{{ old('jabatan_kepala', $pengaturan->jabatan_kepala) }}">
                            @error('jabatan_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: DATA BENDAHARA -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">account_balance_wallet</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">DATA BENDAHARA (PEMBUATAN LAPORAN)</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nama_bendahara">Nama Lengkap <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="nama_bendahara" name="nama_bendahara" type="text" value="{{ old('nama_bendahara', $pengaturan->nama_bendahara) }}">
                            @error('nama_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nip_bendahara">NIP <span class="text-error">*</span></label>
                            <input class="font-data-tabular text-data-tabular h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 w-full transition-all outline-none" 
                                id="nip_bendahara" name="nip_bendahara" type="text" value="{{ old('nip_bendahara', $pengaturan->nip_bendahara) }}">
                            @error('nip_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="pangkat_bendahara">Pangkat <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="pangkat_bendahara" name="pangkat_bendahara" type="text" value="{{ old('pangkat_bendahara', $pengaturan->pangkat_bendahara) }}">
                            @error('pangkat_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="jabatan_bendahara">Jabatan <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="jabatan_bendahara" name="jabatan_bendahara" type="text" value="{{ old('jabatan_bendahara', $pengaturan->jabatan_bendahara) }}">
                            @error('jabatan_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: DATA KASUBAG -->
                <div class="flex flex-col gap-4 xl:col-span-2 max-w-2xl mx-auto w-full">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">person_check</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">DATA KASUBAG KEUANGAN (MENYETUJUI)</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nama_kasubag">Nama Lengkap <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="nama_kasubag" name="nama_kasubag" type="text" value="{{ old('nama_kasubag', $pengaturan->nama_kasubag) }}">
                            @error('nama_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nip_kasubag">NIP <span class="text-error">*</span></label>
                            <input class="font-data-tabular text-data-tabular h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 w-full transition-all outline-none" 
                                id="nip_kasubag" name="nip_kasubag" type="text" value="{{ old('nip_kasubag', $pengaturan->nip_kasubag) }}">
                            @error('nip_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="pangkat_kasubag">Pangkat <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="pangkat_kasubag" name="pangkat_kasubag" type="text" value="{{ old('pangkat_kasubag', $pengaturan->pangkat_kasubag) }}">
                            @error('pangkat_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="jabatan_kasubag">Jabatan <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="jabatan_kasubag" name="jabatan_kasubag" type="text" value="{{ old('jabatan_kasubag', $pengaturan->jabatan_kasubag) }}">
                            @error('jabatan_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 md:left-64 right-0 bg-surface-container-lowest border-t border-outline-variant p-4 shadow-[0_-4px_12px_rgba(0,0,0,0.05)] z-10 flex justify-end gap-3 px-8">
            <button class="px-6 py-2.5 bg-primary text-on-primary hover:bg-primary/90 rounded-lg font-label-sm text-label-sm transition-colors shadow-sm flex items-center gap-2" type="submit">
                <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                Update Perubahan
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kopInput = document.getElementById('isi_kop');
            const logoInput = document.getElementById('logo');
            const previewKop = document.getElementById('preview_kop');
            const previewLogo = document.getElementById('preview_logo');

            function updatePreview() {
                const lines = kopInput.value.split('|').filter(line => line.trim() !== '');
                
                let html = '';
                lines.forEach((line, index) => {
                    if (index === 0) {
                        html += `<span class="text-sm font-bold tracking-wide uppercase">${line}</span>`;
                    } else if (index === 1) {
                        html += `<span class="text-lg font-black tracking-wider uppercase leading-tight">${line}</span>`;
                    } else if (index === 2) {
                        html += `<span class="text-[11px] mt-1 text-on-surface-variant">${line}</span>`;
                    } else {
                        html += `<span class="text-[11px] text-on-surface-variant">${line}</span>`;
                    }
                });
                
                previewKop.innerHTML = html;
                previewLogo.src = logoInput.value || 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGQglX4a91lGBKJ3x84BjayBzB86CFjav3SqOK5oE63MWbYO2Qcazq0aldyUiq4O4QUHgyHX3dIYsy_YZxQrgNA3gnZu-9IDh5PBQyqlamviMO9EYFfXzj-ZmB1cLlx2nTyOGUzDWwaUmkCW2sxkgnhAFG2520U_AyWNIov7XjxkjfYKcEDsZudVlfdUva_l58gAIdKZlkfCSf_qyyKiJjlMlPtKy6VdEbjqUDxlo92seLSowz38NN';
            }

            kopInput.addEventListener('input', updatePreview);
            logoInput.addEventListener('input', updatePreview);
            
            // Initial render
            updatePreview();
        });
    </script>
</x-app-layout>
