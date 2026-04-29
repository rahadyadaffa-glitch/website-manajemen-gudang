<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 leading-tight uppercase tracking-tight">
                    Audit Trail
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1 italic">Rekapitulasi lengkap aktivitas gudang & minimarket</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    <button onclick="updateDateFilter('')" 
                       class="date-filter-btn px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                       data-date="">
                        Semua
                    </button>
                    <button onclick="updateDateFilter('{{ now()->toDateString() }}')" 
                       class="date-filter-btn px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                       data-date="{{ now()->toDateString() }}">
                        Hari Ini
                    </button>
                </div>
                
                <div onclick="document.getElementById('date-input').showPicker()"
                    class="relative flex items-center bg-gray-100 px-4 py-2 rounded-xl hover:bg-white hover:shadow-sm transition-all cursor-pointer border border-transparent hover:border-blue-100 {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                    <input type="date" id="date-input" name="date" value="{{ request('date') }}" onchange="updateDateFilter(this.value)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 mr-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span id="date-label" class="text-xs font-bold pointer-events-none {{ request('date') && request('date') != now()->toDateString() ? 'text-blue-600' : 'text-gray-600' }}">
                        {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="search-input" value="{{ request('search') }}" oninput="fetchLogs()"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs font-black uppercase" 
                            placeholder="Cari SKU atau Nama Produk...">
                    </div>

                    <!-- Tiered Category Filter -->
                    <select id="parent-category-filter" onchange="handleParentChange()" 
                        class="bg-white border border-gray-200 pl-4 pr-10 py-2 rounded-xl text-xs font-black text-gray-700 focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm min-w-[160px]">
                        <option value="">SEMUA KATEGORI</option>
                        @foreach($categories as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_category_id') == $parent->id ? 'selected' : '' }}>
                                {{ strtoupper($parent->name) }}
                            </option>
                        @endforeach
                    </select>

                    <select id="sub-category-filter" onchange="fetchLogs()" 
                        class="bg-white border border-gray-200 pl-4 pr-10 py-2 rounded-xl text-xs font-black text-gray-700 focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm min-w-[160px] {{ !request('parent_category_id') ? 'opacity-50' : '' }}"
                        {{ !request('parent_category_id') ? 'disabled' : '' }}>
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

                    <!-- Time Filters -->
                    <div class="flex items-center gap-2">
                        <input type="time" id="time-start" value="{{ request('time_start') }}" onchange="fetchLogs()"
                            class="bg-white border border-gray-200 px-3 py-2 rounded-xl text-xs font-black text-gray-700 focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-400 text-xs font-bold">s/d</span>
                        <input type="time" id="time-end" value="{{ request('time_end') }}" onchange="fetchLogs()"
                            class="bg-white border border-gray-200 px-3 py-2 rounded-xl text-xs font-black text-gray-700 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div id="loading-spinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu Kejadian</th>
                        <th class="px-6 py-4">Detail Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Petugas</th>
                        <th class="px-6 py-4 text-center">Aktivitas</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="logs-table-body" class="divide-y divide-gray-50">
                    @include('admin.audit._table_body')
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

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
                subSelect.classList.remove('opacity-50');
            } else {
                subSelect.disabled = true;
                subSelect.classList.add('opacity-50');
            }
            fetchLogs();
        }

        function updateDateFilter(date) {
            currentFilters.date = date;
            document.getElementById('date-input').value = date;
            fetchLogs();
        }

        function fetchLogs() {
            const spinner = document.getElementById('loading-spinner');
            const tbody = document.getElementById('logs-table-body');
            
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
                tbody.innerHTML = html;
                spinner.classList.add('hidden');
                window.history.replaceState(null, '', `?${params.toString()}`);
            });
        }
    </script>
</x-app-layout>
