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
                    <li class="text-amber-500 uppercase">TAMBAH ADMIN</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">TAMBAH ADMIN BARU</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic text-xs uppercase font-black">
                Daftarkan akun administrator baru untuk cabang minimarket
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
            <span class="material-symbols-outlined absolute -top-4 -right-4 text-9xl text-on-surface/5 pointer-events-none">person_add</span>

            <form action="{{ route('superadmin.admins.store') }}" method="POST" class="space-y-10 relative z-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                    <!-- Basic Info Section -->
                    <div class="md:col-span-2 flex items-center gap-3 border-b-2 border-stone-800 pb-4">
                        <span class="material-symbols-outlined text-amber-500">person</span>
                        <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Informasi Identitas</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase"
                            placeholder="MISAL: BUDI SANTOSO">
                        @error('name') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="w-full bg-stone-950 pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-amber-500 focus:outline-none focus:border-amber-500 transition-all font-mono font-black uppercase"
                            placeholder="MISAL: ADMIN_CABANG_01">
                        @error('username') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold"
                            placeholder="email@contoh.com">
                        @error('email') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Assignment Section -->
                    <div class="md:col-span-2 flex items-center gap-3 border-b-2 border-stone-800 pb-4 mt-4">
                        <span class="material-symbols-outlined text-amber-500">storefront</span>
                        <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Penugasan Cabang</h3>
                    </div>

                    <div class="md:col-span-2">
                        <label for="minimarket_id" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Lokasi Penempatan</label>
                        <select name="minimarket_id" id="minimarket_id" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-black uppercase">
                            <option value="">-- PILIH CABANG MINIMARKET --</option>
                            @foreach($minimarkets as $mm)
                                <option value="{{ $mm->id }}" {{ old('minimarket_id') == $mm->id ? 'selected' : '' }}>
                                    {{ strtoupper($mm->name) }} ({{ $mm->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('minimarket_id') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Security Section -->
                    <div class="md:col-span-2 flex items-center gap-3 border-b-2 border-stone-800 pb-4 mt-4">
                        <span class="material-symbols-outlined text-amber-500">lock</span>
                        <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Keamanan Akun</h3>
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                        @error('password') <p class="mt-2 text-xs text-red-400 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-10 border-t-2 border-stone-800 mt-10">
                    <a href="{{ route('superadmin.admins.index') }}" class="text-xs font-black text-stone-500 hover:text-white uppercase tracking-widest transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-10 py-4 font-black text-sm uppercase flex items-center gap-3">
                        <span class="material-symbols-outlined">person_add</span>
                        Simpan Data Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>