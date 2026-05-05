<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-amber-500 transition-colors uppercase">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('superadmin.admins.index') }}" class="hover:text-amber-500 transition-colors uppercase">KELOLA ADMIN</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">EDIT AKUN</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">EDIT: {{ $admin->name }}</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic text-xs uppercase font-black">
                Perbarui profil dan hak akses admin cabang
            </p>
        </div>
        <a href="{{ route('superadmin.admins.index') }}"
            class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-xs uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-surface-container pixel-box p-8 md:p-12 relative overflow-hidden">
            <!-- Decorative Icon -->
            <span class="material-symbols-outlined absolute -top-4 -right-4 text-9xl text-on-surface/5 pointer-events-none">manage_accounts</span>

            <form action="{{ route('superadmin.admins.update', $admin) }}" method="POST" class="space-y-10 relative z-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                    <!-- Basic Info Section -->
                    <div class="md:col-span-2 flex items-center gap-3 border-b-2 border-stone-800 pb-4">
                        <span class="material-symbols-outlined text-amber-500">person</span>
                        <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Informasi Identitas</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase">
                        @error('name') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $admin->username) }}" required
                            class="w-full bg-stone-950 pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-amber-500 focus:outline-none focus:border-amber-500 transition-all font-mono font-black uppercase">
                        @error('username') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                        @error('email') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Assignment Section -->
                    <div class="md:col-span-2 flex items-center gap-3 border-b-2 border-stone-800 pb-4 mt-4">
                        <span class="material-symbols-outlined text-amber-500">storefront</span>
                        <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Penugasan & Status</h3>
                    </div>

                    <div>
                        <label for="minimarket_id" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Lokasi Penempatan</label>
                        <select name="minimarket_id" id="minimarket_id" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-black uppercase">
                            @foreach($minimarkets as $mm)
                                <option value="{{ $mm->id }}" {{ old('minimarket_id', $admin->minimarket_id) == $mm->id ? 'selected' : '' }}>
                                    {{ strtoupper($mm->name) }} ({{ $mm->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('minimarket_id') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="is_active" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Status Aktivasi Akun</label>
                        <select name="is_active" id="is_active" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-black uppercase">
                            <option value="1" {{ old('is_active', $admin->is_active) == 1 ? 'selected' : '' }}>AKTIF / TERVALIDASI</option>
                            <option value="0" {{ old('is_active', $admin->is_active) == 0 ? 'selected' : '' }}>NON-AKTIF / SUSPENDED</option>
                        </select>
                        @error('is_active') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Security Section -->
                    <div class="md:col-span-2 flex items-center gap-3 border-b-2 border-stone-800 pb-4 mt-4">
                        <span class="material-symbols-outlined text-amber-500">security</span>
                        <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Keamanan (Opsional)</h3>
                    </div>

                    <div class="md:col-span-2">
                        <div class="bg-amber-500/5 pixel-border border-2 border-amber-500/20 p-4 mb-6">
                            <p class="text-xs text-amber-500 font-bold uppercase italic">
                                * Biarkan kolom password kosong jika Anda tidak bermaksud mengubah kata sandi saat ini.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                        @error('password') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-10 border-t-2 border-stone-800 mt-10">
                    <a href="{{ route('superadmin.admins.index') }}" class="text-xs font-black text-stone-500 hover:text-white uppercase tracking-widest transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-10 py-4 font-black text-sm uppercase flex items-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Perbarui Data Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>