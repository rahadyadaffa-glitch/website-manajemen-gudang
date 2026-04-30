<x-app-layout>
    <x-slot name="header">
        <nav class="mb-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('admin.products.index') }}" class="hover:text-blue-600 transition-colors">Produk</a></li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Tambah Produk</li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900">Tambah Produk Baru</h1>
    </x-slot>

    <div class="max-w-4xl">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="Masukkan nama produk">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Optional)</label>
                            <textarea name="description" id="description" rows="4" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="Detail informasi produk...">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                <select name="category_id" id="category_id" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all select2">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit', 'pcs') }}" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: pcs, box, kg">
                                @error('unit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Identitas & Stok</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono">
                                @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">Barcode (Optional)</label>
                                <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono">
                                @error('barcode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="min_stock_threshold" class="block text-sm font-medium text-gray-700 mb-2">Threshold Stok Minimum</label>
                                <input type="number" name="min_stock_threshold" id="min_stock_threshold" value="{{ old('min_stock_threshold', 10) }}" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                @error('min_stock_threshold') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Panel (Image) -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Foto Produk</h3>
                        <div class="space-y-4">
                            <div class="aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl flex items-center justify-center overflow-hidden relative group">
                                <img id="preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <div id="placeholder" class="text-center p-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-xs text-gray-500">Klik untuk upload foto (Max 2MB)</p>
                                </div>
                                <input type="file" name="image" id="image" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this)">
                            </div>
                            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="font-bold mb-2">Simpan Produk</h3>
                        <p class="text-xs text-blue-100 mb-6">Pastikan data yang diinput sudah sesuai dengan katalog master.</p>
                        <button type="submit" class="w-full py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-md">
                            Tambah Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="block text-center mt-4 text-sm font-medium text-blue-200 hover:text-white transition-colors">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Kategori",
                allowClear: true,
                width: '100%'
            });
        });

        function previewImage(input) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-app-layout>
