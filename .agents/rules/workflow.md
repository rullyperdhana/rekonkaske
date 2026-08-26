# Development Workflow Rules

Setiap kali Anda (AI) selesai mengerjakan tugas atau mengimplementasikan fitur baru untuk proyek ini, Anda **WAJIB SECARA OTOMATIS** melakukan rutinitas berikut:

1. **Update Dokumentasi & Versi (`README.md`)**:
   - Catat rincian perubahan (changelog) ke dalam file `README.md` dengan menaikkan angka rilis versinya (misalnya dari v2.1.1 menjadi v2.1.2).

2. **Sinkronisasi Indikator Versi UI**:
   - Setelah memperbarui versi di `README.md`, Anda wajib mencari dan memperbarui teks indikator versi yang ada di dalam aplikasi.
   - Lokasi indikator versi saat ini berada di: `resources/views/layouts/sidebar.blade.php` (di bagian paling bawah).

3. **Tawarkan Push ke GitHub**:
   - Setelah tugas selesai dan dokumentasi diperbarui, Anda **wajib secara proaktif bertanya** kepada pengguna: *"Apakah pembaruan ini perlu saya upload (push) ke GitHub sekarang?"*
   - Jangan melakukan *git push* tanpa persetujuan pengguna (kecuali diinstruksikan sejak awal), namun selalu tawarkan bantuan ini di akhir kalimat Anda.
