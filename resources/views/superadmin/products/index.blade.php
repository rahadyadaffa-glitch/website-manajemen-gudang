<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-amber-500/30 mb-8">
        <div>
            <nav class="flex text-xs font-black uppercase tracking-widest text-stone-500 mb-2">
                <ol class="inline-flex items-center space-x-1">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-amber-500 transition-colors">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">MASTER PRODUCTS</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">MASTER DATA PRODUK</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-amber-500/5 py-2 w-fit italic text-sm">
                Pusat pengaturan master data barang untuk seluruh sistem
            </p>
        </div>
        <a href="{{ route('superadmin.products.create') }}"
            class="pixel-btn bg-amber-500 text-stone-950 px-6 py-3 font-black text-xs uppercase flex items-center gap-2 hover:bg-amber-400 transition-colors">
            <span class="material-symbols-outlined text-xl">add_box</span>
            Tambah Produk Baru
        </a>
    </div>

    <!-- AJAX Search & Filter -->
    <div class="bg-surface-container pixel-border p-6 mb-8">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 flex items-center bg-stone-950 pixel-input group">
                <span class="material-symbols-outlined pl-4 text-stone-500 group-focus-within:text-amber-500">search</span>
                <input type="text" id="search-input" value="{{ request('search') }}"
                    class="w-full bg-transparent border-none text-on-surface pl-3 pr-4 py-3 focus:ring-0 font-bold text-sm uppercase"
                    placeholder="Ketik untuk mencari Nama Produk atau SKU..." />
            </div>
            <select id="category-filter"
                class="bg-stone-900 border-2 border-stone-800 text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase min-w-[250px]">
                <option value="">Semua Kategori</option>
                @foreach($categories as $parent)
                    <optgroup label="{{ strtoupper($parent->name) }}">
                        @foreach($parent->children as $child)
                            <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>
                                {{ strtoupper($child->name) }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <button onclick="resetFilters()" class="pixel-btn bg-stone-800 text-white px-8 py-3 font-black text-xs uppercase hover:bg-stone-700 transition-colors">
                Reset
            </button>
        </div>
    </div>

    <!-- Product List Container -->
    <div id="product-container" class="relative min-h-[400px]">
        <div id="loading-spinner" class="absolute inset-0 bg-stone-950/50 backdrop-blur-[1px] z-10 flex items-center justify-center opacity-0 pointer-events-none transition-opacity">
            <div class="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <div id="product-list">
            @include('superadmin.products.partials._product_list', ['products' => $products])
        </div>
    </div>

    @push('scripts')
    <script>
        let filters = {
            search: "{{ request('search', '') }}",
            category_id: "{{ request('category_id', '') }}"
        };

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            let debounceTimer;

            const performFetch = () => {
                const spinner = document.getElementById('loading-spinner');
                const list = document.getElementById('product-list');
                
                spinner.classList.remove('opacity-0', 'pointer-events-none');

                const params = new URLSearchParams(filters);
                fetch(`{{ route('superadmin.products.index') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    list.innerHTML = html;
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                    window.history.replaceState(null, '', `?${params.toString()}`);
                })
                .catch(error => {
                    console.error('Product fetch failed:', error);
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                });
            };

            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    filters.search = searchInput.value;
                    performFetch();
                }, 400);
            });

            categoryFilter.addEventListener('change', () => {
                filters.category_id = categoryFilter.value;
                performFetch();
            });

            // AJAX Pagination
            document.addEventListener('click', (e) => {
                if (e.target.closest('.ajax-pagination a')) {
                    e.preventDefault();
                    const url = new URL(e.target.closest('a').href);
                    filters.page = url.searchParams.get('page');
                    performFetch();
                    window.scrollTo({ top: document.getElementById('product-container').offsetTop - 100, behavior: 'smooth' });
                }
            });

            window.resetFilters = () => {
                filters = { search: '', category_id: '' };
                searchInput.value = '';
                categoryFilter.value = '';
                performFetch();
            };
        });
    </script>
    @endpush
</x-app-layout>
