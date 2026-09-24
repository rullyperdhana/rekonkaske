<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'version' => 'v2.7.0',
                'date' => '24 September 2026',
                'badge' => 'VERSI AKTIF',
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

        // 3. Spesifikasi Arsitektur Sistem
        $systemSpecs = [
            'app_name' => config('app.name', 'SiReKa'),
            'app_version' => 'v2.6.0 Enterprise',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_os' => php_uname('s') . ' ' . php_uname('r'),
            'timezone' => config('app.timezone', 'Asia/Makassar') . ' (WITA)',
            'database_driver' => config('database.default', 'mysql'),
            'storage_driver' => config('filesystems.disks.public.driver', 'local'),
            'cache_driver' => config('cache.default', 'file'),
            'session_driver' => config('session.driver', 'file'),
        ];

        return view('pengaturan.dokumentasi.index', compact('changelogs', 'modulKatalog', 'systemSpecs'));
    }
}
