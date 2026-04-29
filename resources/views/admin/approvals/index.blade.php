<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-xs font-black uppercase tracking-widest text-gray-400 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">DASHBOARD</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-blue-600">APPROVAL INVENTORI</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    PERMINTAAN APPROVAL
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Setujui atau tolak pengajuan arus barang dari staff</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30 flex items-center justify-between">
            <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Antrean Pengajuan</h3>
            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded-full uppercase">{{ $pending_transactions->total() }} Menunggu</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu & Petugas</th>
                        <th class="px-6 py-4">Barang / SKU</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pending_transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-[11px] font-black text-gray-900">{{ $trx->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-[10px] text-gray-500 font-medium uppercase tracking-tighter">{{ $trx->user->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($trx->proof_image_path)
                                        <div class="flex-shrink-0 h-10 w-10 mr-3">
                                            <img class="h-10 w-10 rounded-lg object-cover border border-gray-200" src="{{ asset('storage/' . $trx->proof_image_path) }}" alt="Bukti">
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">{{ $trx->product->name }}</p>
                                        <p class="text-[9px] text-gray-400 font-medium">{{ $trx->product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trx->transaction_type === 'in')
                                    <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-lg uppercase">MASUK</span>
                                @else
                                    <span class="text-[10px] font-black text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">KELUAR</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-black text-gray-900">
                                {{ number_format($trx->quantity) }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[10px] text-gray-500 italic max-w-xs truncate">{{ $trx->notes ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <form action="{{ route('admin.approvals.approve', $trx) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm border border-green-100" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <button onclick="openRejectModal('{{ $trx->id }}')" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100" title="Tolak">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400">Tidak ada pengajuan yang perlu diproses</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pending_transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $pending_transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRejectModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-black text-gray-900 uppercase" id="modal-title">Tolak Pengajuan</h3>
                                <div class="mt-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Alasan Penolakan</label>
                                    <textarea name="notes" rows="3" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                                        placeholder="Tuliskan alasan mengapa pengajuan ini ditolak..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest">
                            Konfirmasi Tolak
                        </button>
                        <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');

        function openRejectModal(id) {
            form.action = `/admin/approvals/${id}/reject`;
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            modal.classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>
