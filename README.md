# SiReKa (Sistem Rekonsiliasi Kas Daerah - Pemerintah Kota Banjarbaru)

**SiReKa (Sistem Rekonsiliasi Kas)** adalah platform digital terpadu untuk pengelolaan, validasi, dan rekonsiliasi kas Bendahara Pengeluaran tingkat Satuan Kerja Perangkat Daerah (SKPD) di lingkungan Pemerintah Kota Banjarbaru (Badan Pengelolaan Keuangan dan Aset Daerah - BPKAD). Aplikasi ini dirancang dengan arsitektur verifikasi berjenjang 4-pilar, standar keamanan tinggi (termasuk Autentikasi Dua Faktor 2FA-TOTP), dan kepatuhan standar akuntansi pemerintah.

---

## 🚀 Fitur Unggulan SiReKa (v2.0 - Enterprise Architecture)

### 1. 🏆 Executive Analytics, Leaderboard & Early Warning System (EWS)
* **Timeliness Scoring Algorithm (Bobot Waktu Peringkat):** Papan peringkat SKPD Terbaik di dasbor tidak hanya menghitung kuantitas laporan bulanan, tetapi menerapkan bobot kedisiplinan hari pengiriman (Tgl 1–5 = 100 pt, Tgl 6–10 = 85 pt, Tgl 11–15 = 70 pt, > Tgl 15 = 50 pt).
* **Early Warning System (EWS - Rapor Merah):** Panel pengawasan khusus yang menyorot 5 SKPD dengan keterlambatan terparah atau adanya selisih kas bulanan, memudahkan pembinaan lebih dini oleh Konsolidator dan pimpinan.
* **Cetak Rapor Kepatuhan Eksekutif (PDF):** Kemudahan mengunduh dokumen laporan performa & kepatuhan seluruh instrumen instansi se-Kota Banjarbaru bersertifikasi resmi berklasifikasi Grade A, B, C, hingga D.

### 2. 📱 WhatsApp Generator & Rekap Broadcast Pimpinan (Sinkronisasi Akurat)
* **Rekapitulasi Siap Salin ke Grup WA:** Dasbor pelaporan otomatis menghasilkan rekapitulasi daftar SKPD yang **Sudah Rekonsiliasi** maupun **Belum Rekonsiliasi** per bulan dengan format rapi dan emotikon informatif yang siap dilarang/dibroadcast ke grup WhatsApp Admin, Konsolidator, maupun Kepala SKPD guna efisiensi koordinasi.
* **Filter Kriteria Kelengkapan Bukti (Anti-Perbedaan Laporan):** Admin memiliki kendali penuh memilih kriteria "Sudah Rekonsiliasi", mulai dari *Semua Status*, *Verified Only*, hingga opsi khusus **Khusus yang Sudah Upload Berita Acara (BA Manual)** dan **Khusus yang Sudah Upload Lengkap (4 Dokumen)**, memastikan sinkronisasi 100% dengan Laporan Tunggakan & Tanpa Dokumen Pendukung Lengkap!
* **Laporan Status Cetak Akun:** Log internal admin yang memantau tanggal cetak serta SKPD mana yang belum memiliki operator aktif.

### 3. 🗄️ Brankas Digital & Ekspor ZIP Massal (Paket Audit BPK)
* **Hirarki Dokumen & Quick Pratinjau (Urut Kode SKPD):** Seluruh file Rekening Koran, Buku Kas Umum (BKU), Buku Pembantu Bank, dan Berita Acara (BA) disusun dalam struktur pohon (Tree) rapi dan **diurutkan secara hierarkis berdasarkan Kode SKPD BPKAD** dengan fitur *In-Browser Preview Modal* tanpa harus mendownload file satu per satu.
* **Ekspor Massal Paket Audit BPK (.ZIP):** Fitur kompresi massal satu klik untuk mengunduh seluruh bukti dukung dokumen se-Kota Banjarbaru dalam 1 file ZIP yang **otomatis distrukturkan ke dalam sub-folder Kode & Nama SKPD beserta bulan** (contoh: `1-01-01-dinas-kesehatan/Bulan_06_Juni/Rekening_Koran.pdf`), membedah ratusan jam pemeriksaan akuntansi teknis.
* **🛡️ Sistem Kontrol Proteksi Bukti Audit (Anti-Manipulasi Dokumen & Audit Trail):**
  - **Saklar Eksklusif Admin (Izin Re-Upload & Timpa Dokumen):** Dalam rangka pengamanan jejak audit keuangan daerah (Anti-Fraud BPK/Inspektorat), operator SKPD dilarang mengganti atau menimpa dokumen bukti yang sudah disahkan. Namun saat terjadi masa migrasi atau perbaikan dokumen massal, Admin BKAD dapat mengaktifkan sementara saklar **"Izin Re-Upload Dokumen"** dari dasbor Pengaturan Instansi (`/pengaturan/instansi`).
  - **Penghapusan Dokumen Spesifik oleh Admin:** Jika ada SKPD yang salah unggah pada status transaksi yang sudah diverifikasi, Admin memiliki hak akses khusus berupa tombol **"Hapus"** pada masing-masing dokumen. Hal ini akan menghapus file yang salah dan secara otomatis membuka kembali akses *upload* untuk dokumen tersebut bagi SKPD bersangkutan tanpa harus membuka izin re-upload secara global.
  - **Jejak Log Audit Forensik:** Pencatatan otomatis setiap riwayat penimpaan atau perubahan dokumen bukti ke dalam sistem log server dan database Activity Log yang mengidentifikasi pelaku (User ID/Role), jam kejadian, dan SKPD bersangkutan untuk kebutuhan pembuktian hukum.
  - **Indikator Gembok UI/UX (Lockdown Banners):** Antarmuka upload yang secara pintar memberikan penanda gembok permanen atau informasi status pembukaan akses re-upload secara langsung kepada Operator SKPD.

### 4. 🖥️ Manajemen Storage Dinamis & Koneksi NAS (`/pengaturan/storage`)
* **Real-time Server Storage Gauge:** Pemantauan langsung persentase pemakaian hard disk server SiReKa berstatus warna (Aman / Kapasitas Menipis).
* **Switch Mode Storage (Fleksibel & Bukan Permanen):** Administrator dapat beralih metode penyimpanan kapan saja tanpa mengubah kode:
  - 📁 **Penyimpanan Internal Server (Lokal Disk)** - Mode default di folder `storage/app/public`.
  - 🖥️ **Network Attached Storage (NAS / NFS Mount)** - Menyalurkan file fisik ke mesin Synology/QNAP di jaringan Data Center/Kominfo. Dilengkapi boks instruksi terminal siap pakai.
  - ☁️ **MinIO / Object Storage (S3 Enterprise)** - Dukungan cloud lokal bergaya S3 yang terlindungi secara enkripsi.
* **Uji Koneksi (Test Connection):** Alat pengujian otomatis izin tulis (writable) dan ping koneksi sebelum memindahkan mode aktif.
* **🛡️ Smart Auto-Fallback & Auto-Heal (Pembacaan Ganda):** Ketika Anda beralih ke NAS atau MinIO (S3), file arsip lama di hard disk lokal lama *tidak akan error 404* saat dibuka/diunduh. SiReKa secara cerdas mendeteksi dan mengambil file dari hard disk lokal asal jika belum ada di cloud, sekaligus menyebarkannya/ menyalinya secara otomatis ke storage baru di latar belakang (*Auto-Heal*).
* **🧠 Intelligent Storage Diagnostics (Detektor Hak Akses & Batas PHP):** Dilengkapi sistem deteksi pintar pada mesin pengunggah dokumen yang secara transparan mencegah pesan sukses palsu (*silent failure*). Sistem sanggup menganalisis hambatan batas ukuran di PHP aaPanel (`post_max_size` / `upload_max_filesize`) serta memeriksa hak milik Linux (`www:www` vs `root:root`) pada folder NAS/S3 dan mencetak saran perintah terminal SSH spesifik jika terjadi konflik izin tulis di server!
* **🚀 Batch Storage Synchronizer (Alat Migrasi Massal):** Dilengkapi tombol web **"Sinkronkan Arsip Lama Sekarang"** di dasbor pengaturan (untuk batch 100 file per klik tanpa timeout) serta perintah terminal server khusus: `php artisan sireka:sync-storage` untuk memigrasi ribuan file ke MinIO/NAS dengan indikator progress real-time.

### 5. 🔒 Mode Pemeliharaan & Penguncian Akses SKPD (Animated Lockdown System)
* **Perlindungan Data Saat Update Server:** Saat Admin ingin memperbarui kode, dokumen, atau memulihkan database, Admin dapat mengaktifkan **Lockdown Mode** dari menu `/pengaturan/maintenance`.
* **Proteksi Multi-Layer Middleware:** Operator SKPD ditahan dan dialihkan secara otomatis ke layar notifikasi khusus, mencegah kesalahan input atau data ganda selama masa perawatan sistem. Admin dan Konsolidator tetap dapat beraktivitas secara penuh di dalam sistem!
* **Layar Maintenance Sinematik (Animated Dark Mode):** Meninggalkan layar putih kaku, SiReKa kini mengadopsi tampilan maintenance berdesain premium dengan animasi ganda roda gigi putar interaktif (*Dual Rotating SVG Gears*), efek partikel melayang (*Ambient Floating Orbs*), mikro-animasi pada tombol aksi, serta jam digital real-time Waktu Indonesia Tengah (**Live WITA Clock**) yang menunjukkan kredibilitas sistem berskala pemerintah daerah.

### 6. 🔐 Manajemen Core, Registrasi Mandiri & Keamanan Standar (SiReKa Core Engine)
* **Pendaftaran Akun Mandiri & Kontrol Buka-Tutup:** Operator SKPD dapat mendaftarkan akun secara mandiri (maksimal 1 Operator per SKPD). Admin memiliki tuas kontrol untuk **Membuka/Menutup Pendaftaran** dari dasbor pengaturan, serta mewajibkan aktivasi verifikasi Admin sebelum akun baru dapat digunakan.
* **Laporan Internal Daftar Pengguna & Status Cetak:** Pada menu `/pengaturan/user`, Administrator dapat memantau langsung tanggal cetak dan mengidentifikasi instansi SKPD mana saja yang sudah memiliki user maupun yang belum terdaftar.
* **Cetak Berita Acara (BA) Ber-QR Code & Template Dinamis (Snapshot History):** Pembentukan otomatis Berita Acara Rekonsiliasi bulanan ke format PDF ukuran F4 (Folio) yang sah, dilengkapi tanda tangan digital elektronik dan **QR Code** untuk validasi keaslian dokumen cetak (anti pemalsuan). Tersedia juga fitur **Editor Template BA Dinamis** dengan dukungan *placeholder* variabel (seperti `[NAMA_INSTANSI]`, `[BULAN]`, dll). Sistem dilengkapi mekanisme *Snapshot History* sehingga perubahan template kata pengantar/penutup di masa depan (misal: SOTK baru di tahun 2027) **tidak akan merubah atau merusak susunan teks laporan BA di tahun-tahun sebelumnya** yang sudah pernah diverifikasi!
* **Proteksi Master Data Rekening (Anti-Broken Link):** Sistem secara otomatis mengunci dan menolak penghapusan rekening SKPD apabila rekening tersebut telah memiliki riwayat laporan transaksi. Hal ini menjamin integritas riwayat saldo dan mencegah rusaknya tautan data (broken link) pada laporan masa lalu.
* **Animasi Live Log Dashboard:** Fitur teks berjalan interaktif (*ticker*) di area *footer* yang menyiarkan detak log aktivitas transaksi/verifikasi SKPD secara langsung dan elegan tanpa harus *refresh* halaman terus-menerus (dapat dinyala/matikan).
* **Audit Trail & Keamanan Ekstra:** Dilengkapi catatan log aktivitas lengkap (Alamat IP, User-Agent browser, waktu eksekusi), pembatasan percobaan login (*Rate Limiting*), proteksi Captcha, Auto-Logout karena tidak ada aktivitas (*Session Timeout*), dan kebijakan password yang ketat.

### 7. 🎨 UI/UX Premium & Landing Page Modern (Public Face)
* **Desain Organik & "Fresh" (Anti-Kaku):** Meninggalkan gaya template AI/Admin yang membosankan, wajah depan portal SiReKa dirancang menggunakan antarmuka _floating cards_, *glassmorphism*, gradient mewah, serta spasi tipografi (whitespace) bernuansa *startup fintech* / enterprise.
* **Pencarian SKPD Real-time:** Memudahkan publik atau kepala instansi untuk langsung mencari nama SKPD (tanpa merusak susunan halaman) melalui bilah pencarian cerdas yang langsung memotong data tanpa perlu pusing beralih halaman (*pagination*).
* **Indikator Visual Organik:** Status kemajuan rekonsiliasi per instansi tidak lagi menggunakan sekadar teks, namun direpresentasikan melalui warna status lencana dinamis (Badge) dan bulatan-bulatan (Circles) indikator tiap bulan, menampilkan data dengan cara yang ramah bagi mata masyarakat/auditor.

---

## 🛠️ Persyaratan Sistem (Server Production)
* **PHP:** >= 8.2 (Laravel 11 Framework)
* **Database:** MySQL / MariaDB / SQLite
* **Composer:** Versi 2.x
* **PHP Extensions:** BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/Imagick, DOM, Zip, cURL.

---

## ⚙️ Panduan Instalasi & Pengoperasian Server

### 1. Deployment Awal / Instalasi Baru:
```bash
git clone https://github.com/rullyperdhana/sirekabjb.git sireka
cd sireka
composer install --optimize-autoloader --no-dev
cp .env.example .env
# Atur kredensial DB dan pastikan APP_ENV=production, APP_DEBUG=false
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan optimize:clear && php artisan optimize
```

### 2. Prosedur Keamanan Hak Akses Server (Linux & aaPanel Best Practice):
Demi menjaga prinsip isolasi keamanan Linux (*Least Privilege*) di mana Nginx/PHP berjalan di bawah akun `www:www` (atau `www-data`/`apache`), serta mencegah timbulnya konflik *Permission Denied* saat operasi upload atau mount folder eksternal NAS:
```bash
# 1. Kepemilikan folder web dan arsip harus ditujukan kepada user webserver (contoh: www)
chown -R www:www /www/wwwroot/sireke.cloud/storage
chown -R www:www /www/wwwroot/sireke.cloud/bootstrap/cache
chown -R www:www /www/wwwroot/sireke.cloud/public/storage

# 2. Pengaturan hak akses folder agar bisa didiversifikasi oleh engine Laravel
chmod -R 775 /www/wwwroot/sireke.cloud/storage
chmod -R 775 /www/wwwroot/sireke.cloud/bootstrap/cache

# 3. Jika menggunakan folder eksternal mount NAS (contoh /mnt/nas_sireka_pool atau /sireka_nas_pool)
chown -R www:www /www/wwwroot/sireke.cloud/storage/nas_mount
chmod -R 777 /www/wwwroot/sireke.cloud/storage/nas_mount
```

### 3. Prosedur Melakukan Pembaruan (Update System & Maintenance):
Untuk menjalin integritas database selama proses pembaruan dari repositori GitHub:
1. Masuk ke dasbor SiReKa sebagai **Admin** -> Buka menu **Pengaturan** -> **Maintenance Sistem**.
2. Aktifkan **Lockdown Akses SKPD (Mode Pemeliharaan)** dan isi estimasi waktu pengerjaan (contoh: *30 Menit*).
3. Jalankan pemutakhiran kode di server lokal/data center melalui script utilitas yang disediakan:
   ```bash
   git pull origin main
   composer install --no-dev
   php artisan optimize:clear && php artisan optimize && php artisan view:cache
   ```
4. Setelah verifikasi admin selesai, kembali ke menu Maintenance Sistem dan klik **Buka Kembali Akses SKPD (Normal)**.

---

## 👥 Struktur Hak Akses Berjenjang 4-Pilar (Pemerintah Kota Banjarbaru)
1. **Administrator Sistem (BPKAD Kota Banjarbaru):** Kontrol penuh sistem, manajemen pengguna & instansi 87 SKPD, manajemen storage & NAS, mode maintenance (lockdown), audit log forensik, template penomoran BA dinamis, serta wewenang supervisi menyeluruh atas seluruh siklus rekonsiliasi kas.
2. **Pilar 1 - Operator SKPD (Bendahara Pengeluaran):** Mengelola entri saldo kas bulanan (saldo awal, saldo akhir, mutasi kas), mengunggah 4 berkas bukti dukung (Rekening Koran, BKU, Register/Pembantu Kas, BA Rekon), melakukan pengajuan verifikasi ke pihak Bank, serta membaca riwayat evaluasi revisi jika terdapat catatan perbaikan.
3. **Pilar 2 - Bank Kalsel (Verifikator Bank Mitra):** Melakukan pemeriksaan kesesuaian saldo dan mutasi rekening kas daerah dengan rekening koran resmi Bank Kalsel Cabang Banjarbaru. Memiliki wewenang mengesahkan (*Verified Bank*) atau menerbitkan catatan revisi bank. Memiliki restriksi keamanan tinggi: tidak dapat mengakses Master Rekening dan tidak dapat membuat/mengubah/menghapus entri transaksi.
4. **Pilar 3 - Konsolidator Kasda (BPKAD Kota Banjarbaru):** Melakukan pemeriksaan lanjutan atas laporan yang telah disahkan Bank Kalsel, memverifikasi kesesuaian saldo Kasda, memeriksa kelengkapan checklist 4 berkas fisik, menerbitkan catatan koreksi Kasda, serta memberikan tanda kelayakan (*Valid Konsolidator*).
5. **Pilar 4 - Pengesahan Akhir (Inspektorat Kota Banjarbaru):** Melakukan pengawasan dan audit kepatuhan menyeluruh atas rekonsiliasi yang telah disahkan oleh Bank Kalsel dan Konsolidator Kasda, menerbitkan Nomor Berita Acara (BA) resmi, serta membubuhkan stempel digital pengesahan (*Digital e-Seal*) akhir.

---
*SiReKa - Solusi Digitalisasi Transparan & Akuntabel untuk Pengelolaan Keuangan Pemerintah Kota Banjarbaru.*

---
## 📝 Changelog
* **v2.0.1** - Perbaikan bug pada sistem paginasi tabel (Laporan BA & Transaksi) di mana filter pencarian/bulan kini tetap tersimpan (`withQueryString`) saat berpindah halaman.
* **v2.0.2** - Perbaikan bug JS formatter rupiah yang menyebabkan angka saldo bertambah 2 digit (akibat salah baca desimal `.` dari database). Penambahan fitur penguncian Saldo Kas Awal otomatis secara *read-only* dengan opsi *bypass* di menu Pengaturan Instansi Admin.
* **v2.0.3** - Peningkatan UI/UX struktural pada layout *Sidebar* (menu samping). Mengubah format *floating margin* menjadi tata letak *full-height* yang menempel (seamless) dengan tepi layar utama. Ditambahkan juga efek animasi mikro (slide & scale) dan garis struktur hierarki submenu untuk antarmuka yang lebih dinamis dan rapi dengan tetap mempertahankan warna identitas (brand color) aplikasi bawaan.
* **v2.0.4** - Perbaikan *UI Stacking Context* (z-index) pada layout utama. Mencegah bug visual di mana konten halaman dashboard (seperti ringkasan dan *badge*) menembus lapisan menu atas (*topbar*) saat halaman digulir ke bawah, serta penyesuaian lapisan penutup gelap pada mode *mobile*.
* **v2.0.5** - Penambahan *Active Route Detector* pada *Sidebar*. Memperbaiki masalah menu navigasi anak (sub-menu) yang otomatis tertutup (*reset*) saat halaman dimuat ulang dengan cara mendeteksi URL aktif dan mempertahankan status ekspansi (*expanded state*) dari induk menu yang sedang diakses.
* **v2.0.6** - Penambahan indikator versi rilis aplikasi (*watermark style*) pada bagian bawah antarmuka navigasi samping (*Sidebar*) guna mempermudah pelacakan pembaruan sistem.
* **v2.0.7** - Perbaikan *bug* logika Javascript (JS) pada tombol buka/tutup menu di perangkat *Mobile/Tablet*. Sidebar kini akan meluncur dengan sempurna menutupi layar pelindung (overlay) yang gelap tanpa *error state*.
* **v2.1.0** - Transformasi antarmuka Mobile menjadi *Progressive Web App* (PWA) murni. Pengguna HP dan Tablet kini dapat menginstal SiReKa ke *Home Screen* (layar penuh, layaknya aplikasi *native*). Penambahan fitur *Bottom Navigation Bar* modern bergaya iOS/Android yang lebih ergonomis, menggantikan peran menu hamburger konvensional di layar kecil, lengkap dengan *Active Route Detector* visual.
* **v2.1.1** - Peningkatan UX dengan integrasi **NProgress Loading Bar**. Menambahkan garis animasi pemuatan data interaktif di bagian atas layar setiap kali pengguna mengklik navigasi atau mengirimkan form, memberikan transisi perpindahan halaman yang lebih mulus dan responsif layaknya aplikasi *Single Page Application* (SPA).
* **v2.1.2** - Peningkatan Keamanan (*Security Hardening*): Implementasi validasi unggahan berlapis (*Anti-Malware Upload*) pada seluruh modul yang menerima file (Transaksi, Pengaturan Logo, dan *Maintenance Restore*). Sistem kini memeriksa ekstensi file yang diizinkan secara eksplisit (menggunakan aturan `extensions:pdf,jpg,...`) bersamaan dengan pemindaian tipe konten (MIME type inspection) untuk mencegah serangan manipulasi nama ekstensi berbahaya.
* **v2.2.0** - **Fitur Pemeriksaan Konsolidator, Multi-Round Revision Timeline, & Reset Draft Admin:**
  * Penambahan modul pemeriksaan berkas dan data rekonsiliasi khusus bagi Konsolidator BKAD (`transaksi.pemeriksaan`).
  * Penambahan tabel riwayat koreksi bertingkat (`transaksi_catatans`) yang mencatat setiap putaran evaluasi (tanggal, jam, nama pemeriksa, status, dan teks catatan) secara kronologis tanpa saling menimpa.
  * Penambahan tombol integrasi WhatsApp cepat pada layar Konsolidator yang otomatis memformat pesan ke Admin Pusat saat ditemukan selisih atau kesalahan dokumen.
  * Penambahan wewenang aksi 1-klik bagi Admin Pusat untuk mengembalikan status transaksi menjadi *Draft* (*Reset to Draft*) agar data terbuka kembali bagi SKPD untuk diperbaiki ulang.
  * Peningkatan indikator status ganda pada tabel transaksi: Status SKPD (*Diverifikasi SKPD* / *Draft*) dan Status Konsolidator (*Valid Konsolidator* / *Perlu Perbaikan* / *Menunggu Cek*).
  * Pembaruan hak akses form upload agar SKPD dapat mengunggah berkas bukti dukung sejak awal pengerjaan transaksi (sebelum verifikasi).
* **v2.2.1** - Peningkatan Filter Pengguna: Penambahan fitur input pencarian nama lengkap, username, dan email pada menu Pengaturan Pengguna (*User Management*) serta tombol reset filter.
* **v2.3.0** - **Antrean Verifikasi Terpusat & Mode Cek Cepat (Rapid Review Save & Next):**
  * **Meja Kerja Antrean Verifikasi Terpusat (`/transaksi/antrean`):** Halaman verifikasi khusus bagi Konsolidator dan Administrator yang mengelompokkan data rekonsiliasi ke dalam 4 tab interaktif: *Menunggu Pemeriksaan*, *Perlu Perbaikan*, *Butuh Reset Draft*, dan *Telah Disetujui (Valid)*.
  * **4 Kartu Metrik Ringkasan Real-Time:** Menampilkan jumlah berkas yang menunggu dicek, memerlukan perbaikan SKPD, butuh reset draft admin, dan yang telah berstatus sah pada tahun anggaran aktif.
  * **Mode Pemeriksaan Cepat (Save & Next ⏩):** Tombol aksi *"Simpan & Lanjut ke BA Berikutnya"* yang otomatis membawa pemeriksa langsung ke berkas antrean berikutnya tanpa perlu bolak-balik ke tabel antrean berulang kali.
  * **Navigasi Cepat di Lembar Pemeriksaan:** Dilengkapi tombol *[← Prev]* / *[Next →]* dan indikator sisa antrean yang belum diperiksa.
  * **Indikator Dokumen & Selisih Saldo di Antrean:** Visualisasi langsung status kelengkapan 4 bukti dukung (X/4 Berkas) dan status saldo (KLOP / Rincian Selisih) langsung pada tabel antrean.
  * **Menu Antrean Verifikasi di Sidebar:** Menu langsung khusus Admin dan Konsolidator lengkap dengan *badge counter* (indikator angka berdenyut) jumlah berkas yang menunggu pemeriksaan.
* **v2.4.0** - **Laporan Verifikasi Konsolidator, Tanda Bukti Digital (Slip PDF), Kontrol Unduh SKPD, & Stempel Digital BA:**
  * **Modul Laporan Verifikasi Konsolidator (`/laporan/verifikasi-konsolidator`):** Rekapitulasi register pemeriksaan kas daerah tingkat SKPD untuk Admin & Konsolidator dengan 3 kartu metrik ringkasan, filter bulan/SKPD/status, cetak register PDF (Landscape), dan ekspor Excel (.xlsx).
  * **Surat Tanda Bukti Pemeriksaan Rekonsiliasi Kas Daerah (Slip PDF A4):** Dokumen resmi 1 lembar ber-KOP BPKAD dengan Nomor Register Digital unik (`REG-KONS/BJB/...`), checklist pengujian 4 bukti dukung fisik, identitas pemeriksa, waktu pemeriksaan (WITA), dan QR Code otentikasi.
  * **Saklar Kontrol Izin Unduh SKPD (ON/OFF Toggle):** Tuas kontrol di Pengaturan Instansi bagi Admin BPKAD untuk mengaktifkan atau menonaktifkan izin download Surat Tanda Bukti Digital bagi Operator SKPD (dengan proteksi keamanan 403 saat OFF).
  * **Stempel Digital Pengesahan (*Digital e-Seal*) pada Berita Acara (BA) PDF:** Berita Acara bulanan otomatis mencetak cap stempel pengesahan Konsolidator BPKAD ketika status rekonsiliasi telah disahkan *Valid*.
  * **Peningkatan Laman Verifikasi Publik QR Code (`/verifikasi/{id}`):** Tampilan status ganda transparan yang memuat bukti pengesahan Konsolidator BPKAD dan tombol unduh slip digital.
* **v2.5.0** - **Edisi Khusus Pemerintah Kota Banjarbaru (Arsitektur 4-Pilar, 2FA TOTP, Dynamic BA, & Animasi Interaktif):**
  * **Alur Verifikasi Berjenjang 4-Pilar:** Implementasi alur rekonsiliasi kas daerah resmi: 1) Operator SKPD -> 2) Pihak Bank Kalsel Cabang Banjarbaru -> 3) Konsolidator BPKAD -> 4) Inspektorat Kota Banjarbaru.
  * **Jejak Audit Forensik & Verifikasi Log (`verifikasi_logs`):** Setiap aksi persetujuan, penolakan, dan catatan revisi antar instansi dicatat lengkap dengan timestamp, identitas verifikator, dan segel digital SHA-256.
  * **Penomoran Berita Acara (BA) Dinamis (`BaNumberService`):** Fleksibilitas format penomoran BA melalui pengaturan global/instansi (`900/{NOMOR}/BA-REKON/{KODE_SKPD}/{BULAN_ROMAWI}/{TAHUN}`) dengan generator nomor urut tahunan otomatis.
  * **Autentikasi Dua Faktor (2FA - RFC 6238 TOTP):** Pengamanan ekstra akun pengguna menggunakan aplikasi otentikator (Google/Microsoft Authenticator) dengan SVG QR Code generator bawaan tanpa dependensi API eksternal dan 8 recovery emergency codes terenkripsi.
  * **Redesain Halaman Login (Center Card & Bioluminescent Golden Fireflies):** Kartu login simetris di tengah layar (*center page*), responsif di layar mobile dan desktop, berlatar belakang biru tua kedinasan Banjarbaru (`#001938` ke `#00346f`), serta animasi Kunang-Kunang Emas berbasis HTML5 Canvas 60 FPS kustom tanpa gambar AI.
  * **Halaman Registrasi Operator SKPD (Constellation Mesh & Mode Grab 160px):** Dropdown SKPD cerdas (TomSelect) dan efek partikel konstelasi jaringan dengan mode grab 160px yang otomatis menghubungkan garis putih ke kursor mouse maupun titik sentuh layar (*touch screen*).
  * **Master Data 87 SKPD Pemerintah Kota Banjarbaru:** Import dan seeder lengkap seluruh unit kerja SKPD Pemko Banjarbaru dari berkas master `kodeskpdbjb.xlsx`.
* **v2.5.1** - **Penyelarasan Otorisasi Ketat 4-Pilar, Redesain Profil Material 3, & Integrasi Sidebar:**
  * **Penguatan Otorisasi Ketat 4-Pilar (Security Hardening):** Membatasi hak akses verifikator Bank Kalsel (Pilar 2) agar tidak dapat mengakses Master Rekening, serta menolak operasi penambahan, pengeditan, penghapusan, dan pengunggahan transaksi via FormRequest authorization (`authorize(): bool`) dengan respon HTTP 403 Forbidden.
  * **Perbaikan Signature Verifikasi Bank:** Menuntaskan penanganan error 403 `Invalid signature` pada rute `/verifikasi/bank` sehingga verifikasi dan penandatanganan digital oleh Bank Kalsel berjalan lancar.
  * **Redesain Halaman Profil Pengguna Standar Material 3 (`/profile`):**
    * Menghadirkan **Hero Identity Banner** bergradasi navy/amber khas Pemko Banjarbaru dengan inisial avatar besar, badge peranan kedinasan, instansi SKPD, username, dan chip status 2FA.
    * Mengganti template bawaan dengan formulir Bahasa Indonesia baku, mengunci **Username** dan **Instansi / Unit Kerja** sebagai *Read-Only* permanen bertanda gembok demi kepatuhan identitas yuridis.
    * Formulir kata sandi modern dilengkapi toggle mata interaktif (`visibility` / `visibility_off`) berbasis Alpine.js di semua kolom kata sandi.
    * Kartu pengelolaan 2FA / Google Authenticator terintegrasi dengan badge status perlindungan.
  * **Pengamanan Integritas Akun Kedinasan (Anti-Self-Deletion & Digital Audit Trail):** Menghapus tombol merah hapus akun mandiri dan memblokir method `destroy()` di `ProfileController` guna melindungi keutuhan jejak audit rekonsiliasi kas daerah. Digantikan oleh Kartu Kebijakan Integritas & Tata Kelola Akun Kedinasan resmi.
  * **Integrasi Navigasi Bilah Sisi (Sidebar):** Menambahkan submenu **Profil Saya** (`route('profile.edit')`) di bawah dropdown Pengaturan dan **Kartu Akses Cepat Profil Pengguna** di Bottom Actions sidebar dengan status rute aktif dinamis.
