<x-app-layout>
    <div class="space-y-6" x-data="{ showToken: false }">
        <!-- Page Header -->
        <div class="border-b-[3px] border-primary pb-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-3xl" data-weight="fill">send</span>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface font-extrabold tracking-tight">Pengaturan Notifikasi Telegram</h1>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Konfigurasi siaran notifikasi otomatis ke ponsel Administrator/Konsolidator via Bot Telegram saat SKPD melakukan <strong class="text-primary">Posting Final / Diverifikasi SKPD</strong>.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @php
                    $isAktif = (bool) ($pengaturan->telegram_notif_aktif ?? false);
                    $hasToken = !empty($pengaturan->telegram_bot_token);
                    $hasChatId = !empty($pengaturan->telegram_chat_id);
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold font-mono border {{ $isAktif && $hasToken && $hasChatId ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-800 border-slate-300' }}">
                    <span class="w-2 h-2 rounded-full {{ $isAktif && $hasToken && $hasChatId ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                    STATUS: {{ $isAktif && $hasToken && $hasChatId ? '🟢 NOTIFIKASI AKTIF' : '⚪ NONAKTIF / BELUM SIAP' }}
                </span>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl flex items-center gap-3 shadow-xs">
            <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0" data-weight="fill">check_circle</span>
            <div class="flex-grow font-medium text-sm">{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl flex items-center gap-3 shadow-xs">
            <span class="material-symbols-outlined text-rose-600 text-2xl shrink-0" data-weight="fill">error</span>
            <div class="flex-grow font-medium text-sm">{{ session('error') }}</div>
        </div>
        @endif

        <!-- Main Layout: Form (Left) & Guide/Preview (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Column: Form & Test -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Form Card -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-6 shadow-xs">
                    <form action="{{ route('pengaturan.telegram.update') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Toggle Switch -->
                        <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/60 flex items-center justify-between gap-4">
                            <div>
                                <label for="telegram_notif_aktif" class="text-sm font-bold text-on-surface cursor-pointer">
                                    Aktifkan Siaran Notifikasi Otomatis
                                </label>
                                <p class="text-xs text-on-surface-variant mt-0.5">
                                    Kirim pesan otomatis setiap ada SKPD yang menyelesaikan rekonsiliasi kas (Posting Final / Verified).
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" id="telegram_notif_aktif" name="telegram_notif_aktif" value="1"
                                    {{ old('telegram_notif_aktif', $pengaturan->telegram_notif_aktif ?? false) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <!-- Bot Token Field -->
                        <div class="space-y-1.5">
                            <label for="telegram_bot_token" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center justify-between">
                                <span>Token Bot Telegram (API Token)</span>
                                <span class="text-[11px] text-primary lowercase font-medium">dari @BotFather</span>
                            </label>
                            <div class="relative">
                                <input :type="showToken ? 'text' : 'password'"
                                    id="telegram_bot_token"
                                    name="telegram_bot_token"
                                    value="{{ old('telegram_bot_token', $pengaturan->telegram_bot_token ?? '') }}"
                                    placeholder="Contoh: 1234567890:ABCdefGhIJKlmNoPQRsTUVwxyZ"
                                    class="w-full h-11 pl-4 pr-12 rounded-xl border border-outline-variant bg-surface text-body-sm font-mono text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                <button type="button" @click="showToken = !showToken"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                                    <span class="material-symbols-outlined text-[20px]" x-text="showToken ? 'visibility_off' : 'visibility'"></span>
                                </button>
                            </div>
                            <p class="text-[11px] text-on-surface-variant">Token rahasia bot yang diterbitkan oleh BotFather di aplikasi Telegram.</p>
                        </div>

                        <!-- Chat ID Field -->
                        <div class="space-y-1.5">
                            <label for="telegram_chat_id" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center justify-between">
                                <span>Target Chat ID / ID Grup</span>
                                <span class="text-[11px] text-on-surface-variant lowercase font-medium">Bisa multi-ID (pisahkan koma)</span>
                            </label>
                            <input type="text"
                                id="telegram_chat_id"
                                name="telegram_chat_id"
                                value="{{ old('telegram_chat_id', $pengaturan->telegram_chat_id ?? '') }}"
                                placeholder="Contoh: 123456789 (Pribadi) atau -1001234567890 (Grup BKAD)"
                                class="w-full h-11 px-4 rounded-xl border border-outline-variant bg-surface text-body-sm font-mono text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                            <p class="text-[11px] text-on-surface-variant">
                                Masukkan Chat ID akun Admin atau ID Grup koordinasi BKAD (ID Grup biasanya diawali dengan tanda minus <code>-</code> atau <code>-100</code>).
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-outline-variant/50 space-y-4">
                            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                    <!-- Tombol 1: Tes Koneksi Bot -->
                                    <button type="button" id="btnTestTelegramConnection" onclick="window.testTelegramConnection()"
                                        class="px-4 py-2.5 rounded-xl border border-sky-500/40 bg-sky-500/10 hover:bg-sky-500/20 text-sky-800 text-xs font-bold flex items-center justify-center gap-1.5 transition-all active:scale-95 shadow-xs cursor-pointer"
                                        title="Uji coba koneksi dasar ke bot @BotFather">
                                        <span class="material-symbols-outlined text-[18px]">network_check</span>
                                        <span>Tes Koneksi Bot</span>
                                    </button>

                                    <!-- Tombol 2: Tes Kirim Data Rekonsiliasi (Simulasi SKPD) -->
                                    <button type="button" id="btnTestTelegramData" onclick="window.testTelegramData()"
                                        class="px-4 py-2.5 rounded-xl border border-emerald-600 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center justify-center gap-1.5 transition-all shadow-sm active:scale-95 cursor-pointer"
                                        title="Kirim simulasi pesan notifikasi data rekonsiliasi kas riil ke Telegram">
                                        <span class="material-symbols-outlined text-[18px]">forward_to_inbox</span>
                                        <span>Tes Kirim Data SKPD</span>
                                    </button>
                                </div>

                                <!-- Tombol 3: Simpan Konfigurasi -->
                                <button type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-on-primary text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm transition-transform active:scale-95 shrink-0 cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px]">save</span>
                                    <span>Simpan Konfigurasi</span>
                                </button>
                            </div>

                            <!-- Dynamic Inline Feedback Box -->
                            <div id="testResultBox" class="hidden p-4 bg-emerald-50 border-2 border-emerald-400 text-emerald-950 rounded-2xl flex items-start gap-3 shadow-xs transition-all">
                                <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0 mt-0.5" data-weight="fill">verified</span>
                                <div class="space-y-1">
                                    <p class="font-bold text-xs uppercase tracking-wider text-emerald-800">✅ Berhasil Terkirim ke Telegram!</p>
                                    <p class="text-xs font-medium text-emerald-900" id="testResultText"></p>
                                    <p class="text-[11px] text-emerald-700">Silakan buka notifikasi aplikasi Telegram di ponsel atau grup Anda untuk melihat pesan yang baru masuk.</p>
                                </div>
                            </div>

                            <div id="testErrorBox" class="hidden p-4 bg-rose-50 border-2 border-rose-400 text-rose-950 rounded-2xl flex items-start gap-3 shadow-xs transition-all">
                                <span class="material-symbols-outlined text-rose-600 text-2xl shrink-0 mt-0.5" data-weight="fill">error</span>
                                <div class="space-y-1">
                                    <p class="font-bold text-xs uppercase tracking-wider text-rose-800">❌ Pengiriman Gagal</p>
                                    <p class="text-xs font-medium text-rose-900" id="testErrorText"></p>
                                    <p class="text-[11px] text-rose-700">Pastikan Token Bot dan Chat ID sudah benar serta Bot telah Anda ketik /start.</p>
                                </div>
                            </div>

                            <!-- Keterangan Tombol Pengujian & Tindakan -->
                            <div class="p-3.5 bg-surface-container-low rounded-xl border border-outline-variant/60 text-xs text-on-surface-variant space-y-2.5">
                                <div class="flex items-center gap-1.5 font-bold text-on-surface text-xs">
                                    <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                                    <span>Keterangan Tombol di Atas:</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] leading-relaxed">
                                    <div class="p-2.5 rounded-lg bg-sky-50/80 border border-sky-200/80 text-sky-950 space-y-1">
                                        <p class="font-bold flex items-center gap-1.5 text-sky-800">
                                            <span class="material-symbols-outlined text-[16px]">network_check</span>
                                            Tombol Biru: Tes Koneksi Bot
                                        </p>
                                        <p class="text-[11px] text-sky-900/90 leading-normal">
                                            Menguji keaktifan Bot @BotFather dan mengirim pesan teks pembuka (sapaan singkat) untuk memverifikasi sambungan API.
                                        </p>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-emerald-50/80 border border-emerald-200/80 text-emerald-950 space-y-1">
                                        <p class="font-bold flex items-center gap-1.5 text-emerald-800">
                                            <span class="material-symbols-outlined text-[16px]">forward_to_inbox</span>
                                            Tombol Hijau: Tes Kirim Data SKPD
                                        </p>
                                        <p class="text-[11px] text-emerald-900/90 leading-normal">
                                            Mengirim contoh notifikasi lengkap data rekonsiliasi kas riil (Dinas Pendidikan) ke ponsel Anda, persis seperti siaran otomatis saat SKPD posting final.
                                        </p>
                                    </div>
                                </div>
                                <p class="text-[10px] text-on-surface-variant italic">
                                    💡 <b>Simpan Konfigurasi:</b> Klik tombol biru tua untuk menyimpan Token & Chat ID ke sistem agar siaran otomatis aktif berjalan di latar belakang.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Live Smartphone Notification Mockup -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between border-b border-outline-variant/40 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sky-500 text-xl" data-weight="fill">phone_iphone</span>
                            <span class="text-xs font-bold text-on-surface">Simulasi Tampilan di Layar Ponsel Admin</span>
                        </div>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-sky-100 text-sky-800 font-bold">TELEGRAM NOTIFICATION</span>
                    </div>

                    <!-- Telegram Bubble -->
                    <div class="max-w-md mx-auto bg-slate-900 text-slate-100 p-4 rounded-2xl shadow-lg border border-slate-800 font-sans text-xs space-y-2 relative overflow-hidden">
                        <div class="flex items-center justify-between text-[11px] text-sky-400 font-bold border-b border-slate-800 pb-1.5">
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">campaign</span>
                                SiReKa Bot (Tapin)
                            </span>
                            <span class="text-slate-500 font-mono text-[10px]">Baru saja</span>
                        </div>

                        <div class="space-y-1 leading-relaxed text-slate-200 text-[11px]">
                            <p class="font-bold text-white text-xs">📢 NOTIFIKASI REKONSILIASI KAS (SiReKa)</p>
                            <p class="text-slate-500">━━━━━━━━━━━━━━━━━━━━━━</p>
                            <p>🏛 <b>SKPD:</b> DINAS PENDIDIKAN</p>
                            <p>🔢 <b>Kode:</b> <code>1-01-01</code></p>
                            <p>📅 <b>Periode:</b> September 2026</p>
                            <p>💰 <b>Saldo BKU:</b> Rp 145.250.000,00</p>
                            <p>🏦 <b>Saldo Bank:</b> Rp 145.250.000,00</p>
                            <p>⚖️ <b>Status Kas:</b> 🟢 KLOP (Sesuai Rp 0,00)</p>
                            <p>📎 <b>Kelengkapan:</b> 4/4 Berkas Terunggah</p>
                            <p>📌 <b>Status:</b> Diverifikasi SKPD (Posting Final)</p>
                            <p>👤 <b>Operator:</b> Ahmad Fauzi</p>
                            <p>⏰ <b>Waktu Posting:</b> 24 September 2026, 01:00 WITA</p>
                            <p class="text-slate-500">━━━━━━━━━━━━━━━━━━━━━━</p>
                            <p class="text-sky-300 italic text-[10px]">💡 Silakan buka menu Antrean Verifikasi untuk melakukan pemeriksaan berkas.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Step-by-Step Guide Bento Cards -->
            <div class="lg:col-span-5 space-y-5">
                
                <!-- Guide Header -->
                <div class="bg-gradient-to-br from-sky-950 via-slate-900 to-indigo-950 text-white p-5 rounded-2xl shadow-md border border-sky-500/30 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-sky-500/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-sky-500 text-slate-950 flex items-center justify-center font-black">
                            <span class="material-symbols-outlined text-2xl">help</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-white">Panduan Konfigurasi Bot</h2>
                            <p class="text-xs text-sky-200/80">Ikuti 4 langkah praktis berikut ini</p>
                        </div>
                    </div>
                </div>

                <!-- Step 1 -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs space-y-2">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center shrink-0">1</span>
                        <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider">Buat Bot di @BotFather</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Buka aplikasi Telegram di HP/Komputer, cari akun resmi <strong class="text-on-surface">@BotFather</strong> (bercentang biru), lalu kirim perintah:
                    </p>
                    <div class="p-2.5 bg-slate-900 text-sky-300 rounded-xl font-mono text-[11px]">
                        /newbot
                    </div>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Beri nama bot (misal: <em>SiReKa Tapin Notif</em>) dan username berakhiran <em>bot</em> (misal: <em>sireka_tapin_bot</em>). Salin <strong>API Token</strong> yang diberikan ke kolom Token di samping.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs space-y-2">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center shrink-0">2</span>
                        <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider">Dapatkan Chat ID Akun Anda</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Ketik <code>/start</code> ke bot yang baru Anda buat. Lalu cari bot pencari ID seperti <strong class="text-on-surface">@userinfobot</strong> atau <strong class="text-on-surface">@getidsbot</strong> di Telegram dan kirim pesan apa saja. Bot akan membalas dengan nomor ID unik Anda (contoh: <code>987654321</code>).
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs space-y-2">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center shrink-0">3</span>
                        <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider">Opsi Masuk ke Grup Telegram BKAD</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Jika ingin notifikasi masuk ke <strong>Grup Tim Konsolidator BKAD</strong>:
                    </p>
                    <ul class="text-xs text-on-surface-variant space-y-1.5 list-disc pl-4">
                        <li>Undang bot yang Anda buat ke dalam grup tersebut.</li>
                        <li>Jadikan bot sebagai Administrator grup.</li>
                        <li>Dapatkan ID grup (biasanya bernilai negatif, contoh: <code>-100192837465</code>).</li>
                        <li>Masukkan ID grup tersebut ke kolom Chat ID di samping.</li>
                    </ul>
                </div>

                <!-- Step 4 -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs space-y-2">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-sky-600 text-white font-bold text-xs flex items-center justify-center shrink-0">4</span>
                        <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider">Uji Coba & Simpan</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Klik tombol <strong>"Tes Kirim Data SKPD"</strong> untuk memastikan integrasi berhasil. Setelah pesan masuk ke Telegram Anda, centang switch <strong>"Aktifkan Siaran Notifikasi Otomatis"</strong> dan klik <strong>"Simpan Konfigurasi"</strong>.
                    </p>
                </div>

                <!-- Fail-safe Architecture Note -->
                <div class="p-4 bg-surface-container-low rounded-2xl border border-outline-variant text-xs text-on-surface-variant space-y-1">
                    <div class="flex items-center gap-1.5 font-bold text-on-surface">
                        <span class="material-symbols-outlined text-primary text-[18px]">security</span>
                        <span>Proteksi Keamanan & Kestabilan Sistem</span>
                    </div>
                    <p class="leading-relaxed">
                        Sistem SiReKa menerapkan arsitektur <em>fail-safe</em> dengan batas waktu 10 detik. Jika koneksi Telegram sedang tidak stabil, proses transaksi SKPD akan <strong>tetap tersimpan sukses</strong> tanpa pesan error atau kendala apapun.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side Testing Script with SweetAlert2 & Inline Feedback -->
    <script>
        window.testTelegramConnection = async function() {
            const tokenInput = document.getElementById('telegram_bot_token');
            const chatIdInput = document.getElementById('telegram_chat_id');
            const token = tokenInput ? tokenInput.value.trim() : '';
            const chatId = chatIdInput ? chatIdInput.value.trim() : '';

            const resBox = document.getElementById('testResultBox');
            const errBox = document.getElementById('testErrorBox');
            if (resBox) resBox.classList.add('hidden');
            if (errBox) errBox.classList.add('hidden');

            if (!token) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Token Belum Diisi',
                        text: 'Silakan isi Token Bot Telegram terlebih dahulu sebelum melakukan pengujian.',
                        confirmButtonColor: '#00346f'
                    });
                } else {
                    alert('Token Belum Diisi: Silakan isi Token Bot Telegram terlebih dahulu.');
                }
                tokenInput?.focus();
                return;
            }
            if (!chatId) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chat ID Belum Diisi',
                        text: 'Silakan isi Target Chat ID / ID Grup terlebih dahulu.',
                        confirmButtonColor: '#00346f'
                    });
                } else {
                    alert('Chat ID Belum Diisi: Silakan isi Target Chat ID / ID Grup terlebih dahulu.');
                }
                chatIdInput?.focus();
                return;
            }

            const btn = document.getElementById('btnTestTelegramConnection');
            const originalBtnHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-wait');
                btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">sync</span><span>Menguji Koneksi...</span>';
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Menguji Koneksi Bot...',
                    text: 'Menghubungkan ke API Telegram (@BotFather), mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            try {
                const res = await fetch('{{ route('pengaturan.telegram.test') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        telegram_bot_token: token,
                        telegram_chat_id: chatId
                    })
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Koneksi Berhasil!',
                            html: `<p class="text-sm font-medium">${data.message}</p><p class="text-xs text-slate-500 mt-2">Pesan sambutan telah masuk ke Telegram Anda.</p>`,
                            confirmButtonColor: '#10b981'
                        });
                    }
                    if (resBox) {
                        resBox.classList.remove('hidden');
                        document.getElementById('testResultText').innerText = data.message;
                        resBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                    if (errBox) errBox.classList.add('hidden');
                } else {
                    const errorMsg = data.message || 'Gagal menghubungi Bot Telegram.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Uji Koneksi Gagal',
                            text: errorMsg,
                            confirmButtonColor: '#ba1a1a'
                        });
                    }
                    if (errBox) {
                        errBox.classList.remove('hidden');
                        document.getElementById('testErrorText').innerText = errorMsg;
                        errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                    if (resBox) resBox.classList.add('hidden');
                }
            } catch (e) {
                const connError = 'Terjadi kesalahan komunikasi dengan server: ' + e.message;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan',
                        text: connError,
                        confirmButtonColor: '#ba1a1a'
                    });
                }
                if (errBox) {
                    errBox.classList.remove('hidden');
                    document.getElementById('testErrorText').innerText = connError;
                    errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-wait');
                    btn.innerHTML = originalBtnHtml;
                }
            }
        };

        window.testTelegramData = async function() {
            const tokenInput = document.getElementById('telegram_bot_token');
            const chatIdInput = document.getElementById('telegram_chat_id');
            const token = tokenInput ? tokenInput.value.trim() : '';
            const chatId = chatIdInput ? chatIdInput.value.trim() : '';

            const resBox = document.getElementById('testResultBox');
            const errBox = document.getElementById('testErrorBox');
            if (resBox) resBox.classList.add('hidden');
            if (errBox) errBox.classList.add('hidden');

            if (!token) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Token Belum Diisi',
                        text: 'Silakan isi Token Bot Telegram terlebih dahulu.',
                        confirmButtonColor: '#00346f'
                    });
                } else {
                    alert('Token Belum Diisi: Silakan isi Token Bot Telegram terlebih dahulu.');
                }
                tokenInput?.focus();
                return;
            }
            if (!chatId) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chat ID Belum Diisi',
                        text: 'Silakan isi Target Chat ID / ID Grup terlebih dahulu.',
                        confirmButtonColor: '#00346f'
                    });
                } else {
                    alert('Chat ID Belum Diisi: Silakan isi Target Chat ID / ID Grup terlebih dahulu.');
                }
                chatIdInput?.focus();
                return;
            }

            const btn = document.getElementById('btnTestTelegramData');
            const originalBtnHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-wait');
                btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">sync</span><span>Mengirim Data SKPD...</span>';
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Mengirim Data Simulasi...',
                    text: 'Mengirim notifikasi rekonsiliasi kas riil ke Telegram Anda...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            try {
                const res = await fetch('{{ route('pengaturan.telegram.test-data') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        telegram_bot_token: token,
                        telegram_chat_id: chatId
                    })
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Berhasil Terkirim!',
                            html: `<p class="text-sm font-semibold">${data.message}</p><p class="text-xs text-slate-500 mt-2">Silakan periksa notifikasi masuk pada aplikasi Telegram di ponsel Anda.</p>`,
                            confirmButtonColor: '#10b981'
                        });
                    }
                    if (resBox) {
                        resBox.classList.remove('hidden');
                        document.getElementById('testResultText').innerText = data.message;
                        resBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                    if (errBox) errBox.classList.add('hidden');
                } else {
                    const errorMsg = data.message || 'Gagal mengirim data rekonsiliasi ke Telegram.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pengiriman Gagal',
                            text: errorMsg,
                            confirmButtonColor: '#ba1a1a'
                        });
                    }
                    if (errBox) {
                        errBox.classList.remove('hidden');
                        document.getElementById('testErrorText').innerText = errorMsg;
                        errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                    if (resBox) resBox.classList.add('hidden');
                }
            } catch (e) {
                const connError = 'Terjadi kesalahan komunikasi dengan server: ' + e.message;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan',
                        text: connError,
                        confirmButtonColor: '#ba1a1a'
                    });
                }
                if (errBox) {
                    errBox.classList.remove('hidden');
                    document.getElementById('testErrorText').innerText = connError;
                    errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-wait');
                    btn.innerHTML = originalBtnHtml;
                }
            }
        };
    </script>
</x-app-layout>
