<x-app-layout>
    <!-- Page Header -->
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-6">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">Laporan Konsolidasi</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic uppercase text-xs font-black">
                Rekapitulasi stok dan transaksi antar seluruh cabang
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex gap-2">
                <a href="{{ route('superadmin.reports.index', ['category_id' => request('category_id')]) }}" 
                   class="pixel-btn px-4 py-2 font-label-sm text-xs uppercase {{ !request('date') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                    Semua
                </a>
                <a href="{{ route('superadmin.reports.index', ['date' => now()->toDateString(), 'category_id' => request('category_id')]) }}" 
                   class="pixel-btn px-4 py-2 font-label-sm text-xs uppercase {{ request('date') == now()->toDateString() ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                    Hari Ini
                </a>
                <div onclick="document.getElementById('date-input').showPicker()"
                    class="relative pixel-btn bg-surface-container text-on-surface-variant px-4 py-2 font-label-sm text-xs uppercase cursor-pointer {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-primary bg-primary text-stone-950' : '' }}">
                    <input type="date" id="date-input" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                    <span id="date-label">
                        {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('superadmin.reports.index') }}" method="GET" class="flex gap-2">
                <input type="hidden" name="date" value="{{ request('date') }}">
                <select name="category_id" onchange="this.form.submit()" 
                    class="bg-background border-2 border-outline-variant text-on-surface px-4 py-2 focus:outline-none focus:border-amber-500 pixel-border font-label-sm text-xs uppercase min-w-[180px]">
                    <option value="">SEMUA KATEGORI</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ strtoupper($cat->name) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="space-y-8">
        @foreach($minimarkets as $mm)
            <div class="bg-surface-container pixel-box p-0 overflow-hidden group">
                <!-- Branch Header -->
                <div class="px-6 py-5 border-b-4 border-stone-950 bg-stone-950/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 bg-stone-950 border-2 border-outline-variant flex items-center justify-center text-amber-500 pixel-border group-hover:border-primary transition-colors">
                            <span class="material-symbols-outlined text-3xl">store</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-on-surface leading-none mb-1 uppercase tracking-tight group-hover:text-primary transition-colors">{{ $mm->name }}</h3>
                            <p class="text-xs text-on-surface-variant font-black uppercase tracking-widest">{{ $mm->code }} • {{ $mm->city }}</p>
                        </div>
                    </div>
                    <div class="flex gap-8">
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-widest font-black text-on-surface-variant mb-1">Total Produk</p>
                            <p class="text-3xl font-black text-on-surface leading-none">{{ number_format($mm->inventory_items_count) }}</p>
                        </div>
                        <div class="text-right border-l-2 border-outline-variant pl-8">
                            <p class="text-[10px] uppercase tracking-widest font-black text-amber-500 mb-1">Total Mutasi</p>
                            <p class="text-3xl font-black text-amber-500 leading-none">{{ number_format($mm->inventory_transactions_count) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-6 bg-stone-950/50 pixel-border border-l-8 border-l-outline">
                            <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-2">Persediaan Aktif</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-on-surface">{{ number_format($mm->total_quantity ?? 0) }}</span>
                                <span class="text-xs font-black text-on-surface-variant uppercase">Unit</span>
                            </div>
                        </div>
                        <div class="p-6 bg-secondary/10 pixel-border border-l-8 border-l-secondary">
                            <p class="text-xs font-black text-secondary uppercase tracking-widest mb-2">Masuk (Periode)</p>
                            <p class="text-4xl font-black text-secondary">+{{ number_format($mm->recent_in) }}</p>
                        </div>
                        <div class="p-6 bg-red-900/20 pixel-border border-l-8 border-l-red-500">
                            <p class="text-xs font-black text-red-400 uppercase tracking-widest mb-2">Keluar (Periode)</p>
                            <p class="text-4xl font-black text-red-400">-{{ number_format($mm->recent_out) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 bg-stone-950/20 text-right">
                    <a href="{{ route('superadmin.minimarkets.show', $mm) }}" class="text-xs font-black text-amber-500 hover:text-amber-400 uppercase tracking-widest flex items-center justify-end gap-2 group/btn">
                        LIHAT DETAIL CABANG
                        <span class="material-symbols-outlined text-sm group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
