# Versioning Rules

Setiap kali Anda (AI) memperbarui versi aplikasi dan menambahkannya ke log perubahan di `README.md` (misalnya menjadi v2.1.2), **ANDA WAJIB SECARA OTOMATIS** mencari dan memperbarui teks indikator versi yang ada di dalam aplikasi (di antarmuka pengguna).

Lokasi indikator versi saat ini berada di:
- `resources/views/layouts/sidebar.blade.php` (di bagian paling bawah).

Pembaruan `README.md` dan indikator versi di UI harus selalu berjalan beriringan dalam satu *commit*.
