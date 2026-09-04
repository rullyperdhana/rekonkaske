<x-app-layout>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <div class="max-w-[1200px] mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Manajemen Pengguna & Audit Akun</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola hak akses Admin dan Operator untuk setiap SKPD serta pantau ketersediaan akun.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('user.cetak_laporan') }}" target="_blank" class="bg-tertiary text-on-tertiary hover:bg-tertiary/90 px-4 py-2.5 rounded-lg flex items-center space-x-2 transition-colors shadow-sm font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[18px]" data-weight="fill">print</span>
                    <span>Cetak Laporan Audit (PDF)</span>
                </a>
                @endif
                <a href="{{ route('user.create') }}" class="bg-primary text-on-primary hover:bg-primary/90 px-4 py-2.5 rounded-lg flex items-center space-x-2 transition-colors shadow-sm font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>
        
        @if(auth()->user()->role === 'admin')
        @php
            $totalSkpdCount = \App\Models\Skpd::where('status', true)->count();
            $skpdWithUserCount = \App\Models\Skpd::where('status', true)->whereHas('users')->count();
            $skpdWithoutUserCount = $totalSkpdCount - $skpdWithUserCount;
        @endphp
        <!-- Executive Audit Banner untuk Internal Admin -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-xl p-6 shadow-md border border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
            <!-- Decoration background -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex items-start md:items-center gap-4 z-10">
                <div class="p-3 bg-white/10 backdrop-blur rounded-xl text-amber-400 shrink-0 border border-white/10">
                    <span class="material-symbols-outlined text-3xl" data-weight="fill">shield_person</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-white tracking-wide flex items-center gap-2">
                        <span>Pengecekan Internal Akun SKPD</span>
                        <span class="text-[11px] font-mono px-2 py-0.5 bg-amber-400/20 text-amber-300 rounded-full border border-amber-400/30">Admin Only</span>
                    </h3>
                    <p class="text-body-sm text-slate-300 mt-1 leading-relaxed">
                        Dari total <strong class="text-white">{{ $totalSkpdCount }} SKPD Aktif</strong>, tercatat 
                        <span class="text-emerald-400 font-bold bg-emerald-950/50 px-1.5 py-0.5 rounded border border-emerald-500/30">{{ $skpdWithUserCount }} SKPD Sudah Berakun</span> dan 
                        <span class="text-rose-400 font-bold bg-rose-950/50 px-1.5 py-0.5 rounded border border-rose-500/30">{{ $skpdWithoutUserCount }} SKPD Belum Berakun (Kosong)</span>.
                    </p>
                </div>
            </div>
            <div class="z-10 shrink-0">
                <a href="{{ route('user.cetak_laporan') }}" target="_blank" class="px-5 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl flex items-center justify-center gap-2 shadow-lg hover:shadow-amber-500/20 transition-all active:scale-95 text-body-sm">
                    <span class="material-symbols-outlined text-lg" data-weight="fill">print</span>
                    <span>Cetak Rekapan Audit (PDF)</span>
                </a>
            </div>
        </div>
        @endif
        
        <!-- Filters -->
        <form method="GET" action="{{ route('user.index') }}" class="bg-surface p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-body-md font-bold text-on-surface mb-1">Cari Nama / Username</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau username pengguna..." class="w-full h-10 border border-outline-variant rounded-lg pl-9 pr-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                </div>
            </div>
            <div class="w-full md:w-64">
                <label class="block font-body-md font-bold text-on-surface mb-1">Filter SKPD</label>
                <select id="skpd_id" name="skpd_id" class="w-full h-10 border border-outline-variant rounded-lg px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua SKPD</option>
                    @foreach($skpds as $skpd)
                    <option value="{{ $skpd->id }}" {{ request('skpd_id') == $skpd->id ? 'selected' : '' }}>{{ $skpd->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-44">
                <label class="block font-body-md font-bold text-on-surface mb-1">Filter Peran</label>
                <select name="role" class="w-full h-10 border border-outline-variant rounded-lg px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua Peran</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="konsolidator" {{ request('role') == 'konsolidator' ? 'selected' : '' }}>Konsolidator</option>
                    <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-10 px-4 rounded-lg bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-sm text-label-sm font-bold flex items-center space-x-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request('search') || request('skpd_id') || request('role'))
                <a href="{{ route('user.index') }}" class="h-10 px-3.5 rounded-lg border border-outline-variant bg-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm flex items-center space-x-1.5 text-on-surface-variant">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
        
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
    
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('skpd_id')) {
                new TomSelect("#skpd_id", {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            }
        });
    </script>
</x-app-layout>
