<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('superadmin.dashboard') }}" class="hover:text-amber-500 transition-colors">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('superadmin.minimarkets.index') }}" class="hover:text-amber-500 transition-colors">KELOLA MINIMARKET</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">LOG TRANSAKSI</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">
                LOG: {{ $minimarket->name }}
            </h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit uppercase text-xs font-black italic">
                Riwayat lengkap pergerakan barang masuk dan keluar
            </p>
        </div>
        
        <div class="flex flex-col md:flex-row items-end md:items-center gap-4">
            <div class="flex gap-2">
                <button type="button" onclick="updateDate('')"
                   class="date-btn pixel-btn px-6 py-2 font-black text-xs uppercase {{ !request('date') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                    Semua
                </button>
                <button type="button" onclick="updateDate('{{ now()->toDateString() }}')"
                   class="date-btn pixel-btn px-6 py-2 font-black text-xs uppercase {{ request('date') == now()->toDateString() ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                    Hari Ini
                </button>
                <div onclick="document.getElementById('date-input').showPicker()"
                    class="relative pixel-btn bg-surface-container text-on-surface-variant px-6 py-2 font-black text-xs uppercase cursor-pointer {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-primary bg-primary text-stone-950' : '' }}">
                    <input type="date" id="date-input" name="date" value="{{ request('date') }}" onchange="updateDate(this.value)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                    <span id="date-label">
                        {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                    </span>
                </div>
            </div>

            <a href="{{ route('superadmin.minimarkets.index') }}"
                class="pixel-btn bg-surface-variant text-on-surface px-6 py-2 font-black text-xs uppercase flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-gutter mb-8">
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover">
            <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Produk (Terkini)</p>
            <h4 class="text-3xl font-black text-on-surface">{{ number_format($stats['total_products']) }}</h4>
        </div>
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover">
            <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Stok Saat Ini</p>
            <h4 class="text-3xl font-black text-on-surface">{{ number_format($stats['total_stock']) }}</h4>
        </div>
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover border-l-4 border-l-secondary/50">
            <p class="text-[10px] font-black text-secondary uppercase tracking-widest mb-1">Masuk (Periode)</p>
            <h4 class="text-3xl font-black text-on-surface">+{{ number_format($stats['recent_in']) }}</h4>
        </div>
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover border-l-4 border-l-error/50">
            <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Keluar (Periode)</p>
            <h4 class="text-3xl font-black text-on-surface">-{{ number_format($stats['recent_out']) }}</h4>
        </div>
    </div>

    <div class="space-y-gutter">
        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.minimarkets.show', $minimarket) }}" 
               class="pixel-btn px-8 py-3 font-black text-xs uppercase transition-all {{ Route::is('superadmin.minimarkets.show') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant hover:bg-surface-variant' }}">
                STOK BARANG
            </a>
            <a href="{{ route('superadmin.minimarkets.transactions', $minimarket) }}" 
               class="pixel-btn px-8 py-3 font-black text-xs uppercase transition-all {{ Route::is('superadmin.minimarkets.transactions') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant hover:bg-surface-variant' }}">
                LOG TRANSAKSI
            </a>
            <a href="{{ route('superadmin.minimarkets.trend', $minimarket) }}" 
               class="pixel-btn px-8 py-3 font-black text-xs uppercase transition-all {{ Route::is('superadmin.minimarkets.trend') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant hover:bg-surface-variant' }}">
                GRAFIK TREND
            </a>
        </div>

        <!-- Transactions Filter Section -->
        <div class="bg-surface-container pixel-box p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-gutter mb-8">
                <h3 class="font-headline-md text-headline-md text-on-surface uppercase flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl">history</span>
                    Semua Transaksi Cabang
                </h3>
                
                <div class="flex flex-col xl:flex-row items-center gap-4">
                    <div class="flex-1 flex items-center bg-stone-950 pixel-input focus-within:ring-2 focus-within:ring-amber-500/50 transition-all group overflow-hidden w-full sm:min-w-[300px]">
                        <span class="material-symbols-outlined pl-4 text-on-surface-variant group-focus-within:text-amber-500 pointer-events-none">search</span>
                        <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Ketik untuk mencari barang..." 
                            class="w-full bg-transparent border-none text-on-surface pl-3 pr-4 py-3 focus:ring-0 font-black text-xs uppercase">
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <select id="type-filter"
                            class="bg-background border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase min-w-[150px]">
                            <option value="">SEMUA TIPE</option>
                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>BARANG MASUK</option>
                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>BARANG KELUAR</option>
                        </select>

                        <select id="parent-category-filter"
                            class="bg-background border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase min-w-[180px]">
                            <option value="">SEMUA KATEGORI</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent_category_id') == $parent->id ? 'selected' : '' }}>
                                    {{ strtoupper($parent->name) }}
                                </option>
                            @endforeach
                        </select>

                        <select id="category-filter"
                            class="bg-background border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase min-w-[180px]">
                            <option value="">SEMUA SUB-KATEGORI</option>
                            <!-- Options dynamically loaded via JS -->
                        </select>

                        <button type="button" onclick="resetFilters()" class="text-[10px] font-black text-red-400 hover:underline uppercase tracking-widest">Reset</button>
                    </div>
                </div>
            </div>

            <div id="transaction-container" class="relative min-h-[300px]">
                <div id="loading-spinner" class="absolute inset-0 bg-stone-950/50 backdrop-blur-[1px] z-10 flex items-center justify-center opacity-0 pointer-events-none transition-opacity">
                    <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                </div>
                
                <div id="transaction-list" class="space-y-4">
                    @include('superadmin.minimarkets.partials._transaction_table', ['transactions' => $transactions])
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const categoryData = @json($categories);
        let filters = {
            search: "{{ request('search', '') }}",
            type: "{{ request('type', '') }}",
            parent_category_id: "{{ request('parent_category_id', '') }}",
            category_id: "{{ request('category_id', '') }}",
            date: "{{ request('date', '') }}"
        };

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const typeFilter = document.getElementById('type-filter');
            const parentFilter = document.getElementById('parent-category-filter');
            const categoryFilter = document.getElementById('category-filter');
            let debounceTimer;

            // Load subcategories if parent is selected
            if (filters.parent_category_id) {
                updateSubcategories(filters.parent_category_id, filters.category_id);
            }

            const performFetch = () => {
                const spinner = document.getElementById('loading-spinner');
                const list = document.getElementById('transaction-list');
                
                spinner.classList.remove('opacity-0', 'pointer-events-none');

                const params = new URLSearchParams(filters);
                fetch(`{{ route('superadmin.minimarkets.transactions', $minimarket) }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    list.innerHTML = html;
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                    window.history.replaceState(null, '', `?${params.toString()}`);
                })
                .catch(error => {
                    console.error('Fetch failed:', error);
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

            typeFilter.addEventListener('change', () => {
                filters.type = typeFilter.value;
                performFetch();
            });

            parentFilter.addEventListener('change', () => {
                filters.parent_category_id = parentFilter.value;
                filters.category_id = '';
                updateSubcategories(filters.parent_category_id);
                performFetch();
            });

            categoryFilter.addEventListener('change', () => {
                filters.category_id = categoryFilter.value;
                performFetch();
            });

            // Re-bind pagination clicks for AJAX
            document.addEventListener('click', (e) => {
                if (e.target.closest('.ajax-pagination a')) {
                    e.preventDefault();
                    const url = new URL(e.target.closest('a').href);
                    const page = url.searchParams.get('page');
                    filters.page = page;
                    performFetch();
                    window.scrollTo({ top: document.getElementById('transaction-container').offsetTop - 100, behavior: 'smooth' });
                }
            });

            window.updateDate = (date) => {
                filters.date = date;
                // Update button UI
                document.querySelectorAll('.date-btn').forEach(btn => {
                    // Logic to highlight active date btn
                });
                performFetch();
                if (date) {
                    const d = new Date(date);
                    document.getElementById('date-label').innerText = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                } else {
                    document.getElementById('date-label').innerText = 'Custom';
                }
            };

            window.resetFilters = () => {
                filters = { search: '', type: '', parent_category_id: '', category_id: '', date: '' };
                searchInput.value = '';
                typeFilter.value = '';
                parentFilter.value = '';
                categoryFilter.innerHTML = '<option value="">SEMUA SUB-KATEGORI</option>';
                performFetch();
            };
        });

        function updateSubcategories(parentId, selectedId = '') {
            const categoryFilter = document.getElementById('category-filter');
            categoryFilter.innerHTML = '<option value="">SEMUA SUB-KATEGORI</option>';
            if (parentId) {
                const parent = categoryData.find(c => c.id == parentId);
                if (parent && parent.children) {
                    parent.children.forEach(child => {
                        const option = document.createElement('option');
                        option.value = child.id;
                        option.text = child.name.toUpperCase();
                        if (child.id == selectedId) option.selected = true;
                        categoryFilter.add(option);
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
