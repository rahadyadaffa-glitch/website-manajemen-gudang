<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-900">Input Barang Masuk</h1>
        <p class="text-sm text-gray-500 mt-1">Catat penambahan stok barang dari supplier</p>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="{{ route('user.input.masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Utama</label>
                        <select id="parent_category_id" onchange="handleParentChange()" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}">{{ strtoupper($parent->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sub-Kategori</label>
                        <select id="category_id" onchange="fetchProducts()" disabled 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all opacity-50 cursor-not-allowed">
                            <option value="">Pilih Kategori Utama Dulu</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Produk <span id="loading-products" class="hidden text-blue-500 text-xs ml-2">Memuat...</span></label>
                    <select name="product_id" id="product_id" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all select2">
                        <option value="">Cari produk...</option>
                    </select>
                    @error('product_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Qty)</label>
                        <input type="number" name="quantity" id="quantity" min="1" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-xl font-bold"
                            placeholder="0">
                        @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti / Nota (Optional)</label>
                        <input type="file" name="proof_image" id="proof_image" 
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                        @error('proof_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="3" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Contoh: Dari Supplier PT XYZ, Kondisi Baik"></textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const categoryData = @json($categories);

        function handleParentChange() {
            const parentId = document.getElementById('parent_category_id').value;
            const subSelect = document.getElementById('category_id');
            
            subSelect.innerHTML = '<option value="">Semua Sub-Kategori</option>';
            
            if (parentId) {
                const parent = categoryData.find(c => c.id === parentId);
                if (parent && parent.children) {
                    parent.children.forEach(child => {
                        const opt = document.createElement('option');
                        opt.value = child.id;
                        opt.text = child.name.toUpperCase();
                        subSelect.add(opt);
                    });
                }
                subSelect.disabled = false;
                subSelect.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                subSelect.disabled = true;
                subSelect.classList.add('opacity-50', 'cursor-not-allowed');
                subSelect.innerHTML = '<option value="">Pilih Kategori Utama Dulu</option>';
            }

            fetchProducts();
        }

        function fetchProducts() {
            const categoryId = document.getElementById('category_id').value;
            const productSelect = document.getElementById('product_id');
            const loading = document.getElementById('loading-products');
            
            loading.classList.remove('hidden');
            productSelect.innerHTML = '<option value="">Memuat produk...</option>';
            
            fetch(`{{ route('user.api.products') }}?category_id=${categoryId}`)
                .then(res => res.json())
                .then(products => {
                    productSelect.innerHTML = '<option value="">Cari produk (Nama atau SKU)...</option>';
                    products.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.text = `${p.name} (${p.sku})`;
                        productSelect.add(opt);
                    });
                    loading.classList.add('hidden');
                    // Refresh select2 if initialized
                    if(typeof jQuery !== 'undefined' && jQuery(productSelect).hasClass('select2-hidden-accessible')) {
                        jQuery(productSelect).trigger('change');
                    }
                })
                .catch(err => {
                    console.error('Failed fetching products', err);
                    loading.classList.add('hidden');
                    productSelect.innerHTML = '<option value="">Gagal memuat. Silakan muat ulang.</option>';
                });
        }

        // initial fetch on load
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize select2 with a custom matcher or default
            if(typeof jQuery !== 'undefined') {
                $('#product_id').select2({
                    placeholder: "Cari produk (Nama atau SKU)...",
                    allowClear: true,
                    width: '100%'
                });
            }
            fetchProducts();
        });
    </script>
    @endpush
</x-app-layout>
