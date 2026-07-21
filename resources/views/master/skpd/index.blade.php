<x-app-layout>
    <div class="max-w-container-max mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b-2 border-primary pb-4">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Master SKPD</h2>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1">Kelola data Satuan Kerja Perangkat Daerah (SKPD)</p>
            </div>
            <a href="{{ route('skpd.create') }}" class="bg-primary hover:bg-primary-fixed-variant text-on-primary px-5 py-2.5 rounded-lg text-label-sm font-label-sm flex items-center gap-2 shadow-sm transition-colors">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                Tambah SKPD
            </a>
        </div>
        
        <!-- Action Bar & Table Container -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
            <form action="{{ route('skpd.index') }}" method="GET" class="p-4 border-b border-outline-variant bg-surface-bright flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="relative w-full max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                    <input name="search" value="{{ request('search') }}" class="w-full h-[40px] pl-10 pr-4 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md font-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Cari Kode atau Nama SKPD..." type="text">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="h-[40px] px-4 rounded-lg border border-outline-variant bg-primary text-on-primary hover:bg-primary/90 transition-colors flex items-center gap-2 text-label-sm font-label-sm">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('skpd.index') }}" class="h-[40px] px-4 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container-low transition-colors flex items-center gap-2 text-label-sm font-label-sm">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                        Reset
                    </a>
                    @endif
                </div>
            </form>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant w-16">No</th>
                        <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant w-32 border-l-2 border-outline-variant/30">Kode SKPD</th>
                        <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant border-l border-outline-variant/30">Nama SKPD</th>
                        <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant border-l border-outline-variant/30">Nama Bendahara</th>
                        <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant border-l border-outline-variant/30">No WhatsApp</th>
                        <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant border-l border-outline-variant/30">Status</th>
                        <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant w-28 text-center border-l border-outline-variant/30">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                    @forelse($skpds as $index => $skpd)
                    <tr class="hover:bg-surface-bright transition-colors group">
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface">{{ $skpds->firstItem() + $index }}</td>
                        <td class="py-3 px-4 text-data-tabular font-data-tabular text-on-surface border-l-2 border-outline-variant/10">{{ $skpd->kode }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface font-semibold border-l border-outline-variant/10">{{ $skpd->nama }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface-variant border-l border-outline-variant/10">{{ $skpd->nama_bendahara ?? '-' }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface-variant border-l border-outline-variant/10">{{ $skpd->no_whatsapp ?? '-' }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface-variant border-l border-outline-variant/10">
                            @if($skpd->status)
                                <span class="px-2 py-1 bg-primary/10 text-primary rounded text-xs">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-error/10 text-error rounded text-xs">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 border-l border-outline-variant/10">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('skpd.edit', $skpd->id) }}" class="text-primary hover:bg-primary-container/20 p-1.5 rounded transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('skpd.destroy', $skpd->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus SKPD ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:bg-error-container/50 p-1.5 rounded transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-on-surface-variant">Belum ada data SKPD.</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                {{ $skpds->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
