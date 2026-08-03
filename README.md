# SiReKa (Sistem Rekonsiliasi Kas Daerah - Kabupaten Tapin)

**SiReKa (Sistem Rekonsiliasi Kas)** adalah platform digital terpadu untuk pengelolaan, validasi, dan rekonsiliasi kas Bendahara Pengeluaran tingkat Satuan Kerja Perangkat Daerah (SKPD) di lingkungan Pemerintah Kabupaten Tapin (Badan Keuangan dan Aset Daerah - BKAD). Aplikasi ini dirancang dengan tingkat presisi, kecepatan, keamanan tinggi, dan kepatuhan standar akuntansi pemerintah (BPK/Inspektorat).

---

## 🚀 Fitur Unggulan SiReKa (v2.0 - Enterprise Architecture)

### 1. 🏆 Executive Analytics, Leaderboard & Early Warning System (EWS)
* **Timeliness Scoring Algorithm (Bobot Waktu Peringkat):** Papan peringkat SKPD Terbaik di dasbor tidak hanya menghitung kuantitas laporan bulanan, tetapi menerapkan bobot kedisiplinan hari pengiriman (Tgl 1–5 = 100 pt, Tgl 6–10 = 85 pt, Tgl 11–15 = 70 pt, > Tgl 15 = 50 pt).
* **Early Warning System (EWS - Rapor Merah):** Panel pengawasan khusus yang menyorot 5 SKPD dengan keterlambatan terparah atau adanya selisih kas bulanan, memudahkan pembinaan lebih dini oleh Konsolidator dan pimpinan.
* **Cetak Rapor Kepatuhan Eksekutif (PDF):** Kemudahan mengunduh dokumen laporan performa & kepatuhan seluruh instrumen instansi se-Kabupaten Tapin bersertifikasi resmi berklasifikasi Grade A, B, C, hingga D.

### 2. 📱 WhatsApp Generator & Rekap Broadcast Pimpinan
* **Rekapitulasi Siap Salin ke Grup WA:** Dasbor pelaporan otomatis menghasilkan rekapitulasi daftar SKPD yang **Sudah Rekonsiliasi** maupun **Belum Rekonsiliasi** per bulan dengan format rapi dan emotikon informatif yang siap diledakkan (broadcast) ke grup WhatsApp Admin, Konsolidator, maupun Kepala SKPD guna efisiensi koordinasi.
* **Laporan Status Cetak Akun:** Log internal admin yang memantau tanggal cetak serta SKPD mana yang belum memiliki operator aktif.

### 3. 🗄️ Brankas Digital & Ekspor ZIP Massal (Paket Audit BPK)
* **Hirarki Dokumen & Quick Pratinjau:** Seluruh file Rekening Koran, Buku Kas Umum (BKU), Buku Pembantu Bank, dan Berita Acara (BA) disusun dalam struktur pohon (Tree) rapi dengan fitur *In-Browser Preview Modal* tanpa harus mendownload file satu per satu.
* **Ekspor Massal Paket Audit BPK (.ZIP):** Fitur kompresi massal satu klik untuk mengunduh seluruh bukti dukung dokumen se-Kabupaten Tapin dalam 1 file ZIP yang **otomatis distrukturkan ke dalam sub-folder nama SKPD & bulan** (contoh: `Dinas_Kesehatan/Bulan_06_Juni/Rekening_Koran.pdf`), membedahkan ratusan jam pemeriksaan akuntansi teknis.

### 4. 🖥️ Manajemen Storage Dinamis & Koneksi NAS (`/pengaturan/storage`)
* **Real-time Server Storage Gauge:** Pemantauan langsung persentase pemakaian hard disk server SiReKa berstatus warna (Aman / Kapasitas Menipis).
* **Switch Mode Storage (Fleksibel & Bukan Permanen):** Administrator dapat beralih metode penyimpanan kapan saja tanpa mengubah kode:
  - 📁 **Penyimpanan Internal Server (Lokal Disk)** - Mode default di folder `storage/app/public`.
  - 🖥️ **Network Attached Storage (NAS / NFS Mount)** - Menyalurkan file fisik ke mesin Synology/QNAP di jaringan Data Center/Kominfo. Dilengkapi boks instruksi terminal siap pakai.
  - ☁️ **MinIO / Object Storage (S3 Enterprise)** - Dukungan cloud lokal bergaya S3 yang terlindungi secara enkripsi.
* **Uji Koneksi (Test Connection):** Alat pengujian otomatis izin tulis (writable) dan ping koneksi sebelum memindahkan mode aktif.

### 5. 🔒 Mode Pemeliharaan & Penguncian Akses SKPD (Lockdown System)
* **Perlindungan Data Saat Update Server:** Saat Admin ingin memperbarui kode, dokumen, atau memulihkan database, Admin dapat mengaktifkan **Lockdown Mode**.
* **Proteksi Multi-Layer Middleware:** Operator SKPD ditahan dan dialihkan secara otomatis ke layar notifikasi khusus (*Under Maintenance*) berpenampilan modern lengkap dengan alasan pengerjaan dan estimasi selesai (WITA), mencegah kesalahan input atau data ganda selama masa perawatan sistem. Admin dan Konsolidator tetap dapat beraktivitas secara penuh di dalam sistem!

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

### 2. Prosedur Melakukan Pembaruan (Update System & Maintenance):
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
1. **Admin Pusat (BKAD Tapin):** Kontrol penuh master data, manajemen storage NAS, audit log, buka/tutup registrasi operator, backup/restore DB, dan mode maintenance pengaman.
2. **Konsolidator:** Melakukan validasi rekon, memantau EWS dan Leaderboard, melihat rekapitulasi, mengekspor laporan eksekutif PDF & ZIP Paket Audit BPK.
3. **Operator SKPD:** Mengelola input saldo rekon tahun berjalan dan mencetak Berita Acara ber-QR Code untuk pengesahan pimpinan instansi.

---
*SiReKa - Solusi Digitalisasi Transparan & Akuntabel untuk Pengelolaan Keuangan Pemerintah Kabupaten Tapin.*
