<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4 border-b-2 border-primary pb-4">
            <a href="{{ route('rekening.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Tambah Rekening Baru</h2>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1">Masukkan data rekening bank dan relasinya ke SKPD</p>
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

            <form action="{{ route('rekening.store') }}" method="POST" class="space-y-5">
                @csrf
                @if(auth()->user()->role === 'admin')
                <div>
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">SKPD</label>
                    <select name="skpd_id" required class="w-full h-[40px] px-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="">-- Pilih SKPD --</option>
                        @foreach($skpds as $skpd)
                            <option value="{{ $skpd->id }}" {{ old('skpd_id') == $skpd->id ? 'selected' : '' }}>{{ $skpd->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div>
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Nama Rekening</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full h-[40px] px-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Misal: Kas Daerah">
                </div>
                
                <div>
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Nomor Rekening</label>
                    <input type="text" name="nomor" value="{{ old('nomor') }}" required class="w-full h-[40px] px-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Misal: 001.03.01.XXXXX">
                </div>

                <div>
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Bank</label>
                    <input type="text" name="bank" value="{{ old('bank') }}" required class="w-full h-[40px] px-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Misal: Bank Kalsel">
                </div>
                


                <div>
                    <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Status</label>
                    <select name="status" class="w-full h-[40px] px-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('rekening.index') }}" class="px-5 py-2.5 rounded-lg border border-outline-variant text-on-surface hover:bg-surface-container-low transition-colors text-label-sm font-label-sm font-semibold">Batal</a>
                    <button type="submit" class="bg-primary hover:bg-primary-fixed-variant text-on-primary px-5 py-2.5 rounded-lg text-label-sm font-label-sm font-semibold shadow-sm transition-colors">Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
