<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-amber-500 transition-colors uppercase">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('superadmin.products.index') }}" class="hover:text-amber-500 transition-colors uppercase">MASTER PRODUCTS</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">EDIT PRODUK</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">EDIT: {{ $product->name }}</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic text-xs">
                Perbarui informasi data master produk di sistem pusat
            </p>
        </div>
        <a href="{{ route('superadmin.products.index') }}"
            class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-[10px] uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('superadmin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Product Image -->
                <div class="lg:col-span-1">
                    <div class="bg-surface-container pixel-box p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-amber-500 text-sm">image</span>
                            <h3 class="text-[10px] font-black text-on-surface uppercase tracking-widest">Foto Produk</h3>
                        </div>
                        
                        <div class="aspect-square bg-stone-950 pixel-border border-2 border-dashed border-stone-800 flex flex-col items-center justify-center relative overflow-hidden group">
                            @if($variant->image_path)
                                <img id="preview-image" src="{{ asset('storage/' . $variant->image_path) }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <img id="preview-image" class="absolute inset-0 w-full h-full object-cover hidden">
                                <div id="upload-placeholder" class="flex flex-col items-center text-on-surface-variant group-hover:text-amber-500 transition-colors">
                                    <span class="material-symbols-outlined text-4xl mb-2">add_a_photo</span>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-center px-4">Upload Foto Baru</p>
                                </div>
                            @endif
                            <input type="file" name="image" onchange="previewFile()" id="image-input" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        </div>
                        <p class="text-[9px] text-stone-500 mt-4 font-bold text-center uppercase tracking-tighter italic">Format: JPG, PNG. Max: 2MB.</p>
                        @error('image') <p class="text-[10px] text-red-400 mt-2 font-black uppercase italic text-center">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Right: Product Details -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-surface-container pixel-box p-6 md:p-8">
                        <div class="flex items-center gap-2 mb-8 border-b-2 border-stone-800 pb-4">
                            <span class="material-symbols-outlined text-amber-500">info</span>
                            <h3 class="text-xs font-black text-on-surface uppercase tracking-widest">Informasi Dasar</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Nama Produk</label>
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                        class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase">
                                    @error('name') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-span-2 md:col-span-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Berat/Ukuran</label>
                                        <input type="number" step="0.01" name="weight_value" value="{{ old('weight_value', $variant->weight_value) }}"
                                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                                        @error('weight_value') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Satuan Berat</label>
                                        <select name="weight_unit"
                                            class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-[10px] text-on-surface focus:outline-none focus:border-amber-500 transition-all font-black uppercase">
                                            <option value="">Pilih</option>
                                            <option value="gr" {{ old('weight_unit', $variant->weight_unit) == 'gr' ? 'selected' : '' }}>GR</option>
                                            <option value="kg" {{ old('weight_unit', $variant->weight_unit) == 'kg' ? 'selected' : '' }}>KG</option>
                                            <option value="ml" {{ old('weight_unit', $variant->weight_unit) == 'ml' ? 'selected' : '' }}>ML</option>
                                            <option value="l" {{ old('weight_unit', $variant->weight_unit) == 'l' ? 'selected' : '' }}>L</option>
                                            <option value="pcs" {{ old('weight_unit', $variant->weight_unit) == 'pcs' ? 'selected' : '' }}>PCS</option>
                                        </select>
                                        @error('weight_unit') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Kategori</label>
                                    <select name="category_id" required
                                        class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-black uppercase">
                                        @foreach($categories as $parent)
                                            <optgroup label="{{ strtoupper($parent->name) }}">
                                                @foreach($parent->children as $child)
                                                    <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                                        {{ strtoupper($child->name) }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('category_id') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">SKU</label>
                                    <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" required
                                        class="w-full bg-stone-950 pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-amber-500 font-mono font-black focus:outline-none focus:border-amber-500 transition-all uppercase">
                                    @error('sku') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Barcode (Opsional)</label>
                                    <input type="text" name="barcode" value="{{ old('barcode', $variant->barcode) }}"
                                        class="w-full bg-stone-950 pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-amber-500 font-mono font-black focus:outline-none focus:border-amber-500 transition-all">
                                    @error('barcode') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Satuan Stok (Unit Kecil)</label>
                                    <input type="text" name="unit" value="{{ old('unit', $variant->unit) }}" required
                                        class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase">
                                    @error('unit') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="p-6 bg-amber-500/10 pixel-border border-2 border-amber-500/20">
                                <div class="flex items-center gap-2 mb-6">
                                    <span class="material-symbols-outlined text-amber-500 text-sm">settings_input_component</span>
                                    <h4 class="text-xs font-black text-amber-500 uppercase tracking-widest">Konversi Unit (Grosir ke Eceran)</h4>
                                </div>
                                
                                <div class="flex items-center gap-4">
                                    <!-- Fixed "1 DUS" -->
                                    <div class="flex-1">
                                        <div class="bg-stone-950 pixel-border border-2 border-stone-800 px-4 py-3 flex items-center justify-between opacity-60">
                                            <span class="text-2xl font-black text-stone-600">1</span>
                                            <span class="text-[10px] font-black text-stone-600 uppercase">DUS</span>
                                        </div>
                                    </div>

                                    <div class="text-2xl font-black text-amber-500">=</div>

                                    <!-- Editable "X PCS" -->
                                    <div class="flex-[2]">
                                        <div class="relative">
                                            <input type="number" name="pcs_per_dus" value="{{ old('pcs_per_dus', $variant->pcs_per_dus) }}" required min="1"
                                                class="w-full bg-stone-950 pixel-border border-2 border-amber-500/50 px-4 py-3 text-2xl font-black text-amber-500 focus:outline-none focus:border-amber-500 transition-all text-center">
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-amber-500/50 uppercase">{{ strtoupper($variant->unit ?? 'PCS') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center gap-2 text-stone-500 italic text-[10px] font-bold">
                                    <span class="material-symbols-outlined text-sm">help</span>
                                    <span>Tentukan berapa banyak <strong>{{ strtoupper($variant->unit ?? 'PCS') }}</strong> dalam 1 <strong>DUS</strong>.</span>
                                </div>
                                @error('pcs_per_dus') <p class="text-[10px] text-red-400 mt-2 font-black uppercase italic">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Deskripsi Produk</label>
                                <textarea name="description" rows="3"
                                    class="w-full bg-background pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all">{{ old('description', $product->description) }}</textarea>
                                @error('description') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Batas Stok Minimum</label>
                                <input type="number" name="min_stock_threshold" value="{{ old('min_stock_threshold', $variant->min_stock_threshold) }}" required min="0"
                                    class="w-full bg-stone-950 pixel-border border-2 border-stone-800 px-4 py-3 text-sm text-amber-500 font-black focus:outline-none focus:border-amber-500 transition-all">
                                @error('min_stock_threshold') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('superadmin.products.index') }}" class="text-[10px] font-black text-stone-500 hover:text-white uppercase tracking-widest transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-10 py-4 font-black text-sm uppercase flex items-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Perbarui Master Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewFile() {
            const preview = document.getElementById('preview-image');
            const placeholder = document.getElementById('upload-placeholder');
            const file = document.getElementById('image-input').files[0];
            const reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    </script>
    @endpush
</x-app-layout>
