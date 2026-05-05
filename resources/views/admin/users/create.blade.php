<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-amber-500 transition-colors uppercase">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('admin.users.index') }}" class="hover:text-amber-500 transition-colors uppercase">KELOLA USER</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">TAMBAH PETUGAS</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">TAMBAH PETUGAS BARU</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic">
                Daftarkan petugas baru untuk mengelola operasional gudang cabang
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-[10px] uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-surface-container pixel-box p-6 md:p-8">
            <div class="flex items-center gap-2 mb-8 border-b-2 border-outline-variant pb-4">
                <span class="material-symbols-outlined text-primary">person_add</span>
                <h3 class="text-xs font-black text-on-surface uppercase tracking-widest">Formulir Data Petugas</h3>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase"
                            placeholder="BUDI SANTOSO">
                        @error('name') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" required
                                class="w-full bg-stone-950 pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-amber-500 font-mono font-black focus:outline-none focus:border-amber-500 transition-all uppercase"
                                placeholder="BUDI_STAFF">
                            @error('username') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold"
                                placeholder="BUDI@EXAMPLE.COM">
                            @error('email') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t-2 border-outline-variant border-dashed">
                        <div>
                            <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Password</label>
                            <input type="password" name="password" required
                                class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all"
                                placeholder="••••••••">
                            @error('password') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex items-center justify-between border-t-4 border-surface-variant">
                    <a href="{{ route('admin.users.index') }}" class="text-[10px] font-black text-on-surface-variant hover:text-on-surface uppercase tracking-widest">
                        Batal
                    </a>
                    <button type="submit" class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-10 py-4 font-black text-sm uppercase flex items-center gap-2">
                        <span class="material-symbols-outlined">person_add</span>
                        Simpan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
