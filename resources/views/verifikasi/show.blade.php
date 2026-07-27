<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen - SiReKa</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    @php
        $selisih = abs($transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir);
        $hasSelisih = $selisih > 0;
        
        $bannerClass = $hasSelisih ? 'bg-red-600' : 'bg-green-600';
        $iconClass = $hasSelisih ? 'warning' : 'verified_user';
        $titleText = $hasSelisih ? 'Valid (Terdapat Selisih)' : 'Dokumen Valid';
        $subTextColor = $hasSelisih ? 'text-red-100' : 'text-green-100';
        $btnClass = $hasSelisih ? 'border-red-600 text-red-600 hover:bg-red-50' : 'border-green-600 text-green-600 hover:bg-green-50';
    @endphp
    <div class="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden">
        <div class="{{ $bannerClass }} p-6 text-center text-white">
            <span class="material-symbols-outlined text-6xl mb-2">{{ $iconClass }}</span>
            <h1 class="text-2xl font-bold">{{ $titleText }}</h1>
            <p class="{{ $subTextColor }} text-sm mt-1">Dokumen Berita Acara Rekonsiliasi ini SAH dan tercatat pada sistem SiReKa.</p>
        </div>
        
        <div class="p-6 space-y-4">
            <div>
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide">Instansi (SKPD)</p>
                <p class="text-lg font-medium text-gray-900">{{ $transaksi->skpd->nama ?? '-' }}</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide">Periode</p>
                    <p class="text-base font-medium text-gray-900">{{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide">Rekening Bank</p>
                    <p class="text-base font-medium text-gray-900">{{ $transaksi->rekening->nomor ?? '-' }}<br><span class="text-sm text-gray-500">{{ $transaksi->rekening->bank ?? '' }}</span></p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide mb-3">Nilai Saldo Akhir</p>
                <div class="flex justify-between items-center mb-2 bg-gray-50 p-2 rounded">
                    <span class="text-gray-700">BKU Bendahara:</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                    <span class="text-gray-700">Rekening Bank:</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</span>
                </div>
                @if($hasSelisih)
                <div class="flex justify-between items-center bg-red-50 p-2 rounded mt-2 border border-red-200">
                    <span class="text-red-700 font-semibold">Selisih:</span>
                    <span class="font-bold text-red-700">Rp {{ number_format($selisih, 2, ',', '.') }}</span>
                </div>
                @endif
            </div>
            
            <div class="pt-6 text-center">
                <a href="{{ route('landing') }}" class="inline-block border {{ $btnClass }} font-medium transition-colors rounded-full px-6 py-2 text-sm">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
