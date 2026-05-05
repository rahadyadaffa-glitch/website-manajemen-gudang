<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('superadmin.dashboard') }}" class="hover:text-amber-500 transition-colors uppercase">GLOBAL DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('superadmin.minimarkets.index') }}" class="hover:text-amber-500 transition-colors uppercase">MINIMARKET</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">EDIT MINIMARKET</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">EDIT: {{ $minimarket->name }}</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic">
                Perbarui informasi cabang minimarket dalam jaringan Voxel
            </p>
        </div>
        <a href="{{ route('superadmin.minimarkets.index') }}"
            class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-[10px] uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-surface-container pixel-border p-6 md:p-10">
            <form action="{{ route('superadmin.minimarkets.update', $minimarket) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-6">
                    <div>
                        <label for="name" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Nama Minimarket</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $minimarket->name) }}" required 
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase font-bold"
                            placeholder="NAMA MINIMARKET">
                        @error('name') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Kode Minimarket</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $minimarket->code) }}" required 
                            class="w-full bg-stone-950 pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-amber-500 focus:outline-none focus:border-amber-500 transition-all font-mono uppercase font-black">
                        @error('code') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Alamat Lengkap</label>
                    <textarea name="address" id="address" rows="3" required 
                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all">{{ old('address', $minimarket->address) }}</textarea>
                    @error('address') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-6">
                    <div>
                        <label for="city" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Kota</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $minimarket->city) }}" required 
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase font-bold">
                        @error('city') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="province" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Provinsi</label>
                        <input type="text" name="province" id="province" value="{{ old('province', $minimarket->province) }}" required 
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase font-bold">
                        @error('province') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-8">
                    <div>
                        <label for="phone" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $minimarket->phone) }}" 
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                        @error('phone') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Status</label>
                        <select name="status" id="status" required 
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase">
                            <option value="active" {{ old('status', $minimarket->status) == 'active' ? 'selected' : '' }}>AKTIF</option>
                            <option value="archived" {{ old('status', $minimarket->status) == 'archived' ? 'selected' : '' }}>ARSIP</option>
                        </select>
                        @error('status') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t-4 border-surface-variant">
                    <a href="{{ route('superadmin.minimarkets.index') }}" class="text-[10px] font-black text-on-surface-variant hover:text-on-surface uppercase tracking-widest">
                        Batal
                    </a>
                    <button type="submit" class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-10 py-4 font-black text-sm uppercase flex items-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
