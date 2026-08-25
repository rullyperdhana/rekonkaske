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
1. **Admin Pusat (BKAD Tapin):** Kontrol penuh master data, manajemen storage NAS, proteksi audit (izin re-upload SKPD), audit log, buka/tutup registrasi operator, backup/restore DB, dan mode maintenance pengaman.
2. **Konsolidator:** Melakukan validasi rekon, memantau EWS dan Leaderboard, melihat rekapitulasi, mengekspor laporan eksekutif PDF & ZIP Paket Audit BPK.
3. **Operator SKPD:** Mengelola input saldo rekon tahun berjalan dan mencetak Berita Acara ber-QR Code untuk pengesahan pimpinan instansi.

---
*SiReKa - Solusi Digitalisasi Transparan & Akuntabel untuk Pengelolaan Keuangan Pemerintah Kabupaten Tapin.*

---
## 📝 Changelog
* **v2.0.1** - Perbaikan bug pada sistem paginasi tabel (Laporan BA & Transaksi) di mana filter pencarian/bulan kini tetap tersimpan (`withQueryString`) saat berpindah halaman.
* **v2.0.2** - Perbaikan bug JS formatter rupiah yang menyebabkan angka saldo bertambah 2 digit (akibat salah baca desimal `.` dari database). Penambahan fitur penguncian Saldo Kas Awal otomatis secara *read-only* dengan opsi *bypass* di menu Pengaturan Instansi Admin.
* **v2.0.3** - Peningkatan UI/UX struktural pada layout *Sidebar* (menu samping). Mengubah format *floating margin* menjadi tata letak *full-height* yang menempel (seamless) dengan tepi layar utama. Ditambahkan juga efek animasi mikro (slide & scale) dan garis struktur hierarki submenu untuk antarmuka yang lebih dinamis dan rapi dengan tetap mempertahankan warna identitas (brand color) aplikasi bawaan.
