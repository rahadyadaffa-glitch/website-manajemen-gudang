<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="inline-flex items-center self-start px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-black rounded-xl hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest mr-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <div>
                <nav class="flex text-xs font-black uppercase tracking-widest text-gray-400 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">DASHBOARD</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li><a href="{{ route('admin.products.index', request()->only(['date', 'category_id'])) }}" class="hover:text-blue-600 transition-colors">KELOLA PRODUK</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-blue-600">DETAIL PRODUK</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    {{ strtoupper($product->name) }}
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Informasi lengkap detail produk di sistem</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Image & Stock Status -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Product Image -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="aspect-square bg-gray-50 relative">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced Stock Status Card -->
            @php
                $isRejected = ($inventory->quantity ?? 0) <= $product->min_stock_threshold;
            @endphp
            <div class="rounded-3xl p-8 shadow-lg border {{ $isRejected ? 'bg-red-50 border-red-100 ring-4 ring-red-50' : 'bg-green-50 border-green-100 ring-4 ring-green-50' }} transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-xs font-black {{ $isRejected ? 'text-red-400' : 'text-green-400' }} uppercase tracking-widest">Stok Saat Ini (Cabang)</p>
                    <span class="px-3 py-1 {{ $isRejected ? 'bg-red-600' : 'bg-green-600' }} text-white text-[10px] font-black rounded-lg uppercase tracking-wider">
                        {{ $isRejected ? 'REJECTED' : 'APPROVED' }}
                    </span>
                </div>
                
                <div class="flex items-baseline space-x-3">
                    <h4 class="text-6xl font-black {{ $isRejected ? 'text-red-700' : 'text-green-700' }} leading-none tracking-tighter">
                        {{ number_format($inventory->quantity ?? 0) }}
                    </h4>
                    <span class="text-xl font-black {{ $isRejected ? 'text-red-400' : 'text-green-400' }} uppercase tracking-tight">
                        {{ $product->unit }}
                    </span>
                </div>

                <div class="mt-6 pt-6 border-t {{ $isRejected ? 'border-red-200' : 'border-green-200' }}">
                    @if($isRejected)
                        <p class="text-xs font-bold text-red-600 leading-relaxed italic">
                            * Perhatian: Stok telah mencapai atau di bawah batas minimum ({{ $product->min_stock_threshold }}). Segera lakukan penambahan stok!
                        </p>
                    @else
                        <p class="text-xs font-bold text-green-600 leading-relaxed italic">
                            * Ketersediaan stok dalam kondisi aman di atas batas minimum.
                        </p>
                    @endif
                </div>
            </div>
            
        </div>

        <!-- Right Column: Product Details & History -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Product Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200 bg-gray-50/30 flex items-center justify-between">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">Spesifikasi Produk</h3>
                    <span class="text-xs font-mono font-bold text-gray-400">ID: {{ substr($product->id, 0, 8) }}</span>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori Produk</p>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-tight">
                                    {{ $product->category->parent->name ?? 'Kategori Utama' }}
                                </span>
                                <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-sm font-black rounded-lg uppercase self-start">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">SKU / Barcode</p>
                            <p class="text-sm font-black text-gray-900">{{ $product->sku }}</p>
                            <p class="text-xs text-gray-400 font-medium tracking-wider">{{ $product->barcode ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Deskripsi Produk</p>
                        <p class="text-sm text-gray-600 leading-relaxed font-medium">
                            {{ $product->description ?: 'Tidak ada deskripsi produk yang tersedia.' }}
                        </p>
                    </div>

                    @if($product->category->storage_note)
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Instruksi Penyimpanan
                        </p>
                        <p class="text-xs text-amber-800 font-bold leading-relaxed">
                            {{ $product->category->storage_note }}
                        </p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-8 pt-8 border-t border-gray-100">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Satuan Dasar</p>
                            <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $product->unit }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Batas Minimum Stok</p>
                            <p class="text-sm font-black text-gray-900">{{ number_format($product->min_stock_threshold) }} {{ $product->unit }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200 bg-gray-50/30 flex items-center justify-between">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">Riwayat Stok</h3>
                    <span class="px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-full uppercase tracking-tighter shadow-md shadow-blue-100">
                        {{ $date ? \Carbon\Carbon::parse($date)->format('d M Y') : '10 Terbaru' }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-4">Waktu</th>
                                <th class="px-8 py-4">Tipe</th>
                                <th class="px-8 py-4 text-right">Jumlah</th>
                                <th class="px-8 py-4">Petugas</th>
                                <th class="px-8 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm font-medium">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5 text-gray-500">
                                        {{ $trx->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-8 py-5">
                                        @if($trx->transaction_type === 'in')
                                            <span class="font-black text-green-600 uppercase">MASUK</span>
                                        @else
                                            <span class="font-black text-red-600 uppercase">KELUAR</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right font-black text-gray-900">
                                        {{ $trx->transaction_type === 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                                    </td>
                                    <td class="px-8 py-5 text-gray-700">
                                        {{ $trx->user->name }}
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @if($trx->status === 'approved')
                                            <span class="text-[9px] font-black text-green-600 bg-green-50 px-2.5 py-1 rounded-lg uppercase">BERHASIL</span>
                                        @elseif($trx->status === 'pending')
                                            <span class="text-[9px] font-black text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg uppercase">PROSES</span>
                                        @else
                                            <span class="text-[9px] font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-lg uppercase">GAGAL</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-gray-400 italic font-medium">
                                        Tidak ada data transaksi untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>