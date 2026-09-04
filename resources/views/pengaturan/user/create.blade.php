<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4 border-b-[3px] border-primary pb-4">
            <a href="{{ route('user.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-lg font-headline-lg text-on-surface">Tambah Pengguna Baru</h1>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1">Buat akun untuk Pengguna Alur 4-Pilar (Operator SKPD, Bank Kalsel, Konsolidator, Inspektorat, atau Admin)</p>
            </div>
        </div>

        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden p-6">
            @if ($errors->any())
                <div class="bg-error/10 text-error p-4 rounded mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="font-label-sm text-label-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Fauzi, S.E." class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Username (Login)</label>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: op_disdik" class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Email Resmi</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: op.disdik@banjarbarukota.go.id" class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Password</label>
                        <input type="password" name="password" required class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                </div>
                
                <!-- Pilihan Peran Sesuai Probis 4-Pilar -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                        Peran & Tingkat Otoritas (Probis 4-Pilar)
                    </label>
                    <select id="user_role" name="role" required onchange="handleRoleChange(this.value)" class="w-full h-11 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium">
                        <option value="operator" {{ old('role', 'operator') == 'operator' ? 'selected' : '' }}>
                            🔹 Pilar 1: Operator SKPD (Entri Mutasi, Saldo BKU & Upload Bukti Rekon)
                        </option>
                        <option value="bank" {{ old('role') == 'bank' ? 'selected' : '' }}>
                            🏛️ Pilar 2: Pihak Bank (Verifikasi Rekening Koran & Mutasi Bank Kalsel)
                        </option>
                        <option value="konsolidator" {{ old('role') == 'konsolidator' ? 'selected' : '' }}>
                            📑 Pilar 3: Konsolidator BPKAD (Verifikasi Teknis Kasda & Checklist 5 Berkas)
                        </option>
                        <option value="inspektorat" {{ old('role') == 'inspektorat' ? 'selected' : '' }}>
                            🛡️ Pilar 4: Inspektorat Daerah (Pengawasan Internal & Pengesahan BA Final)
                        </option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            ⚙️ Administrator Pusat (Akses Penuh Seluruh Pengaturan Sistem)
                        </option>
                    </select>

                    <!-- Penjelasan Dinamis Probis -->
                    <div id="role_desc" class="mt-2.5 p-3 rounded-lg text-xs leading-relaxed border bg-slate-50 border-slate-200 text-slate-700">
                        <span id="role_desc_text">
                            <strong>Pilar 1 (Operator SKPD):</strong> Bertugas mengisi saldo BKU, mengunggah rekening koran dan register kas, serta mengajukan rekonsiliasi bulanan ke Bank Kalsel. <em>Wajib memilih SKPD asal di bawah.</em>
                        </span>
                    </div>
                </div>

                <!-- Pemilihan SKPD -->
                <div id="skpd_wrapper">
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                        Instansi / SKPD <span id="skpd_required_tag" class="text-error font-bold">*</span>
                    </label>
                    <select name="skpd_id" id="skpd_select" class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="">-- Tanpa Terikat SKPD Tertentu (Tingkat Kota / Global) --</option>
                        @foreach($skpds as $skpd)
                            <option value="{{ $skpd->id }}" {{ old('skpd_id') == $skpd->id ? 'selected' : '' }}>
                                [{{ $skpd->kode }}] {{ $skpd->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p id="skpd_help_text" class="text-[11px] text-on-surface-variant mt-1">
                        Pilih SKPD tempat operator bertugas. Untuk Bank Kalsel, Inspektorat, Konsolidator, atau Admin dapat dikosongkan.
                    </p>
                </div>

                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Status Akun</label>
                    <select name="status" class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif (Dapat langsung digunakan untuk login)</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non-Aktif (Akses login ditangguhkan)</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('user.index') }}" class="px-5 py-2.5 rounded border border-outline-variant text-on-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm font-semibold">Batal</a>
                    <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container px-5 py-2.5 rounded font-label-sm text-label-sm font-semibold shadow-sm transition-colors">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function handleRoleChange(role) {
            const descBox = document.getElementById('role_desc');
            const descText = document.getElementById('role_desc_text');
            const skpdRequiredTag = document.getElementById('skpd_required_tag');
            const skpdHelpText = document.getElementById('skpd_help_text');

            if (role === 'operator') {
                descBox.className = 'mt-2.5 p-3 rounded-lg text-xs leading-relaxed border bg-blue-50 border-blue-200 text-blue-900';
                descText.innerHTML = '<strong>Pilar 1 (Operator SKPD):</strong> Bertugas mengisi saldo BKU, mengunggah rekening koran dan register kas, serta mengajukan rekonsiliasi bulanan ke Bank Kalsel. <em>Wajib memilih SKPD asal di bawah.</em>';
                skpdRequiredTag.style.display = 'inline';
                skpdHelpText.innerText = 'Wajib: Pilih SKPD tempat Operator ini bertugas mengelola kas.';
            } else if (role === 'bank') {
                descBox.className = 'mt-2.5 p-3 rounded-lg text-xs leading-relaxed border bg-amber-50 border-amber-200 text-amber-900';
                descText.innerHTML = '<strong>Pilar 2 (Pihak Bank - Bank Kalsel):</strong> Bertugas memverifikasi rekening koran dan mutasi kas bank untuk <strong>seluruh SKPD</strong> se-Kota Banjarbaru. Dapat menyetujui mutasi rekening atau meminta perbaikan.';
                skpdRequiredTag.style.display = 'none';
                skpdHelpText.innerText = 'Otoritas Kota: Pengguna Bank Kalsel tidak terikat pada 1 SKPD tunggal (mengakses seluruh SKPD).';
            } else if (role === 'konsolidator') {
                descBox.className = 'mt-2.5 p-3 rounded-lg text-xs leading-relaxed border bg-purple-50 border-purple-200 text-purple-900';
                descText.innerHTML = '<strong>Pilar 3 (Konsolidator Kasda BPKAD):</strong> Bertugas memverifikasi kelengkapan berkas fisik kasda (5 dokumen digital) dan pembukuan BKU setelah disahkan Bank Kalsel. Berwenang menyetujui atau mengembalikan berkas.';
                skpdRequiredTag.style.display = 'none';
                skpdHelpText.innerText = 'Otoritas BPKAD: Konsolidator memeriksa seluruh SKPD. (Dapat dikosongkan atau pilih BPKAD).';
            } else if (role === 'inspektorat') {
                descBox.className = 'mt-2.5 p-3 rounded-lg text-xs leading-relaxed border bg-rose-50 border-rose-200 text-rose-900';
                descText.innerHTML = '<strong>Pilar 4 (Inspektorat Daerah Kota Banjarbaru):</strong> Bertugas melakukan audit & pengawasan internal tingkat akhir, mengesahkan Berita Acara Rekonsiliasi, dan menerbitkan Nomor Registrasi BA Resmi.';
                skpdRequiredTag.style.display = 'none';
                skpdHelpText.innerText = 'Otoritas Pengawasan: Inspektorat mengawasi seluruh SKPD (Biarkan kosong atau pilih Inspektorat).';
            } else if (role === 'admin') {
                descBox.className = 'mt-2.5 p-3 rounded-lg text-xs leading-relaxed border bg-slate-100 border-slate-300 text-slate-900';
                descText.innerHTML = '<strong>Administrator Sistem (BPKAD):</strong> Memiliki hak akses tertinggi meliputi manajemen pengguna 4-pilar, konfigurasi instansi, penomoran BA dinamis, pengaturan storage/NAS, jejak audit, dan pemeliharaan sistem.';
                skpdRequiredTag.style.display = 'none';
                skpdHelpText.innerText = 'Akses Penuh: Administrator mengelola seluruh sistem.';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('user_role');
            if (roleSelect) {
                handleRoleChange(roleSelect.value);
            }
        });
    </script>
</x-app-layout>
