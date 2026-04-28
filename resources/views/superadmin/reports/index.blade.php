<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 leading-tight">Laporan Konsolidasi</h1>
                <p class="text-sm text-gray-500 font-medium mt-1">Rekapitulasi stok dan transaksi antar cabang</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    <a href="{{ route('superadmin.reports.index', ['category_id' => request('category_id')]) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.reports.index', ['date' => now()->toDateString(), 'category_id' => request('category_id')]) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                </div>
                
                <form action="{{ route('superadmin.reports.index') }}" method="GET" 
                    onclick="this.querySelector('input[type=date]').showPicker()"
                    class="relative flex items-center bg-gray-100 px-4 py-2 rounded-xl hover:bg-white hover:shadow-sm transition-all cursor-pointer border border-transparent hover:border-blue-100 {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 mr-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-bold pointer-events-none {{ request('date') && request('date') != now()->toDateString() ? 'text-blue-600' : 'text-gray-600' }}">
                        {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Pilih Tanggal' }}
                    </span>
                </form>

                <form action="{{ route('superadmin.reports.index') }}" method="GET" class="relative group">
                    <input type="hidden" name="date" value="{{ request('date') }}">
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
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @foreach($minimarkets as $mm)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Branch Header -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 leading-none mb-1">{{ strtoupper($mm->name) }}</h3>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $mm->code }} • {{ $mm->city }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-8">
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-widest font-black text-gray-400 mb-1">Total Produk</p>
                            <p class="text-xl font-black text-gray-900 leading-none">{{ number_format($mm->inventory_items_count) }}</p>
                        </div>
                        <div class="text-right border-l border-gray-100 pl-8">
                            <p class="text-[10px] uppercase tracking-widest font-black text-blue-400 mb-1">Total Mutasi</p>
                            <p class="text-xl font-black text-blue-600 leading-none">{{ number_format($mm->inventory_transactions_count) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Persediaan Aktif</p>
                            <p class="text-2xl font-black text-gray-900">{{ number_format($mm->total_quantity ?? 0) }} <span class="text-xs font-bold text-gray-400">Unit</span></p>
                        </div>
                        <div class="p-5 bg-green-50/50 rounded-2xl border border-green-100">
                            <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Masuk (Periode)</p>
                            <p class="text-2xl font-black text-green-700">+{{ number_format($mm->recent_in) }}</p>
                        </div>
                        <div class="p-5 bg-red-50/50 rounded-2xl border border-red-100">
                            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Keluar (Periode)</p>
                            <p class="text-2xl font-black text-red-700">-{{ number_format($mm->recent_out) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
