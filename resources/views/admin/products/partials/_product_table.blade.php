<div class="bg-surface-container pixel-box p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-stone-950 text-xs font-black text-on-surface-variant uppercase tracking-widest border-b-2 border-outline-variant">
                <tr>
                    <th class="px-8 py-5">Informasi Produk</th>
                    <th class="px-8 py-5">Kategori</th>
                    <th class="px-8 py-5">Satuan & Isi</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y-2 divide-outline-variant">
                @forelse($products as $product)
                    <tr class="hover:bg-surface-container-high transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 shrink-0 bg-stone-950 pixel-border overflow-hidden">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-stone-700">
                                            <span class="material-symbols-outlined text-2xl">inventory_2</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-black text-on-surface uppercase group-hover:text-primary transition-colors leading-tight">{{ $product->name }}</p>
                                    <p class="text-[10px] text-stone-500 font-mono mt-1 uppercase tracking-widest">SKU: {{ $product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-1 bg-stone-950 text-amber-500 text-[10px] font-black pixel-border uppercase">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-on-surface uppercase tracking-tighter">{{ $product->unit }}</span>
                                <span class="text-[10px] text-stone-500 font-bold uppercase italic">Isi: {{ $product->pcs_per_dus }} Pcs/Dus</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('admin.products.show', $product->product_id) }}" 
                                class="pixel-btn bg-surface-variant text-on-surface p-2.5 hover:text-primary transition-colors" title="Lihat Detail">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-on-surface-variant italic font-black uppercase tracking-widest opacity-30">
                            <span class="material-symbols-outlined text-5xl mb-4 block">inventory</span>
                            Belum ada produk terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 ajax-pagination">
    {{ $products->links() }}
</div>
