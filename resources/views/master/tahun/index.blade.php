<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2">Master Tahun Anggaran</h2>
            <p class="text-body-md font-body-md text-on-surface-variant">Kelola daftar tahun anggaran yang dapat digunakan oleh user saat login.</p>
        </div>
    </div>

    <!-- Add Form -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Tambah Tahun Anggaran</h3>
        <form action="{{ route('tahun.store') }}" method="POST" class="flex gap-4 items-end">
            @csrf
            <div class="flex-1">
                <label for="tahun" class="block text-label-sm font-label-sm text-on-surface mb-1">Tahun <span class="text-error">*</span></label>
                <input type="number" id="tahun" name="tahun" min="2000" max="2099" required value="{{ old('tahun', date('Y')) }}"
                    class="h-10 px-3 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md font-body-md w-full transition-all outline-none">
            </div>
            <div class="flex-1 flex items-center h-10">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                    class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-background bg-surface-bright cursor-pointer">
                <label for="is_active" class="ml-2 block font-body-md text-body-md text-on-surface cursor-pointer">
                    Status Aktif (Tersedia untuk Login)
                </label>
            </div>
            <button type="submit" class="h-10 px-6 rounded-lg bg-primary text-on-primary font-label-sm text-label-sm hover:bg-primary/90 transition-colors shadow-sm">
                Simpan
            </button>
        </form>
    </div>

    <!-- Data List -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-4 px-6 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider w-24 text-center">No</th>
                        <th class="py-4 px-6 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Tahun</th>
                        <th class="py-4 px-6 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                        <th class="py-4 px-6 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md divide-y divide-outline-variant/50">
                    @forelse($tahunAnggarans as $index => $ta)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6 text-on-surface text-center">{{ $index + 1 }}</td>
                        <td class="py-4 px-6 text-on-surface font-semibold text-lg">{{ $ta->tahun }}</td>
                        <td class="py-4 px-6 text-center">
                            @if($ta->is_active)
                                <span class="bg-secondary-container/30 text-on-secondary-container px-3 py-1 rounded-full text-label-sm font-label-sm font-semibold">Aktif</span>
                            @else
                                <span class="bg-surface-container-high text-on-surface-variant px-3 py-1 rounded-full text-label-sm font-label-sm font-semibold">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Form Toggle Status -->
                                <form action="{{ route('tahun.update', $ta->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    @if(!$ta->is_active)
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" class="p-2 text-secondary hover:bg-secondary/10 rounded transition-colors" title="Aktifkan">
                                            <span class="material-symbols-outlined text-[20px]">toggle_off</span>
                                        </button>
                                    @else
                                        <button type="submit" class="p-2 text-primary hover:bg-primary/10 rounded transition-colors" title="Non-aktifkan">
                                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">toggle_on</span>
                                        </button>
                                    @endif
                                </form>

                                <!-- Form Delete -->
                                <form action="{{ route('tahun.destroy', $ta->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Tahun Anggaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-error hover:bg-error/10 rounded transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 px-6 text-center text-on-surface-variant text-body-lg">
                            Belum ada data Tahun Anggaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
