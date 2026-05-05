<div class="space-y-4">
    @forelse($products as $product)
        <div class="bg-surface-container pixel-box p-4 md:p-6 flex flex-col md:flex-row items-center gap-6 group hover:border-amber-500/50 transition-colors">
            <!-- Image -->
            <div class="w-24 h-24 shrink-0 bg-stone-950 pixel-border overflow-hidden">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-stone-700">
                        <span class="material-symbols-outlined text-4xl">inventory_2</span>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-2">
                    <span class="bg-amber-500/10 text-amber-500 px-3 py-1 text-xs font-black uppercase tracking-widest border-2 border-amber-500/20 pixel-border">
                        {{ $product->category->name }}
                    </span>
                    <span class="text-stone-500 text-xs font-mono font-black">SKU: {{ $product->sku }}</span>
                </div>
                <h3 class="text-xl font-black text-on-surface uppercase group-hover:text-primary transition-colors leading-tight">{{ $product->name }}</h3>
                <div class="flex items-center justify-center md:justify-start gap-6 mt-3">
                    <div class="flex items-center gap-2 text-stone-400">
                        <span class="material-symbols-outlined text-sm">scale</span>
                        <span class="text-xs font-black uppercase tracking-tighter">
                            {{ $product->weight_value ? $product->weight_value . ' ' . $product->weight_unit : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-stone-400">
                        <span class="material-symbols-outlined text-sm">package_2</span>
                        <span class="text-xs font-black uppercase tracking-tighter">{{ $product->unit }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <a href="{{ route('superadmin.products.edit', $product) }}" 
                    class="pixel-btn bg-stone-800 text-amber-500 p-3 hover:bg-amber-500 hover:text-stone-950 transition-all"
                    title="Edit">
                    <span class="material-symbols-outlined">edit</span>
                </a>
                <form id="delete-form-{{ $product->id }}" action="{{ route('superadmin.products.destroy', $product) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                        onclick="confirmDelete('delete-form-{{ $product->id }}', '{{ $product->name }}')"
                        class="pixel-btn bg-stone-800 text-red-500 p-3 hover:bg-red-500 hover:text-white transition-all" title="Delete">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-surface-container pixel-border p-20 text-center text-on-surface-variant italic font-black uppercase tracking-widest bg-stone-950/20">
            <span class="material-symbols-outlined text-5xl mb-4 opacity-20 block">inventory</span>
            Belum ada data produk terdaftar.
        </div>
    @endforelse

    <div class="mt-8 ajax-pagination">
        {{ $products->links() }}
    </div>
</div>
