<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen Rekonsiliasi - SiReKa</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 py-8">
    @php
        $selisih = abs($transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir);
        $hasSelisih = $selisih > 0.01;
        $isValidKonsolidator = ($transaksi->status_konsolidator === 'valid');
        
        $regNo = 'REG-KONS/TAPIN/' . $transaksi->periode_tahun . '/' . str_pad($transaksi->periode_bulan, 2, '0', STR_PAD_LEFT) . '/' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT);
    @endphp

    <div class="bg-white max-w-lg w-full rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        <!-- Banner Header -->
        <div class="{{ $isValidKonsolidator ? 'bg-gradient-to-r from-emerald-600 to-teal-700' : ($hasSelisih ? 'bg-gradient-to-r from-rose-600 to-red-700' : 'bg-gradient-to-r from-blue-600 to-indigo-700') }} p-6 text-center text-white relative">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-inner mb-3">
                <span class="material-symbols-outlined text-[36px] text-white">
                    {{ $isValidKonsolidator ? 'verified' : ($hasSelisih ? 'warning' : 'task_alt') }}
                </span>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight">
                {{ $isValidKonsolidator ? 'Dokumen Sah & Tervalidasi BKAD' : ($hasSelisih ? 'Validasi SKPD (Ada Selisih Kas)' : 'Dokumen Valid Diverifikasi SKPD') }}
            </h1>
            <p class="text-white/80 text-xs mt-1 max-w-xs mx-auto">
                {{ $isValidKonsolidator ? 'Berita Acara dan 4 Berkas Fisik telah diuji dan disahkan oleh Konsolidator Kas Daerah.' : 'Tercatat resmi dalam database SiReKa Pemerintah Kabupaten Tapin.' }}
            </p>

            @if($isValidKonsolidator)
            <div class="mt-3 inline-block bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-3 py-0.5 text-[11px] font-mono font-bold tracking-wider">
                {{ $regNo }}
            </div>
            @endif
        </div>
        
        <div class="p-6 space-y-5">
            <!-- Box Status Konsolidator -->
            @if($isValidKonsolidator)
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
                <span class="material-symbols-outlined text-emerald-600 text-[24px] shrink-0 mt-0.5">verified_user</span>
                <div class="text-xs">
                    <p class="font-bold text-emerald-900 text-sm">Pengesahan Konsolidator BKAD:</p>
                    <p class="text-emerald-700 mt-0.5">
                        Diperiksa oleh <strong>{{ $transaksi->checker->name ?? 'Konsolidator BKAD' }}</strong> pada {{ $transaksi->checked_at ? \Carbon\Carbon::parse($transaksi->checked_at)->timezone('Asia/Makassar')->format('d F Y, H:i') . ' WITA' : '-' }}.
                    </p>
                    <div class="mt-2 flex items-center gap-1 text-[11px] font-semibold text-emerald-800">
                        <span class="material-symbols-outlined text-[14px]">check</span>
                        <span>4 Bukti Dukung Lengkap &amp; Bebas Tanggungan</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Instansi -->
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Satuan Kerja (SKPD)</p>
                <p class="text-base font-bold text-slate-800 mt-0.5">{{ $transaksi->skpd->nama ?? '-' }}</p>
            </div>
            
            <!-- Grid Info -->
            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                <div>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Periode Rekonsiliasi</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Rekening Kas Bank</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $transaksi->rekening->nomor ?? '-' }}</p>
                    <p class="text-[11px] text-slate-500 font-medium">{{ $transaksi->rekening->bank ?? '' }}</p>
                </div>
            </div>

            <!-- Rincian Saldo -->
            <div class="pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-2.5">Data Saldo Akhir Kas</p>
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-600 font-medium">Saldo Kas BKU (SIPANDA):</span>
                        <span class="font-bold text-slate-900 font-mono text-sm">Rp {{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-600 font-medium">Saldo Rekening Koran Bank:</span>
                        <span class="font-bold text-slate-900 font-mono text-sm">Rp {{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</span>
                    </div>
                    @if($hasSelisih)
                    <div class="flex justify-between items-center p-2.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700">
                        <span class="font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">warning</span> Selisih Kas:
                        </span>
                        <span class="font-extrabold font-mono text-sm">Rp {{ number_format($selisih, 2, ',', '.') }}</span>
                    </div>
                    @else
                    <div class="flex justify-between items-center p-2 bg-emerald-50 text-emerald-700 rounded-xl font-semibold text-[11px]">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">check_circle</span> Status Selisih:
                        </span>
                        <span class="font-bold">SESUAI / KLOP (Rp 0)</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="pt-4 flex flex-col gap-2">
                @if($isValidKonsolidator)
                <a href="{{ route('transaksi.bukti-digital-pdf', $transaksi->id) }}" target="_blank" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    <span>Unduh Surat Tanda Bukti Digital (PDF)</span>
                </a>
                @endif
                <a href="{{ route('landing') }}" class="w-full py-2.5 px-4 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-semibold text-xs text-center transition-colors">
                    Kembali ke Beranda SiReKa
                </a>
            </div>
        </div>
    </div>
</body>
</html>
