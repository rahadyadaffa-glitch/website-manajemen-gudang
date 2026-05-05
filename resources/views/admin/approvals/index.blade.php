<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-6">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">Antrean Approval</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic uppercase text-[10px] font-black">
                Konfirmasi arus barang masuk dan keluar dari staff operasional
            </p>
        </div>
        <div class="bg-stone-950 px-4 py-2 pixel-border">
            <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">
                {{ $pending_transactions->total() }} Permintaan Menunggu
            </span>
        </div>
    </div>

    <!-- Approval Grid -->
    <div class="space-y-4">
        <!-- Table Header (hidden on mobile) -->
        <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest bg-stone-900/50 pixel-border border-b-2 border-outline-variant mb-2">
            <div class="col-span-2">WAKTU & STAFF</div>
            <div class="col-span-3">PRODUK / VARIAN</div>
            <div class="col-span-2 text-center">TIPE & JUMLAH</div>
            <div class="col-span-3">CATATAN / BUKTI</div>
            <div class="col-span-2 text-right">AKSI CEPAT</div>
        </div>

        @forelse($pending_transactions as $trx)
            <div class="approval-card pixel-box p-4 md:p-0 md:grid md:grid-cols-12 md:items-center gap-4 overflow-hidden">
                <div class="md:col-span-2 md:pl-8 md:py-5">
                    <p class="text-xs font-black text-on-surface">{{ $trx->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-amber-500 font-bold uppercase tracking-tighter mt-1">{{ $trx->user->name }}</p>
                </div>
                
                <div class="md:col-span-3 py-2 md:py-0">
                    <h4 class="text-sm font-black text-on-surface uppercase truncate">{{ $trx->productVariant->product->name }}</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-black text-amber-500 uppercase">{{ $trx->productVariant->weight_value }} {{ $trx->productVariant->weight_unit }}</span>
                        <span class="text-xs text-on-surface-variant font-mono tracking-tighter">{{ $trx->productVariant->sku }}</span>
                    </div>
                </div>

                <div class="md:col-span-2 text-center py-2 md:py-0">
                    <div class="flex flex-col items-center">
                        @if($trx->transaction_type === 'in')
                            <span class="inline-block px-2 py-1 bg-secondary text-stone-950 text-xs font-black pixel-border uppercase mb-1">MASUK</span>
                        @else
                            <span class="inline-block px-2 py-1 bg-red-500 text-stone-950 text-xs font-black pixel-border uppercase mb-1">KELUAR</span>
                        @endif
                        @php
                            $pcsPerDus = $trx->productVariant->pcs_per_dus ?? 1;
                            $isMultiple = $pcsPerDus > 1 && $trx->quantity % $pcsPerDus === 0;
                            $dusCount = $isMultiple ? $trx->quantity / $pcsPerDus : null;
                        @endphp
                        <div class="flex flex-col items-center">
                            <p class="text-xl font-black text-on-surface">
                                {{ number_format($trx->quantity) }} <span class="text-xs text-on-surface-variant">{{ $trx->productVariant->unit }}</span>
                            </p>
                            @if($isMultiple)
                                <div class="mt-1 px-2 py-0.5 bg-amber-500 text-stone-950 text-[10px] font-black pixel-border uppercase">
                                    {{ number_format($dusCount) }} DUS
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3 py-2 md:py-0 flex items-center gap-3">
                    @if($trx->proof_image_path)
                        <div class="relative group cursor-pointer" onclick="window.open('{{ asset('storage/' . $trx->proof_image_path) }}', '_blank')">
                            <img src="{{ asset('storage/' . $trx->proof_image_path) }}" class="w-14 h-14 object-cover pixel-border border-outline-variant group-hover:border-amber-500 transition-colors">
                            <div class="absolute inset-0 bg-stone-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <span class="material-symbols-outlined text-white text-xs">zoom_in</span>
                            </div>
                        </div>
                    @endif
                    <p class="text-xs text-on-surface-variant italic leading-tight line-clamp-2">
                        {{ $trx->notes ?: 'Tidak ada catatan.' }}
                    </p>
                </div>
                
                <div class="md:col-span-2 text-right md:pr-8 py-4 md:py-0">
                    <div class="flex items-center justify-end gap-2">
                        <form action="{{ route('admin.approvals.approve', $trx) }}" method="POST">
                            @csrf
                            <button type="submit" class="pixel-btn bg-secondary text-stone-900 p-2 hover:bg-green-400 transition-colors" title="Setujui">
                                <span class="material-symbols-outlined">check</span>
                            </button>
                        </form>
                        <button onclick="openRejectModal('{{ $trx->id }}')" class="pixel-btn bg-red-500 text-stone-900 p-2 hover:bg-red-400 transition-colors" title="Tolak">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-stone-950/30 pixel-border p-20 text-center text-on-surface-variant italic">
                <span class="material-symbols-outlined text-5xl mb-4 opacity-20">fact_check</span>
                <p class="text-sm">Tidak ada permintaan approval yang tertunda.</p>
            </div>
        @endforelse

        @if($pending_transactions->hasPages())
            <div class="mt-8">
                {{ $pending_transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-stone-950/80 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
            
            <div class="relative bg-surface-container pixel-box border-2 border-red-500 max-w-md w-full p-8 shadow-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-red-500/20 rounded-full">
                        <span class="material-symbols-outlined text-red-500">warning</span>
                    </div>
                    <h3 class="text-xl font-black text-on-surface uppercase tracking-tight">Tolak Pengajuan</h3>
                </div>

                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2">Alasan Penolakan</label>
                        <textarea name="notes" rows="4" required
                            class="w-full bg-stone-950 border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-red-500 pixel-border font-body-lg text-sm"
                            placeholder="Berikan alasan mengapa pengajuan ini ditolak..."></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 pixel-btn bg-red-500 text-stone-900 py-3 font-black text-xs uppercase hover:bg-red-400">
                            Konfirmasi Tolak
                        </button>
                        <button type="button" onclick="closeRejectModal()" class="flex-1 pixel-btn bg-surface-variant text-on-surface py-3 font-black text-xs uppercase hover:bg-stone-700">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .approval-card {
            background: rgba(31, 32, 32, 0.8);
            backdrop-filter: blur(8px);
            border: 2px solid #383939;
            box-shadow: inset 2px 2px 0px rgba(255, 255, 255, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .approval-card:hover {
            border-color: #a8d47a;
            transform: scale(1.005);
            background: rgba(41, 42, 42, 0.9);
        }
    </style>

    @push('scripts')
        <script>
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');

            function openRejectModal(id) {
                form.action = `/admin/approvals/${id}/reject`;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeRejectModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
    @endpush
</x-app-layout>