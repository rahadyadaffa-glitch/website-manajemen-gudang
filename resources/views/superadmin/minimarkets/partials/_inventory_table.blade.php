@forelse($inventory as $item)
    <div class="bg-surface-container-high p-5 pixel-border hover:bg-surface-bright transition-colors group">
        <div class="flex flex-col md:grid md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-5">
                <p class="text-base font-black text-on-surface uppercase tracking-tight group-hover:text-primary transition-colors">{{ $item->productVariant->product->name }}</p>
                <p class="text-[10px] text-on-surface-variant font-mono mt-1 uppercase tracking-widest">SKU: {{ $item->productVariant->sku }}</p>
            </div>
            <div class="md:col-span-3 text-center">
                <span class="text-[10px] font-black text-on-surface-variant uppercase bg-stone-900 px-3 py-1.5 pixel-border">
                    {{ $item->productVariant->product->category->name }}
                </span>
            </div>
            <div class="md:col-span-2 text-right">
                @php
                    $product = $item->productVariant->product;
                    $variant = $item->productVariant;
                    $pcsPerDus = $variant->pcs_per_dus ?? 1;
                    $isMultiple = $pcsPerDus > 1 && $item->quantity % $pcsPerDus === 0 && $item->quantity > 0;
                    $dusCount = $isMultiple ? $item->quantity / $pcsPerDus : null;
                @endphp
                <div class="flex flex-col items-end">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-amber-500">{{ number_format($item->quantity) }}</span>
                        <span class="text-[11px] text-on-surface-variant uppercase font-black">{{ $variant->unit }}</span>
                    </div>
                    @if($isMultiple)
                        <span class="mt-2 px-3 py-1.5 bg-amber-500 text-stone-950 text-[11px] font-black pixel-border uppercase leading-none shadow-[2px_2px_0px_rgba(0,0,0,0.5)]">
                            {{ number_format($dusCount) }} DUS
                        </span>
                    @endif
                </div>
            </div>
            <div class="md:col-span-2 text-center">
                @if($item->quantity <= ($variant->min_stock_threshold ?? 10))
                    <span class="text-[10px] font-black text-red-400 uppercase bg-red-400/10 px-4 py-1.5 pixel-border tracking-widest">LOW STOCK</span>
                @else
                    <span class="text-[10px] font-black text-secondary uppercase bg-secondary/10 px-4 py-1.5 pixel-border tracking-widest">SECURE</span>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="p-20 text-center text-on-surface-variant italic font-black uppercase tracking-widest opacity-30">
        Data tidak ditemukan
    </div>
@endforelse
