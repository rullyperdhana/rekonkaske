<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Composer\Semver\Semver;

class DokumentasiController extends Controller
{
    /**
     * Tampilkan halaman dokumentasi khusus administrator.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak: Menu Dokumentasi & Log Pembaruan hanya diperuntukkan bagi Administrator BKAD.');
        }

        // 1. Data Riwayat Pembaruan (Changelog)
        $changelogs = [
            [
                'version' => 'v2.7.7',
                'date' => '24 Oktober 2026',
                'badge' => 'VERSI AKTIF',
                'badge_color' => 'rose',
                'title' => 'Pembatasan Periode Bulan Pelaporan & Cutoff Tutup Kas Desember (Modul Transaksi)',
                'summary' => 'Penerapan aturan baru pemilihan periode bulan pada modul transaksi (/transaksi), di mana hanya bulan pelaporan yang aktif yang dapat dipilih dan bulan yang belum tiba tidak dapat dipilih, serta aturan khusus bulan Desember yang baru terbuka di atas tanggal 20.',
                'items' => [
                    [
                        'type' => 'business_rule',
                        'label' => 'Aturan Bisnis',
                        'desc' => 'Pembatasan Bulan Pelaporan: Pilihan periode bulan pada form input dan edit transaksi hanya menampilkan bulan yang sudah memasuki periode pelaporan. Bulan yang belum tiba tidak dapat dipilih.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Cutoff Desember',
                        'desc' => 'Aturan Khusus Tutup Buku Kas: Bulan Desember hanya dapat dipilih dan diinput apabila waktu server telah melewati tanggal 20 Desember (WITA), mencegah input dini sebelum penutupan anggaran selesai.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Multi-layer Guard',
                        'desc' => 'Validasi Dinamis Front-end & Back-end: Sinkronisasi instan dropdown periode bulan saat tahun diubah, filter cerdas pada tabel data transaksi, serta validasi backend FormRequest.'
                    ]
                ]
            ],
            [
                'version' => 'v2.7.6',
                'date' => '24 Oktober 2026',
                'badge' => 'STABLE',
                'badge_color' => 'slate',
                'title' => 'Penyempurnaan Live CVE Scanner (Semver Matching & Zero False-Positive Engine)',
                'summary' => 'Peningkatan akurasi Live CVE Scanner dengan integrasi Composer Semver matching untuk mengeliminasi alarm palsu pada pemindaian Packagist Security Advisories API serta penambahan kartu visual rincian advisory.',
                'items' => [
                    [
                        'type' => 'fix',
                        'label' => 'Akurasi Scan',
                        'desc' => 'Zero False-Positive Engine: Mengintegrasikan Composer\Semver\Semver::satisfies() dan fallback parser version_compare untuk memvalidasi versi terpasang terhadap constraint affectedVersions Packagist API secara presisi.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Visual Advisory',
                        'desc' => 'Kartu Rincian Kerentanan Interaktif: Menampilkan rincian paket terdampak, ID CVE, batasan versi rentan, dan tautan referensi resmi GitHub/Packagist apabila ditemukan celah keamanan.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Audit Keamanan',
                        'desc' => 'Verifikasi Integritas Dependensi: Seluruh 130 paket dependensi vendor terkonfirmasi bersih (0 CVE) dan sinkron dengan standar Composer CLI Audit.'
                    ]
                ]
            ],
            [
                'version' => 'v2.7.5',
                'date' => '24 Oktober 2026',
                'badge' => 'STABLE',
                'badge_color' => 'slate',
                'title' => 'Radar Keamanan Framework Laravel & Live CVE Vulnerability Scanner',
                'summary' => 'Penyediaan modul pemantauan versi Laravel aktif (v13.33.0), deteksi dini potensi celah keamanan (Live CVE Scan), radar pengawasan advisory global, SOP cepat tanggap patching keamanan, serta solusi diagnostik CLI PHP 8.4 VPS aaPanel.',
                'items' => [
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Radar Keamanan Framework: Menampilkan versi Laravel terpasang secara transparan pada header dan metric bar (/pengaturan/dokumentasi) beserta status pemeliharaan keamanannya.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Live CVE Scanner: Tombol 1-klik untuk memindai database kerentanan keamanan internasional (Composer Audit) langsung dari antarmuka web administrator.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'SOP Hotfix',
                        'desc' => 'Playbook Penambalan Keamanan: Panduan standar 4-langkah mitigasi celah keamanan (deteksi, update lokal, testing, dan deployment pull.sh).'
                    ],
                    [
                        'type' => 'fix',
                        'label' => 'Diagnostik CLI',
                        'desc' => 'Kotak Solusi Terminal VPS aaPanel: Panduan teknis mengatasi error platform PHP CLI 8.3 vs 8.4 saat menjalankan php artisan --version.'
                    ]
                ]
            ],
            [
                'version' => 'v2.7.4',
                'date' => '11 Oktober 2026',
                'badge' => 'STABLE',
                'badge_color' => 'slate',
                'title' => 'Pusat Informasi & Dokumentasi Update Aplikasi serta SOP Deployment VPS Terpadu',
                'summary' => 'Penyediaan portal informasi pembaruan sistem khusus Administrator BKAD yang menyajikan log pembaruan interaktif, matriks hak akses 4 peran (termasuk Auditor), dan panduan komprehensif prosedur update server VPS aaPanel dengan 1-klik salin perintah.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Portal Hub Update Aplikasi (/pengaturan/dokumentasi & /pengaturan/update-aplikasi) berdesain modern Material Design 3 dengan 4 tab navigasi terpadu: Panduan SOP Update VPS, Log Pembaruan (Changelog), Matriks Hak Akses Peran, dan Katalog Arsitektur Sistem.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Deployment SOP',
                        'desc' => 'Panduan Interaktif Deployment Server VPS: Salin cepat 1-klik perintah eksekusi otomatis (cd /www/wwwroot/sireke.cloud && bash pull.sh), panduan CLI manual 8 langkah, prosedur darurat rollback commit, dan checklist pra/pasca-deployment.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Hak Akses',
                        'desc' => 'Matriks Hak Akses 4 Peran Pengguna: Perbandingan transparan wewenang antara Admin, Konsolidator, Operator SKPD, dan Auditor Inspektorat/BPK untuk 10 pilar modul utama.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Runtime & Git',
                        'desc' => 'Detektor Runtime & Git Status: Integrasi otomatis hash commit git aktif, branch rilis, status pemeliharaan server, dan spesifikasi lingkungan server hosting/VPS.'
                    ]
                ]
            ],
            [
                'version' => 'v2.7.3',
                'date' => '11 Oktober 2026',
                'badge' => 'STABLE',
                'badge_color' => 'slate',
                'title' => 'Grup Peran Baru: Auditor (Inspektorat / BPK) & Read-Only Audit Privileges',
                'summary' => 'Penambahan peran pengguna khusus Auditor pada modul Manajemen Pengguna (/pengaturan/user), dilengkapi hak akses pengawasan penuh (read-only) terhadap seluruh data rekonsiliasi, analitik eksekutif, serta brankas berkas digital BPK tanpa izin mutasi data.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Grup & Peran Auditor: Penambahan peran "Auditor (Inspektorat Daerah / BPK)" pada form Tambah & Edit Pengguna, filter pencarian peran, serta lencana status khusus di tabel pengguna.'
                    ],
                    [
                        'type' => 'feature',
                        'label' => 'Hak Akses',
                        'desc' => 'Read-Only Audit Privileges: Akun Auditor diberikan akses melihat seluruh transaksi kas SKPD se-Kabupaten Tapin, Analitik Eksekutif KDH/Sekda, Laporan Verifikasi, Rekapitulasi Tahunan, Konsolidasi Daerah, Ringkasan Selisih Kas, Tunggakan, serta Brankas Dokumen Tree.'
                    ],
                    [
                        'type' => 'feature',
                        'label' => 'Arsip & Audit',
                        'desc' => 'Akses Unduh Massal ZIP Audit BPK: Auditor memiliki izin mengunduh paket arsip digital rekonsiliasi kas per transaksi maupun paket ZIP massal skala kabupaten.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Strict Data Integrity Lockdown: Perlindungan controller berlapis yang memblokir akun Auditor dari membuat, mengedit, menghapus, atau mengunggah bukti transaksi, memverifikasi laporan, serta mengubah pengaturan instansi.'
                    ]
                ]
            ],
            [
                'version' => 'v2.7.1',
                'date' => '24 September 2026',
                'badge' => 'STABLE',
                'badge_color' => 'slate',
                'title' => 'Security Hardening: Patch CVE Dependensi, Proteksi Path Traversal & Anti-IDOR',
                'summary' => 'Peningkatan keamanan komprehensif: pembaruan dependensi (0 CVE advisories di composer audit), eliminasi potensi celah Path Traversal pada storage stream, serta pengetatan otorisasi transaksi anti-IDOR.',
                'items' => [
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Patch CVE-2026-84374 pada maatwebsite/excel (diperbarui ke v3.1.70) dan patch DoS CVE pada league/commonmark (v2.10.3). Status composer audit bersih 100%.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Proteksi Path Traversal pada /storage-stream/{path}: Memblokir seluruh pola traversal (../), null byte, berkas sistem (.env, storage_nas_config.json), serta membatasi akses berkas rahasia hanya untuk user terotentikasi.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Proteksi Anti-IDOR pada Transaksi: Memastikan operator SKPD hanya berwenang menyimpan, mengedit, memperbarui, atau menghapus transaksi milik SKPD-nya sendiri.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Validasi Berkas Berlapis (Defense-in-Depth): Penambahan aturan ekstensi eksplisit (extensions:pdf,jpg,jpeg,png) di samping MIME-type magic byte check.'
                    ]
                ]
            ],
            [
                'version' => 'v2.7.0',
                'date' => '24 September 2026',
                'badge' => 'STABLE',
                'badge_color' => 'indigo',
                'title' => 'Dashboard Analitik Eksekutif / KDH & Sekda View (Executive Command Center)',
                'summary' => 'Pusat komando eksekutif satu layar untuk Bupati, Wakil Bupati, dan Sekda yang menyajikan 4 KPI fiskal makro, sistem rekomendasi kebijakan cerdas (DSS), visualisasi tren likuiditas kas 12 bulan, matriks status instansi, dan lembar brief eksekutif 1 halaman.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Dashboard Command Center Eksekutif (/eksekutif) dengan mode tampilan layar penuh (Kiosk / Smart TV BKAD) dan auto-refresh 60 detik beranimasi countdown.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => '4 KPI Fiskal Makro: Likuiditas Kas Pemda di Bank Kalsel, Indeks Kepatuhan Rekonsiliasi Pemda (%), Akumulasi Selisih Kas, dan Ketepatan Waktu Pelaporan (<= tgl 10).'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Decision Support System (DSS): Kartu rekomendasi kebijakan cerdas yang otomatis menganalisis anomali selisih kas, SKPD yang butuh pendampingan audit, dan idle cash.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Grafik Tren Likuiditas 12 Bulan (BKU vs Bank Kalsel), Donut Status Rekon, serta Leaderboard Early Warning System (Top 5 Tertib vs Bottom 5 Butuh Perhatian).'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Matriks Visual Status SKPD interaktif dengan pencarian langsung (live search), filter chip status, dan tombol WhatsApp instan ke Bendahara SKPD.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Cetak Lembar Briefing Eksekutif 1 Halaman (/eksekutif/cetak-brief) ber-KOP resmi Pemkab Tapin untuk bahan rapat pimpinan KDH & Sekda.'
                    ]
                ]
            ],
            [
                'version' => 'v2.6.0',
                'date' => '23 September 2026',
                'badge' => 'STABLE',
                'badge_color' => 'emerald',
                'title' => 'Integrasi Notifikasi Telegram Otomatis & Pusat Dokumentasi Administrator',
                'summary' => 'Penambahan modul integrasi Bot Telegram untuk siaran instan ke ponsel admin saat SKPD Posting Final, serta pusat dokumentasi internal & changelog sistem.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Modul Konfigurasi Telegram (/pengaturan/telegram) dengan fitur simpan token bot, target chat/grup ID, switch aktivasi otomatis, dan alat uji coba koneksi instan.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Auto-Broadcast Telegram saat SKPD melakukan Posting Final / Diverifikasi SKPD dengan ringkasan status kas, selisih saldo, kelengkapan berkas, operator, dan waktu WITA.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Modul Dokumentasi Administrator (/pengaturan/dokumentasi) interaktif dengan tab Changelog versi, katalog seluruh modul sistem, dan spesifikasi arsitektur teknis.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Dukungan multi-chat ID pada pengiriman Telegram (bisa kirim ke chat admin pribadi dan grup koordinasi BKAD sekaligus dengan pemisah koma).'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Mekanisme proteksi fail-safe pada service Telegram sehingga jika jaringan API Telegram mengalami kendala, penyimpanan transaksi SKPD tidak terganggu sama sekali.'
                    ]
                ]
            ],
            [
                'version' => 'v2.5.2',
                'date' => '20 September 2026',
                'badge' => 'STABLE',
                'badge_color' => 'blue',
                'title' => 'Dukungan Penuh Presisi Data Sen (2 Desimal) & Anti-Floating Point Noise',
                'summary' => 'Peningkatan kalkulasi desimal keuangan, penanganan input masking koma/titik tanpa terhapus otomatis, serta eliminasi dead-zone pada selisih kas.',
                'items' => [
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Input Masking JS Cerdas: Operator dapat mengetik koma atau titik numpad secara leluasa dengan format sen tanpa terpotong.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Kalkulasi presisi Math.round((saldo) * 100) / 100 bebas noise floating-point JavaScript.'
                    ],
                    [
                        'type' => 'fix',
                        'label' => 'Perbaikan Bug',
                        'desc' => 'Eliminasi false-positive selisih pada validasi FormRequest dan sinkronisasi ambang batas selisih Rp 0,01 pada dashboard eksekutif.'
                    ]
                ]
            ],
            [
                'version' => 'v2.5.1',
                'date' => '15 September 2026',
                'badge' => 'UI/UX',
                'badge_color' => 'purple',
                'title' => 'Animasi Bioluminescent Fireflies Interaktif & Desain Modern Login Page',
                'summary' => 'Sentuhan visual premium pada portal login dengan partikel cahaya kunang-kunang 60 FPS, topbar glassmorphism, dan jam digital WITA real-time.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Canvas animasi HTML5 Bioluminescent Fireflies yang meliuk dinamis dan menghindari kursor mouse.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Bilah navigasi glassmorphism dengan jam digital real-time WITA dan kartu login berbasis Plus Jakarta Sans.'
                    ]
                ]
            ],
            [
                'version' => 'v2.5.0',
                'date' => '05 September 2026',
                'badge' => 'SECURITY',
                'badge_color' => 'rose',
                'title' => 'Submenu Bento Profil, Dukungan NIP Pegawai & Penguncian Pemeriksaan Konsolidator',
                'summary' => 'Penambahan data NIP pada dokumen pengesahan, layout profil pengguna bento grid, serta proteksi anti-tampering saat rekonsiliasi telah berstatus VALID.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Dukungan kolom NIP pada database user, form profil, serta pencetakan otomatis di Surat Tanda Bukti Digital dan stempel BA.'
                    ],
                    [
                        'type' => 'security',
                        'label' => 'Keamanan',
                        'desc' => 'Formulir pemeriksaan konsolidator otomatis terkunci (read-only) saat status transaksi VALID, dilengkapi wewenang buka kunci khusus Admin.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Desain ulang halaman profil pengguna (/profile) dengan tata letak Bento Grid Material Design 3.'
                    ]
                ]
            ],
            [
                'version' => 'v2.4.0',
                'date' => '28 Agustus 2026',
                'badge' => 'FEATURE',
                'badge_color' => 'amber',
                'title' => 'Laporan Register Konsolidator, Slip Tanda Bukti Digital & Stempel BA Otomatis',
                'summary' => 'Modul register pengujian kas tingkat SKPD, penerbitan dokumen resmi Tanda Bukti Digital PDF 1 lembar, dan stempel digital pengesahan.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Modul Laporan Verifikasi Konsolidator (/laporan/verifikasi-konsolidator) dengan cetak register PDF Landscape dan ekspor Excel.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Surat Tanda Bukti Pemeriksaan Rekonsiliasi Kas Daerah (PDF A4) ber-KOP resmi BKAD dengan Nomor Register Digital dan QR Code verifikasi.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Stempel digital pengesahan otomatis pada Berita Acara PDF saat transaksi berstatus VALID Konsolidator.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Saklar kontrol izin unduh slip tanda bukti digital bagi SKPD di Pengaturan Instansi.'
                    ]
                ]
            ],
            [
                'version' => 'v2.3.0',
                'date' => '15 Agustus 2026',
                'badge' => 'WORKFLOW',
                'badge_color' => 'cyan',
                'title' => 'Meja Kerja Antrean Verifikasi Terpusat & Mode Pemeriksaan Cepat (Save & Next)',
                'summary' => 'Alur kerja pemeriksaan satu pintu bagi konsolidator dengan 4 tab klasifikasi berkas dan fitur navigasi cepat antar-SKPD.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Halaman Antrean Verifikasi (/transaksi/antrean) dengan tab Menunggu Cek, Perlu Perbaikan, Butuh Reset, dan Valid.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Mode Pemeriksaan Cepat (Save & Next) yang membawa pemeriksa langsung ke berkas berikutnya secara otomatis.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Badge counter antrean berdenyut pada menu Sidebar untuk memberitahukan berkas yang menunggu verifikasi.'
                    ]
                ]
            ],
            [
                'version' => 'v2.2.0',
                'date' => '05 Agustus 2026',
                'badge' => 'CORE',
                'badge_color' => 'indigo',
                'title' => 'Pemeriksaan Konsolidator, Multi-Round Revision Timeline & Reset Draft Admin',
                'summary' => 'Lembar pemeriksaan terstruktur, riwayat catatan koreksi bertingkat, dan wewenang reset status oleh Admin Pusat.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Modul pemeriksaan berkas dan data rekonsiliasi khusus bagi Konsolidator BKAD.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Tabel transaksi_catatans yang mencatat kronologi evaluasi dan putaran revisi tanpa menimpa catatan lama.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Wewenang 1-klik bagi Admin Pusat untuk mereset transaksi kembali ke Draft (Reset to Draft).'
                    ]
                ]
            ],
            [
                'version' => 'v2.1.0',
                'date' => '25 Juli 2026',
                'badge' => 'MOBILE',
                'badge_color' => 'teal',
                'title' => 'Progressive Web App (PWA) Mobile & NProgress Loading Bar',
                'summary' => 'Optimasi penuh untuk layar smartphone dan tablet, bottom navigation bar ergonomis, dan transisi layar halus.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Dukungan Progressive Web App (PWA) untuk instalasi aplikasi langsung ke layar utama Android & iOS.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Bottom Navigation Bar modern dengan Active Route Detector untuk navigasi cepat di perangkat seluler.'
                    ],
                    [
                        'type' => 'improvement',
                        'label' => 'Peningkatan',
                        'desc' => 'Integrasi NProgress Loading Bar pada setiap perpindahan halaman dan pengiriman form.'
                    ]
                ]
            ],
            [
                'version' => 'v2.0.0',
                'date' => '10 Juli 2026',
                'badge' => 'MILESTONE',
                'badge_color' => 'sky',
                'title' => 'SiReKa Enterprise Architecture: Brankas ZIP Massal, NAS Sync & Early Warning System',
                'summary' => 'Pondasi arsitektur enterprise dengan EWS Rapor Merah, ekspor arsip BPK dalam satu klik ZIP, dan integrasi storage NAS.',
                'items' => [
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Early Warning System (EWS) dan Timeliness Scoring Algorithm untuk memeringkat kedisiplinan pelaporan SKPD.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Ekspor Massal ZIP Paket Audit BPK yang otomatis menata folder per Kode SKPD dan bulan rekonsiliasi.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Manajemen Storage Dinamis pendukung Network Attached Storage (NAS NFS) dan MinIO S3 Object Storage.'
                    ],
                    [
                        'type' => 'new',
                        'label' => 'Fitur Baru',
                        'desc' => 'Mode Lockdown Pemeliharaan Server beranimasi roda gigi sinematik dan live WITA clock.'
                    ]
                ]
            ]
        ];

        // 2. Katalog Modul & Fitur Unggulan SiReKa
        $modulKatalog = [
            [
                'id' => 'command_center',
                'nama' => 'Executive Command Center (KDH & Sekda View)',
                'icon' => 'analytics',
                'color' => 'indigo',
                'roles' => ['Admin', 'Konsolidator'],
                'url' => route('eksekutif.index'),
                'url_label' => 'Buka Command Center',
                'deskripsi' => 'Pusat komando eksekutif satu layar untuk pimpinan daerah (KDH & Sekda) dengan 4 KPI makro kas pemda, grafik tren likuiditas 12 bulan, DSS rekomendasi kebijakan cerdas, matriks instansi interaktif, dan lembar briefing 1 halaman siap cetak.',
                'fitur_kunci' => [
                    'Mode TV Kiosk Display dengan auto-refresh 60 detik & live WITA clock',
                    'Kalkulasi Likuiditas Kas Pemda (Bank Kalsel) & Indeks Kepatuhan Fiskal',
                    'Decision Support System (DSS) rekomendasi cerdas pimpinan daerah',
                    'Cetak Lembar Briefing Eksekutif 1 Lembar siap tanda tangan KDH/Sekda'
                ]
            ],
            [
                'id' => 'telegram',
                'nama' => 'Notifikasi Telegram Otomatis',
                'icon' => 'send',
                'color' => 'sky',
                'roles' => ['Admin', 'Konsolidator'],
                'url' => route('pengaturan.telegram.index'),
                'url_label' => 'Buka Pengaturan Telegram',
                'deskripsi' => 'Pengiriman siaran instan ke ponsel admin atau grup BKAD saat SKPD melakukan Posting Final / Diverifikasi SKPD tanpa perlu membuka aplikasi terlebih dahulu.',
                'fitur_kunci' => [
                    'Otomatis terpicu saat status verifikasi menjadi verified',
                    'Dukungan multi-chat ID (Pribadi & Grup)',
                    'Ringkasan data: Saldo BKU, Bank, Status Selisih, Operator, dan Waktu WITA',
                    'Alat uji coba koneksi instan dari browser'
                ]
            ],
            [
                'id' => 'antrean',
                'nama' => 'Antrean Verifikasi & Cek Cepat',
                'icon' => 'fact_check',
                'color' => 'blue',
                'roles' => ['Admin', 'Konsolidator'],
                'url' => route('transaksi.antrean'),
                'url_label' => 'Buka Antrean Verifikasi',
                'deskripsi' => 'Pusat meja kerja verifikasi konsolidator yang mengelompokkan laporan ke dalam 4 tab interaktif dengan tombol "Simpan & Lanjut ke BA Berikutnya".',
                'fitur_kunci' => [
                    '4 Kartu metrik real-time status antrean tahun berjalan',
                    'Mode Pemeriksaan Cepat (Save & Next)',
                    'Visualisasi status 4 berkas fisik dan status selisih saldo',
                    'Navigasi riwayat evaluasi bertingkat (Multi-Round Revision)'
                ]
            ],
            [
                'id' => 'laporan_konsolidator',
                'nama' => 'Laporan Register & Slip Digital QR Code',
                'icon' => 'verified_user',
                'color' => 'emerald',
                'roles' => ['Admin', 'Konsolidator'],
                'url' => route('laporan.verifikasi-konsolidator'),
                'url_label' => 'Buka Laporan Register',
                'deskripsi' => 'Rekapitulasi resmi register verifikasi kas tingkat SKPD dilengkapi Surat Tanda Bukti Digital 1 lembar ber-KOP resmi dan QR Code validasi keaslian.',
                'fitur_kunci' => [
                    'Penerbitan Surat Tanda Bukti Pemeriksaan Rekonsiliasi (PDF A4)',
                    'Nomor register unik format REG-KONS/TAPIN/YYYY/MM/ID',
                    'Pencetakan NIP dan stempel digital pengesahan otomatis',
                    'Ekspor register ke Excel dan PDF Landscape'
                ]
            ],
            [
                'id' => 'brankas_zip',
                'nama' => 'Brankas Dokumen & Ekspor ZIP Massal',
                'icon' => 'folder_zip',
                'color' => 'indigo',
                'roles' => ['Admin', 'Konsolidator'],
                'url' => route('dokumen.tree'),
                'url_label' => 'Buka Arsip Dokumen',
                'deskripsi' => 'Penyusunan berkas bukti dukung (BA, BKU, Bank, Koran) dalam struktur hirarki pohon kode instansi dengan fitur kompresi ZIP 1-klik untuk auditor BPK.',
                'fitur_kunci' => [
                    'Pengurutan hierarkis berdasarkan Kode SKPD resmi',
                    'In-browser PDF preview tanpa perlu download berulang kali',
                    'Ekspor massal 1-klik terstruktur ke subfolder SKPD dan bulan',
                    'Saklar proteksi izin re-upload dokumen anti-manipulasi'
                ]
            ],
            [
                'id' => 'analytics_ews',
                'nama' => 'Executive Analytics & Early Warning System',
                'icon' => 'leaderboard',
                'color' => 'purple',
                'roles' => ['Semua Pengguna'],
                'url' => route('dashboard'),
                'url_label' => 'Buka Dashboard',
                'deskripsi' => 'Dasbor analitik cerdas yang menyajikan tingkat kepatuhan SKPD, Timeliness Scoring, peringatan dini (EWS) laporan terlambat, dan cetak rapor eksekutif.',
                'fitur_kunci' => [
                    'Algoritma pembobotan hari ketepatan waktu pengiriman',
                    'Peringatan dini (Rapor Merah) SKPD terlambat / selisih kas',
                    'Cetak Rapor Kepatuhan Eksekutif ber-Grade A s.d. D',
                    'Statistik visual kepatuhan per bulan dan tahun anggaran'
                ]
            ],
            [
                'id' => 'wa_broadcast',
                'nama' => 'WhatsApp Broadcast Generator',
                'icon' => 'chat',
                'color' => 'teal',
                'roles' => ['Admin', 'Konsolidator'],
                'url' => route('laporan.rekap-wa'),
                'url_label' => 'Buka Broadcast WA',
                'deskripsi' => 'Penyusun pesan rekapitulasi progres rekonsiliasi yang siap disalin ke grup WhatsApp pimpinan dengan filter kelengkapan 4 berkas pendukung.',
                'fitur_kunci' => [
                    'Format pesan siap salin dengan emotikon informatif',
                    'Penyaringan berdasarkan kelengkapan berkas fisik',
                    'Daftar SKPD yang sudah vs yang belum rekonsiliasi',
                    'Sinkronisasi 100% dengan Laporan Tunggakan Kas'
                ]
            ],
            [
                'id' => 'storage_nas',
                'nama' => 'Manajemen Storage Dinamis & NAS Sync',
                'icon' => 'dns',
                'color' => 'amber',
                'roles' => ['Admin'],
                'url' => route('pengaturan.storage.index'),
                'url_label' => 'Buka Manajemen Storage',
                'deskripsi' => 'Panel kontrol infrastruktur berkas yang mendukung peralihan fleksibel antara Disk Lokal, Network Attached Storage (NAS), dan MinIO Object Storage (S3).',
                'fitur_kunci' => [
                    'Real-time disk gauge pemakaian server fisik',
                    'Uji koneksi (writable & ping test) otomatis',
                    'Mekanisme Auto-Heal & Auto-Fallback pembacaan ganda',
                    'Alat migrasi massal web & artisan command sireka:sync-storage'
                ]
            ],
            [
                'id' => 'maintenance',
                'nama' => 'Mode Lockdown & Maintenance Sistem',
                'icon' => 'lock',
                'color' => 'rose',
                'roles' => ['Admin'],
                'url' => route('pengaturan.maintenance.index'),
                'url_label' => 'Buka Maintenance Sistem',
                'deskripsi' => 'Proteksi keamanan saat pembaruan kode atau perbaikan server dengan mengunci akses operator SKPD ke layar pemeliharaan animasi roda gigi sinematik.',
                'fitur_kunci' => [
                    'Penguncian multi-layer middleware bagi role operator',
                    'Layar animasi dual rotating gears dan live WITA clock',
                    'Admin dan konsolidator tetap memiliki akses penuh',
                    'Utilitas backup dan restore database sekali klik'
                ]
            ],
            [
                'id' => 'audit_trail',
                'nama' => 'Jejak Audit & Keamanan Forensik',
                'icon' => 'history',
                'color' => 'slate',
                'roles' => ['Admin'],
                'url' => route('log.index'),
                'url_label' => 'Buka Jejak Audit',
                'deskripsi' => 'Pencatatan komprehensif setiap aktivitas transaksi, login, perubahan dokumen, dan penimpaan file lengkap dengan alamat IP dan user agent.',
                'fitur_kunci' => [
                    'Pelacakan aktivitas Spatie Laravel Activitylog v5',
                    'Riwayat penimpaan dan penghapusan dokumen bukti',
                    'Pencatatan login sukses dan gagal untuk audit BPK',
                    'Fitur reset status transaksi ke draft dengan audit trail'
                ]
            ]
        ];

        // 3. Informasi Git & Runtime Environment
        $gitInfo = [
            'branch' => 'main',
            'commit' => 'HEAD',
            'date' => '11 Okt 2026',
            'message' => 'Pembaruan rilis SiReKa',
            'repo_url' => 'https://github.com/rullyperdhana/rekonkaske',
        ];

        try {
            if (function_exists('shell_exec')) {
                $b = trim((string) @shell_exec('git rev-parse --abbrev-ref HEAD 2>/dev/null'));
                if (!empty($b)) $gitInfo['branch'] = $b;

                $c = trim((string) @shell_exec('git log -1 --format="%h" 2>/dev/null'));
                if (!empty($c)) $gitInfo['commit'] = $c;

                $d = trim((string) @shell_exec('git log -1 --format="%cd" --date=format:"%d %b %Y %H:%M" 2>/dev/null'));
                if (!empty($d)) $gitInfo['date'] = $d;

                $m = trim((string) @shell_exec('git log -1 --format="%s" 2>/dev/null'));
                if (!empty($m)) $gitInfo['message'] = $m;
            }
        } catch (\Throwable $e) {
            // graceful fallback
        }

        // 4. Prosedur & SOP Update VPS Production (aaPanel)
        $vpsDeploymentInfo = [
            'vps_path' => '/www/wwwroot/sireke.cloud',
            'quick_cmd' => 'cd /www/wwwroot/sireke.cloud && bash pull.sh',
            'php_bin' => '/www/server/php/84/bin/php',
            'composer_bin' => '/usr/bin/composer',
            'web_user' => 'www:www',
            'github_repo' => 'https://github.com/rullyperdhana/rekonkaske.git',
            'branch' => 'main',
            'steps' => [
                [
                    'num' => '1',
                    'title' => 'Arahkan ke Direktori Aplikasi',
                    'desc' => 'Buka Terminal SSH (Putty / Terminal Mac / aaPanel Web Terminal) dan buka folder aplikasi SiReKa di server.',
                    'cmd' => 'cd /www/wwwroot/sireke.cloud',
                ],
                [
                    'num' => '2',
                    'title' => 'Aktifkan Mode Maintenance Sementara',
                    'desc' => 'Mengunci akses pengguna umum sementara agar proses update tidak mengganggu transaksi SKPD yang sedang berlangsung.',
                    'cmd' => '/www/server/php/84/bin/php artisan down --secret="bypass-admin"',
                ],
                [
                    'num' => '3',
                    'title' => 'Tarik Pembaruan Kode dari GitHub',
                    'desc' => 'Menyelaraskan codebase server dengan branch main resmi di GitHub.',
                    'cmd' => 'git fetch --all && git reset --hard origin/main',
                ],
                [
                    'num' => '4',
                    'title' => 'Perbarui Dependensi Composer',
                    'desc' => 'Menginstal dependensi backend secara teroptimasi tanpa pustaka dev untuk efisiensi server produksi.',
                    'cmd' => '/www/server/php/84/bin/php /usr/bin/composer install --optimize-autoloader --no-dev',
                ],
                [
                    'num' => '5',
                    'title' => 'Jalankan Migrasi Database',
                    'desc' => 'Menyesuaikan struktur tabel database terbaru dengan flag eksekusi paksa production (--force).',
                    'cmd' => '/www/server/php/84/bin/php artisan migrate --force',
                ],
                [
                    'num' => '6',
                    'title' => 'Segarkan & Optimalkan Kinerja Cache',
                    'desc' => 'Membersihkan cache view, route, config lama dan menyusun cache terkompilasi berkecepatan tinggi.',
                    'cmd' => '/www/server/php/84/bin/php artisan view:clear && /www/server/php/84/bin/php artisan optimize',
                ],
                [
                    'num' => '7',
                    'title' => 'Amankan Hak Akses Berkas (aaPanel)',
                    'desc' => 'Memastikan permission folder storage dan bootstrap/cache dapat ditulis oleh user www:www web server.',
                    'cmd' => 'chown -R www:www /www/wwwroot/sireke.cloud && chmod -R 775 storage bootstrap/cache',
                ],
                [
                    'num' => '8',
                    'title' => 'Buka Kembali Akses Web (Selesai)',
                    'desc' => 'Menonaktifkan mode pemeliharaan sehingga seluruh SKPD dapat kembali mengakses aplikasi.',
                    'cmd' => '/www/server/php/84/bin/php artisan up',
                ],
            ],
            'rollback_cmds' => [
                'Kembali ke 1 Commit Sebelumnya' => 'git reset --hard HEAD~1 && /www/server/php/84/bin/php artisan optimize:clear',
                'Pantau Log Error Real-time' => 'tail -n 50 -f storage/logs/laravel.log',
                'Cek Status & Integritas Sistem' => '/www/server/php/84/bin/php artisan about',
                'Perbaiki Permission Storage aaPanel' => 'chown -R www:www /www/wwwroot/sireke.cloud && chmod -R 775 storage bootstrap/cache',
            ],
            'preflight_checklist' => [
                'Pastikan backup database terbaru telah diekspor melalui aaPanel Database Backup atau phpMyAdmin.',
                'Pastikan tidak ada SKPD yang sedang dalam proses upload berkas Berita Acara mendesak.',
                'Pastikan commit perubahan di GitHub telah di-review dan berstatus green/lulus build.',
            ],
            'postflight_checklist' => [
                'Buka browser dan muat ulang halaman login SiReKa.',
                'Verifikasi nomor rilis versi pada footer sidebar telah terbarui ke versi target.',
                'Coba buka menu Laporan Konsolidasi & Brankas Dokumen untuk memastikan driver storage berjalan normal.',
                'Jika ada notifikasi Telegram aktif, lakukan tombol uji coba pesan dari menu Pengaturan Telegram.',
            ]
        ];

        // 5. Matriks Hak Akses Peran Pengguna (Access Control Matrix)
        $roleMatrix = [
            [
                'modul' => 'Entri & Edit Transaksi Kas SKPD',
                'kategori' => 'Transaksi',
                'admin' => ['status' => 'full', 'desc' => 'Akses penuh entri, edit, dan hapus transaksi'],
                'konsolidator' => ['status' => 'readonly', 'desc' => 'Hanya memeriksa berkas tanpa hak ubah angka saldo'],
                'operator' => ['status' => 'limited', 'desc' => 'Kelola transaksi SKPD sendiri sebelum verifikasi'],
                'auditor' => ['status' => 'forbidden', 'desc' => 'Dilarang keras (Strict Read-Only Audit)'],
            ],
            [
                'modul' => 'Unggah Bukti Dukung (4 Berkas Fisik)',
                'kategori' => 'Dokumen',
                'admin' => ['status' => 'full', 'desc' => 'Hapus file salah & kontrol izin re-upload'],
                'konsolidator' => ['status' => 'readonly', 'desc' => 'Pratinjau in-browser dokumen untuk verifikasi'],
                'operator' => ['status' => 'limited', 'desc' => 'Unggah berkas bukti SKPD sendiri'],
                'auditor' => ['status' => 'forbidden', 'desc' => 'Dilarang memanipulasi atau mengunggah dokumen'],
            ],
            [
                'modul' => 'Pemeriksaan & Pengesahan Berita Acara',
                'kategori' => 'Verifikasi',
                'admin' => ['status' => 'full', 'desc' => 'Akses verifikasi & reset status transaksi ke draft'],
                'konsolidator' => ['status' => 'full', 'desc' => 'Wewenang utama pengesahan & tanda bukti digital'],
                'operator' => ['status' => 'forbidden', 'desc' => 'Objek yang diperiksa (tidak ada wewenang)'],
                'auditor' => ['status' => 'forbidden', 'desc' => 'Dilarang mengesahkan demi netralitas audit'],
            ],
            [
                'modul' => 'Dashboard Analitik Eksekutif (KDH/Sekda)',
                'kategori' => 'Monitoring',
                'admin' => ['status' => 'full', 'desc' => 'Akses penuh Kiosk Mode, DSS & cetak brief eksekutif'],
                'konsolidator' => ['status' => 'full', 'desc' => 'Akses pemantauan kepatuhan fiskal daerah'],
                'operator' => ['status' => 'forbidden', 'desc' => 'Dibatasi (hanya dashboard operasional SKPD)'],
                'auditor' => ['status' => 'full', 'desc' => 'Akses pengawasan penuh 4 KPI & status seluruh SKPD'],
            ],
            [
                'modul' => 'Brankas Dokumen & Unduh ZIP Massal BPK',
                'kategori' => 'Arsip & Audit',
                'admin' => ['status' => 'full', 'desc' => 'Akses hirarki pohon, preview, & ekspor ZIP massal'],
                'konsolidator' => ['status' => 'full', 'desc' => 'Akses hirarki pohon & ekspor ZIP massal'],
                'operator' => ['status' => 'forbidden', 'desc' => 'Hanya melihat arsip instansi sendiri'],
                'auditor' => ['status' => 'full', 'desc' => 'Akses penuh arsip se-Kabupaten & unduh ZIP audit BPK'],
            ],
            [
                'modul' => 'Broadcast Rekap WhatsApp & Kontak Bendahara',
                'kategori' => 'Komunikasi',
                'admin' => ['status' => 'full', 'desc' => 'Generator broadcast rekap WA & kontak bendahara'],
                'konsolidator' => ['status' => 'full', 'desc' => 'Akses broadcast WA & kontak bendahara SKPD'],
                'operator' => ['status' => 'forbidden', 'desc' => 'Tidak tersedia menu broadcast pimpinan'],
                'auditor' => ['status' => 'forbidden', 'desc' => 'Eksklusi 100% demi independensi audit'],
            ],
            [
                'modul' => 'Notifikasi Telegram Otomatis Posting Final',
                'kategori' => 'Notifikasi',
                'admin' => ['status' => 'full', 'desc' => 'Konfigurasi Bot Token, target chat ID & trigger siaran'],
                'konsolidator' => ['status' => 'readonly', 'desc' => 'Penerima siaran verifikasi berkas SKPD'],
                'operator' => ['status' => 'forbidden', 'desc' => 'Tidak menerima siaran antrean verifikasi'],
                'auditor' => ['status' => 'forbidden', 'desc' => 'Tidak dilibatkan dalam jalur komunikasi operasional'],
            ],
            [
                'modul' => 'Manajemen Pengguna & Pengaturan Peran',
                'kategori' => 'Administrasi',
                'admin' => ['status' => 'full', 'desc' => 'Kelola seluruh user, reset password & tetapkan peran'],
                'konsolidator' => ['status' => 'forbidden', 'desc' => 'Hanya dapat mengubah password sendiri'],
                'operator' => ['status' => 'forbidden', 'desc' => 'Hanya dapat mengubah profil & password sendiri'],
                'auditor' => ['status' => 'forbidden', 'desc' => 'Hanya dapat mengubah profil & password sendiri'],
            ],
            [
                'modul' => 'Mode Lockdown & Maintenance Sistem',
                'kategori' => 'Infrastruktur',
                'admin' => ['status' => 'full', 'desc' => 'Aktivasi/nonaktifkan maintenance & backup database'],
                'konsolidator' => ['status' => 'bypass', 'desc' => 'Bypass lock (tetap dapat login saat maintenance)'],
                'operator' => ['status' => 'locked', 'desc' => 'Terkunci otomatis ke layar pemeliharaan animasi'],
                'auditor' => ['status' => 'locked', 'desc' => 'Terkunci selama masa pemeliharaan sistem'],
            ],
            [
                'modul' => 'Jejak Audit Forensik (Activity Log)',
                'kategori' => 'Keamanan',
                'admin' => ['status' => 'full', 'desc' => 'Akses pemantauan seluruh log aktivitas & alamat IP'],
                'konsolidator' => ['status' => 'forbidden', 'desc' => 'Tidak memiliki hak akses log server'],
                'operator' => ['status' => 'forbidden', 'desc' => 'Tidak memiliki hak akses log server'],
                'auditor' => ['status' => 'forbidden', 'desc' => 'Objek pengawasan internal admin BKAD'],
            ],
        ];

        // 6. Spesifikasi Arsitektur Sistem
        $systemSpecs = [
            'app_name' => config('app.name', 'SiReKa'),
            'app_version' => 'v2.7.7 Enterprise',
            'release_date' => '24 Oktober 2026',
            'release_status' => 'Production Ready (Stable)',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_os' => php_uname('s') . ' ' . php_uname('r'),
            'timezone' => config('app.timezone', 'Asia/Makassar') . ' (WITA)',
            'database_driver' => config('database.default', 'mysql'),
            'storage_driver' => config('filesystems.disks.public.driver', 'local'),
            'cache_driver' => config('cache.default', 'file'),
            'session_driver' => config('session.driver', 'file'),
            'maintenance_mode' => app()->isDownForMaintenance() ? 'Aktif (Maintenance Mode)' : 'Normal (Live Production)',
        ];

        // 7. Informasi Framework Laravel & Radar Keamanan
        $laravelVersion = app()->version();
        $majorVersion = explode('.', $laravelVersion)[0] ?? '13';

        $laravelInfo = [
            'version' => $laravelVersion,
            'series' => 'Laravel ' . $majorVersion . '.x',
            'status' => 'Active Support & Security Maintenance',
            'badge_color' => 'emerald',
            'php_runtime' => PHP_VERSION,
            'php_cli_default' => PHP_BINARY,
            'php_min_req' => '^8.3',
            'vps_php_bin' => '/www/server/php/84/bin/php',
            'vps_cli_check_cmd' => '/www/server/php/84/bin/php artisan --version',
            'vps_symlink_cmd' => 'ln -sf /www/server/php/84/bin/php /usr/bin/php',
            'composer_audit_cmd' => '/www/server/php/84/bin/php /usr/bin/composer audit',
            'local_update_cmd' => 'composer update laravel/framework --with-dependencies',
            'vps_pull_cmd' => 'cd /www/wwwroot/sireke.cloud && bash pull.sh',
            'advisory_url' => 'https://github.com/laravel/framework/security/advisories',
            'release_notes_url' => 'https://github.com/laravel/framework/releases',
            'packagist_url' => 'https://packagist.org/packages/laravel/framework',
            'cve_tracker_url' => 'https://cve.mitre.org/cgi-bin/cvekey.cgi?keyword=laravel',
        ];

        return view('pengaturan.dokumentasi.index', compact(
            'changelogs',
            'modulKatalog',
            'systemSpecs',
            'vpsDeploymentInfo',
            'roleMatrix',
            'gitInfo',
            'laravelInfo'
        ));
    }

    /**
     * Jalankan scan audit keamanan Composer / Framework secara live.
     */
    public function auditSecurity(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Akses ditolak: Hanya Administrator BKAD yang berwenang menjalankan audit keamanan sistem.'
            ], 403);
        }

        $frameworkVersion = app()->version();
        $phpVersion = PHP_VERSION;
        $scannedAt = now()->setTimezone('Asia/Makassar')->translatedFormat('d F Y - H:i:s') . ' WITA';
        $cliCommand = '/www/server/php/84/bin/php /usr/bin/composer audit';

        // ============================================================
        // STRATEGI 1: Packagist Security Advisories API (HTTP-based)
        // Tidak butuh proc_open / shell_exec — paling andal di aaPanel
        // ============================================================
        try {
            $lockPath = base_path('composer.lock');
            if (!file_exists($lockPath)) {
                throw new \RuntimeException('composer.lock tidak ditemukan.');
            }

            $lockContent = json_decode(file_get_contents($lockPath), true);
            if (!$lockContent || !isset($lockContent['packages'])) {
                throw new \RuntimeException('composer.lock tidak valid.');
            }

            // Kumpulkan daftar paket + versi dari composer.lock
            $packages = [];
            foreach (array_merge($lockContent['packages'] ?? [], $lockContent['packages-dev'] ?? []) as $pkg) {
                if (isset($pkg['name'], $pkg['version'])) {
                    $name = $pkg['name'];
                    $version = ltrim($pkg['version'], 'v');
                    $packages[$name] = $version;
                }
            }

            if (empty($packages)) {
                throw new \RuntimeException('Tidak ada paket ditemukan di composer.lock.');
            }

            // Kirim ke Packagist Security Advisories API
            $postData = http_build_query(['packages' => array_keys($packages)]);

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
                    'content' => $postData,
                    'timeout' => 15,
                    'ignore_errors' => true,
                ],
                'ssl' => [
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                ],
            ]);

            $response = @file_get_contents('https://packagist.org/api/security-advisories/', false, $context);

            if ($response === false) {
                throw new \RuntimeException('Gagal menghubungi Packagist Security API.');
            }

            $decoded = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded['advisories'])) {
                throw new \RuntimeException('Response Packagist tidak valid.');
            }

            $advisories = $decoded['advisories'];
            $totalAdvisories = 0;
            $affectedPackages = [];

            foreach ($advisories as $pkgName => $pkgAdvisories) {
                if (!is_array($pkgAdvisories) || empty($pkgAdvisories)) {
                    continue;
                }

                $installedVersion = $packages[$pkgName] ?? null;
                if (!$installedVersion) {
                    continue;
                }

                // Evaluasi apakah versi paket yang terpasang benar-benar masuk dalam affectedVersions
                $matchedAdvisories = [];
                foreach ($pkgAdvisories as $adv) {
                    $constraint = $adv['affectedVersions'] ?? '';
                    if ($this->isVersionVulnerable($installedVersion, $constraint)) {
                        $adv['installed_version'] = $installedVersion;
                        $matchedAdvisories[] = $adv;
                    }
                }

                if (!empty($matchedAdvisories)) {
                    $totalAdvisories += count($matchedAdvisories);
                    $affectedPackages[$pkgName] = $matchedAdvisories;
                }
            }

            $isClean = ($totalAdvisories === 0);

            return response()->json([
                'success' => true,
                'status' => $isClean ? 'clean' : 'vulnerable',
                'scan_method' => 'Packagist Security API',
                'framework_version' => $frameworkVersion,
                'php_version' => $phpVersion,
                'scanned_at' => $scannedAt,
                'total_packages_scanned' => count($packages),
                'total_vulnerabilities' => $totalAdvisories,
                'advisories' => $affectedPackages,
                'message' => $isClean
                    ? "Sistem Aman! Tidak ditemukan kerentanan (0 CVE) pada " . count($packages) . " paket dependensi aktif. Terverifikasi via Packagist Security Advisories API."
                    : "Peringatan: Ditemukan {$totalAdvisories} kerentanan keamanan pada " . count($affectedPackages) . " paket. Segera lakukan penambalan (patching).",
                'cli_command' => $cliCommand,
            ]);

        } catch (\Throwable $apiError) {
            // API gagal, lanjut ke strategi 2
        }

        // ============================================================
        // STRATEGI 2: Composer CLI via proc_open (fallback)
        // Butuh proc_open enabled — aaPanel biasanya memblokir ini
        // ============================================================
        if (function_exists('proc_open')) {
            try {
                $phpBin = file_exists('/www/server/php/84/bin/php') ? '/www/server/php/84/bin/php' : (PHP_BINARY ?: 'php');
                $composerBin = file_exists('/usr/bin/composer') ? '/usr/bin/composer' : 'composer';

                $process = new \Symfony\Component\Process\Process(
                    [$phpBin, $composerBin, 'audit', '--format=json'],
                    base_path()
                );
                $process->setTimeout(30);
                $process->run();

                $output = trim($process->getOutput());
                $decoded = json_decode($output, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $advisories = $decoded['advisories'] ?? [];
                    $totalAdvisories = 0;

                    foreach ($advisories as $pkg => $pkgAdvisories) {
                        if (is_array($pkgAdvisories)) {
                            $totalAdvisories += count($pkgAdvisories);
                        }
                    }

                    $isClean = ($totalAdvisories === 0);

                    return response()->json([
                        'success' => true,
                        'status' => $isClean ? 'clean' : 'vulnerable',
                        'scan_method' => 'Composer CLI Audit',
                        'framework_version' => $frameworkVersion,
                        'php_version' => $phpVersion,
                        'scanned_at' => $scannedAt,
                        'total_vulnerabilities' => $totalAdvisories,
                        'advisories' => $advisories,
                        'message' => $isClean
                            ? "Sistem Aman! Tidak ditemukan kerentanan (0 CVE) via Composer CLI Audit."
                            : "Peringatan: Ditemukan {$totalAdvisories} kerentanan keamanan. Segera lakukan penambalan.",
                        'cli_command' => $cliCommand,
                    ]);
                }
            } catch (\Throwable $cliError) {
                // CLI juga gagal
            }
        }

        // ============================================================
        // FALLBACK FINAL: Semua strategi gagal
        // ============================================================
        $reasons = [];
        if (isset($apiError)) {
            $reasons[] = 'Packagist API: ' . mb_substr($apiError->getMessage(), 0, 150);
        }
        if (!function_exists('proc_open')) {
            $reasons[] = 'proc_open diblokir oleh konfigurasi PHP (aaPanel disable_functions)';
        } elseif (isset($cliError)) {
            $reasons[] = 'Composer CLI: ' . mb_substr($cliError->getMessage(), 0, 150);
        }

        return response()->json([
            'success' => false,
            'status' => 'unavailable',
            'framework_version' => $frameworkVersion,
            'php_version' => $phpVersion,
            'scanned_at' => $scannedAt,
            'total_vulnerabilities' => null,
            'advisories' => [],
            'message' => "Live scan tidak dapat dijalankan. "
                . (!empty($reasons) ? "Detail: " . implode(' | ', $reasons) . ". " : "")
                . "Silakan verifikasi manual via Terminal VPS menggunakan perintah CLI di bawah.",
            'cli_command' => $cliCommand,
        ]);
    }

    /**
     * Evaluasi apakah versi paket yang terpasang memenuhi constraint kerentanan (affectedVersions).
     */
    private function isVersionVulnerable(string $installedVersion, string $affectedVersions): bool
    {
        $affectedVersions = trim($affectedVersions);
        if ($affectedVersions === '') {
            return false;
        }

        if (class_exists(Semver::class)) {
            try {
                return Semver::satisfies($installedVersion, $affectedVersions);
            } catch (\Throwable $e) {
                // Lanjut ke fallback jika format constraint tidak didukung parser utama
            }
        }

        // Fallback: parser manual berbasis version_compare
        $orGroups = preg_split('/\|\||\|/', $affectedVersions);
        foreach ($orGroups as $group) {
            $parts = array_filter(array_map('trim', explode(',', $group)));
            $allPassed = true;
            foreach ($parts as $part) {
                if (preg_match('/^(<=|<|>=|>|==|=|!=)\s*([0-9A-Za-z\.\-]+)$/', $part, $matches)) {
                    $op = $matches[1] === '=' ? '==' : $matches[1];
                    $targetVer = ltrim($matches[2], 'v');
                    if (!version_compare($installedVersion, $targetVer, $op)) {
                        $allPassed = false;
                        break;
                    }
                } else {
                    $allPassed = false;
                    break;
                }
            }
            if ($allPassed && !empty($parts)) {
                return true;
            }
        }

        return false;
    }
}

