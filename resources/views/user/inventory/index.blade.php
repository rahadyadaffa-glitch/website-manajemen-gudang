<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <h1 class="font-headline-lg text-headline-lg uppercase flex items-center gap-2">
                <span class="text-amber-500">Input Barang</span>
                @if($type === 'in')
                    <span class="text-secondary tracking-tighter">MASUK</span>
                @else
                    <span class="text-red-500 tracking-tighter">KELUAR</span>
                @endif
            </h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic text-xs">
                @if($type === 'in')
                    Catat penambahan stok barang di sistem inventaris
                @else
                    Catat pengurangan stok barang di sistem inventaris
                @endif
            </p>
        </div>
        <a href="javascript:history.back()"
            class="pixel-btn bg-surface-variant text-on-surface px-6 py-3 font-label-sm text-xs uppercase font-black flex items-center gap-2">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-surface-container pixel-box p-6 md:p-10">
            <form action="{{ route('user.inventory.store') }}" method="POST" enctype="multipart/form-data" id="inventory-form">
                @csrf
                
                <!-- Type Selection -->
                <div class="grid grid-cols-2 gap-4 mb-10">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="type" value="in" {{ $type == 'in' ? 'checked' : '' }} onchange="handleTypeChange()" class="peer hidden">
                        <div class="pixel-btn p-4 flex flex-col items-center gap-2 transition-all bg-surface-variant text-on-surface-variant peer-checked:bg-secondary peer-checked:text-stone-950 group-hover:scale-[1.02]">
                            <span class="material-symbols-outlined text-3xl">add_box</span>
                            <span class="font-black text-[10px] uppercase tracking-widest">Barang Masuk</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="type" value="out" {{ $type == 'out' ? 'checked' : '' }} onchange="handleTypeChange()" class="peer hidden">
                        <div class="pixel-btn p-4 flex flex-col items-center gap-2 transition-all bg-surface-variant text-on-surface-variant peer-checked:bg-red-500 peer-checked:text-stone-950 group-hover:scale-[1.02]">
                            <span class="material-symbols-outlined text-3xl">indeterminate_check_box</span>
                            <span class="font-black text-[10px] uppercase tracking-widest">Barang Keluar</span>
                        </div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-6">
                    <div>
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Kategori Utama</label>
                        <select id="parent_category_id" onchange="handleParentChange()"
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase select2-searchable">
                            <option value="">CARI KATEGORI...</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}">{{ strtoupper($parent->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Sub-Kategori</label>
                        <select id="category_id" onchange="fetchProducts()"
                            class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase select2-searchable">
                            <option value="">CARI SUB-KATEGORI...</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="product_id_select" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">
                        1. Pilih Nama Produk <span id="loading-products" class="hidden text-amber-500 text-[10px] ml-2 animate-pulse font-black uppercase">Scanning Database...</span>
                    </label>
                    <select id="product_id_select" onchange="fetchVariants()"
                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all select2-custom">
                        <option value="">CARI PRODUK...</option>
                    </select>
                </div>

                <div id="variant-section" class="mb-6 hidden">
                    <label for="product_variant_id" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">
                        2. Pilih Ukuran/Berat <span id="loading-variants" class="hidden text-amber-500 text-[10px] ml-2 animate-pulse font-black uppercase">Loading Variants...</span>
                    </label>
                    <select name="product_variant_id" id="product_variant_id" required onchange="handleVariantChange()"
                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all select2-custom">
                        <option value="">PILIH UKURAN...</option>
                    </select>
                    @error('product_variant_id') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div id="quantity-section" class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-6 hidden">
                    <div class="md:col-span-1">
                        <label for="input_unit" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Satuan Input</label>
                        <select name="input_unit" id="input_unit" required onchange="updateQtyPreview()"
                            class="w-full h-[76px] bg-stone-950 pixel-border border-2 border-outline-variant px-4 text-sm text-amber-500 font-black focus:outline-none focus:border-amber-500 transition-all uppercase">
                            <option value="pcs">PCS (ECERAN)</option>
                            <option value="dus">DUS (GROSIR)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="quantity_input" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Jumlah (Qty)</label>
                        <div class="relative">
                            <input type="number" name="quantity_input" id="quantity_input" step="0.01" min="0.01" required oninput="updateQtyPreview()"
                                class="w-full bg-stone-950 pixel-border border-2 border-outline-variant px-4 py-4 text-3xl font-black text-amber-500 focus:outline-none focus:border-amber-500 transition-all text-center"
                                placeholder="0">
                            <div id="qty-preview" class="absolute bottom-2 right-4 text-[8px] font-black text-stone-500 uppercase">
                                Total: <span id="total-pcs-preview">0</span> Pcs
                            </div>
                        </div>
                        @error('quantity_input') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="proof_image" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Foto Bukti / Nota</label>
                    <div class="pixel-border border-2 border-dashed border-outline-variant p-4 bg-background/50 hover:bg-background/80 transition-all cursor-pointer relative group h-[88px] flex items-center justify-center">
                        <input type="file" name="proof_image" id="proof_image"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center justify-center text-on-surface-variant group-hover:text-amber-500 transition-colors">
                            <span class="material-symbols-outlined text-3xl mb-1">add_a_photo</span>
                            <span class="text-[9px] font-black uppercase">Pilih File Image</span>
                        </div>
                    </div>
                    @error('proof_image') <p class="mt-1 text-[10px] text-red-400 font-bold uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-8">
                    <label for="notes" class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Alasan / Catatan</label>
                    <select name="notes" id="notes" required
                        class="w-full bg-background pixel-border border-2 border-outline-variant px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-amber-500 transition-all uppercase select2-searchable">
                        <option value="">Pilih Alasan...</option>
                        <!-- Options will be populated via JS based on type -->
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

                <div class="flex items-center justify-between pt-8 border-t-4 border-surface-variant">
                    <a href="javascript:history.back()" class="text-[10px] font-black text-on-surface-variant hover:text-on-surface uppercase tracking-widest transition-colors">
                        Batal
                    </a>
                    <button type="submit" id="submit-btn"
                        class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-12 py-4 font-black text-sm uppercase flex items-center gap-3 transition-all">
                        <span class="material-symbols-outlined">save</span>
                        <span id="submit-label">Simpan Transaksi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmation-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-stone-950/80 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="bg-surface-container-high pixel-box w-full max-w-lg relative z-10 p-8">
            <div class="flex items-center gap-3 mb-6 border-b-4 border-amber-500 pb-4">
                <span class="material-symbols-outlined text-amber-500 text-3xl">task_alt</span>
                <h2 class="text-xl font-black text-on-surface uppercase">Konfirmasi Transaksi</h2>
            </div>
            
            <p class="text-sm text-on-surface-variant mb-8 italic">Apakah Anda yakin data yang diisi sudah benar? Pastikan semua informasi berikut sesuai.</p>
            
            <div class="space-y-4 bg-stone-950/50 p-6 pixel-border mb-8">
                <div class="flex justify-between border-b border-white/5 pb-2">
                    <span class="text-[10px] font-black text-stone-300 uppercase">Jenis Transaksi</span>
                    <span id="preview-type" class="text-xs font-black uppercase"></span>
                </div>
                <div class="flex justify-between border-b border-white/5 pb-2">
                    <span class="text-[10px] font-black text-stone-300 uppercase">Nama Produk</span>
                    <span id="preview-product" class="text-xs font-black text-white uppercase"></span>
                </div>
                <div class="flex justify-between border-b border-white/5 pb-2">
                    <span class="text-[10px] font-black text-stone-300 uppercase">Jumlah</span>
                    <span id="preview-qty" class="text-lg font-black text-white"></span>
                </div>
                <div class="flex justify-between border-b border-white/5 pb-2">
                    <span class="text-[10px] font-black text-stone-300 uppercase">Kategori</span>
                    <span id="preview-category" class="text-xs font-black text-white uppercase"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-black text-stone-300 uppercase">Alasan / Catatan</span>
                    <span id="preview-notes" class="text-xs text-white italic"></span>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 pixel-btn bg-surface-variant text-on-surface py-4 font-black uppercase text-xs">
                    Periksa Kembali
                </button>
                <button type="button" onclick="confirmSubmit()" class="flex-1 pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-950 py-4 font-black uppercase text-xs flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">send</span>
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const categoryData = @json($categories);
        const reasons = {
            in: [
                "Pengiriman dari Supplier",
                "Retur dari Customer",
                "Koreksi Stok (Tambah)",
                "Lainnya"
            ],
            out: [
                "Barang Rusak",
                "Barang Expired",
                "Retur ke Supplier",
                "Koreksi Stok (Kurang)",
                "Operasional Toko",
                "Lainnya"
            ]
        };

        function closeModal() {
            document.getElementById('confirmation-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openModal() {
            const form = document.getElementById('inventory-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const typeInput = document.querySelector('input[name="type"]:checked');
            const productSelect = document.getElementById('product_id_select');
            const variantSelect = document.getElementById('product_variant_id');
            const qtyInput = document.getElementById('quantity_input');
            const unitSelect = document.getElementById('input_unit');
            const parentCatSelect = document.getElementById('parent_category_id');
            const subCatSelect = document.getElementById('category_id');
            const notesSelect = document.getElementById('notes');
            const customNotesInput = document.getElementById('custom_notes');

            document.getElementById('preview-type').innerText = typeInput.value === 'in' ? 'BARANG MASUK' : 'BARANG KELUAR';
            document.getElementById('preview-type').className = `text-xs font-black uppercase ${typeInput.value === 'in' ? 'text-secondary' : 'text-red-500'}`;
            document.getElementById('preview-product').innerText = `${productSelect.options[productSelect.selectedIndex].text} (${variantSelect.options[variantSelect.selectedIndex].text})`;
            
            const totalPcs = document.getElementById('total-pcs-preview').innerText;
            document.getElementById('preview-qty').innerText = `${qtyInput.value} ${unitSelect.value.toUpperCase()} (${totalPcs} PCS)`;
            
            document.getElementById('preview-category').innerText = `${parentCatSelect.options[parentCatSelect.selectedIndex].text} > ${subCatSelect.options[subCatSelect.selectedIndex].text}`;
            
            let notes = notesSelect.value;
            if (notes === 'Lainnya') notes = customNotesInput.value;
            document.getElementById('preview-notes').innerText = notes || '-';

            document.getElementById('confirmation-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function confirmSubmit() {
            document.getElementById('inventory-form').submit();
        }

        function handleTypeChange() {
            const typeInput = document.querySelector('input[name="type"]:checked');
            if (!typeInput) return;
            const type = typeInput.value;
            const currentSlug = "{{ $slug }}";
            const targetSlug = type === 'in' ? 'inputmasuk' : 'inputkeluar';
            
            if (currentSlug !== targetSlug) {
                window.location.href = `{{ url('user/inventory/create') }}/${targetSlug}`;
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            const submitLabel = document.getElementById('submit-label');
            const notesSelect = document.getElementById('notes');

            if (type === 'out') {
                submitBtn.classList.remove('bg-amber-500', 'hover:bg-amber-400');
                submitBtn.classList.add('bg-red-500', 'hover:bg-red-400');
                submitLabel.innerText = "Catat Barang Keluar";
            } else {
                submitBtn.classList.add('bg-amber-500', 'hover:bg-amber-400');
                submitBtn.classList.remove('bg-red-500', 'hover:bg-red-400');
                submitLabel.innerText = "Simpan Transaksi";
            }

            notesSelect.innerHTML = '<option value="">Pilih Alasan...</option>';
            reasons[type].forEach(reason => {
                const opt = document.createElement('option');
                opt.value = reason;
                opt.text = reason.toUpperCase();
                notesSelect.add(opt);
            });

            fetchProducts();
        }

        function handleParentChange() {
            const parentId = document.getElementById('parent_category_id').value;
            const subSelect = document.getElementById('category_id');
            
            subSelect.innerHTML = '<option value="">CARI SUB-KATEGORI...</option>';
            
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
            if ($.fn.select2) {
                $('#category_id').trigger('change');
            }
        }

        function fetchProducts() {
            const categoryId = document.getElementById('category_id').value;
            const productSelect = document.getElementById('product_id_select');
            const loading = document.getElementById('loading-products');
            
            loading.classList.remove('hidden');
            productSelect.innerHTML = '<option value="">Memuat produk...</option>';
            
            fetch(`{{ route('user.api.products') }}?category_id=${categoryId}`)
                .then(res => res.json())
                .then(products => {
                    productSelect.innerHTML = '<option value="">CARI PRODUK...</option>';
                    products.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.text = p.name.toUpperCase();
                        productSelect.add(opt);
                    });
                    
                    if ($.fn.select2) {
                        $('#product_id_select').trigger('change');
                    }
                    loading.classList.add('hidden');
                    resetSections();
                })
                .catch(err => {
                    console.error('Fetch products error:', err);
                    loading.classList.add('hidden');
                    alert('Gagal mengambil daftar produk. Silakan refresh halaman.');
                });
        }

        let currentVariants = [];

        function fetchVariants() {
            const productId = document.getElementById('product_id_select').value;
            const variantSelect = document.getElementById('product_variant_id');
            const variantSection = document.getElementById('variant-section');
            const loading = document.getElementById('loading-variants');
            const typeInput = document.querySelector('input[name="type"]:checked').value;

            if (!productId) {
                resetSections();
                return;
            }

            loading.classList.remove('hidden');
            variantSection.classList.remove('hidden');
            
            fetch(`{{ url('user/api/products') }}/${productId}/variants?type=${typeInput}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(variants => {
                    currentVariants = variants;
                    variantSelect.innerHTML = '<option value="">PILIH UKURAN...</option>';
                    variants.forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.id;
                        opt.text = v.weight_label.toUpperCase();
                        variantSelect.add(opt);
                    });
                    
                    if ($.fn.select2) {
                        $('#product_variant_id').trigger('change');
                    }
                })
                .catch(err => {
                    console.error('Fetch variants error:', err);
                    alert('Gagal mengambil varian produk. Pastikan koneksi stabil.');
                })
                .finally(() => {
                    loading.classList.add('hidden');
                });
        }

        function handleVariantChange() {
            const variantId = document.getElementById('product_variant_id').value;
            const qtySection = document.getElementById('quantity-section');
            
            if (variantId) {
                qtySection.classList.remove('hidden');
                updateQtyPreview();
            } else {
                qtySection.classList.add('hidden');
            }
        }

        function updateQtyPreview() {
            const variantId = document.getElementById('product_variant_id').value;
            const unit = document.getElementById('input_unit').value;
            const qtyInput = document.getElementById('quantity_input').value || 0;
            const previewSpan = document.getElementById('total-pcs-preview');
            
            const variant = currentVariants.find(v => v.id == variantId);
            if (variant) {
                const factor = unit === 'dus' ? variant.pcs_per_dus : 1;
                previewSpan.innerText = Math.round(qtyInput * factor);
            }
        }

        function resetSections() {
            document.getElementById('variant-section').classList.add('hidden');
            document.getElementById('quantity-section').classList.add('hidden');
            document.getElementById('product_variant_id').innerHTML = '<option value="">PILIH UKURAN...</option>';
        }

        document.addEventListener('DOMContentLoaded', () => {
            handleTypeChange();

            if ($.fn.select2) {
                $('.select2-custom, .select2-searchable').select2({
                    width: '100%',
                    placeholder: 'CARI...',
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

            document.getElementById('inventory-form').onsubmit = function(e) {
                e.preventDefault();
                openModal();
            };
        });
    </script>
    @endpush
</x-app-layout>
