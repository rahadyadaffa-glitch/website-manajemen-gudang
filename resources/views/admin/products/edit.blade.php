<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="inline-flex items-center self-start px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-black rounded-xl hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest mr-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <div>
                <nav class="flex text-xs font-black uppercase tracking-widest text-gray-400 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">DASHBOARD</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li><a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="hover:text-blue-600 transition-colors">KELOLA PRODUK</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-blue-600">EDIT PRODUK</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    EDIT PRODUK
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Perbarui informasi detail produk di sistem</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.products.update', array_merge([$product], request()->only(['date', 'category_id']))) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Product Image -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30">
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-tight">Foto Produk</h3>
                        </div>
                        <div class="p-6">
                            <div class="aspect-square bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center relative overflow-hidden group">
                                @if($product->image_path)
                                    <img id="preview-image" src="{{ asset('storage/' . $product->image_path) }}" class="absolute inset-0 w-full h-full object-cover">
                                @else
                                    <img id="preview-image" class="absolute inset-0 w-full h-full object-cover hidden">
                                    <div id="upload-placeholder" class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center px-4">Upload Foto Baru</p>
                                    </div>
                                @endif
                                <input type="file" name="image" onchange="previewFile()" id="image-input" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-4 font-medium text-center">Format: JPG, PNG. Max: 2MB.</p>
                            @error('image') <p class="text-xs text-red-600 mt-1 text-center font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Right: Product Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30">
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-tight">Informasi Dasar</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Produk</label>
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    @error('name') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kategori</label>
                                    <select name="category_id" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
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
                                    @error('category_id') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">SKU</label>
                                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    @error('sku') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Barcode (Opsional)</label>
                                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    @error('barcode') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Deskripsi Produk</label>
                                <textarea name="description" rows="4"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ old('description', $product->description) }}</textarea>
                                @error('description') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30">
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-tight">Pengaturan Stok & Satuan</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Satuan (Unit)</label>
                                    <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Contoh: Pcs, Box, Kg">
                                    @error('unit') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Batas Stok Minimum</label>
                                    <input type="number" name="min_stock_threshold" value="{{ old('min_stock_threshold', $product->min_stock_threshold) }}" required min="0"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    @error('min_stock_threshold') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="px-8 py-3 bg-white border border-gray-200 text-gray-600 text-xs font-black rounded-xl hover:bg-gray-50 transition-all uppercase tracking-widest">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white text-xs font-black rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 uppercase tracking-widest">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

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
</x-app-layout>
