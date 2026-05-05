<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase leading-none">Riwayat Transaksi</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic text-xs">
                Pantau status pengajuan pergerakan barang Anda
            </p>
        </div>
        <div class="flex gap-2">
            <button onclick="updateDateFilter('')"
                class="date-filter-btn pixel-btn px-6 py-3 font-label-sm text-xs uppercase font-black {{ !request('date') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}"
                data-date="">
                Semua
            </button>
            <button onclick="updateDateFilter('{{ now()->toDateString() }}')"
                class="date-filter-btn pixel-btn px-6 py-3 font-label-sm text-xs uppercase font-black {{ request('date') == now()->toDateString() ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}"
                data-date="{{ now()->toDateString() }}">
                Hari Ini
            </button>
            <div onclick="document.getElementById('date-input').showPicker()"
                class="relative pixel-btn bg-surface-container text-on-surface-variant px-6 py-3 font-label-sm text-xs uppercase font-black cursor-pointer {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-primary bg-primary text-stone-950' : '' }}">
                <input type="date" id="date-input" name="date" value="{{ request('date') }}"
                    onchange="updateDateFilter(this.value)"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                <span id="date-label">
                    {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Kalender' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-surface-container pixel-box p-4 flex flex-col gap-4 mb-8">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 flex items-center bg-stone-950 pixel-input focus-within:ring-2 focus-within:ring-amber-500/50 transition-all group overflow-hidden">
                <span class="material-symbols-outlined px-4 text-on-surface-variant group-focus-within:text-amber-500 pointer-events-none text-xl">search</span>
                <input type="text" id="search-input" value="{{ request('search') }}" oninput="fetchHistory()"
                    class="w-full bg-transparent border-none text-on-surface py-4 focus:ring-0 font-body-lg text-sm -ml-4"
                    placeholder="Cari nama produk atau SKU..." />
            </div>
            <div class="flex gap-4">
                <select id="status-filter" onchange="fetchHistory()"
                    class="bg-background border-2 border-outline-variant text-on-surface px-8 py-3 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs font-black uppercase min-w-[200px]">
                    <option value="">SEMUA STATUS</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>MENUNGGU</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>DISETUJUI</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>DITOLAK</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <select id="parent-category-filter" onchange="handleParentFilterChange()"
                class="bg-background border-2 border-outline-variant text-on-surface px-8 py-3 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs font-black uppercase w-full">
                <option value="">SEMUA KATEGORI UTAMA</option>
                @foreach($categories as $parent)
                    <option value="{{ $parent->id }}">{{ strtoupper($parent->name) }}</option>
                @endforeach
            </select>
            
            <select id="category-filter" onchange="fetchHistory()" disabled
                class="bg-background border-2 border-outline-variant text-on-surface px-8 py-3 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs font-black uppercase w-full opacity-50 cursor-not-allowed">
                <option value="">PILIH SUB KATEGORI...</option>
            </select>
        </div>
    </div>

    <style>
        .pixel-box-light {
            border: 4px solid #4a4a4a; /* Dark gray but visible */
            box-shadow: inset 2px 2px 0px rgba(255, 255, 255, 0.1), 0 4px 0 #000;
        }
        
        .history-item {
            border: 4px solid #383939; /* Lighter than background */
            background: #1f2020;
            box-shadow: inset 2px 2px 0px rgba(255, 255, 255, 0.05);
        }

        .history-item:hover {
            border-color: #57534e;
        }

        /* White dropdown icon */
        select.pixel-border {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>

    <!-- History Grid -->
    <div class="space-y-4">
        <!-- Table Header (hidden on mobile) -->
        <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-2 text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest">
            <div class="col-span-2">Waktu Pengajuan</div>
            <div class="col-span-3">Produk</div>
            <div class="col-span-1 text-center">Tipe</div>
            <div class="col-span-2 text-right">Jumlah</div>
            <div class="col-span-2">Alasan / Catatan</div>
            <div class="col-span-2 text-center">Status</div>
        </div>

        <div id="history-table-body" class="space-y-3 relative min-h-[100px]">
            @include('user.history._table_body')
        </div>

        <div id="loading-spinner" class="hidden flex justify-center py-12">
            <div class="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentFilters = {
            date: '{{ request('date', '') }}',
            status: '{{ request('status', '') }}',
            search: '{{ request('search', '') }}',
            parent_category_id: '',
            category_id: ''
        };

        const allCategories = @json($categories);

        function handleParentFilterChange() {
            const parentId = document.getElementById('parent-category-filter').value;
            const subSelect = document.getElementById('category-filter');
            
            currentFilters.parent_category_id = parentId;
            currentFilters.category_id = '';
            
            subSelect.innerHTML = '<option value="">SEMUA SUB KATEGORI</option>';
            
            if (parentId) {
                const parent = allCategories.find(c => c.id == parentId);
                if (parent && parent.children.length > 0) {
                    parent.children.forEach(child => {
                        const opt = document.createElement('option');
                        opt.value = child.id;
                        opt.textContent = child.name.toUpperCase();
                        subSelect.appendChild(opt);
                    });
                    subSelect.disabled = false;
                    subSelect.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    subSelect.disabled = true;
                    subSelect.classList.add('opacity-50', 'cursor-not-allowed');
                }
            } else {
                subSelect.disabled = true;
                subSelect.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            fetchHistory();
        }

        function updateDateFilter(date) {
            currentFilters.date = date;
            
            document.querySelectorAll('.date-filter-btn').forEach(btn => {
                if (btn.dataset.date === date) {
                    btn.classList.add('bg-primary', 'text-stone-950');
                    btn.classList.remove('bg-surface-container', 'text-on-surface-variant');
                } else {
                    btn.classList.remove('bg-primary', 'text-stone-950');
                    btn.classList.add('bg-surface-container', 'text-on-surface-variant');
                }
            });

            const label = document.getElementById('date-label');
            const input = document.getElementById('date-input');
            input.value = date;
            
            if (date && date !== '{{ now()->toDateString() }}') {
                const d = new Date(date);
                label.innerText = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            } else if (date === '{{ now()->toDateString() }}') {
                label.innerText = 'Hari Ini';
            } else {
                label.innerText = 'Kalender';
            }

            fetchHistory();
        }

        let debounceTimer;
        function fetchHistory() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const spinner = document.getElementById('loading-spinner');
                const container = document.getElementById('history-table-body');
                
                currentFilters.search = document.getElementById('search-input').value;
                currentFilters.status = document.getElementById('status-filter').value;
                currentFilters.category_id = document.getElementById('category-filter').value;

                spinner.classList.remove('hidden');
                container.classList.add('opacity-50');

                const params = new URLSearchParams();
                for (const key in currentFilters) {
                    if (currentFilters[key]) params.append(key, currentFilters[key]);
                }
                
                fetch(`{{ route('user.history.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                    spinner.classList.add('hidden');
                    container.classList.remove('opacity-50');
                    window.history.replaceState(null, '', `?${params.toString()}`);
                })
                .catch(error => {
                    console.error('Error fetching history:', error);
                    spinner.classList.add('hidden');
                    container.classList.remove('opacity-50');
                });
            }, 300);
        }
    </script>
    @endpush
</x-app-layout>
