<div class="bg-surface-container pixel-box p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-stone-950 text-xs font-black text-stone-500 uppercase tracking-widest border-b-2 border-outline-variant">
                <tr>
                    <th class="px-8 py-6">WAKTU & TANGGAL</th>
                    <th class="px-8 py-6">PENGGUNA</th>
                    <th class="px-8 py-6 text-center">TIPE TRANSAKSI</th>
                    <th class="px-8 py-6 text-right">JUMLAH STOK</th>
                    <th class="px-8 py-6 text-right">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y-2 divide-outline-variant">
                @forelse($logs as $log)
                    <tr class="hover:bg-surface-container-high transition-colors group">
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-on-surface uppercase">{{ $log->created_at->format('d/m/Y') }}</p>
                            <p class="text-[10px] font-bold text-amber-500 uppercase">{{ $log->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 pixel-box bg-surface-variant flex items-center justify-center text-on-surface font-black text-sm">
                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-on-surface uppercase group-hover:text-primary transition-colors">{{ $log->user->name }}</p>
                                    <p class="text-[10px] text-stone-500 font-mono tracking-widest uppercase">ROLE: {{ $log->user->role->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($log->transaction_type == 'in')
                                <span class="inline-flex items-center px-4 py-1.5 bg-secondary/10 text-secondary text-[11px] font-black pixel-border uppercase tracking-widest">
                                    MASUK
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 bg-red-400/10 text-red-400 text-[11px] font-black pixel-border uppercase tracking-widest">
                                    KELUAR
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-xl font-black text-on-surface">{{ number_format($log->quantity) }}</span>
                            <span class="text-[10px] text-stone-500 font-black uppercase ml-1">PCS</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="#" class="pixel-btn bg-stone-900 text-on-surface-variant p-2.5 hover:text-primary transition-colors" title="Lihat Detail Log">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-on-surface-variant italic font-black uppercase tracking-widest opacity-30">
                            <span class="material-symbols-outlined text-5xl mb-4 block">history_toggle_off</span>
                            Belum ada riwayat aktivitas tercatat
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 ajax-pagination">
    {{ $logs->links() }}
</div>
