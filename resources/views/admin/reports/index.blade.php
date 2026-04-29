<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    LAPORAN CABANG
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Laporan inventori dan mutasi barang {{ $minimarket->name }}</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    <a href="{{ route('admin.reports.index') }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.reports.index', ['date' => now()->toDateString()]) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                </div>
                
                <form action="{{ route('admin.reports.index') }}" method="GET" 
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

    <!-- Report Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Saldo Stok (Akhir Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">{{ number_format($displayStock) }}</h4>
            <p class="text-xs text-gray-400 mt-1 font-medium">Total ketersediaan barang di gudang</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Masuk (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">+{{ number_format($totalIn) }}</h4>
            <p class="text-xs text-gray-400 mt-1 font-medium">Penambahan stok selama periode terpilih</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Keluar (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">-{{ number_format($totalOut) }}</h4>
            <p class="text-xs text-gray-400 mt-1 font-medium">Pengurangan stok selama periode terpilih</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-gray-50/30">
            <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Rincian Per Produk</h3>
            
            <form action="{{ route('admin.reports.index') }}" method="GET">
                <input type="hidden" name="date" value="{{ request('date') }}">
                <select name="category_id" onchange="this.form.submit()" 
                    class="bg-white border border-gray-200 pl-4 pr-10 py-2 rounded-xl text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm">
                    <option value="">SEMUA KATEGORI</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ strtoupper($cat->name) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4 text-center">Kategori</th>
                        <th class="px-6 py-4 text-right">Saldo Akhir</th>
                        <th class="px-6 py-4 text-right">Masuk</th>
                        <th class="px-6 py-4 text-right">Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($productSummary as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $item->product->sku }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-black text-gray-500 bg-gray-100 px-2 py-1 rounded uppercase">
                                    {{ $item->product->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-gray-900 text-sm">
                                {{ number_format($item->display_qty) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-green-600 text-sm">
                                {{ $item->period_in > 0 ? '+' . number_format($item->period_in) : '0' }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-red-600 text-sm">
                                {{ $item->period_out > 0 ? '-' . number_format($item->period_out) : '0' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-sm">
                                Tidak ada data aktivitas untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
