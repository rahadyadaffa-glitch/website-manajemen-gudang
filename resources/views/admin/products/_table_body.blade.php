@forelse($inventory as $item)
    <div class="product-card pixel-box p-4 md:p-0 md:grid md:grid-cols-12 md:items-center gap-4 overflow-hidden">
        <div class="md:col-span-3 md:pl-8 md:py-5">
            <h4 class="text-sm font-black text-on-surface uppercase truncate">{{ $item->productVariant->product->name }}</h4>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs font-black text-amber-500 uppercase">{{ $item->productVariant->weight_value }} {{ $item->productVariant->weight_unit }}</span>
                <span class="text-[11px] text-on-surface-variant font-bold italic">Update: {{ $item->last_updated->format('d/m/y H:i') }}</span>
            </div>
        </div>
        
        <div class="md:col-span-2 text-center py-2 md:py-0">
            <span class="inline-block px-3 py-1 bg-primary text-stone-950 text-xs font-black pixel-border uppercase">
                {{ $item->productVariant->product->category->name }}
            </span>
        </div>
        
        <div class="md:col-span-2 py-2 md:py-0">
            <p class="text-sm font-mono font-black text-on-surface tracking-tighter">{{ $item->productVariant->sku }}</p>
            <p class="text-[11px] text-on-surface-variant font-mono mt-0.5">{{ $item->productVariant->barcode ?? '-' }}</p>
        </div>
        
        <div class="md:col-span-2 text-right md:pr-4 py-2 md:py-0">
            @php
                $isLow = $item->quantity <= $item->productVariant->min_stock_threshold;
                $pcsPerDus = $item->productVariant->pcs_per_dus ?? 1;
                $isMultiple = $pcsPerDus > 1 && $item->quantity % $pcsPerDus === 0 && $item->quantity > 0;
                $dusCount = $isMultiple ? $item->quantity / $pcsPerDus : null;
            @endphp
            <div class="flex flex-col items-end">
                <div class="flex items-baseline gap-1">
                    @if($isMultiple)
                        <span class="text-2xl font-black text-amber-500">{{ number_format($dusCount) }}</span>
                        <span class="text-[10px] font-black text-amber-500/70 uppercase">DUS</span>
                        <span class="text-xs font-black text-stone-600 mx-1">/</span>
                    @endif
                    <span class="text-2xl font-black {{ $isLow ? 'text-red-400' : 'text-secondary' }}">{{ number_format($item->quantity) }}</span>
                    <span class="text-xs text-on-surface-variant font-black uppercase">{{ $item->productVariant->unit }}</span>
                </div>
                @if($isLow)
                    <span class="text-[9px] text-red-400 font-black uppercase mt-0.5 animate-pulse">STOK KRITIS</span>
                @endif
            </div>
        </div>
        
        <div class="md:col-span-1 text-center py-2 md:py-0">
            @if($isLow)
                <span class="text-xs font-black text-red-400 uppercase border-b-2 border-red-400">Low Stock</span>
            @else
                <span class="text-xs font-black text-secondary uppercase border-b-2 border-secondary">In Stock</span>
            @endif
        </div>
        
        <div class="md:col-span-2 text-right md:pr-8 py-4 md:py-0">
            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.products.show', array_merge([$item->product_variant_id], request()->only(['date', 'category_id', 'search', 'status']))) }}"
                    class="pixel-btn bg-surface-variant text-on-surface px-4 py-2 font-label-sm text-[10px] uppercase flex items-center gap-2 hover:bg-stone-700">
                    <span class="material-symbols-outlined text-xs">visibility</span>
                    Detail
                </a>
                @if(auth()->user()->isSuperadmin())
                <a href="{{ route('superadmin.products.edit', array_merge([$item->productVariant->product_id], request()->only(['date', 'category_id', 'search', 'status']))) }}"
                    class="pixel-btn bg-amber-500 text-stone-900 px-4 py-2 font-label-sm text-[10px] uppercase flex items-center gap-2 hover:bg-amber-400">
                    <span class="material-symbols-outlined text-xs">edit</span>
                    Edit
                </a>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="bg-stone-950/30 pixel-border p-20 text-center text-on-surface-variant italic">
        <span class="material-symbols-outlined text-5xl mb-4 opacity-20">inventory_2</span>
        <p class="text-sm">Belum ada data produk atau filter tidak ditemukan.</p>
    </div>
@endforelse