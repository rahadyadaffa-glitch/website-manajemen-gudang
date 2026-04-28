<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-900">Input Barang Keluar</h1>
        <p class="text-sm text-gray-500 mt-1">Catat pengurangan stok barang (retur/rusak/expired)</p>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="{{ route('user.input.keluar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Produk</label>
                    <select name="product_id" id="product_id" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all select2">
                        <option value="">Cari Produk...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Keluar</label>
                        <input type="number" name="quantity" id="quantity" min="1" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-xl font-bold text-red-600"
                            placeholder="0">
                        @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti (Kondisi Barang)</label>
                        <input type="file" name="proof_image" id="proof_image" 
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-all">
                        @error('proof_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Alasan Keluar</label>
                    <textarea name="notes" id="notes" rows="3" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        placeholder="Sebutkan alasan: Rusak saat bongkar muat, Expired, Retur supplier, dll."></textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Catat Barang Keluar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
