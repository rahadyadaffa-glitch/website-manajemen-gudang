@forelse($logs as $log)
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
            <p class="text-[11px] font-black text-gray-900">{{ $log->created_at->format('d/m/Y') }}</p>
            <p class="text-[10px] font-bold text-blue-500">{{ $log->created_at->format('H:i') }} <span class="text-[8px] opacity-60">WIB</span></p>
        </td>
        <td class="px-6 py-4">
            <div class="text-xs font-bold text-gray-900">{{ $log->product->name }}</div>
            <div class="text-[10px] font-mono text-gray-400">{{ $log->product->sku }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-[10px] font-black text-gray-500 uppercase">{{ $log->product->category->name }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-[10px] mr-2">
                    {{ substr($log->user->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-[11px] font-bold text-gray-900">{{ $log->user->name }}</div>
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-tight">{{ $log->user->role->name }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 text-center whitespace-nowrap">
            @if($log->transaction_type == 'in')
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-black bg-green-50 text-green-700 uppercase tracking-wider">
                    MASUK
                </span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-black bg-red-50 text-red-700 uppercase tracking-wider">
                    KELUAR
                </span>
            @endif
        </td>
        <td class="px-6 py-4 text-right font-black text-xs {{ $log->transaction_type == 'in' ? 'text-green-600' : 'text-red-600' }}">
            {{ $log->transaction_type == 'in' ? '+' : '-' }}{{ number_format($log->quantity) }}
        </td>
        <td class="px-6 py-4">
            <p class="text-[10px] text-gray-500 italic max-w-[150px] truncate">{{ $log->notes ?: '-' }}</p>
        </td>
        <td class="px-6 py-4 text-center">
            @if($log->status === 'approved')
                <span class="text-[9px] font-black text-green-600 bg-green-50 px-2 py-0.5 rounded-lg uppercase">DISETUJUI</span>
            @elseif($log->status === 'pending')
                <span class="text-[9px] font-black text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg uppercase">PENDING</span>
            @else
                <span class="text-[9px] font-black text-red-600 bg-red-50 px-2 py-0.5 rounded-lg uppercase">DITOLAK</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-20 text-center text-gray-400 italic font-medium text-sm">
            Belum ada riwayat aktivitas yang tercatat.
        </td>
    </tr>
@endforelse
