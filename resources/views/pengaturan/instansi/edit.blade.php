<x-app-layout>
    <div class="mb-6 pb-4 border-b border-outline-variant flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-primary mb-1">
                <span class="material-symbols-outlined">edit_document</span>
                <h2 class="text-headline-md font-headline-md text-on-surface">Edit Pengaturan Instansi</h2>
            </div>
            <p class="text-body-md font-body-md text-on-surface-variant">Perbarui informasi kop surat dan penanda tangan laporan</p>
        </div>
    </div>

    <form action="{{ route('pengaturan.instansi.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col mb-24">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col mb-8">
            <div class="p-6 grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Bagian Form -->
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">domain</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">INFORMASI SKPD & KOP SURAT</h3>
                    </div>
                    <div class="space-y-4">
                        @if(auth()->user()->role === 'admin')
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="logo_file">Logo Aplikasi (Hanya Admin) <span class="text-error">*</span></label>
                            <input class="h-10 p-1.5 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-sm font-body-md w-full transition-all outline-none" 
                                id="logo_file" name="logo_file" type="file" accept="image/*" />
                            <p class="text-[11px] text-on-surface-variant mt-1">Kosongkan jika tidak ingin mengubah logo.</p>
                            @error('logo_file') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        @endif
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="isi_kop">Isi Kop Surat <span class="text-error">*</span></label>
                            <textarea class="p-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none resize-y" 
                                id="isi_kop" name="isi_kop" rows="5">{{ old('isi_kop', $pengaturan->isi_kop) }}</textarea>
                            <p class="text-[11px] text-on-surface-variant mt-1">Gunakan tanda pemisah <code class="text-error bg-error-container/30 px-1 rounded">|</code> untuk ganti baris.</p>
                            @error('isi_kop') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Bagian Preview -->
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col h-full">
                        <div class="flex items-center justify-between border-b border-outline-variant/50 pb-2 mb-2">
                            <h3 class="text-label-sm font-label-sm text-on-surface-variant">Preview Tampilan</h3>
                            <span class="text-[10px] font-semibold tracking-wider uppercase text-outline bg-surface-container-high px-2 py-0.5 rounded">Live Preview</span>
                        </div>
                        <div class="flex-1 bg-surface border border-outline-variant rounded-lg p-6 flex flex-col items-center shadow-sm relative overflow-hidden group">
                            <div class="w-full max-w-[500px] flex gap-4 items-center border-b-[3px] border-black pb-4">
                                <div class="w-20 h-20 shrink-0 flex items-center justify-center grayscale opacity-80">
                                    @php
                                        $logoAppPreview = ($pengaturan && $pengaturan->logo) 
                                            ? (Str::startsWith($pengaturan->logo, 'http') ? $pengaturan->logo : asset('storage/' . $pengaturan->logo)) 
                                            : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGQglX4a91lGBKJ3x84BjayBzB86CFjav3SqOK5oE63MWbYO2Qcazq0aldyUiq4O4QUHgyHX3dIYsy_YZxQrgNA3gnZu-9IDh5PBQyqlamviMO9EYFfXzj-ZmB1cLlx2nTyOGUzDWwaUmkCW2sxkgnhAFG2520U_AyWNIov7XjxkjfYKcEDsZudVlfdUva_l58gAIdKZlkfCSf_qyyKiJjlMlPtKy6VdEbjqUDxlo92seLSowz38NN';
                                    @endphp
                                    <img id="preview_logo" class="max-w-full max-h-full object-contain" src="{{ $logoAppPreview }}">
                                </div>
                                <div id="preview_kop" class="flex-1 text-center font-serif flex flex-col text-black">
                                    <!-- Diisi oleh Javascript -->
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none flex items-center justify-center backdrop-blur-[1px]">
                                <span class="material-symbols-outlined text-primary/40" style="font-size: 48px;">visibility</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-2">
                <div class="h-px w-full bg-outline-variant border-dashed"></div>
            </div>

            <!-- Lower Forms Grid -->
            <div class="p-6 grid grid-cols-1 xl:grid-cols-2 gap-8 gap-y-12">
                <!-- Section 2: DATA KEPALA SKPD -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">badge</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">DATA KEPALA SKPD (MENGETAHUI)</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nama_kepala">Nama Lengkap <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="nama_kepala" name="nama_kepala" type="text" value="{{ old('nama_kepala', $pengaturan->nama_kepala) }}">
                            @error('nama_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nip_kepala">NIP <span class="text-error">*</span></label>
                            <input class="font-data-tabular text-data-tabular h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 w-full transition-all outline-none" 
                                id="nip_kepala" name="nip_kepala" type="text" value="{{ old('nip_kepala', $pengaturan->nip_kepala) }}">
                            @error('nip_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="pangkat_kepala">Pangkat <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="pangkat_kepala" name="pangkat_kepala" type="text" value="{{ old('pangkat_kepala', $pengaturan->pangkat_kepala) }}">
                            @error('pangkat_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="jabatan_kepala">Jabatan <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="jabatan_kepala" name="jabatan_kepala" type="text" value="{{ old('jabatan_kepala', $pengaturan->jabatan_kepala) }}">
                            @error('jabatan_kepala') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: DATA BENDAHARA -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">account_balance_wallet</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">DATA BENDAHARA (PEMBUATAN LAPORAN)</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nama_bendahara">Nama Lengkap <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="nama_bendahara" name="nama_bendahara" type="text" value="{{ old('nama_bendahara', $pengaturan->nama_bendahara) }}">
                            @error('nama_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nip_bendahara">NIP <span class="text-error">*</span></label>
                            <input class="font-data-tabular text-data-tabular h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 w-full transition-all outline-none" 
                                id="nip_bendahara" name="nip_bendahara" type="text" value="{{ old('nip_bendahara', $pengaturan->nip_bendahara) }}">
                            @error('nip_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="pangkat_bendahara">Pangkat <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="pangkat_bendahara" name="pangkat_bendahara" type="text" value="{{ old('pangkat_bendahara', $pengaturan->pangkat_bendahara) }}">
                            @error('pangkat_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="jabatan_bendahara">Jabatan <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="jabatan_bendahara" name="jabatan_bendahara" type="text" value="{{ old('jabatan_bendahara', $pengaturan->jabatan_bendahara) }}">
                            @error('jabatan_bendahara') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: DATA KASUBAG -->
                <div class="flex flex-col gap-4 xl:col-span-2 max-w-2xl mx-auto w-full">
                    <div class="flex items-center gap-2 text-primary border-b border-outline-variant/50 pb-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">person_check</span>
                        <h3 class="text-label-sm font-label-sm uppercase tracking-wider text-primary">DATA KASUBAG KEUANGAN (MENYETUJUI)</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nama_kasubag">Nama Lengkap <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="nama_kasubag" name="nama_kasubag" type="text" value="{{ old('nama_kasubag', $pengaturan->nama_kasubag) }}">
                            @error('nama_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="text-label-sm font-label-sm text-on-surface" for="nip_kasubag">NIP <span class="text-error">*</span></label>
                            <input class="font-data-tabular text-data-tabular h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 w-full transition-all outline-none" 
                                id="nip_kasubag" name="nip_kasubag" type="text" value="{{ old('nip_kasubag', $pengaturan->nip_kasubag) }}">
                            @error('nip_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="pangkat_kasubag">Pangkat <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="pangkat_kasubag" name="pangkat_kasubag" type="text" value="{{ old('pangkat_kasubag', $pengaturan->pangkat_kasubag) }}">
                            @error('pangkat_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-label-sm font-label-sm text-on-surface" for="jabatan_kasubag">Jabatan <span class="text-error">*</span></label>
                            <input class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none" 
                                id="jabatan_kasubag" name="jabatan_kasubag" type="text" value="{{ old('jabatan_kasubag', $pengaturan->jabatan_kasubag) }}">
                            @error('jabatan_kasubag') <span class="text-error text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 md:left-64 right-0 bg-surface-container-lowest border-t border-outline-variant p-4 shadow-[0_-4px_12px_rgba(0,0,0,0.05)] z-10 flex justify-end gap-3 px-8">
            <button class="px-6 py-2.5 bg-primary text-on-primary hover:bg-primary/90 rounded-lg font-label-sm text-label-sm transition-colors shadow-sm flex items-center gap-2" type="submit">
                <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                Update Perubahan
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kopInput = document.getElementById('isi_kop');
            const logoInput = document.getElementById('logo');
            const previewKop = document.getElementById('preview_kop');
            const previewLogo = document.getElementById('preview_logo');

            function updatePreview() {
                const lines = kopInput.value.split('|').filter(line => line.trim() !== '');
                
                let html = '';
                lines.forEach((line, index) => {
                    if (index === 0) {
                        html += `<span class="text-sm font-bold tracking-wide uppercase">${line}</span>`;
                    } else if (index === 1) {
                        html += `<span class="text-lg font-black tracking-wider uppercase leading-tight">${line}</span>`;
                    } else if (index === 2) {
                        html += `<span class="text-[11px] mt-1 text-on-surface-variant">${line}</span>`;
                    } else {
                        html += `<span class="text-[11px] text-on-surface-variant">${line}</span>`;
                    }
                });
                
                previewKop.innerHTML = html;
                previewLogo.src = logoInput.value || 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGQglX4a91lGBKJ3x84BjayBzB86CFjav3SqOK5oE63MWbYO2Qcazq0aldyUiq4O4QUHgyHX3dIYsy_YZxQrgNA3gnZu-9IDh5PBQyqlamviMO9EYFfXzj-ZmB1cLlx2nTyOGUzDWwaUmkCW2sxkgnhAFG2520U_AyWNIov7XjxkjfYKcEDsZudVlfdUva_l58gAIdKZlkfCSf_qyyKiJjlMlPtKy6VdEbjqUDxlo92seLSowz38NN';
            }

            kopInput.addEventListener('input', updatePreview);
            logoInput.addEventListener('input', updatePreview);
            
            // Initial render
            updatePreview();
        });
    </script>
</x-app-layout>
