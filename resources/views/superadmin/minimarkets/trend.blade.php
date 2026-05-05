<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-stone-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('superadmin.dashboard') }}" class="hover:text-amber-500 transition-colors">DASHBOARD</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('superadmin.minimarkets.index') }}" class="hover:text-amber-500 transition-colors">KELOLA MINIMARKET</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 uppercase">GRAFIK TREND</li>
                </ol>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">
                TREND: {{ $minimarket->name }}
            </h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit uppercase text-xs font-black italic">
                Visualisasi performa barang masuk dan keluar 30 hari terakhir
            </p>
        </div>
        
        <div class="flex flex-col md:flex-row items-end md:items-center gap-4">
            <div class="flex gap-2">
                <a href="{{ route('superadmin.minimarkets.trend', $minimarket) }}" 
                   class="pixel-btn px-6 py-2 font-black text-xs uppercase {{ !request('date') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                    Semua
                </a>
                <a href="{{ route('superadmin.minimarkets.trend', [$minimarket, 'date' => now()->toDateString()]) }}" 
                   class="pixel-btn px-6 py-2 font-black text-xs uppercase {{ request('date') == now()->toDateString() ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                    Hari Ini
                </a>
                <div onclick="document.getElementById('date-input').showPicker()"
                    class="relative pixel-btn bg-surface-container text-on-surface-variant px-6 py-2 font-black text-xs uppercase cursor-pointer {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-primary bg-primary text-stone-950' : '' }}">
                    <input type="date" id="date-input" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                    <span id="date-label">
                        {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                    </span>
                </div>
            </div>

            <a href="{{ route('superadmin.minimarkets.index') }}"
                class="pixel-btn bg-surface-variant text-on-surface px-6 py-2 font-black text-xs uppercase flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-gutter mb-8">
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover">
            <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Produk (Terkini)</p>
            <h4 class="text-3xl font-black text-on-surface">{{ number_format($stats['total_products']) }}</h4>
        </div>
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover">
            <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Stok Saat Ini</p>
            <h4 class="text-3xl font-black text-on-surface">{{ number_format($stats['total_stock']) }}</h4>
        </div>
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover border-l-4 border-l-secondary/50">
            <p class="text-[10px] font-black text-secondary uppercase tracking-widest mb-1">Masuk (Periode)</p>
            <h4 class="text-3xl font-black text-on-surface">+{{ number_format($stats['recent_in']) }}</h4>
        </div>
        <div class="bg-surface-container-highest pixel-box p-6 pixel-box-hover border-l-4 border-l-error/50">
            <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Keluar (Periode)</p>
            <h4 class="text-3xl font-black text-on-surface">-{{ number_format($stats['recent_out']) }}</h4>
        </div>
    </div>

    <div class="space-y-gutter">
        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.minimarkets.show', $minimarket) }}" 
               class="pixel-btn px-8 py-3 font-black text-xs uppercase transition-all {{ Route::is('superadmin.minimarkets.show') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant hover:bg-surface-variant' }}">
                STOK BARANG
            </a>
            <a href="{{ route('superadmin.minimarkets.transactions', $minimarket) }}" 
               class="pixel-btn px-8 py-3 font-black text-xs uppercase transition-all {{ Route::is('superadmin.minimarkets.transactions') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant hover:bg-surface-variant' }}">
                LOG TRANSAKSI
            </a>
            <a href="{{ route('superadmin.minimarkets.trend', $minimarket) }}" 
               class="pixel-btn px-8 py-3 font-black text-xs uppercase transition-all {{ Route::is('superadmin.minimarkets.trend') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant hover:bg-surface-variant' }}">
                GRAFIK TREND
            </a>
        </div>

        <!-- Trend Chart Section -->
        <div class="bg-surface-container pixel-box p-8">
            <div class="flex items-center justify-between mb-8 pb-4 border-b-2 border-stone-800">
                <h3 class="font-headline-md text-headline-md text-on-surface uppercase flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl">analytics</span>
                    Analisis Pergerakan Stok (30 Hari)
                </h3>
                
                <div class="flex items-center gap-6 text-[10px] font-black uppercase tracking-widest text-stone-500">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-secondary rounded-full"></span> MASUK
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-error rounded-full"></span> KELUAR
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="h-[450px]">
                    <canvas id="branchTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('branchTrendChart').getContext('2d');
            
            // Custom Voxel/Pixel Style Chart Configuration
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chart_data['labels']) !!},
                    datasets: [
                        {
                            label: 'Masuk',
                            data: {!! json_encode($chart_data['in']) !!},
                            borderColor: '#84cc16', // secondary
                            backgroundColor: 'rgba(132, 204, 22, 0.1)',
                            borderWidth: 4,
                            pointRadius: 6,
                            pointBackgroundColor: '#84cc16',
                            pointBorderColor: '#1c1c1c',
                            pointBorderWidth: 2,
                            fill: true,
                            tension: 0, // Pixely look
                        },
                        {
                            label: 'Keluar',
                            data: {!! json_encode($chart_data['out']) !!},
                            borderColor: '#ef4444', // error
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 4,
                            pointRadius: 6,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#1c1c1c',
                            pointBorderWidth: 2,
                            fill: true,
                            tension: 0, // Pixely look
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0a0a0a',
                            titleFont: { family: "'Outfit', sans-serif", size: 14, weight: 'bold' },
                            bodyFont: { family: "'Outfit', sans-serif", size: 13 },
                            padding: 15,
                            displayColors: true,
                            borderColor: '#383939',
                            borderWidth: 2,
                            cornerRadius: 0,
                        }
                    },
                    scales: {
                        x: { 
                            grid: { color: '#383939', drawBorder: false }, 
                            ticks: { 
                                font: { family: "'Outfit', sans-serif", size: 11, weight: 'bold' }, 
                                color: '#78716c' 
                            } 
                        },
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#383939', drawBorder: false }, 
                            ticks: { 
                                font: { family: "'Outfit', sans-serif", size: 11, weight: 'bold' }, 
                                color: '#78716c', 
                                padding: 10 
                            } 
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
