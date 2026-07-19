<x-app-layout>
    <div class="max-w-[1200px] mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Manajemen Pengguna</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola hak akses Admin dan Operator untuk setiap SKPD.</p>
            </div>
            <a href="{{ route('user.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded flex items-center space-x-2 hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm self-start md:self-auto font-label-sm text-label-sm">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Tambah Pengguna</span>
            </a>
        </div>
        
        <!-- Filters -->
        <div class="bg-surface p-4 rounded border border-outline-variant shadow-sm flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-body-md font-bold text-on-surface mb-1">Filter by SKPD</label>
                <select class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua SKPD</option>
                    <option value="diknas">Dinas Pendidikan</option>
                    <option value="dinkes">Dinas Kesehatan</option>
                    <option value="pu">Dinas Pekerjaan Umum</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block font-body-md font-bold text-on-surface mb-1">Filter by Role</label>
                <select class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua Peran</option>
                    <option value="admin">Admin</option>
                    <option value="operator">Operator</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="h-10 px-4 border border-outline-variant rounded bg-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm flex items-center space-x-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    <span>Apply Filters</span>
                </button>
            </div>
        </div>
        
        <!-- Table Data -->
        <div class="bg-surface rounded border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Nama Lengkap</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Username</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">SKPD</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Peran</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Status</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($users as $user)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-body-md text-on-surface">{{ $user->name }}</td>
                            <td class="py-3 px-4 font-data-tabular text-data-tabular text-on-surface-variant">{{ $user->username }}</td>
                            <td class="py-3 px-4 font-body-md text-on-surface">{{ $user->skpd ? $user->skpd->nama : 'BKAD Pusat' }}</td>
                            <td class="py-3 px-4 font-body-md text-on-surface">{{ ucfirst($user->role) }}</td>
                            <td class="py-3 px-4">
                                @if($user->status)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-secondary/10 text-secondary font-label-sm text-label-sm">
                                    <span class="material-symbols-outlined text-[14px] mr-1">check_circle</span>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-surface-variant text-on-surface-variant font-label-sm text-label-sm">
                                    <span class="material-symbols-outlined text-[14px] mr-1">block</span>
                                    Non-Aktif
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('user.edit', $user->id) }}" class="inline-block text-primary hover:text-primary-container p-1 mx-1 transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:text-error-container p-1 mx-1 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-on-surface-variant">Belum ada data Pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="border-t border-outline-variant p-4 bg-surface-container-lowest">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
