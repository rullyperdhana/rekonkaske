<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-4 border-b-2 border-primary pb-4">
            <a href="{{ route('pengumuman.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Edit Pengumuman</h2>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1">Ubah isi atau status aktif dari pengumuman ini.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden p-6">
            @if ($errors->any())
                <div class="bg-error/10 text-error p-4 rounded-lg mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-label-sm font-label-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Judul Pengumuman</label>
                    <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required class="w-full h-[40px] px-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Misal: Perpanjangan Waktu Rekonsiliasi">
                </div>
                
                <div>
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Isi Pengumuman</label>
                    <textarea name="isi" required rows="5" class="w-full p-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Tulis pesan pengumuman di sini...">{{ old('isi', $pengumuman->isi) }}</textarea>
                </div>

                <div class="flex items-center gap-3 bg-surface-container-low p-4 rounded-lg border border-outline-variant/50">
                    <input type="checkbox" name="is_aktif" value="1" id="is_aktif" class="w-5 h-5 text-primary bg-surface-container border-outline-variant rounded focus:ring-primary focus:ring-2" {{ old('is_aktif', $pengumuman->is_aktif) ? 'checked' : '' }}>
                    <label for="is_aktif" class="text-label-md font-label-md text-on-surface cursor-pointer select-none">Tampilkan pengumuman ini sekarang (Status Aktif)</label>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-outline-variant">
                    <a href="{{ route('pengumuman.index') }}" class="px-5 py-2.5 rounded-lg border border-outline-variant text-on-surface hover:bg-surface-container-low transition-colors text-label-sm font-label-sm font-semibold">Batal</a>
                    <button type="submit" class="bg-primary hover:bg-primary-fixed-variant text-on-primary px-5 py-2.5 rounded-lg text-label-sm font-label-sm font-semibold shadow-sm transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
