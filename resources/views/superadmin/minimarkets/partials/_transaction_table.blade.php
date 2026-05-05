@forelse($transactions as $trx)
    <div class="bg-surface-container-high p-5 pixel-border border-l-4 {{ $trx->transaction_type == 'in' ? 'border-l-secondary' : 'border-l-error' }} hover:bg-surface-bright transition-colors group">
        <div class="flex flex-col md:grid md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-2">
                <p class="text-xs font-black text-on-surface">{{ $trx->created_at->format('d/m/Y') }}</p>
                <p class="text-[10px] font-bold text-amber-500 uppercase">{{ $trx->created_at->format('H:i') }} WIB</p>
            </div>
            <div class="md:col-span-4">
                @php
                    $product = $trx->productVariant->product;
                    $variant = $trx->productVariant;
                @endphp
                <p class="text-base font-black text-on-surface uppercase tracking-tight group-hover:text-primary transition-colors">{{ $product->name }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] bg-stone-900 px-2 py-0.5 pixel-border text-on-surface-variant font-mono tracking-widest uppercase">SKU: {{ $variant->sku }}</span>
                    <span class="text-[10px] text-stone-500 font-bold uppercase italic">{{ $product->category->name }}</span>
                </div>
            </div>
            <div class="md:col-span-2 text-center">
                @if($trx->transaction_type === 'in')
                    <span class="text-[11px] font-black text-secondary uppercase bg-secondary/10 px-4 py-1.5 pixel-border tracking-widest">BARANG MASUK</span>
                @else
                    <span class="text-[11px] font-black text-red-400 uppercase bg-red-400/10 px-4 py-1.5 pixel-border tracking-widest">BARANG KELUAR</span>
                @endif
            </div>
            <div class="md:col-span-2 text-right">
                @php
                    $pcsPerDus = $variant->pcs_per_dus ?? 1;
                    $isMultiple = $pcsPerDus > 1 && $trx->quantity % $pcsPerDus === 0 && $trx->quantity > 0;
                    $dusCount = $isMultiple ? $trx->quantity / $pcsPerDus : null;
                @endphp
                <div class="flex flex-col items-end">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-on-surface">{{ number_format($trx->quantity) }}</span>
                        <span class="text-[11px] text-on-surface-variant uppercase font-black">{{ $variant->unit }}</span>
                    </div>
                    @if($isMultiple)
                        <span class="mt-2 px-3 py-1.5 bg-amber-500 text-stone-900 text-xs font-black pixel-border uppercase leading-none shadow-[2px_2px_0px_#000]">
                            {{ number_format($dusCount) }} DUS
                        </span>
                    @endif
                </div>
            </div>
            <div class="md:col-span-2 text-right">
                <p class="text-[12px] font-black text-on-surface-variant uppercase">{{ $trx->user->name }}</p>
                <p class="text-[10px] text-stone-600 font-bold uppercase">{{ $trx->user->role->name }}</p>
            </div>
        </div>
    </div>
@empty
    <div class="p-20 text-center text-on-surface-variant italic font-black uppercase tracking-widest opacity-30">
        Belum ada transaksi tercatat
    </div>
@endforelse

<div class="mt-8 ajax-pagination">
    {{ $transactions->links() }}
</div>
