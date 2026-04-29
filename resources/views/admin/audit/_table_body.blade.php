@forelse($logs as $log)
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-8 py-5 whitespace-nowrap">
            <p class="text-sm font-bold text-gray-900">{{ $log->created_at->format('d/m/Y') }}</p>
            <p class="text-xs font-bold text-blue-500">{{ $log->created_at->format('H:i') }} <span class="text-[9px] opacity-60">WIB</span></p>
        </td>
        <td class="px-8 py-5 whitespace-nowrap">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs mr-3">
                    {{ substr($log->user->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-900">{{ $log->user->name }}</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-tight">{{ $log->user->role->name }}</div>
                </div>
            </div>
        </td>
        <td class="px-8 py-5 whitespace-nowrap">
            @if($log->transaction_type == 'in')
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-green-50 text-green-700 uppercase tracking-wider">
                    Barang Masuk
                </span>
            @elseif($log->transaction_type == 'out')
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-red-50 text-red-700 uppercase tracking-wider">
                    Barang Keluar
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-gray-50 text-gray-700 uppercase tracking-wider">
                    Penyesuaian
                </span>
            @endif
        </td>
        <td class="px-8 py-5">
            <div class="text-sm font-bold text-gray-900">{{ $log->product->name }}</div>
            <div class="text-xs font-mono text-gray-400">{{ $log->product->sku }}</div>
        </td>
        <td class="px-8 py-5 whitespace-nowrap text-right font-black text-base {{ $log->transaction_type == 'in' ? 'text-green-600' : 'text-red-600' }}">
            {{ $log->transaction_type == 'in' ? '+' : '-' }}{{ number_format($log->quantity) }}
            <span class="text-[10px] text-gray-400 ml-1">{{ $log->product->unit }}</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-8 py-20 text-center text-gray-400 italic font-medium">
            Belum ada riwayat aktivitas yang tercatat untuk kriteria ini.
        </td>
    </tr>
@endforelse
