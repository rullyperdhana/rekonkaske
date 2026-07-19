# SiReKe (Sistem Rekonsiliasi Kas)

SiReKe adalah platform terpadu untuk rekonsiliasi kas Bendahara Pengeluaran tingkat Satuan Kerja Perangkat Daerah (SKPD) di Kabupaten Tapin. Aplikasi ini menyediakan tingkat presisi dan keamanan tinggi dalam pengelolaan data keuangan daerah, serta mempermudah pencetakan Berita Acara Rekonsiliasi bulanan.

## Fitur Utama
- **Dashboard & Landing Page:** Menampilkan rekapitulasi status rekonsiliasi seluruh SKPD secara *real-time*.
- **Manajemen Transaksi Rekon:** Input data saldo, mutasi kas, dan rekon pajak dengan mudah. Terintegrasi dengan bukti setor/lampiran.
- **Cetak Berita Acara (BA):** Fitur cetak BA ke format PDF standar ukuran kertas F4 (Folio) secara otomatis.
- **Master Data Lengkap:** Pengaturan SKPD, Rekening Koran, Tahun Anggaran, hingga Pengaturan Instansi (Kop Surat & Logo).
- **Keamanan Lanjut:** Terlindungi oleh sistem Rate Limiting, Audit Trail (Catatan Log Aktivitas IP & User Agent), Auto-Logout, Captcha, dan Kebijakan Password Kuat.

## Persyaratan Sistem (Server Production)
- PHP >= 8.2
- MySQL / MariaDB
- Composer
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/Imagick, DOM.

## Instalasi & Deployment
1. Klon repositori ke server:
   ```bash
   git clone https://github.com/rullyperdhana/rekonkaske.git sireke
   cd sireke
   ```
2. Instal dependensi:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
3. Konfigurasi Environment:
   ```bash
   cp .env.example .env
   # Edit file .env sesuaikan dengan database Anda
   # Pastikan APP_ENV=production dan APP_DEBUG=false
   ```
4. Setup Aplikasi:
   ```bash
   php artisan key:generate
   php artisan storage:link
   php artisan migrate --force
   ```
5. Optimasi Kinerja (Wajib di Production):
   ```bash
   php artisan optimize
   php artisan view:cache
   ```

## Pemeliharaan dan Update
Untuk mendorong (push) perubahan terbaru dari komputer lokal ke GitHub, Anda dapat menggunakan script utilitas `push.sh`:
```bash
./push.sh
```

---
*Dikembangkan untuk BKAD Kabupaten Tapin.*
