<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-xs font-black uppercase tracking-widest text-gray-400 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('superadmin.dashboard') }}" class="hover:text-blue-600 transition-colors">DASHBOARD</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li><a href="{{ route('superadmin.minimarkets.index') }}" class="hover:text-blue-600 transition-colors">KELOLA MINIMARKET</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-blue-600">DETAIL CABANG</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    {{ strtoupper($minimarket->name) }}
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Kelola stok dan pantau performa cabang secara real-time</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    <a href="{{ route('superadmin.minimarkets.show', $minimarket) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.minimarkets.show', [$minimarket, 'date' => now()->toDateString()]) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                </div>
                
                <form action="{{ route('superadmin.minimarkets.show', $minimarket) }}" method="GET" 
                    onclick="this.querySelector('input').showPicker()"
                    class="relative flex items-center bg-gray-100 px-4 py-2 rounded-xl hover:bg-white hover:shadow-sm transition-all cursor-pointer border border-transparent hover:border-blue-100 {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 mr-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-bold pointer-events-none {{ request('date') && request('date') != now()->toDateString() ? 'text-blue-600' : 'text-gray-600' }}">
                        {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                    </span>
                </form>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Produk (Terkini)</p>
            <h4 class="text-2xl font-black text-gray-900">{{ number_format($stats['total_products']) }}</h4>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Stok Saat Ini</p>
            <h4 class="text-2xl font-black text-gray-900">{{ number_format($stats['total_stock']) }}</h4>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-1">Masuk (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">+{{ number_format($stats['recent_in']) }}</h4>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Keluar (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">-{{ number_format($stats['recent_out']) }}</h4>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Navigation Tabs -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('superadmin.minimarkets.show', $minimarket) }}" 
               class="px-5 py-2 text-xs font-bold rounded-xl transition-all {{ Route::is('superadmin.minimarkets.show') ? 'bg-white border-2 border-blue-600 text-blue-600 shadow-sm' : 'bg-gray-50 border border-gray-200 text-gray-500 hover:bg-white' }}">
                STOK BARANG
            </a>
            <a href="{{ route('superadmin.minimarkets.trend', $minimarket) }}" 
               class="px-5 py-2 text-xs font-bold rounded-xl transition-all {{ Route::is('superadmin.minimarkets.trend') ? 'bg-white border-2 border-blue-600 text-blue-600 shadow-sm' : 'bg-gray-50 border border-gray-200 text-gray-500 hover:bg-white' }}">
                GRAFIK TREND
            </a>
        </div>

        <!-- Inventory Table Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-gray-50/30">
                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Inventori Cabang</h3>
                
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <!-- Search Input -->
                    <form action="{{ route('superadmin.minimarkets.show', $minimarket) }}" method="GET" class="relative w-full sm:w-64">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang atau SKU..." 
                            class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>

                    <!-- Category Filter -->
                    <form action="{{ route('superadmin.minimarkets.show', $minimarket) }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <div class="relative">
                            <select name="category_id" onchange="this.form.submit()" 
                                class="appearance-none bg-white border border-gray-200 pl-4 pr-10 py-2 rounded-xl text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer shadow-sm">
                                <option value="">SEMUA KATEGORI</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ strtoupper($cat->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @if(request()->anyFilled(['category_id', 'search']))
                            <a href="{{ route('superadmin.minimarkets.show', $minimarket) }}" class="text-[10px] font-bold text-red-500 hover:underline uppercase tracking-tight">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto relative">
                <div id="loading-spinner" class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-10 flex items-center justify-center opacity-0 pointer-events-none transition-opacity">
                    <div class="w-6 h-6 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Barang / SKU</th>
                            <th class="px-6 py-4 text-center">Kategori</th>
                            <th class="px-6 py-4 text-right">Stok</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-table-body" class="divide-y divide-gray-50">
                        @include('superadmin.minimarkets.partials._inventory_table', ['inventory' => $inventory])
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions Section (Audit Log Style) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30 flex items-center justify-between">
                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Log Transaksi Terakhir</h3>
                <a href="#" class="text-xs font-bold text-blue-600 hover:underline uppercase tracking-tight">Selengkapnya &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Waktu</th>
                            <th class="px-6 py-3">Barang</th>
                            <th class="px-6 py-3 text-center">Tipe</th>
                            <th class="px-6 py-3 text-right">Jumlah</th>
                            <th class="px-6 py-3">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-[11px] font-medium text-gray-500 whitespace-nowrap">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ $trx->product->name }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $trx->product->sku }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($trx->transaction_type === 'in')
                                        <span class="text-[10px] font-bold text-green-600 uppercase">MASUK</span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-600 uppercase">KELUAR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-black text-gray-900">
                                    {{ number_format($trx->quantity) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600">
                                    {{ $trx->user->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                    Belum ada transaksi tercatat
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('input[name="search"]');
            const categorySelect = document.querySelector('select[name="category_id"]');
            const tableBody = document.getElementById('inventory-table-body');
            const spinner = document.getElementById('loading-spinner');
            let debounceTimer;

            const performSearch = () => {
                const search = searchInput.value;
                const category = categorySelect.value;
                const date = "{{ request('date') }}";
                
                spinner.classList.remove('opacity-0', 'pointer-events-none');

                const params = new URLSearchParams({
                    search: search,
                    category_id: category,
                    date: date
                });

                fetch(`{{ route('superadmin.minimarkets.show', $minimarket) }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                })
                .catch(error => {
                    console.error('Search failed:', error);
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                });
            };

            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(performSearch, 300);
            });

            categorySelect.addEventListener('change', (e) => {
                e.preventDefault(); // Stop form submission
                performSearch();
            });
        });
    </script>
    @endpush
</x-app-layout>
