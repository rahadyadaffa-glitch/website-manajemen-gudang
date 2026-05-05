<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-amber-500 transition-colors">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="hover:text-amber-500 transition-colors uppercase">KELOLA PRODUK</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">TAMBAH PRODUK</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">TAMBAH PRODUK BARU</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic">
                Inisialisasi data produk baru ke dalam sistem inventaris
            </p>
        </div>
        <a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}"
            class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-[10px] uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.products.store', request()->only(['date', 'category_id'])) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
                <!-- Left: Product Image -->
                <div class="lg:col-span-1">
                    <div class="bg-surface-container pixel-box p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-primary text-sm">image</span>
                            <h3 class="text-[10px] font-black text-on-surface uppercase tracking-widest">Foto Produk</h3>
                        </div>
                        
                        <div class="aspect-square bg-stone-950 pixel-border border-2 border-dashed border-outline-variant flex flex-col items-center justify-center relative overflow-hidden group">
                            <img id="preview-image" class="absolute inset-0 w-full h-full object-cover hidden">
                            <div id="upload-placeholder" class="flex flex-col items-center text-on-surface-variant group-hover:text-amber-500 transition-colors">
                                <span class="material-symbols-outlined text-4xl mb-2">add_a_photo</span>
                                <p class="text-[9px] font-black uppercase tracking-widest text-center px-4">Upload Foto</p>
                            </div>
                            <input type="file" name="image" onchange="previewFile()" id="image-input" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        </div>
                        <p class="text-[9px] text-on-surface-variant mt-4 font-bold text-center uppercase tracking-tighter">Format: JPG, PNG. Max: 2MB.</p>
                        @error('image') <p class="text-[10px] text-red-400 mt-2 font-black uppercase italic text-center">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Right: Product Details -->
                <div class="lg:col-span-2 space-y-gutter">
                    <div class="bg-surface-container pixel-box p-6 md:p-8">
                        <div class="flex items-center gap-2 mb-8 border-b-2 border-outline-variant pb-4">
                            <span class="material-symbols-outlined text-primary">info</span>
                            <h3 class="text-xs font-black text-on-surface uppercase tracking-widest">Informasi Dasar</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                     <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Nama Produk</label>
                                     <input type="text" name="name" value="{{ old('name') }}" required
                                         class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase">
                                     @error('name') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                 </div>
                                 <div class="grid grid-cols-2 gap-4">
                                     <div>
                                         <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Berat/Ukuran</label>
                                         <input type="number" step="0.01" name="weight_value" value="{{ old('weight_value') }}"
                                             class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold">
                                         @error('weight_value') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                     </div>
                                     <div>
                                         <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Satuan Berat</label>
                                         <select name="weight_unit"
                                             class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase select2">
                                             <option value="">Pilih</option>
                                             <option value="gr" {{ old('weight_unit') == 'gr' ? 'selected' : '' }}>GR</option>
                                             <option value="kg" {{ old('weight_unit') == 'kg' ? 'selected' : '' }}>KG</option>
                                             <option value="ml" {{ old('weight_unit') == 'ml' ? 'selected' : '' }}>ML</option>
                                             <option value="l" {{ old('weight_unit') == 'l' ? 'selected' : '' }}>L</option>
                                             <option value="pcs" {{ old('weight_unit') == 'pcs' ? 'selected' : '' }}>PCS</option>
                                         </select>
                                         @error('weight_unit') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                     </div>
                                 </div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 <div>
                                     <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Kategori</label>
                                     <select name="category_id" required
                                         class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase select2">
                                         <option value="">Pilih Kategori</option>
                                         @foreach($categories as $parent)
                                             <optgroup label="{{ strtoupper($parent->name) }}">
                                                 @foreach($parent->children as $child)
                                                     <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                                         {{ strtoupper($child->name) }}
                                                     </option>
                                                 @endforeach
                                             </optgroup>
                                         @endforeach
                                     </select>
                                     @error('category_id') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                 </div>
                                 <div>
                                     <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">SKU</label>
                                     <input type="text" name="sku" value="{{ old('sku') }}" required
                                         class="w-full bg-stone-950 pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-amber-500 font-mono font-black focus:outline-none focus:border-amber-500 transition-all uppercase"
                                         placeholder="PROD-001">
                                     @error('sku') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                 </div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 <div>
                                     <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Barcode (Opsional)</label>
                                     <input type="text" name="barcode" value="{{ old('barcode') }}"
                                         class="w-full bg-stone-950 pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-amber-500 font-mono font-black focus:outline-none focus:border-amber-500 transition-all"
                                         placeholder="899XXXXXXXXX">
                                     @error('barcode') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                 </div>
                                 <div>
                                     <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Deskripsi Produk</label>
                                     <input type="text" name="description" value="{{ old('description') }}"
                                         class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all">
                                     @error('description') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                 </div>
                             </div>
                        </div>

                        <div class="mt-12 border-t-2 border-outline-variant pt-8">
                            <div class="flex items-center gap-2 mb-8">
                                <span class="material-symbols-outlined text-primary">settings_suggest</span>
                                <h3 class="text-xs font-black text-on-surface uppercase tracking-widest">Pengaturan Stok & Satuan</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Satuan (Unit)</label>
                                    <input type="text" name="unit" value="{{ old('unit', 'Pcs') }}" required
                                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all font-bold uppercase"
                                        placeholder="Pcs, Box, Kg">
                                    @error('unit') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Batas Stok Minimum</label>
                                    <input type="number" name="min_stock_threshold" value="{{ old('min_stock_threshold', 10) }}" required min="0"
                                        class="w-full bg-stone-950 pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-amber-500 font-black focus:outline-none focus:border-amber-500 transition-all">
                                    @error('min_stock_threshold') <p class="text-[10px] text-red-400 mt-1 font-black uppercase italic">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="text-[10px] font-black text-on-surface-variant hover:text-on-surface uppercase tracking-widest">
                            Batal
                        </a>
                        <button type="submit" class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-10 py-4 font-black text-sm uppercase flex items-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2({
                    width: '100%'
                });
            }
        });

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
