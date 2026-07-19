<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface">Manajemen Pengumuman</h2>
            <p class="text-body-md font-body-md text-on-surface-variant mt-1">Kelola pengumuman yang akan ditampilkan di dashboard seluruh SKPD.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('pengumuman.create') }}" class="bg-primary hover:bg-primary-fixed-variant text-on-primary px-5 py-2.5 rounded-lg text-label-sm font-label-sm font-semibold shadow-sm transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Pengumuman Baru
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-primary-container text-on-primary-container p-4 rounded-lg mb-6 border border-primary-container/50">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface w-20">ID</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">Judul Pengumuman</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">Status</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">Tanggal</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md divide-y divide-outline-variant/50">
                    @forelse($pengumumans as $p)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="px-6 py-4 text-on-surface-variant">#{{ $p->id }}</td>
                        <td class="px-6 py-4 font-medium text-on-surface">{{ $p->judul }}</td>
                        <td class="px-6 py-4">
                            @if($p->is_aktif)
                                <span class="bg-primary/10 text-primary px-2 py-1 rounded text-xs font-bold uppercase">Aktif</span>
                            @else
                                <span class="bg-error/10 text-error px-2 py-1 rounded text-xs font-bold uppercase">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant">{{ $p->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('pengumuman.edit', $p->id) }}" class="inline-block text-primary hover:text-primary-container p-1 mx-1 transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:text-error-container p-1 mx-1 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">Belum ada pengumuman yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengumumans->hasPages())
        <div class="border-t border-outline-variant p-4">
            {{ $pengumumans->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
