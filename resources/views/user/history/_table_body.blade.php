@forelse($transactions as $trx)
    <div
        class="bg-surface-container history-item p-4 flex flex-col md:grid md:grid-cols-12 md:items-center gap-4 hover:bg-surface-container-high transition-colors group">
        <div class="col-span-2">
            <p class="text-[11px] font-black text-on-surface">{{ $trx->created_at->format('d/m/Y H:i') }}</p>
            <p class="text-[9px] text-on-surface-variant uppercase tracking-tighter">{{ $trx->created_at->diffForHumans() }}</p>
        </div>
        <div class="col-span-3">
            <p class="text-xs font-bold text-on-surface uppercase">{{ $trx->productVariant->product->name }}</p>
            <p class="text-[9px] text-amber-500 font-black uppercase tracking-tighter">{{ $trx->productVariant->weight_value }} {{ $trx->productVariant->weight_unit }}</p>
            <p class="text-[10px] text-gray-500 font-mono mt-1 tracking-widest">{{ $trx->productVariant->sku }}</p>
        </div>
        <div class="col-span-1 text-center">
            @if($trx->transaction_type === 'in')
                <span class="inline-block px-2 py-1 bg-secondary text-stone-950 text-[9px] font-black pixel-border uppercase">MASUK</span>
            @else
                <span class="inline-block px-2 py-1 bg-red-500 text-stone-950 text-[9px] font-black pixel-border uppercase">KELUAR</span>
            @endif
        </div>
        <div class="col-span-2 text-right">
            <span class="text-base font-black text-on-surface {{ $trx->transaction_type === 'in' ? 'text-secondary' : 'text-red-400' }}">
                {{ $trx->transaction_type === 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }}
            </span>
            <span class="text-[11px] text-on-surface-variant uppercase ml-1">{{ $trx->productVariant->unit }}</span>
        </div>
        <div class="col-span-2">
            <p class="text-xs text-on-surface-variant italic max-w-xs truncate group-hover:whitespace-normal group-hover:overflow-visible transition-all">
                {{ $trx->notes ?: '-' }}
            </p>
        </div>
        <div class="col-span-2 text-center">
            @if($trx->status === 'pending')
                <span class="inline-block px-3 py-1 bg-amber-500/10 text-amber-500 border-b-2 border-amber-500 text-[9px] font-black uppercase">Menunggu</span>
            @elseif($trx->status === 'approved')
                <span class="inline-block px-3 py-1 bg-secondary/10 text-secondary border-b-2 border-secondary text-[9px] font-black uppercase">Disetujui</span>
            @else
                <span class="inline-block px-3 py-1 bg-red-500/10 text-red-500 border-b-2 border-red-500 text-[9px] font-black uppercase">Ditolak</span>
            @endif
        </div>
    </div>
@empty
    <div class="bg-stone-950/30 pixel-border p-12 text-center text-on-surface-variant italic text-sm">
        <span class="material-symbols-outlined text-4xl mb-2 opacity-20">search_off</span>
        <p>Tidak ada riwayat transaksi yang ditemukan.</p>
    </div>
@endforelse

@if($transactions->hasPages())
    <div class="mt-8">
        {{ $transactions->links() }}
    </div>
@endif
