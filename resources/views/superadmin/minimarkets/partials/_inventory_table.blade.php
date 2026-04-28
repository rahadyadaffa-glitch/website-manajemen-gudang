@forelse($inventory as $item)
    <tr class="hover:bg-gray-50/50 transition-colors">
        <td class="px-6 py-4">
            <p class="text-sm font-bold text-gray-900">{{ $item->product->name }}</p>
            <p class="text-[10px] text-gray-400">SKU: {{ $item->product->sku }}</p>
        </td>
        <td class="px-6 py-4 text-center">
            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-lg uppercase">
                {{ $item->product->category->name }}
            </span>
        </td>
        <td class="px-6 py-4 text-right font-bold text-gray-900">
            {{ number_format($item->quantity) }}
        </td>
        <td class="px-6 py-4 text-center">
            @if($item->quantity <= 10)
                <span class="px-2 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg uppercase">Low Stock</span>
            @else
                <span class="px-2 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg uppercase">Secure</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="px-6 py-16 text-center text-gray-400 font-medium text-sm">
            Data tidak ditemukan
        </td>
    </tr>
@endforelse
