<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi Saya</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar pengajuan barang masuk dan keluar Anda</p>
            </div>
            <a href="{{ route('user.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition-all text-sm">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu Pengajuan</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4">Alasan / Catatan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-[11px] font-medium text-gray-500">
                                {{ $trx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-900">{{ $trx->product->name }}</p>
                                <p class="text-[9px] text-gray-400 font-medium uppercase tracking-tighter">SKU: {{ $trx->product->sku }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trx->transaction_type === 'in')
                                    <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase">MASUK</span>
                                @else
                                    <span class="text-[9px] font-black text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">KELUAR</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-black text-gray-900">
                                {{ number_format($trx->quantity) }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 italic">
                                {{ $trx->notes ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trx->status === 'pending')
                                    <span class="text-[9px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-lg uppercase">MENUNGGU</span>
                                @elseif($trx->status === 'approved')
                                    <span class="text-[9px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-lg uppercase">DISETUJUI</span>
                                @else
                                    <span class="text-[9px] font-black text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">DITOLAK</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                Belum ada riwayat pengajuan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
