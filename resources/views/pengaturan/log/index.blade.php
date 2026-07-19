<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface">Jejak Audit (Activity Log)</h2>
            <p class="text-body-md font-body-md text-on-surface-variant mt-1">Riwayat lengkap perubahan data di sistem.</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">Waktu</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">User / Pelaku</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">Aksi</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">Modul</th>
                        <th class="px-6 py-4 text-label-sm font-label-sm font-semibold text-on-surface">Detail Perubahan</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md divide-y divide-outline-variant/50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-on-surface-variant text-sm">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-on-surface">
                            {{ $log->causer->name ?? 'Sistem / Otomatis' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($log->event === 'created')
                                <span class="bg-primary-container text-on-primary-container px-2 py-1 rounded text-xs font-bold uppercase">Tambah</span>
                            @elseif($log->event === 'updated')
                                <span class="bg-secondary-container text-on-secondary-container px-2 py-1 rounded text-xs font-bold uppercase">Ubah</span>
                            @elseif($log->event === 'deleted')
                                <span class="bg-error-container text-on-error-container px-2 py-1 rounded text-xs font-bold uppercase">Hapus</span>
                            @else
                                <span class="bg-surface-variant text-on-surface-variant px-2 py-1 rounded text-xs font-bold uppercase">{{ $log->event }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant">
                            {{ class_basename($log->subject_type) }} (#{{ $log->subject_id }})
                        </td>
                        <td class="px-6 py-4 text-sm text-on-surface-variant max-w-md">
                            @if($log->properties && isset($log->properties['attributes']))
                                <button onclick="document.getElementById('modal-{{ $log->id }}').classList.remove('hidden')" class="text-primary hover:underline font-medium">Lihat Detail</button>
                                
                                <!-- Modal Detail Log -->
                                <div id="modal-{{ $log->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                                    <div class="bg-surface-container-lowest rounded-2xl p-6 w-full max-w-2xl max-h-[80vh] overflow-y-auto shadow-xl">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-title-lg font-bold">Detail Perubahan</h3>
                                            <button onclick="document.getElementById('modal-{{ $log->id }}').classList.add('hidden')" class="material-symbols-outlined hover:text-error transition-colors">close</button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            @if(isset($log->properties['old']))
                                                <div>
                                                    <h4 class="font-semibold text-error mb-2 border-b border-error/20 pb-1">Data Lama:</h4>
                                                    <pre class="bg-surface-container p-3 rounded text-xs overflow-x-auto text-on-surface-variant">{{ json_encode($log->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="font-semibold text-primary mb-2 border-b border-primary/20 pb-1">Data Baru:</h4>
                                                <pre class="bg-surface-container p-3 rounded text-xs overflow-x-auto text-on-surface-variant">{{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">Belum ada riwayat aktivitas yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant p-4">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
