<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">Input Barang Masuk</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit">
                Catat penambahan stok barang dari supplier
            </p>
        </div>
        <a href="{{ route('dashboard') }}"
            class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-[10px] uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-surface-container pixel-border p-6 md:p-10">
            <form action="{{ route('user.input.masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-6">
                    <div>
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Kategori Utama</label>
                        <select id="parent_category_id" onchange="handleParentChange()"
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}">{{ strtoupper($parent->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Sub-Kategori</label>
                        <select id="category_id" onchange="fetchProducts()"
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase">
                            <option value="">Semua Sub-Kategori</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="product_id" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">
                        Pilih Produk <span id="loading-products" class="hidden text-amber-500 text-[10px] ml-2 animate-pulse">Memuat...</span>
                    </label>
                    <select name="product_id" id="product_id" required
                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all select2-custom">
                        <option value="">Cari produk...</option>
                    </select>
                    @error('product_id') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-6">
                    <div>
                        <label for="quantity" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Jumlah (Qty)</label>
                        <div class="relative">
                            <input type="number" name="quantity" id="quantity" min="1" required
                                class="w-full bg-stone-950 pixel-border border-2 border-outline-variant px-4 py-4 text-3xl font-black text-amber-500 focus:outline-none focus:border-amber-500 transition-all text-center"
                                placeholder="0">
                        </div>
                        @error('quantity') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="proof_image" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Foto Bukti / Nota (Optional)</label>
                        <div class="pixel-border border-2 border-dashed border-outline-variant p-4 bg-background/50 hover:bg-background/80 transition-all cursor-pointer relative group">
                            <input type="file" name="proof_image" id="proof_image"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex flex-col items-center justify-center text-on-surface-variant group-hover:text-amber-500">
                                <span class="material-symbols-outlined text-4xl mb-2">add_a_photo</span>
                                <span class="text-[10px] font-black uppercase">Pilih File</span>
                            </div>
                        </div>
                        @error('proof_image') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label for="notes" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Alasan Masuk</label>
                    <select name="notes" id="notes" required
                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase">
                        <option value="">Pilih Alasan Masuk...</option>
                        <option value="Stok Baru (Supplier)">Stok Baru (Supplier)</option>
                        <option value="Retur dari Customer">Retur dari Customer</option>
                        <option value="Koreksi Stok (Tambah)">Koreksi Stok (Tambah)</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('notes') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div id="custom-notes-wrapper" class="mb-8 hidden">
                    <label for="custom_notes" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Sebutkan Alasan Lainnya</label>
                    <textarea name="custom_notes" id="custom_notes" rows="3"
                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all"
                        placeholder="Tuliskan alasan lengkap..."></textarea>
                    @error('custom_notes') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between pt-6 border-t-4 border-surface-variant">
                    <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-on-surface-variant hover:text-on-surface uppercase tracking-widest">
                        Batal
                    </a>
                    <button type="submit"
                        class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-10 py-4 font-black text-sm uppercase flex items-center gap-2">
                        <span class="material-symbols-outlined">save</span>
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
                const parent = categoryData.find(c => c.id == parentId);
                if (parent && parent.children) {
                    parent.children.forEach(child => {
                        const opt = document.createElement('option');
                        opt.value = child.id;
                        opt.text = child.name.toUpperCase();
                        subSelect.add(opt);
                    });
                }
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
                    productSelect.innerHTML = '<option value="">CARI PRODUK (NAMA ATAU SKU)...</option>';
                    products.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.text = `${p.name} (${p.sku})`.toUpperCase();
                        productSelect.add(opt);
                    });
                    
                    // Re-initialize Select2 to pick up new options
                    if ($.fn.select2) {
                        $('.select2-custom').trigger('change');
                    }
                    
                    loading.classList.add('hidden');
                })
                .catch(err => {
                    console.error('Failed fetching products', err);
                    loading.classList.add('hidden');
                    productSelect.innerHTML = '<option value="">Gagal memuat. Silakan muat ulang.</option>';
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Select2
            if ($.fn.select2) {
                $('.select2-custom').select2({
                    width: '100%',
                    placeholder: 'CARI PRODUK...',
                    allowClear: true
                });
            }

            document.getElementById('notes').addEventListener('change', function() {
                const wrapper = document.getElementById('custom-notes-wrapper');
                const customInput = document.getElementById('custom_notes');
                if (this.value === 'Lainnya') {
                    wrapper.classList.remove('hidden');
                    customInput.required = true;
                } else {
                    wrapper.classList.add('hidden');
                    customInput.required = false;
                }
            });
            fetchProducts();
        });
    </script>
    @endpush
</x-app-layout>
