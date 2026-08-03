<x-app-layout>
@section('title', 'Maintenance Sistem')

<div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <h2 class="text-headline-sm font-headline-sm text-on-surface">Maintenance & Pengamanan Sistem</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Kelola penguncian akses saat update server, pencadangan (backup), serta pemeliharaan data.</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl flex items-center gap-3 shadow-sm font-body-md text-sm font-semibold">
    <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0" data-weight="fill">check_circle</span>
    <div class="flex-grow">{{ session('success') }}</div>
</div>
@endif
@if(session('error'))
<div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-xl flex items-center gap-3 shadow-sm font-body-md text-sm font-semibold">
    <span class="material-symbols-outlined text-rose-600 text-2xl shrink-0" data-weight="fill">error</span>
    <div class="flex-grow">{{ session('error') }}</div>
</div>
@endif

<!-- Panel Mode Pemeliharaan (Lockdown SKPD) -->
<div class="mb-8 bg-gradient-to-r {{ !empty($lockStatus['active']) ? 'from-rose-950 via-slate-900 to-amber-950 border-rose-600/80 shadow-rose-950/20' : 'from-slate-900 via-indigo-950 to-slate-900 border-slate-700' }} text-white p-6 rounded-2xl border-2 shadow-xl relative overflow-hidden transition-all">
    <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div class="space-y-2 max-w-xl">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined {{ !empty($lockStatus['active']) ? 'text-rose-400 animate-bounce' : 'text-amber-400' }} text-3xl" data-weight="fill">
                    {{ !empty($lockStatus['active']) ? 'lock' : 'admin_panel_settings' }}
                </span>
                <h3 class="text-xl font-black tracking-tight">Mode Pemeliharaan & Penguncian Akses SKPD</h3>
                @if(!empty($lockStatus['active']))
                    <span class="px-3 py-0.5 bg-rose-500 text-white font-extrabold text-xs rounded-full shadow animate-pulse">🔴 LOCKDOWN AKTIF</span>
                @else
                    <span class="px-3 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 font-bold text-xs rounded-full">🟢 NORMA / BERJALAN</span>
                @endif
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
                Gunakan fitur ini apabila Anda (Admin) sedang melakukan pembaruan dokumen, sinkronisasi file server, atau restore database. Saat aktif, <strong class="text-white underline">seluruh operator SKPD tidak akan dapat masuk atau menginput data</strong> guna mencegah duplikasi atau konflik data selama masa pengerjaan Admin.
            </p>
        </div>

        <div class="shrink-0 bg-slate-800/90 backdrop-blur border border-slate-700 p-5 rounded-2xl min-w-[320px] shadow-inner">
            @if(!empty($lockStatus['active']))
                <div class="space-y-3">
                    <div class="text-xs text-rose-300 font-semibold flex items-start gap-2">
                        <span class="material-symbols-outlined text-rose-400 text-base shrink-0" data-weight="fill">warning</span>
                        <span>Operator SKPD saat ini sedang dicegah mengakses aplikasi dan diarahkan ke layar pemeliharaan.</span>
                    </div>
                    <div class="p-2.5 bg-slate-900 rounded-lg text-[11px] text-slate-300 space-y-1">
                        <div><strong class="text-slate-400">Alasan Aktif:</strong> {{ $lockStatus['reason'] ?? '-' }}</div>
                        <div><strong class="text-slate-400">Target Selesai:</strong> {{ $lockStatus['estimated_end'] ?? '-' }}</div>
                    </div>
                    <form action="{{ route('pengaturan.maintenance.lockdown') }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="disable">
                        <button type="submit" class="w-full py-2.5 px-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-1.5 active:scale-95">
                            <span class="material-symbols-outlined text-base" data-weight="fill">lock_open</span>
                            <span>Buka Kembali Akses SKPD (Normal)</span>
                        </button>
                    </form>
                </div>
            @else
                <form action="{{ route('pengaturan.maintenance.lockdown') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="action" value="enable">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 mb-1">Pesan / Alasan Untuk SKPD:</label>
                        <input type="text" name="reason" value="Sinkronisasi, pemeliharaan server & pemutakhiran data arsip SiReKa." class="w-full h-9 bg-slate-900 border border-slate-700 rounded-lg px-3 text-xs text-white focus:ring-1 focus:ring-amber-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 mb-1">Perkiraan Waktu Selesai:</label>
                        <input type="text" name="estimated_end" placeholder="Contoh: 14:30 WITA / 30 Menit" value="30 Menit (Dalam Proses Admin)" class="w-full h-9 bg-slate-900 border border-slate-700 rounded-lg px-3 text-xs text-white focus:ring-1 focus:ring-amber-400 outline-none">
                    </div>
                    <button type="submit" onclick="return confirm('Anda yakin ingin menutup sementara akses input seluruh SKPD? Hanya Admin dan Konsolidator yang bisa tetap beroperasi dalam sistem.')" class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black rounded-xl text-xs shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-1.5 active:scale-95 mt-2">
                        <span class="material-symbols-outlined text-base" data-weight="fill">lock</span>
                        <span>Aktifkan Lockdown Akses SKPD</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Card Backup Database -->
    <div class="flex flex-col">
        <div class="bg-surface rounded-xl shadow-sm border border-outline-variant p-6 flex flex-col h-full relative overflow-hidden group">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors pointer-events-none"></div>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[28px]" data-weight="fill">cloud_download</span>
                </div>
                <div>
                    <h3 class="text-title-md font-title-md text-on-surface font-semibold">Backup Database</h3>
                    <p class="text-label-sm text-on-surface-variant">Unduh salinan data aplikasi</p>
                </div>
            </div>
            
            <p class="text-body-md font-body-md text-on-surface-variant mb-6 flex-grow">
                Amankan seluruh data aplikasi dengan membuat salinan lengkap database dalam format <code>.sql</code>. 
                Sangat disarankan untuk melakukan backup secara rutin sebelum melakukan perubahan besar atau penghapusan data.
            </p>
            
            <div class="bg-primary/5 border border-primary/20 rounded-lg p-4 flex gap-3 mb-6">
                <span class="material-symbols-outlined text-primary shrink-0">info</span>
                <p class="text-body-sm text-on-surface-variant">Data backup berisi seluruh rekam jejak, transaksi, dan master data. Simpan file backup di tempat yang aman.</p>
            </div>

            <form action="{{ route('pengaturan.maintenance.backup') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="flex items-start gap-3 cursor-pointer group/cb">
                        <div class="relative flex items-center mt-0.5">
                            <input type="checkbox" name="include_dokumen" value="1" class="w-5 h-5 border-2 border-outline-variant rounded bg-surface checked:bg-primary checked:border-primary focus:ring-primary focus:ring-offset-2 transition-colors cursor-pointer appearance-none peer">
                            <span class="material-symbols-outlined text-[16px] text-on-primary absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" data-weight="bold">check</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-label-md font-label-md text-on-surface group-hover/cb:text-primary transition-colors">Sertakan Dokumen Pendukung</span>
                            <span class="text-body-sm text-on-surface-variant">File Berita Acara, Buku Kas, dll. Hasil unduhan akan berformat .zip</span>
                        </div>
                    </label>
                </div>
                <button type="submit" class="w-full h-11 bg-primary text-on-primary hover:bg-primary/90 rounded-lg flex items-center justify-center gap-2 font-label-md transition-colors shadow-sm">
                    <span class="material-symbols-outlined" data-weight="fill">download</span>
                    Download Backup Sekarang
                </button>
            </form>
        </div>
    </div>

    <!-- Card Restore Database -->
    <div class="flex flex-col">
        <div class="bg-surface rounded-xl shadow-sm border border-outline-variant p-6 flex flex-col h-full relative overflow-hidden group">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors pointer-events-none"></div>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[28px]" data-weight="fill">cloud_upload</span>
                </div>
                <div>
                    <h3 class="text-title-md font-title-md text-on-surface font-semibold">Restore Database</h3>
                    <p class="text-label-sm text-on-surface-variant">Pulihkan data aplikasi</p>
                </div>
            </div>
            
            <p class="text-body-md font-body-md text-on-surface-variant mb-6 flex-grow">
                Unggah file backup <code>.sqlite</code> (untuk SQLite) atau <code>.sql</code> (untuk MySQL) untuk mengembalikan data seperti semula.
            </p>
            
            <form action="{{ route('pengaturan.maintenance.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apakah Anda yakin ingin me-restore database? Data saat ini akan DITIMPA oleh data dari file backup!');">
                @csrf
                <div class="mb-4">
                    <input type="file" name="backup_file" required class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                    @error('backup_file') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full h-11 bg-primary text-on-primary hover:bg-primary/90 rounded-lg flex items-center justify-center gap-2 font-label-md transition-colors shadow-sm">
                    <span class="material-symbols-outlined" data-weight="fill">upload</span>
                    Restore Sekarang
                </button>
            </form>
        </div>
    </div>

    <!-- Card Hapus/Reset Data -->
    <div class="flex flex-col">
        <div class="bg-surface rounded-xl shadow-sm border border-error/30 p-6 flex flex-col h-full relative overflow-hidden group">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-error/5 rounded-full blur-2xl group-hover:bg-error/10 transition-colors pointer-events-none"></div>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg bg-error/10 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined text-[28px]" data-weight="fill">delete_forever</span>
                </div>
                <div>
                    <h3 class="text-title-md font-title-md text-error font-semibold">Hapus/Reset Data</h3>
                    <p class="text-label-sm text-error/80">Penghapusan permanen tidak dapat dibatalkan</p>
                </div>
            </div>
            
            <p class="text-body-md font-body-md text-on-surface-variant mb-6">
                Pilih data yang ingin Anda hapus secara permanen. Master Data (Admin, SKPD) tidak akan terhapus.
            </p>

            <form action="{{ route('pengaturan.maintenance.reset') }}" method="POST" class="flex-grow flex flex-col" onsubmit="return confirm('PERINGATAN KRITIKAL!\n\nAnda yakin ingin menghapus data yang dicentang secara PERMANEN? Data yang dihapus tidak dapat dikembalikan!');">
                @csrf
                @method('DELETE')

                <div class="space-y-3 mb-6">
                    <label class="flex items-start gap-3 p-3 rounded-lg border border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors">
                        <input type="checkbox" name="tipe_data[]" value="transaksi" class="mt-1 w-5 h-5 rounded border-outline-variant text-error focus:ring-error" required onchange="validateCheckboxes()">
                        <div>
                            <span class="block text-body-md font-semibold text-on-surface">Data Transaksi & Berita Acara</span>
                            <span class="block text-body-sm text-on-surface-variant">Menghapus seluruh rekam jejak saldo, mutasi, dan rekon seluruh tahun dan SKPD.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-lg border border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors">
                        <input type="checkbox" name="tipe_data[]" value="log" class="mt-1 w-5 h-5 rounded border-outline-variant text-error focus:ring-error" onchange="validateCheckboxes()">
                        <div>
                            <span class="block text-body-md font-semibold text-on-surface">Riwayat Log Aktivitas</span>
                            <span class="block text-body-sm text-on-surface-variant">Mereset catatan jejak digital siapa melakukan apa dalam sistem.</span>
                        </div>
                    </label>
                </div>

                <div class="mt-auto pt-4 border-t border-outline-variant">
                    <div class="flex flex-col gap-1.5 mb-4">
                        <label class="text-label-sm font-label-sm text-error flex items-center gap-1" for="password">
                            <span class="material-symbols-outlined text-[14px]">lock</span>
                            Konfirmasi Kata Sandi Anda
                        </label>
                        <input class="h-10 px-3 rounded-lg border border-error/50 bg-surface focus:border-error focus:ring-2 focus:ring-error/20 text-body-md w-full transition-all outline-none" 
                            id="password" name="password" type="password" required placeholder="Masukkan kata sandi untuk verifikasi" />
                        @error('password') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        @error('tipe_data') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" id="btnDelete" class="w-full h-11 bg-error text-on-error hover:bg-error/90 rounded-lg flex items-center justify-center gap-2 font-label-md transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined" data-weight="fill">warning</span>
                        Hapus Data Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function validateCheckboxes() {
        const checkboxes = document.querySelectorAll('input[name="tipe_data[]"]');
        let isChecked = false;
        checkboxes.forEach(cb => {
            if (cb.checked) isChecked = true;
            // Remove the required attribute from all to let JS handle the visual, or just leave it.
            // If one is checked, remove required from others
            cb.removeAttribute('required');
        });
        
        // If none checked, make the first one required so HTML5 validation blocks submit
        if (!isChecked) {
            checkboxes[0].setAttribute('required', 'required');
        }
    }
</script>
</x-app-layout>
