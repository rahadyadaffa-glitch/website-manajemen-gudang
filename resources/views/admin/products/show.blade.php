<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-amber-500 transition-colors uppercase">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="hover:text-amber-500 transition-colors uppercase">KELOLA PRODUK</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">DETAIL VARIAN</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">{{ $productVariant->product->name ?? 'Produk Tidak Terdaftar' }}</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic">
                Detail varian: <span class="font-black uppercase text-secondary text-sm">{{ $productVariant->weight_value }} {{ $productVariant->weight_unit }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->isSuperadmin())
            <a href="{{ route('superadmin.products.edit', array_merge([$productVariant->product_id], request()->only(['date', 'category_id']))) }}"
                class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-4 py-2 font-label-sm text-xs uppercase flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">edit</span>
                Edit Master
            </a>
            @endif
            <a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}"
                class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-xs uppercase flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
        <!-- Left Column: Image & Stock Status -->
        <div class="lg:col-span-1 space-y-gutter">
            <!-- Product Image -->
            <div class="bg-surface-container pixel-box p-4">
                <div class="aspect-square bg-stone-950 pixel-border border-2 border-outline-variant relative overflow-hidden">
                    @if($productVariant->image_path)
                        <img src="{{ asset('storage/' . $productVariant->image_path) }}" alt="{{ $productVariant->product->name ?? 'Produk' }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-6xl">inventory_2</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced Stock Status Card -->
            @php
                $isRejected = ($inventory->quantity ?? 0) <= $productVariant->min_stock_threshold;
            @endphp
            <div class="bg-surface-container pixel-box p-8 border-l-8 {{ $isRejected ? 'border-l-error' : 'border-l-secondary' }} transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest">Stok Saat Ini</p>
                    <span class="px-3 py-1 {{ $isRejected ? 'bg-error text-stone-950' : 'bg-secondary text-stone-950' }} text-[11px] font-black pixel-border uppercase">
                        {{ $isRejected ? 'CRITICAL' : 'SECURE' }}
                    </span>
                </div>
                
                <div class="flex items-baseline gap-3">
                    <h4 class="text-7xl font-black {{ $isRejected ? 'text-red-400' : 'text-secondary' }} leading-none tracking-tighter">
                        {{ number_format($inventory->quantity ?? 0) }}
                    </h4>
                    <span class="text-2xl font-black text-on-surface-variant uppercase tracking-tight">
                        {{ $productVariant->unit }}
                    </span>
                </div>

                <div class="mt-8 pt-6 border-t-2 border-outline-variant border-dashed">
                    @if($isRejected)
                        <div class="flex gap-3 text-red-400">
                            <span class="material-symbols-outlined">warning</span>
                            <p class="text-sm font-bold uppercase leading-relaxed italic">
                                Stok mencapai batas minimum ({{ $productVariant->min_stock_threshold }}). Segera restock!
                            </p>
                        </div>
                    @else
                        <div class="flex gap-3 text-secondary">
                            <span class="material-symbols-outlined">check_circle</span>
                            <p class="text-sm font-bold uppercase leading-relaxed italic">
                                Ketersediaan stok aman di atas batas minimum.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Product Details & History -->
        <div class="lg:col-span-2 space-y-gutter">
            <!-- Product Info -->
            <div class="bg-surface-container pixel-box p-6 md:p-8">
                <div class="flex items-center justify-between mb-8 border-b-2 border-outline-variant pb-4">
                    <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Spesifikasi Varian</h3>
                    <span class="text-xs font-mono font-black text-on-surface-variant uppercase">VAR-ID: {{ substr($productVariant->id, 0, 8) }}</span>
                </div>
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-2">Kategori Produk</p>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-black text-stone-500 uppercase italic">
                                    {{ $productVariant->product->category->parent->name ?? 'Kategori Utama' }}
                                </span>
                                <span class="inline-block px-3 py-1 bg-primary text-stone-950 text-sm font-black pixel-border uppercase self-start">
                                    {{ $productVariant->product->category->name ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-2">SKU / Barcode</p>
                            <p class="text-base font-black text-amber-500 font-mono">{{ $productVariant->sku }}</p>
                            <p class="text-sm text-on-surface-variant font-mono mt-1 uppercase tracking-widest">{{ $productVariant->barcode ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-2">Deskripsi Produk</p>
                        <div class="bg-background pixel-border border-2 border-outline-variant p-4 text-sm text-on-surface leading-relaxed italic">
                            {{ $productVariant->product->description ?? 'Tidak ada deskripsi produk yang tersedia.' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 pt-8 border-t-2 border-outline-variant">
                        <div>
                            <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1">Satuan Eceran</p>
                            <p class="text-base font-black text-on-surface uppercase tracking-tight">{{ $productVariant->unit }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1">Isi Per Dus (Grosir)</p>
                            <p class="text-base font-black text-on-surface">{{ $productVariant->pcs_per_dus }} {{ $productVariant->unit }} / DUS</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="bg-surface-container pixel-box p-0 overflow-hidden">
                <div class="px-8 py-5 border-b-2 border-outline-variant bg-stone-950/30 flex items-center justify-between">
                    <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">Riwayat Stok Varian Ini</h3>
                    <span class="px-3 py-1 bg-stone-950 text-amber-500 text-xs font-black pixel-border uppercase">
                        {{ $date ? \Carbon\Carbon::parse($date)->format('d M Y') : 'LIVE LOG' }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-stone-950/50 text-xs font-black text-on-surface-variant uppercase tracking-widest border-b-2 border-outline-variant">
                            <tr>
                                <th class="px-8 py-4 font-black">Waktu</th>
                                <th class="px-8 py-4 font-black">Tipe</th>
                                <th class="px-8 py-4 font-black text-right">Jumlah</th>
                                <th class="px-8 py-4 font-black">Petugas</th>
                                <th class="px-8 py-4 font-black text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-outline-variant">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-surface-container-high transition-colors">
                                    <td class="px-8 py-5 text-sm font-black text-on-surface-variant">
                                        {{ $trx->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-8 py-5">
                                        @if($trx->transaction_type === 'in')
                                            <span class="text-xs font-black text-secondary uppercase bg-secondary/10 px-2 py-0.5 pixel-border">MASUK</span>
                                        @else
                                            <span class="text-xs font-black text-red-400 uppercase bg-red-400/10 px-2 py-0.5 pixel-border">KELUAR</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right font-black text-on-surface">
                                        <span class="{{ $trx->transaction_type === 'in' ? 'text-secondary' : 'text-red-400' }} text-base">
                                            {{ $trx->transaction_type === 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-xs font-black text-on-surface-variant uppercase">
                                        {{ $trx->user->name ?? 'User Tidak Dikenal' }}
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @if($trx->status === 'approved')
                                            <span class="text-xs font-black text-secondary uppercase border-b-2 border-secondary">Approved</span>
                                        @elseif($trx->status === 'pending')
                                            <span class="text-xs font-black text-amber-500 uppercase border-b-2 border-amber-500">Pending</span>
                                        @else
                                            <span class="text-xs font-black text-red-400 uppercase border-b-2 border-red-400">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-on-surface-variant italic text-sm">
                                        Tidak ada data transaksi untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>