<x-app-layout>
    <div class="max-w-[700px] mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-headline-md font-headline-md font-bold text-on-surface mb-1">Ubah Password</h2>
            <p class="text-body-md text-on-surface-variant">Perbarui password akun Anda untuk menjaga keamanan.</p>
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

        <!-- Form Card -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <div class="bg-surface-container-low border-b border-outline-variant p-4">
                <h3 class="text-body-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined">key</span>
                    Form Ubah Password
                </h3>
            </div>
            
            <form action="{{ route('password.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    
                    <div class="border-t border-outline-variant pt-6">
                        <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Password Baru</label>
                        <input type="password" name="password" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <p class="text-body-sm text-on-surface-variant mt-1">Minimal 8 karakter.</p>
                    </div>

                    <div>
                        <label class="block text-label-sm font-label-sm text-on-surface font-semibold mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-outline-variant flex justify-end">
                    <button type="submit" class="px-8 py-2 bg-primary text-on-primary rounded-lg font-label-sm font-bold shadow hover:bg-primary/90 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
