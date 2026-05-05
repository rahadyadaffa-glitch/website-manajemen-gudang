<x-app-layout>
    <!-- Page Header -->
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-6">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">GLOBAL DASHBOARD</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit text-sm italic">
                Pantau performa seluruh jaringan minimarket Anda
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('superadmin.dashboard') }}"
                class="pixel-btn px-4 py-2 font-label-sm text-xs uppercase {{ !request('date') ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                Semua
            </a>
            <a href="{{ route('superadmin.dashboard', ['date' => now()->toDateString()]) }}"
                class="pixel-btn px-4 py-2 font-label-sm text-xs uppercase {{ request('date') == now()->toDateString() ? 'bg-primary text-stone-950' : 'bg-surface-container text-on-surface-variant' }}">
                Hari Ini
            </a>
            <div onclick="document.getElementById('date-input').showPicker()"
                class="relative pixel-btn bg-surface-container text-on-surface-variant px-4 py-2 font-label-sm text-xs uppercase cursor-pointer {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-primary bg-primary text-stone-950' : '' }}">
                <input type="date" id="date-input" name="date" value="{{ request('date') }}"
                    onchange="this.form.submit()"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                <span id="date-label">
                    {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface-container-highest pixel-box p-6 flex items-center pixel-box-hover">
            <div class="p-3 bg-primary/20 rounded-sm pixel-border mr-4">
                <span class="material-symbols-outlined text-primary text-3xl">store</span>
            </div>
            <div>
                <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest">Total Minimarket</p>
                <p class="text-4xl font-black text-on-surface">{{ $stats['total_minimarkets'] }}</p>
            </div>
        </div>

        <div class="bg-surface-container-highest pixel-box p-6 flex items-center pixel-box-hover border-l-4 border-l-secondary/50">
            <div class="p-3 bg-secondary/20 rounded-sm pixel-border mr-4">
                <span class="material-symbols-outlined text-secondary text-2xl">call_received</span>
            </div>
            <div>
                <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest">Barang Masuk</p>
                <p class="text-4xl font-black text-on-surface">{{ number_format($stats['total_in']) }}</p>
            </div>
        </div>

        <div class="bg-surface-container-highest pixel-box p-6 flex items-center pixel-box-hover border-l-4 border-l-error/50">
            <div class="p-3 bg-red-900/20 rounded-sm pixel-border mr-4">
                <span class="material-symbols-outlined text-red-400 text-2xl">call_made</span>
            </div>
            <div>
                <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest">Barang Keluar</p>
                <p class="text-4xl font-black text-on-surface">{{ number_format($stats['total_out']) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
        <!-- Main Chart Area -->
        <div class="lg:col-span-3 bg-surface-container pixel-box p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 space-y-4 md:space-y-0">
                <h3 class="font-headline-md text-headline-md text-on-surface uppercase flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">trending_up</span>
                    Trend Inventory Global
                </h3>
                
                <div class="flex items-center gap-4">
                    <!-- Range Selector -->
                    <form action="{{ route('superadmin.dashboard') }}" method="GET" class="flex bg-stone-950 pixel-input">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        @foreach(['7' => '7D', '30' => '30D', '90' => '90D', 'all' => 'ALL'] as $val => $label)
                            <button type="submit" name="chart_range" value="{{ $val }}"
                                class="px-3 py-1 font-label-sm text-xs uppercase transition-all {{ request('chart_range', '30') == $val ? 'bg-primary text-stone-950 font-black' : 'text-stone-400 hover:bg-stone-800' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </form>
                </div>
            </div>
            <div class="h-80 bg-stone-950 pixel-input p-4">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-3 bg-surface-container pixel-box p-6">
            <div class="flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-primary">list_alt</span>
                <h3 class="font-headline-md text-headline-md text-on-surface uppercase">Aktivitas Terbaru Seluruh Jaringan</h3>
            </div>
            <div class="space-y-3">
                @foreach($recent_activities as $activity)
                    <div class="bg-surface-container-high p-4 pixel-border border-l-4 border-l-primary hover:bg-surface-bright transition-colors cursor-pointer">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-black text-on-surface uppercase">{{ $activity['user'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">
                                    {{ $activity['action'] }}: <span class="text-primary font-bold">{{ $activity['target'] }}</span>
                                </p>
                            </div>
                            <time class="text-xs font-black text-on-surface-variant uppercase">{{ $activity['time'] }}</time>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('inventoryChart').getContext('2d');
        const secondaryColor = '#a8d47a';
        const errorColor = '#ffb4ab';
        const gridColor = 'rgba(255, 255, 255, 0.05)';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_data['labels']) !!},
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: {!! json_encode($chart_data['in']) !!},
                        borderColor: secondaryColor,
                        backgroundColor: 'rgba(168, 212, 122, 0.1)',
                        borderWidth: 4,
                        fill: true,
                        stepped: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: secondaryColor
                    },
                    {
                        label: 'Barang Keluar',
                        data: {!! json_encode($chart_data['out']) !!},
                        borderColor: errorColor,
                        backgroundColor: 'rgba(255, 180, 171, 0.1)',
                        borderWidth: 4,
                        fill: true,
                        stepped: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: errorColor
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1f2020',
                        titleFont: { family: 'Space Grotesk', size: 12 },
                        bodyFont: { family: 'Inter', size: 11 },
                        borderColor: '#a28c81',
                        borderWidth: 2,
                        cornerRadius: 0
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#a28c81', font: { family: 'Space Grotesk', size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: '#a28c81', font: { family: 'Space Grotesk', size: 10 } }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
