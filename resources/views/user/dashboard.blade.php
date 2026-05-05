<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">SELAMAT DATANG, {{ auth()->user()->name }}</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic">
                Staff Gudang di {{ $minimarket->name }}
            </p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-8">
        <a href="{{ route('user.inventory.create', ['type' => 'inputmasuk']) }}" class="group relative bg-surface-container pixel-box p-8 hover:bg-surface-bright transition-all">
            <div class="flex items-center gap-6">
                <div class="flex-shrink-0 w-16 h-16 bg-secondary/20 pixel-border flex items-center justify-center group-hover:bg-secondary transition-colors">
                    <span class="material-symbols-outlined text-secondary group-hover:text-stone-900 text-4xl transition-colors">add_box</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-on-surface uppercase tracking-tight">Input Barang Masuk</h3>
                    <p class="text-xs text-on-surface-variant mt-1 uppercase">Tambah stok barang yang baru datang dari supplier</p>
                </div>
            </div>
            <div class="absolute top-4 right-4 text-stone-700 group-hover:text-secondary">
                <span class="material-symbols-outlined">arrow_forward</span>
            </div>
        </a>

        <a href="{{ route('user.inventory.create', ['type' => 'inputkeluar']) }}" class="group relative bg-surface-container pixel-box p-8 hover:bg-surface-bright transition-all border-l-4 border-l-error/50">
            <div class="flex items-center gap-6">
                <div class="flex-shrink-0 w-16 h-16 bg-error/20 pixel-border flex items-center justify-center group-hover:bg-error transition-colors">
                    <span class="material-symbols-outlined text-error group-hover:text-stone-900 text-4xl transition-colors">outbox</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-on-surface uppercase tracking-tight">Input Barang Keluar</h3>
                    <p class="text-xs text-on-surface-variant mt-1 uppercase">Catat barang retur, rusak, atau expired</p>
                </div>
            </div>
            <div class="absolute top-4 right-4 text-stone-700 group-hover:text-error">
                <span class="material-symbols-outlined">arrow_forward</span>
            </div>
        </a>
    </div>

    <!-- Stats & Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
        <div class="bg-surface-container pixel-box p-6">
            <h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                Ringkasan Anda
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-stone-950 pixel-border">
                    <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Input Hari Ini</span>
                    <span class="text-sm font-black text-amber-500 uppercase">{{ $stats['my_transactions_today'] }} Transaksi</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-stone-950 pixel-border">
                    <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Produk di Gudang</span>
                    <span class="text-sm font-black text-amber-500 uppercase">{{ $stats['total_items'] }} Item</span>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('user.history.index') }}" class="pixel-btn bg-surface-variant hover:bg-surface-bright text-on-surface w-full py-3 font-black text-[10px] uppercase flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">history</span>
                    Lihat Riwayat Saya
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 bg-surface-container pixel-box p-6">
            <h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Info Penting
            </h3>
            <div class="space-y-4">
                <div class="flex p-4 bg-stone-950 pixel-border border-l-4 border-l-primary group">
                    <span class="material-symbols-outlined text-primary mr-3 text-2xl group-hover:scale-110 transition-transform">barcode_scanner</span>
                    <div>
                        <p class="text-xs font-black text-on-surface uppercase tracking-tight">Update Stok</p>
                        <p class="text-[11px] text-on-surface-variant mt-1">Gunakan fitur scan barcode untuk mempercepat input barang masuk ke dalam sistem.</p>
                    </div>
                </div>
                <div class="flex p-4 bg-stone-950 pixel-border border-l-4 border-l-error group">
                    <span class="material-symbols-outlined text-error mr-3 text-2xl group-hover:scale-110 transition-transform">warning</span>
                    <div>
                        <p class="text-xs font-black text-on-surface uppercase tracking-tight">Peringatan</p>
                        <p class="text-[11px] text-on-surface-variant mt-1">Pastikan foto bukti upload jelas untuk barang yang rusak agar proses approval lancar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
