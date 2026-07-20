<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4 border-b-[3px] border-primary pb-4">
            <a href="{{ route('user.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-lg font-headline-lg text-on-surface">Tambah Pengguna Baru</h1>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1">Buat akun untuk Admin atau Operator</p>
            </div>
        </div>

        <div class="bg-surface rounded border border-outline-variant shadow-sm overflow-hidden p-6">
            @if ($errors->any())
                <div class="bg-error/10 text-error p-4 rounded mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="font-label-sm text-label-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Password</label>
                    <input type="password" name="password" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Peran (Role)</label>
                    <select name="role" required class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="konsolidator" {{ old('role') == 'konsolidator' ? 'selected' : '' }}>Konsolidator</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">SKPD (Kosongkan jika Admin Pusat / Konsolidator)</label>
                    <select name="skpd_id" class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="">-- Pilih SKPD --</option>
                        @foreach($skpds as $skpd)
                            <option value="{{ $skpd->id }}" {{ old('skpd_id') == $skpd->id ? 'selected' : '' }}>{{ $skpd->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">Status</label>
                    <select name="status" class="w-full h-10 px-3 rounded border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('user.index') }}" class="px-5 py-2.5 rounded border border-outline-variant text-on-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm font-semibold">Batal</a>
                    <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container px-5 py-2.5 rounded font-label-sm text-label-sm font-semibold shadow-sm transition-colors">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
