@forelse($logs as $log)
    <div class="audit-card pixel-box p-4 md:p-0 md:grid md:grid-cols-12 md:items-center gap-4 overflow-hidden">
        <div class="md:col-span-2 md:pl-8 md:py-5">
            <p class="text-xs font-black text-on-surface">{{ $log->created_at->format('d/m/Y') }}</p>
            <p class="text-[11px] font-bold text-amber-500">{{ $log->created_at->format('H:i') }} <span class="text-[9px] opacity-70">WIB</span></p>
        </div>
        
        <div class="md:col-span-3 py-2 md:py-0">
            <h4 class="text-sm font-black text-on-surface uppercase truncate">{{ $log->productVariant->product->name }}</h4>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs font-black text-amber-500 uppercase">{{ $log->productVariant->weight_value }} {{ $log->productVariant->weight_unit }}</span>
                <span class="text-xs text-on-surface-variant font-mono tracking-tighter">{{ $log->productVariant->sku }}</span>
            </div>
        </div>

        <div class="md:col-span-2 text-center py-2 md:py-0">
            <div class="flex flex-col items-center">
                <span class="text-xs font-black text-primary uppercase mb-1">{{ $log->minimarket->name }}</span>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs text-on-surface-variant">person</span>
                    <span class="text-xs text-on-surface-variant font-bold uppercase truncate max-w-[100px]">{{ $log->user->name }}</span>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 text-center py-2 md:py-0">
            @if($log->transaction_type == 'in')
                <span class="inline-block px-3 py-1 bg-secondary text-stone-950 text-xs font-black pixel-border uppercase">Masuk</span>
            @else
                <span class="inline-block px-3 py-1 bg-red-500 text-stone-950 text-xs font-black pixel-border uppercase">Keluar</span>
            @endif
        </div>
        
        <div class="md:col-span-1 text-right md:pr-4 py-2 md:py-0">
            @php
                $pcsPerDus = $log->productVariant->pcs_per_dus ?? 1;
                $isMultiple = $pcsPerDus > 1 && $log->quantity % $pcsPerDus === 0 && $log->quantity > 0;
                $dusCount = $isMultiple ? $log->quantity / $pcsPerDus : null;
            @endphp
            <div class="flex flex-col items-end">
                <span class="text-xl font-black {{ $log->transaction_type == 'in' ? 'text-secondary' : 'text-red-400' }}">
                    {{ $log->transaction_type == 'in' ? '+' : '-' }}{{ number_format($log->quantity) }}
                </span>
                <span class="text-[9px] font-black text-on-surface-variant uppercase tracking-tighter">{{ $log->productVariant->unit }}</span>
                @if($isMultiple)
                    <span class="mt-1 px-1.5 py-0.5 bg-amber-500 text-stone-950 text-[8px] font-black pixel-border uppercase leading-none">
                        {{ number_format($dusCount) }} DUS
                    </span>
                @endif
            </div>
        </div>
        
        <div class="md:col-span-2 text-center py-4 md:py-0">
            @if($log->status === 'approved')
                <span class="text-xs font-black text-secondary uppercase border-b-2 border-secondary">Approved</span>
            @elseif($log->status === 'pending')
                <span class="text-xs font-black text-amber-500 uppercase border-b-2 border-amber-500">Pending</span>
            @else
                <span class="text-xs font-black text-red-400 uppercase border-b-2 border-red-400">Rejected</span>
            @endif
        </div>
    </div>
@empty
    <div class="bg-stone-950/30 pixel-border p-20 text-center text-on-surface-variant italic">
        <span class="material-symbols-outlined text-5xl mb-4 opacity-20">history</span>
        <p class="text-sm">Belum ada riwayat aktivitas yang tercatat.</p>
    </div>
@endforelse
