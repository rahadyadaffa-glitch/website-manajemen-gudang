<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">Staff Gudang di {{ $minimarket->name }}</p>
    </x-slot>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('user.input.masuk.create') }}" class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:border-blue-500 hover:shadow-md transition-all">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0 w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Input Barang Masuk</h3>
                    <p class="text-sm text-gray-500 mt-1">Tambah stok barang yang baru datang dari supplier</p>
                </div>
            </div>
            <div class="absolute top-4 right-4 text-gray-300 group-hover:text-blue-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </a>

        <a href="{{ route('user.input.keluar.create') }}" class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:border-red-500 hover:shadow-md transition-all">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0 w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center group-hover:bg-red-600 transition-colors">
                    <svg class="w-8 h-8 text-red-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Input Barang Keluar</h3>
                    <p class="text-sm text-gray-500 mt-1">Catat barang retur, rusak, atau expired</p>
                </div>
            </div>
            <div class="absolute top-4 right-4 text-gray-300 group-hover:text-red-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Stats & History -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Anda</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Input Hari Ini</span>
                    <span class="text-lg font-bold text-gray-900">{{ $stats['my_transactions_today'] }} Transaksi</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Produk di Gudang</span>
                    <span class="text-lg font-bold text-gray-900">{{ $stats['total_items'] }} Item</span>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('user.history.index') }}" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Lihat Riwayat Saya
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Info Penting</h3>
            </div>
            <div class="space-y-4">
                <div class="flex p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Update Stok</p>
                        <p class="text-sm text-blue-700 mt-1">Gunakan fitur scan barcode untuk mempercepat input barang masuk.</p>
                    </div>
                </div>
                <div class="flex p-4 bg-amber-50 rounded-lg border border-amber-100">
                    <svg class="w-5 h-5 text-amber-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-amber-900">Peringatan</p>
                        <p class="text-sm text-amber-700 mt-1">Pastikan foto bukti upload jelas untuk barang yang rusak.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
