<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-6">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">Audit Trail</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit">
                Rekapitulasi lengkap aktivitas gudang & minimarket
            </p>
        </div>
        <div class="flex gap-2">
            <button onclick="updateDateFilter('')"
                class="date-filter-btn pixel-btn px-4 py-2 font-label-sm text-xs uppercase {{ !request('date') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}"
                data-date="">
                Semua
            </button>
            <button onclick="updateDateFilter('{{ now()->toDateString() }}')"
                class="date-filter-btn pixel-btn px-4 py-2 font-label-sm text-xs uppercase {{ request('date') == now()->toDateString() ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}"
                data-date="{{ now()->toDateString() }}">
                Hari Ini
            </button>
            <div onclick="document.getElementById('date-input').showPicker()"
                class="relative pixel-btn bg-surface-container text-on-surface-variant px-4 py-2 font-label-sm text-xs uppercase cursor-pointer {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-primary bg-primary text-stone-950' : '' }}">
                <input type="date" id="date-input" name="date" value="{{ request('date') }}"
                    onchange="updateDateFilter(this.value)"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                <span id="date-label">
                    {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-surface-container pixel-border p-4 flex flex-col lg:flex-row gap-4 mb-6">
        <div class="flex-1 relative">
            <span
                class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text" id="search-input" value="{{ request('search') }}" oninput="fetchLogs()"
                class="w-full bg-background border-2 border-outline-variant text-on-surface pl-10 pr-4 py-2 focus:outline-none focus:border-amber-500 focus:ring-0 pixel-border font-body-lg text-sm"
                placeholder="Cari SKU atau Nama Produk..." />
        </div>
        <div class="flex flex-wrap gap-4">
            <select id="parent-category-filter" onchange="handleParentChange()"
                class="bg-background border-2 border-outline-variant text-on-surface px-4 py-2 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs uppercase min-w-[160px]">
                <option value="">SEMUA KATEGORI</option>
                @foreach($categories as $parent)
                    <option value="{{ $parent->id }}" {{ request('parent_category_id') == $parent->id ? 'selected' : '' }}>
                        {{ strtoupper($parent->name) }}
                    </option>
                @endforeach
            </select>

            <select id="sub-category-filter" onchange="fetchLogs()"
                class="bg-background border-2 border-outline-variant text-on-surface px-4 py-2 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs uppercase min-w-[160px]">
                <option value="">SEMUA SUB-KATEGORI</option>
                @if(request('parent_category_id'))
                    @php $selParent = $categories->firstWhere('id', request('parent_category_id')); @endphp
                    @if($selParent)
                        @foreach($selParent->children as $child)
                            <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>
                                {{ strtoupper($child->name) }}
                            </option>
                        @endforeach
                    @endif
                @endif
            </select>

            <div class="flex items-center gap-2">
                <input type="time" id="time-start" value="{{ request('time_start') }}" onchange="fetchLogs()"
                    class="bg-background border-2 border-outline-variant text-on-surface px-3 py-2 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs uppercase">
                <span class="text-on-surface-variant text-xs font-black uppercase">s/d</span>
                <input type="time" id="time-end" value="{{ request('time_end') }}" onchange="fetchLogs()"
                    class="bg-background border-2 border-outline-variant text-on-surface px-3 py-2 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs uppercase">
            </div>
        </div>
    </div>

    <!-- Audit Grid -->
    <div class="space-y-4">
        <!-- Table Header (hidden on mobile) -->
        <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 text-on-surface-variant font-label-sm text-xs uppercase tracking-widest bg-stone-900/50 pixel-border border-b-2 border-outline-variant mb-2">
            <div class="col-span-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">schedule</span>
                WAKTU
            </div>
            <div class="col-span-3">PRODUK / SKU</div>
            <div class="col-span-2 text-center">CABANG & USER</div>
            <div class="col-span-2 text-center">AKTIVITAS</div>
            <div class="col-span-1 text-right">JUMLAH</div>
            <div class="col-span-2 text-center">STATUS</div>
        </div>

        <div id="logs-table-body" class="space-y-3 relative min-h-[200px]">
            @include('admin.audit._table_body')
        </div>

        <div id="loading-spinner" class="hidden flex justify-center py-12">
            <div class="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        @if($logs->hasPages())
            <div class="mt-8">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <style>
        .audit-card {
            background: rgba(31, 32, 32, 0.8);
            backdrop-filter: blur(8px);
            border: 2px solid #383939;
            box-shadow: inset 2px 2px 0px rgba(255, 255, 255, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .audit-card:hover {
            border-color: #f59e0b;
            background: rgba(41, 42, 42, 0.9);
        }

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

    @push('scripts')
    <script>
        const categoryData = @json($categories);
        let currentFilters = {
            date: '{{ request('date', '') }}',
            parent_category_id: '{{ request('parent_category_id', '') }}',
            category_id: '{{ request('category_id', '') }}',
            search: '{{ request('search', '') }}',
            time_start: '{{ request('time_start', '') }}',
            time_end: '{{ request('time_end', '') }}'
        };

        function handleParentChange() {
            const parentId = document.getElementById('parent-category-filter').value;
            const subSelect = document.getElementById('sub-category-filter');
            
            currentFilters.parent_category_id = parentId;
            currentFilters.category_id = ''; 

            subSelect.innerHTML = '<option value="">SEMUA SUB-KATEGORI</option>';
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

            fetchLogs();
        }

        function updateDateFilter(date) {
            currentFilters.date = date;
            document.getElementById('date-input').value = date;
            
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
            if (date && date !== '{{ now()->toDateString() }}') {
                const d = new Date(date);
                label.innerText = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            } else {
                label.innerText = 'Custom';
            }

            fetchLogs();
        }

        function fetchLogs() {
            const spinner = document.getElementById('loading-spinner');
            const container = document.getElementById('logs-table-body');
            
            currentFilters.search = document.getElementById('search-input').value;
            currentFilters.time_start = document.getElementById('time-start').value;
            currentFilters.time_end = document.getElementById('time-end').value;
            currentFilters.category_id = document.getElementById('sub-category-filter').value;

            spinner.classList.remove('hidden');
            const params = new URLSearchParams(currentFilters);
            
            fetch(`{{ route('admin.audit.index') }}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                spinner.classList.add('hidden');
                window.history.replaceState(null, '', `?${params.toString()}`);
            });
        }
    </script>
    @endpush
</x-app-layout>
