# SiReKa (Sistem Rekonsiliasi Kas Daerah - Kabupaten Tapin)

**SiReKa (Sistem Rekonsiliasi Kas)** adalah platform digital terpadu untuk pengelolaan, validasi, dan rekonsiliasi kas Bendahara Pengeluaran tingkat Satuan Kerja Perangkat Daerah (SKPD) di lingkungan Pemerintah Kabupaten Tapin (Badan Keuangan dan Aset Daerah - BKAD). Aplikasi ini dirancang dengan tingkat presisi, kecepatan, keamanan tinggi, dan kepatuhan standar akuntansi pemerintah (BPK/Inspektorat).

---

## 🚀 Fitur Unggulan SiReKa (v2.0 - Enterprise Architecture)

### 1. 🏆 Executive Analytics, Leaderboard & Early Warning System (EWS)
* **Timeliness Scoring Algorithm (Bobot Waktu Peringkat):** Papan peringkat SKPD Terbaik di dasbor tidak hanya menghitung kuantitas laporan bulanan, tetapi menerapkan bobot kedisiplinan hari pengiriman (Tgl 1–5 = 100 pt, Tgl 6–10 = 85 pt, Tgl 11–15 = 70 pt, > Tgl 15 = 50 pt).
* **Early Warning System (EWS - Rapor Merah):** Panel pengawasan khusus yang menyorot 5 SKPD dengan keterlambatan terparah atau adanya selisih kas bulanan, memudahkan pembinaan lebih dini oleh Konsolidator dan pimpinan.
* **Cetak Rapor Kepatuhan Eksekutif (PDF):** Kemudahan mengunduh dokumen laporan performa & kepatuhan seluruh instrumen instansi se-Kabupaten Tapin bersertifikasi resmi berklasifikasi Grade A, B, C, hingga D.

### 2. 📱 WhatsApp Generator & Rekap Broadcast Pimpinan (Sinkronisasi Akurat)
* **Rekapitulasi Siap Salin ke Grup WA:** Dasbor pelaporan otomatis menghasilkan rekapitulasi daftar SKPD yang **Sudah Rekonsiliasi** maupun **Belum Rekonsiliasi** per bulan dengan format rapi dan emotikon informatif yang siap dilarang/dibroadcast ke grup WhatsApp Admin, Konsolidator, maupun Kepala SKPD guna efisiensi koordinasi.
* **Filter Kriteria Kelengkapan Bukti (Anti-Perbedaan Laporan):** Admin memiliki kendali penuh memilih kriteria "Sudah Rekonsiliasi", mulai dari *Semua Status*, *Verified Only*, hingga opsi khusus **Khusus yang Sudah Upload Berita Acara (BA Manual)** dan **Khusus yang Sudah Upload Lengkap (4 Dokumen)**, memastikan sinkronisasi 100% dengan Laporan Tunggakan & Tanpa Dokumen Pendukung Lengkap!
* **Laporan Status Cetak Akun:** Log internal admin yang memantau tanggal cetak serta SKPD mana yang belum memiliki operator aktif.

### 3. 🗄️ Brankas Digital & Ekspor ZIP Massal (Paket Audit BPK)
* **Hirarki Dokumen & Quick Pratinjau (Urut Kode SKPD):** Seluruh file Rekening Koran, Buku Kas Umum (BKU), Buku Pembantu Bank, dan Berita Acara (BA) disusun dalam struktur pohon (Tree) rapi dan **diurutkan secara hierarkis berdasarkan Kode SKPD BPKAD** dengan fitur *In-Browser Preview Modal* tanpa harus mendownload file satu per satu.
* **Ekspor Massal Paket Audit BPK (.ZIP):** Fitur kompresi massal satu klik untuk mengunduh seluruh bukti dukung dokumen se-Kabupaten Tapin dalam 1 file ZIP yang **otomatis distrukturkan ke dalam sub-folder Kode & Nama SKPD beserta bulan** (contoh: `1-01-01-dinas-kesehatan/Bulan_06_Juni/Rekening_Koran.pdf`), membedah ratusan jam pemeriksaan akuntansi teknis.
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

### 8. 🤖 Integrasi Notifikasi Telegram Otomatis & Pusat Dokumentasi Sistem (`v2.6.0`)
* **Siaran Notifikasi Telegram Instan ke HP Admin:** Aplikasi secara otomatis mengirimkan notifikasi langsung ke smartphone Administrator dan Konsolidator via Bot Telegram saat SKPD menyelesaikan rekonsiliasi kas (*Posting Final / Diverifikasi SKPD*). Admin tidak perlu bolak-balik membuka aplikasi web untuk mengetahui berkas yang siap diperiksa.
* **Format Pesan Informatif & Elegan:** Notifikasi memuat ringkasan lengkap: Nama & Kode SKPD, periode bulan/tahun, nominal Saldo Akhir BKU, Saldo Akhir Bank, status KLOP/Selisih kas, jumlah berkas fisik yang terunggah (X/4 Berkas), nama Operator penginput, dan waktu posting (WITA).
* **Dukungan Multi-Chat ID (Pribadi & Grup):** Fleksibilitas pengiriman ke chat pribadi admin maupun ke grup koordinasi tim konsolidator BKAD sekaligus (cukup pisahkan Chat ID dengan koma).
* **Alat Uji Koneksi Langsung (Test Connection):** Administrator dapat menguji kredensial Token Bot dan Chat ID langsung dari dasbor web dengan respons instan dan pratinjau pesan di layar.
* **Arsitektur Fail-Safe Anti-Macet:** Proses penyimpanan transaksi SKPD dijamin tetap berjalan lancar dan sukses 100% tanpa gangguan error meskipun koneksi API Telegram sedang mengalami gangguan jaringan atau timeout.
* **Pusat Dokumentasi & Log Pembaruan Administrator (`/pengaturan/dokumentasi`):** Halaman internal terpadu khusus admin yang menyajikan katalog 10 modul unggulan SiReKa, riwayat versi (changelog) interaktif ber-filter kategori, dan ringkasan arsitektur spesifikasi teknis server.

### 9. 🏛️ Executive Command Center & KDH / Sekda View (`v2.7.0`)
* **Mode Kiosk & Big Screen Display TV:** Dirancang khusus untuk monitor ruang kerja pimpinan (Bupati, Wakil Bupati, Sekretaris Daerah, Kepala BKAD) dengan tata letak satu layar penuh (*One-Screen Helicopter View*), tombol mode layar penuh (*Fullscreen*), serta mesin penyegaran otomatis (*Auto-Refresh Kiosk Engine*) tiap 60 detik yang dapat dijeda/dilanjutkan.
* **4 Indikator Makro Fiskal Daerah:** Menampilkan ringkasan likuiditas kas pemda di Bank Kalsel, Indeks Kepatuhan Rekonsiliasi Daerah (%), Akumulasi Nominal Selisih Kas, dan Tingkat Ketepatan Waktu Pelaporan (&le; tgl 10) beserta kesiapan berkas pemeriksaan BPK.
* **Decision Support System (Rekomendasi Kebijakan Cerdas):** Mesin telaah cerdas yang otomatis merumuskan rekomendasi tindakan konkret bagi pimpinan daerah (misal instruksi audit penelusuran jika ada selisih kas, atau penerbitan surat peringatan/teguran pimpinan bagi SKPD yang menunggak).
* **Tren Likuiditas Kas Pemda 12 Bulan:** Grafik interaktif Chart.js yang melacak fluktuasi total saldo kas bendahara pengeluaran se-Kabupaten Tapin dari Januari s.d. Desember.
* **Matriks Status Seluruh SKPD se-Kabupaten Tapin:** Grid visual interaktif yang memetakan seluruh instansi dengan kode warna status (Klop, Draft, Selisih, Belum Lapor), pencarian instan, dan tautan langsung WhatsApp ke Bendahara SKPD.
* **Cetak Ringkasan Eksekutif 1-Halaman (`/eksekutif/cetak-brief`):** Dokumen formal berkop resmi Pemerintah Kabupaten Tapin siap cetak/unduh PDF untuk bahan rapat pimpinan (Rapim) mingguan.

### 10. 📅 Aturan Ketat Periode Bulan Pelaporan & Cutoff Tutup Kas Desember (`v2.7.7`)
* **Pembatasan Periode Bulan Pelaporan (Reporting Period Guard):** Form input dan filter transaksi rekonsiliasi kas (`/transaksi`, `/transaksi/create`, `/transaksi/{id}/edit`, dan antrean pemeriksaan) secara cerdas hanya menampilkan bulan-bulan yang sudah memasuki masa pelaporan (bulan berjalan dan bulan lampau). Bulan masa depan yang belum tiba (*future months*) dicegah dari pemilihan untuk menjaga integritas data akuntansi.
* **Cutoff Khusus Tutup Buku Kas Akhir Tahun (Desember > Tgl 20):** Bulan Desember hanya dapat dipilih dan diinput apabila waktu server telah melewati tanggal 20 Desember (WITA), mencegah input dini rekonsiliasi kas sebelum seluruh transaksi SP2D akhir tahun dan penutupan buku anggaran selesai.
* **Sinkronisasi Multi-Layer Interaktif:** Sinkronisasi instan via JavaScript pada form input saat tahun anggaran diubah (tahun lampau otomatis membuka seluruh 12 bulan), serta diperkuat validasi ketat lapis kedua pada backend FormRequest (`StoreTransaksiRequest` dan `UpdateTransaksiRequest`).

### 11. 🛡️ Proteksi Duplikasi Berita Acara Rekonsiliasi (`v2.7.8`)
* **Pencegahan Duplikasi Berita Rekon (Anti-Duplicate Guard):** Dropdown pilihan periode bulan pada form tambah dan edit transaksi secara otomatis menonaktifkan (`disabled`) dan menandai bulan yang sudah memiliki Berita Acara Rekonsiliasi aktif untuk rekening dan tahun bersangkutan dengan label `[Nama Bulan] (Sudah Ada Berita Rekon)`.
* **Smart Auto-Switch Selection:** Jika bulan yang sedang dipilih ternyata dinonaktifkan karena telah ada Berita Rekon, antarmuka otomatis mengalihkan pilihan ke bulan aktif pertama berikutnya yang belum memiliki Berita Rekon, sekaligus memuat saldo kas awal bulan sebelumnya secara instan via API AJAX `/transaksi/get-saldo-awal`.
* **Multi-Layer Backend Guard & Mode Edit:** Endpoint `/transaksi/get-saldo-awal` mendukung parameter `ignore_id` sehingga pada mode edit (`/transaksi/{id}/edit`), bulan dari transaksi yang sedang disunting tidak dinonaktifkan. Dilengkapi pesan validasi `periode_bulan.unique` yang eksplisit dan banner peringatan visual jika seluruh periode bulan telah tuntas direkonsiliasi.

---

## 🛠️ Persyaratan Sistem (Server Production)
* **PHP:** >= 8.3 / 8.4 (Laravel 13 Framework - Server Production aaPanel PHP 8.4 CLI)
* **Database:** MySQL / MariaDB / SQLite
* **Composer:** Versi 2.x
* **PHP Extensions:** BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/Imagick, DOM, Zip, cURL.

---

## ⚙️ Panduan Instalasi & Pengoperasian Server

### 1. Deployment Awal / Instalasi Baru:
```bash
git clone https://github.com/rullyperdhana/rekonkaske.git sireka
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

## 👥 Struktur Hak Akses (Role Base)
1. **Admin Pusat (BKAD Tapin):** Kontrol penuh master data, manajemen storage NAS, proteksi audit (izin re-upload SKPD), audit log, buka/tutup registrasi operator, backup/restore DB, mode maintenance pengaman, dan **wewenang khusus merubah status transaksi verified kembali ke draft (*Reset to Draft*) atas rekomendasi Konsolidator**.
2. **Konsolidator:** Melakukan pemeriksaan (*fact-check*) laporan rekonsiliasi yang telah diverifikasi SKPD beserta 4 berkas bukti dukung fisik, memberikan tanda kelayakan (*Valid Konsolidator*), mencatat evaluasi/catatan kesalahan bertingkat (*multi-round revision timeline*), memicu koordinasi pemulihan draft ke Admin via integrasi WhatsApp otomatis, memantau EWS dan Leaderboard, serta mengekspor laporan eksekutif PDF & ZIP Paket Audit BPK.
3. **Operator SKPD:** Mengelola input saldo rekonsiliasi, mengunggah 4 berkas bukti dukung (BA Manual, BKU, Pembantu Bank, Rekening Koran), melakukan verifikasi mandiri (*Verified*) untuk pengesahan awal, membaca riwayat catatan koreksi Konsolidator, dan memperbaiki data jika transaksi dikembalikan ke draft oleh Admin.

---
*SiReKa - Solusi Digitalisasi Transparan & Akuntabel untuk Pengelolaan Keuangan Pemerintah Kabupaten Tapin.*

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
  * **Surat Tanda Bukti Pemeriksaan Rekonsiliasi Kas Daerah (Slip PDF A4):** Dokumen resmi 1 lembar ber-KOP BKAD dengan Nomor Register Digital unik (`REG-KONS/TAPIN/...`), checklist pengujian 4 bukti dukung fisik, identitas pemeriksa, waktu pemeriksaan (WITA), dan QR Code otentikasi.
  * **Saklar Kontrol Izin Unduh SKPD (ON/OFF Toggle):** Tuas kontrol di Pengaturan Instansi bagi Admin BKAD untuk mengaktifkan atau menonaktifkan izin download Surat Tanda Bukti Digital bagi Operator SKPD (dengan proteksi keamanan 403 saat OFF).
  * **Stempel Digital Pengesahan (*Digital e-Seal*) pada Berita Acara (BA) PDF:** Berita Acara bulanan otomatis mencetak cap stempel pengesahan Konsolidator BKAD ketika status rekonsiliasi telah disahkan *Valid*.
  * **Peningkatan Laman Verifikasi Publik QR Code (`/verifikasi/{id}`):** Tampilan status ganda transparan yang memuat bukti pengesahan Konsolidator BKAD dan tombol unduh slip digital.
* **v2.5.0** - **Submenu & Halaman Profil Bento Modern, Dukungan NIP Pegawai, & Proteksi Penguncian Pemeriksaan Konsolidator:**
  * **Navigasi Submenu & Topbar Dropdown:** Penambahan menu **"Profil Saya"** pada submenu Pengaturan Sidebar dengan deteksi rute aktif, serta peningkatan popover profil akun di Topbar (lengkap dengan inisial avatar, profil akun, ubah password, dan manajemen user).
  * **Desain Ulang Halaman Profil Bento Grid (`/profile`):** Tampilan modern Material Design 3 dengan kartu identitas pengguna (Avatar inisial, status aktif, badge Role, unit kerja SKPD BKAD Tapin, tanggal bergabung), form pembaruan data diri (Nama, NIP, Username, Email), dan tautan cepat ke ubah password.
  * **Dukungan NIP (Nomor Induk Pegawai):** Penambahan kolom `nip` pada database pengguna (`users`), validasi FormRequest, serta form input pada profil akun dan menu Pengaturan Pengguna.
  * **Pencetakan NIP Otomatis pada Dokumen Verifikasi:** Nomor NIP pemeriksa kini otomatis tercetak resmi di bawah nama pejabat Konsolidator pada Surat Tanda Bukti Pemeriksaan Rekonsiliasi Digital (PDF), stempel Berita Acara (BA), dan landing page QR code otentikasi.
  * **Proteksi Penguncian Pemeriksaan Konsolidator (Anti-Tampering Lock):** Formulir pemeriksaan laporan rekonsiliasi otomatis dikunci (*Locked & Read-Only*) saat status transaksi telah disahkan **VALID** guna mencegah perubahan data sembarangan.
  * **Panel Pengesahan Resmi:** Panel keputusan pemeriksaan digantikan dengan tampilan status **"Sah & Terkunci"** berbadge `TERKUNCI` dengan ringkasan pemeriksa (Nama, NIP, Waktu Disahkan) dan tombol unduh langsung slip digital PDF & BA.
  * **Wewenang Khusus Buka Kunci Admin:** Administrator Pusat memiliki tombol wewenang khusus *"Buka Kunci & Reset ke Draft"* yang secara otomatis mengembalikan status transaksi dan konsolidator ke tahap awal jika terdapat instruksi revisi resmi.
* **v2.5.1** - **Animasi Bioluminescent Fireflies Interaktif & Desain Modern Login Page:**
  * **Animasi Latar Belakang Interaktif (*Bioluminescent Golden Fireflies*):** Implementasi canvas HTML5 60 FPS dengan partikel cahaya lembut berpendar emas, amber, dan hijau zamrud yang meliuk dinamis serta menghindari pergerakan kursor mouse (*swirling evasion & organic wandering*).
  * **Top Navigation Bar Glassmorphism:** Bilah navigasi atas transparan dengan identitas resmi Pemkab Tapin, jam digital real-time Waktu Indonesia Tengah (**Live WITA Clock**), dan tautan cepat kembali ke halaman Beranda (*Landing*).
  * **Peningkatan Visual Kartu Login:** Desain kartu minimalis berbasis font *Plus Jakarta Sans*, ikon *Material Symbols*, tombol intip kata sandi (*toggle eye visibility*), penataan kartu *Math Captcha* yang proporsional, serta tombol aksi bersudut halus dengan gradasi warna khas brand.
* **v2.5.2** - **Dukungan Penuh Presisi Data Sen (2 Angka di Belakang Koma) & Anti-Floating Point Precision:**
  * **Input Masking Cerdas & Responsif:** Pembaruan logika JavaScript pada form transaksi (`/transaksi/create` & `/transaksi/{id}/edit`) agar pengguna dapat mengetik koma `,` atau titik desimal numpad `.` secara langsung tanpa terhapus otomatis, serta mempertahankan angka desimal sen (contoh: `,50` atau `,75`).
  * **Normalisasi Otomatis Saat Blur:** Format nominal otomatis dirapikan ke standar 2 desimal (`Rp xx.xxx,xx`) ketika berpindah kolom input.
  * **Kalkulasi Akurat Bebas Floating-Point Noise:** Perhitungan otomatis saldo akhir BKU, Bank, dan Selisih kini menggunakan pembulatan presisi `Math.round((awal + terima - keluar) * 100) / 100` dan disimpan dalam format `.toFixed(2)` untuk mencegah desimal tak berhingga bawaan JavaScript.
  * **Penyempurnaan Validasi FormRequest:** Pengujian kondisi selisih pada `StoreTransaksiRequest` dan `UpdateTransaksiRequest` kini menggunakan `round(abs($bku - $bank), 2) > 0` guna mencegah penolakan form palsu (*false-positive* selisih).
  * **Ambang Batas Selisih Terkalibrasi pada Dashboard & Laporan:** Mengeliminasi *dead-zone* fixed `0.01` pada `DashboardController`, halaman Antrean, Pemeriksaan, dan Verifikasi QR Code, sehingga selisih sekecil Rp 0,01 (1 sen) sah dan presisi terhitung ke dalam riwayat selisih kas maupun rapor kepatuhan.
* **v2.6.0** - **Integrasi Notifikasi Telegram Otomatis saat SKPD Posting Final & Pusat Dokumentasi Administrator:**
  * **Modul Pengaturan Notifikasi Telegram (`/pengaturan/telegram`):** Panel administrasi untuk mengonfigurasi Token Bot Telegram dari @BotFather, Target Chat ID / Grup ID BKAD, switch aktifasi siaran, simulasi notifikasi di layar smartphone, dan kartu panduan step-by-step.
  * **Fitur Uji Coba Ganda & Penjelasan Tombol:** Tersedia 2 tombol pengujian: *Tes Koneksi Bot* (menguji sambungan bot) dan *Tes Kirim Data SKPD* (mengirim contoh riil rekonsiliasi kas dengan nominal KLOP Rp 0,00 ke HP Admin), dilengkapi kartu keterangan fungsi tombol serta indikator loading interaktif.
  * **Pemicu Notifikasi Otomatis (Auto-Broadcast Posting Final):** Sistem otomatis mendeteksi dan mengirim pesan siaran Telegram saat transaksi disimpan dalam posisi diverifikasi (`status_verifikasi = verified`), lengkap dengan rincian saldo BKU, bank, selisih kas, kelengkapan berkas bukti dukung, operator pelapor, dan waktu pencatatan (WITA).
  * **Dukungan Multi-Penerima (Multi-Chat ID):** Kemampuan mengirim pesan sekaligus ke beberapa akun atau grup tim pemeriksa BKAD dengan pemisah tanda koma.
  * **Mekanisme Proteksi Fail-Safe:** Kegagalan jaringan atau timeout API Telegram ditangani secara anggun melalui log sistem tanpa pernah membatalkan atau memicu error pada penyimpanan data transaksi SKPD.
  * **Modul Dokumentasi & Log Pembaruan Internal (`/pengaturan/dokumentasi`):** Meja kerja dokumentasi sistem khusus Administrator BKAD yang menyajikan 3 tab utama: *Log Update (Changelog)* bertimeline interaktif dengan filter kategori pembaruan, *Katalog Modul & Fitur Unggulan SiReKa* dengan ringkasan wewenang hak akses dan tombol aksi cepat, serta *Spesifikasi Arsitektur Sistem* lengkap dengan panduan SOP deployment server.
  * **Integrasi Menu Sidebar Pengaturan:** Penambahan tautan menu *Notifikasi Telegram* dan *Dokumentasi & Log Fitur* pada kelompok navigasi Pengaturan Administrator serta pembaruan penanda versi footer menjadi *SiReKa v2.6.0*.
* **v2.7.0** - **Executive Command Center & KDH / Sekda View (One-Screen Big Display Monitoring):**
  * **Modul Dashboard Eksekutif (`/eksekutif`):** Antarmuka terpadu satu layar (*Helicopter View*) yang memvisualisasikan 4 indikator makro fiskal daerah (Likuiditas Kas di SKPD, Indeks Kepatuhan Pemda %, Akumulasi Selisih Kas, dan Tingkat Ketepatan Waktu Pelaporan).
  * **Mode Kiosk & TV Monitor Pimpinan:** Dilengkapi tombol *Fullscreen* (Layar Penuh), jam digital real-time Waktu Indonesia Tengah (WITA), serta mesin penyegaran otomatis (*Auto-Refresh Kiosk Engine*) tiap 60 detik yang dapat dijeda/dilanjutkan tanpa perlu sentuhan manual.
  * **Decision Support System (Rekomendasi Kebijakan Cerdas):** Algoritma penelaah cerdas yang otomatis merumuskan rekomendasi kebijakan bagi Bupati dan Sekretaris Daerah (perintah audit penelusuran jika ada selisih kas, surat peringatan/teguran keterlambatan bagi SKPD menunggak, dan evaluasi likuiditas kas mengendap).
  * **Tren Fluktuasi Kas 12 Bulan & Donut Kepatuhan:** Grafik analitik Chart.js melacak fluktuasi pergerakan saldo BKU vs Rekening Koran Bank Kalsel sepanjang tahun anggaran aktif serta distribusi status seluruh SKPD.
  * **Matriks Status Seluruh SKPD Kabupaten Tapin:** Grid bento interaktif untuk seluruh instansi dengan status warna dinamis (Klop, Draft, Selisih, Belum Lapor), fitur pencarian instan teks, dan tombol kontak langsung WhatsApp ke Bendahara SKPD.
  * **Cetak Ringkasan Eksekutif 1 Halaman (`/eksekutif/cetak-brief`):** Dokumen formal berkop resmi Pemerintah Kabupaten Tapin dan bertanda tangan Sekretaris Daerah / Kepala BKAD yang siap cetak atau disimpan sebagai PDF untuk bahan rapat pimpinan (Rapim).
  * **Integrasi Menu Navigasi Sidebar:** Penambahan menu *Analitik Eksekutif (KDH/SEKDA)* pada bilah samping dan pembaruan versi rilis aplikasi menjadi *SiReKa v2.7.0*.
* **v2.7.1** - **Security Hardening, CVE Dependency Patching & Anti-IDOR Authorization:**
  * **Patch CVE Dependensi (0 Advisories):** Pembaruan paket `maatwebsite/excel` ke versi 3.1.70 untuk menutup celah kritis `CVE-2026-84374` (arbitrary path traversal export), serta pembaruan `league/commonmark` ke versi 2.10.3 untuk menutup celah Denial of Service (DoS). Status pemindaian `composer audit` bersih 100%.
  * **Proteksi Path Traversal pada Storage Stream:** Pengetatan keamanan rute `/storage-stream/{path}` dengan memblokir karakter traversal (`../`, null byte, backslash), menolak akses berkas konfigurasi sistem (`.env`, `storage_nas_config.json`, `composer.json`), serta mewajibkan autentikasi login untuk mengakses dokumen keuangan daerah.
  * **Proteksi Anti-IDOR (Insecure Direct Object Reference) Transaksi:** Pengetatan wewenang pada `TransaksiController` (`store`, `edit`, `update`, `destroy`) yang memastikan akun Operator SKPD tidak dapat memanipulasi, mengedit, ataupun menghapus transaksi milik instansi/SKPD lain.
* **v2.7.2** - **Penambahan Peran Grup Auditor (Inspektorat / BPK) & Read-Only Audit Privileges:**
  * **Grup & Peran Pengguna Baru (`auditor`):** Penambahan opsi peran *Auditor (Inspektorat / BPK)* pada modul Manajemen Pengguna (`/pengaturan/user`), validasi form request (`StoreUserRequest` & `UpdateUserRequest`), serta filter pencarian peran pengguna.
  * **Badge Identitas Khusus:** Penyematan lencana amber/emas terverifikasi (*Auditor Pengawas*) pada tabel daftar pengguna dan laporan cetak kepemilikan akun non-SKPD.
  * **Hak Akses Pengawasan Terpadu (Read-Only Audit Privileges):** Akun Auditor secara otomatis diberikan wewenang melihat seluruh data rekonsiliasi kas se-Kabupaten Tapin, Analitik Eksekutif KDH/Sekda, Laporan Verifikasi Konsolidator, Konsolidasi Daerah, Ringkasan Selisih Kas, Tunggakan Kepatuhan, Berita Acara, serta akses unduh arsip berkas bukti dan paket ZIP massal BPK pada Brankas Digital (`/dokumen/tree`).
  * **Strict Data Integrity Lockdown (Anti-Manipulasi Data Audit):** Perlindungan tingkat controller yang secara ketat memblokir Auditor dari aksi mutasi data (dilarang membuat, mengedit, menghapus, atau mengunggah bukti transaksi, dilarang melakukan verifikasi/pemeriksaan laporan, serta dilarang merubah master rekening dan kop instansi).
* **v2.7.3** - **Eksklusi Fitur Komunikasi WhatsApp untuk Peran Auditor (Anti-Interferensi & Independensi Audit):**
  * **Penyembunyian Modul Broadcast WhatsApp:** Rute dan menu sidebar `/laporan/rekap-wa` disembunyikan dari navigasi Auditor dan diproteksi ketat pada controller level (`403 Forbidden`).
  * **Eksklusi Tombol Kirim WhatsApp pada Laporan:** Menghilangkan kolom aksi WhatsApp pada tabel Laporan Konsolidasi Daerah, ketiga tab Laporan Tunggakan Kepatuhan, serta tombol kontak langsung Bendahara pada Dashboard Analitik Eksekutif khusus saat akun yang login adalah Auditor.
* **v2.7.4** - **Pusat Informasi & Log Update Aplikasi serta SOP Prosedur Update Server VPS Production:**
  * **Pusat Informasi & Log Update Aplikasi Khusus Admin (`/pengaturan/dokumentasi` & `/pengaturan/update-aplikasi`):** Halaman komprehensif Material Design 3 terpadu bagi Administrator BKAD yang mengintegrasikan 4 tab navigasi: *Panduan Update VPS (SOP)*, *Log Update (Changelog 14 Versi)*, *Matriks Hak Akses Peran*, dan *Katalog Modul & Arsitektur*.
  * **Panduan Interaktif Deployment VPS aaPanel (SOP Production):**
    * Tombol salin 1-klik jalur eksekusi script otomatis: `cd /www/wwwroot/sireke.cloud && bash pull.sh`.
    * Prosedur manual CLI 8 langkah lengkap dengan tombol copy tiap baris perintah (Maintenance -> Git Reset -> Composer -> Migrate -> Optimize Cache -> Chmod/Chown Web Permissions -> Live).
    * Toolkit prosedur darurat & rollback commit jika terjadi kendala pasca rilis (`git reset --hard HEAD~1`, pemantauan log error `tail -n 50 -f storage/logs/laravel.log`, dan pengecekan integritas sistem `artisan about`).
    * Checklist interaktif Pra-Update & Pasca-Update dengan checkbox interaktif di browser.
  * **Matriks Hak Akses 4 Peran Pengguna (Access Control Matrix):**
    * Tabel pembanding transparan hak akses untuk 10 pilar modul antara *Administrator BKAD*, *Konsolidator*, *Operator SKPD*, dan *Auditor (Inspektorat / BPK)*.
    * Penjelasan landasan Standar Pemeriksaan Keuangan Negara (SPKN) terkait perlakuan *Strict Read-Only & Zero-Interference* serta eksklusi fitur komunikasi WhatsApp bagi akun peran Auditor.
  * **Detektor Runtime & Git Status Real-time:**
    * Deteksi otomatis hash commit git aktif, branch target, pesan commit rilis, dan status mode pemeliharaan sistem.
* **v2.7.5** - **Radar Keamanan Framework Laravel, Live CVE Scanner & Solusi Diagnostik CLI VPS aaPanel:**
  * **Tampilan Transparan Versi Laravel & Header Metric Bar:** Menampilkan versi Laravel terpasang (`v13.33.0`) secara jelas di header dan metric banner `/pengaturan/dokumentasi`, memudahkan administrator memantau siklus hidup framework.
  * **Live CVE Vulnerability Scanner (Audit Keamanan 1-Klik):** Tombol scan keamanan interaktif di browser yang langsung memindai seluruh paket dependensi terhadap basis data kerentanan global (*GitHub Advisories & Packagist CVE*) via Composer Audit API tanpa harus login SSH.
  * **SOP Tanggap Darurat Penambalan Celah Keamanan (Security Hotfix):** Panduan standar 4-langkah mitigasi celah keamanan (Audit di VPS, Update Patch di Komputer Lokal, Testing Mandiri, dan Deploy aman ke VPS via `pull.sh`).
  * **Solusi Diagnostik CLI Terminal VPS aaPanel:** Panduan penyelesaian kendala platform PHP 8.3 bawaan OS vs PHP 8.4 aaPanel saat menjalankan perintah `php artisan --version` di terminal SSH.
  * **Radar Eksternal Celah Keamanan (Zero-Day Watch):** Tautan langsung ke basis data pengumuman resmi GitHub Security Advisories, Packagist Tracker, Changelog Rilis Laravel, dan MITRE CVE Directory.
* **v2.7.6** - **Penyempurnaan Live CVE Scanner (Semver Version Matching & Zero False-Positive Engine):**
  * **Eliminasi Alarm Palsu (Zero False-Positive Engine):** Mengintegrasikan semantic versioning parser `Composer\Semver\Semver::satisfies()` dengan fallback parser cerdas `version_compare` pada scanner Packagist Security Advisories API. Sistem kini memvalidasi versi dependensi yang benar-benar terpasang di `composer.lock` terhadap rentang kerentanan (`affectedVersions`), menghapuskan alarm palsu yang sebelumnya menghitung seluruh 172 riwayat CVE historis paket.
  * **Rincian Kerentanan Interaktif (Actionable Advisory Card):** Menambahkan kartu visual rincian celah keamanan jika di kemudian hari ditemukan paket rentan secara riil, memuat ID CVE, versi terdampak, dan tautan langsung ke referensi penambalan resmi.
  * **Hasil Audit Terverifikasi (0 CVE Clean Status):** Memastikan 130 paket dependensi aktif terproteksi penuh sesuai verifikasi resmi `composer audit`.
* **v2.7.7** - **Pembatasan Periode Bulan Pelaporan & Cutoff Tutup Kas Desember (Modul Transaksi):**
  * **Pembatasan Periode Bulan Pelaporan (Reporting Period Guard):** Opsi periode bulan pada modul transaksi (`/transaksi`, input transaksi `/transaksi/create`, edit `/transaksi/{id}/edit`, dan antrean pemeriksaan) hanya memunculkan bulan yang sudah memasuki periode pelaporan (bulan berjalan dan bulan lampau). Bulan masa depan yang belum tiba tidak dapat dipilih.
  * **Aturan Khusus Tutup Buku Kas Akhir Tahun (Cutoff Desember > Tgl 20):** Bulan Desember hanya dapat dipilih dan diinput apabila waktu server telah melewati tanggal 20 Desember (WITA), mencegah input dini rekonsiliasi kas sebelum penerbitan SP2D / penutupan buku anggaran selesai.
  * **Proteksi Validasi Multi-Layer (Front-end & Back-end):** Dilengkapi sinkronisasi interaktif instan via JavaScript saat tahun anggaran diubah, serta validasi ketat lapis kedua pada backend FormRequest (`StoreTransaksiRequest` dan `UpdateTransaksiRequest`).
* **v2.7.8** - **Proteksi Duplikasi Berita Acara Rekonsiliasi (Anti-Duplicate Guard):**
  * **Pencegahan Duplikasi Berita Rekon:** Dropdown periode bulan pada form tambah dan edit transaksi secara otomatis menonaktifkan (`disabled`) dan memberi label `[Nama Bulan] (Sudah Ada Berita Rekon)` pada bulan yang sudah memiliki Berita Rekon untuk rekening dan tahun bersangkutan.
  * **Smart Auto-Switch Selection:** Jika bulan terpilih ternyata dinonaktifkan, antarmuka otomatis memilih bulan aktif pertama berikutnya yang belum memiliki Berita Rekon dan memuat saldo kas awal secara otomatis.
  * **Integrasi API Saldo Awal & Multi-layer Backend:** Endpoint `/transaksi/get-saldo-awal` mendukung parameter `ignore_id` untuk mode edit agar tidak mendisabel bulan transaksi aktif yang sedang disunting, pesan validasi `periode_bulan.unique` yang jelas, dan banner peringatan ramah pengguna jika seluruh periode bulan telah terselesaikan.

