@forelse($inventory as $item)
    <tr class="hover:bg-gray-50/50 transition-colors">
        <td class="px-6 py-4">
            <p class="text-sm font-bold text-gray-900">{{ $item->product->name }}</p>
            <p class="text-sm text-gray-500 font-medium">Update: {{ $item->last_updated->format('d/m/Y H:i') }}</p>
        </td>
        <td class="px-6 py-4 text-center">
            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded text-xs font-black uppercase">
                {{ $item->product->category->name }}
            </span>
        </td>
        <td class="px-6 py-4">
            <p class="text-sm font-bold text-gray-700">{{ $item->product->sku }}</p>
            <p class="text-sm text-gray-400">{{ $item->product->barcode ?? '-' }}</p>
        </td>
        <td class="px-6 py-4 text-right">
            <span class="text-lg font-black text-gray-900">{{ number_format($item->quantity) }}</span>
            <span class="text-sm text-gray-500 font-bold uppercase ml-1">{{ $item->product->unit }}</span>
        </td>
        <td class="px-6 py-4 text-center">
            @if($item->quantity <= $item->product->min_stock_threshold)
                <span class="text-xs font-black text-red-600 bg-red-50 px-3 py-2 rounded-lg uppercase tracking-wider">REJECTED</span>
            @else
                <span class="text-xs font-black text-green-600 bg-green-50 px-3 py-2 rounded-lg uppercase tracking-wider">APPROVED</span>
            @endif
        </td>
        <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-2">
                <a href="{{ route('admin.products.show', array_merge([$item->product], request()->only(['date', 'category_id', 'search', 'status']))) }}" 
                   class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 text-xs font-black rounded-lg hover:bg-blue-600 hover:text-white transition-all border border-blue-100 uppercase tracking-tight"
                   title="Detail">
                    Detail
                </a>
                <a href="{{ route('admin.products.edit', array_merge([$item->product], request()->only(['date', 'category_id', 'search', 'status']))) }}" 
                   class="inline-flex items-center px-3 py-2 bg-amber-50 text-amber-600 text-xs font-black rounded-lg hover:bg-amber-600 hover:text-white transition-all border border-amber-100 uppercase tracking-tight"
                   title="Edit">
                    Edit
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic text-base font-medium">
            Belum ada data produk atau filter tidak ditemukan.
        </td>
    </tr>
@endforelse
