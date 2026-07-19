<x-app-layout>
    <div class="max-w-[900px] mx-auto">
        <!-- Page Header -->
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('transaksi.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-md font-headline-md font-bold text-on-surface mb-1">Upload Dokumen Rekonsiliasi</h2>
                <p class="text-body-md text-on-surface-variant">
                    {{ $transaksi->skpd->nama ?? '-' }} — 
                    {{ date('F', mktime(0, 0, 0, $transaksi->periode_bulan, 10)) }} {{ $transaksi->periode_tahun }}
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-secondary/10 text-secondary border border-secondary/20 p-4 rounded-lg mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-body-md font-body-md">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-error/10 text-error p-4 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="font-label-sm text-label-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transaksi.upload.store', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- 1. BA Manual (Wajib) -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-error/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-error">description</span>
                        </div>
                        <div>
                            <h3 class="text-body-lg font-bold text-on-surface">Berita Acara (Manual)</h3>
                            <p class="text-label-sm text-error font-semibold">★ Wajib Diupload</p>
                        </div>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mb-4">Berita Acara rekonsiliasi bertanda tangan basah.</p>
                    @if($transaksi->file_ba_manual)
                        <div class="bg-secondary/5 border border-secondary/20 rounded-lg p-3 mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-secondary">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                <span class="text-label-sm font-label-sm">File sudah diupload</span>
                            </div>
                            <a href="{{ Storage::url($transaksi->file_ba_manual) }}" target="_blank" class="text-primary hover:text-primary-container text-label-sm font-label-sm underline">Lihat</a>
                        </div>
                    @endif
                    <input type="file" name="file_ba_manual" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-body-md rounded-lg border border-outline-variant bg-surface-container-lowest file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-error/10 file:text-error hover:file:bg-error/20 cursor-pointer">
                </div>

                <!-- 2. Buku Kas Pengeluaran -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">menu_book</span>
                        </div>
                        <div>
                            <h3 class="text-body-lg font-bold text-on-surface">Buku Kas Pengeluaran</h3>
                            <p class="text-label-sm text-on-surface-variant">Opsional</p>
                        </div>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mb-4">Laporan buku kas pengeluaran bendahara.</p>
                    @if($transaksi->file_buku_kas)
                        <div class="bg-secondary/5 border border-secondary/20 rounded-lg p-3 mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-secondary">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                <span class="text-label-sm font-label-sm">File sudah diupload</span>
                            </div>
                            <a href="{{ Storage::url($transaksi->file_buku_kas) }}" target="_blank" class="text-primary hover:text-primary-container text-label-sm font-label-sm underline">Lihat</a>
                        </div>
                    @endif
                    <input type="file" name="file_buku_kas" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-body-md rounded-lg border border-outline-variant bg-surface-container-lowest file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                </div>

                <!-- 3. Buku Pembantu Bank -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-tertiary/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-tertiary">account_balance</span>
                        </div>
                        <div>
                            <h3 class="text-body-lg font-bold text-on-surface">Buku Pembantu Bank</h3>
                            <p class="text-label-sm text-on-surface-variant">Opsional</p>
                        </div>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mb-4">Buku pembantu bank bendahara pengeluaran.</p>
                    @if($transaksi->file_buku_pembantu_bank)
                        <div class="bg-secondary/5 border border-secondary/20 rounded-lg p-3 mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-secondary">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                <span class="text-label-sm font-label-sm">File sudah diupload</span>
                            </div>
                            <a href="{{ Storage::url($transaksi->file_buku_pembantu_bank) }}" target="_blank" class="text-primary hover:text-primary-container text-label-sm font-label-sm underline">Lihat</a>
                        </div>
                    @endif
                    <input type="file" name="file_buku_pembantu_bank" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-body-md rounded-lg border border-outline-variant bg-surface-container-lowest file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-tertiary/10 file:text-tertiary hover:file:bg-tertiary/20 cursor-pointer">
                </div>

                <!-- 4. Rekening Koran Bank -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-secondary">receipt_long</span>
                        </div>
                        <div>
                            <h3 class="text-body-lg font-bold text-on-surface">Rekening Koran Bank</h3>
                            <p class="text-label-sm text-on-surface-variant">Opsional</p>
                        </div>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mb-4">Rekening koran dari bank terkait.</p>
                    @if($transaksi->file_rekening_koran)
                        <div class="bg-secondary/5 border border-secondary/20 rounded-lg p-3 mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-secondary">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                <span class="text-label-sm font-label-sm">File sudah diupload</span>
                            </div>
                            <a href="{{ Storage::url($transaksi->file_rekening_koran) }}" target="_blank" class="text-primary hover:text-primary-container text-label-sm font-label-sm underline">Lihat</a>
                        </div>
                    @endif
                    <input type="file" name="file_rekening_koran" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-body-md rounded-lg border border-outline-variant bg-surface-container-lowest file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20 cursor-pointer">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 mt-8 border-t border-outline-variant pt-6">
                <a href="{{ route('transaksi.index') }}" class="px-6 py-2 border border-outline rounded-lg text-on-surface-variant font-label-sm hover:bg-surface-container transition-colors">
                    Kembali
                </a>
                <button type="submit" class="px-8 py-2 bg-primary text-on-primary rounded-lg font-label-sm font-bold shadow hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">cloud_upload</span>
                    Simpan Dokumen
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
