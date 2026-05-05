<x-app-layout>
    <!-- Page Header -->
    <div class="mb-stack-md">
        <h1 class="font-headline-lg text-headline-lg text-primary uppercase drop-shadow-[2px_2px_0_rgba(0,0,0,1)]">
            STORE DASHBOARD</h1>
        <p
            class="font-body-lg text-body-lg text-on-surface-variant mt-2 border-l-4 border-primary pl-4 bg-surface-container-high/50 py-2 w-fit">
            Ringkasan stok & performa cabang {{ $minimarket->name }}
        </p>
    </div>

    <!-- Date Filter -->
    <div class="flex items-center space-x-4 mb-8">
        <div class="flex items-center bg-stone-900 pixel-input p-1">
            <a href="{{ route('admin.dashboard') }}"
                class="px-4 py-2 text-xs font-black uppercase transition-all {{ !request('date') ? 'bg-primary text-stone-950 shadow-sm' : 'text-stone-400 hover:text-stone-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.dashboard', ['date' => now()->toDateString()]) }}"
                class="px-4 py-2 text-xs font-black uppercase transition-all {{ request('date') == now()->toDateString() ? 'bg-primary text-stone-950 shadow-sm' : 'text-stone-400 hover:text-stone-200' }}">
                Hari Ini
            </a>
        </div>

        <form action="{{ route('admin.dashboard') }}" method="GET" onclick="this.querySelector('input').showPicker()"
            class="relative flex items-center bg-stone-900 pixel-input px-4 py-2 hover:bg-stone-800 transition-all cursor-pointer {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-primary' : '' }}">
            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
            <span class="material-symbols-outlined text-stone-400 mr-2 text-sm">calendar_month</span>
            <span
                class="text-xs font-black uppercase {{ request('date') && request('date') != now()->toDateString() ? 'text-primary' : 'text-stone-400' }}">
                {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
            </span>
        </form>
    </div>

    <!-- Stats Grid (Bento Style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <!-- Stat 1 -->
        <div class="bg-surface-container-highest pixel-box p-4 flex flex-col justify-between pixel-box-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-primary text-3xl">inventory_2</span>
                <span
                    class="bg-primary/20 text-primary font-label-sm text-xs px-2 py-1 uppercase border border-primary/50">Total
                    Stok</span>
            </div>
            <div>
                <div class="font-headline-lg text-headline-lg text-on-surface">
                    {{ number_format($stats['total_items']) }}
                </div>
                <div class="font-body-lg text-xs text-on-surface mt-2 italic">Seluruh produk tersedia</div>
            </div>
        </div>
        <!-- Stat 2 (Highlight/Glow) -->
        <div
            class="bg-surface-container-highest pixel-box p-4 flex flex-col justify-between {{ $stats['pending_approval'] > 0 ? 'pixel-glow' : '' }}">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-tertiary text-3xl">fact_check</span>
                <span
                    class="bg-tertiary/20 text-tertiary font-label-sm text-xs px-2 py-1 uppercase border border-tertiary/50">Menunggu
                    Approval</span>
            </div>
            <div>
                <div class="font-headline-lg text-headline-lg text-tertiary drop-shadow-[1px_1px_0_#000]">
                    {{ $stats['pending_approval'] }}
                </div>
                <a class="font-label-sm text-xs text-tertiary mt-2 uppercase flex items-center hover:underline cursor-pointer group"
                    href="{{ route('admin.approvals.index') }}">
                    Kelola <span
                        class="material-symbols-outlined text-sm ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>
        <!-- Stat 3 -->
        <div
            class="bg-surface-container-highest pixel-box p-4 flex flex-col justify-between pixel-box-hover border-l-4 border-l-secondary/50">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-secondary text-3xl">call_received</span>
                <span
                    class="bg-secondary/20 text-secondary font-label-sm text-xs px-2 py-1 uppercase border border-secondary/50">Masuk
                    (Periode)</span>
            </div>
            <div>
                <div class="font-headline-lg text-headline-lg text-secondary">
                    +{{ number_format($stats['total_in_period']) }}
                </div>
                <div class="font-body-lg text-xs text-on-surface mt-2 italic">Berdasarkan filter aktif</div>
            </div>
        </div>
        <!-- Stat 4 -->
        <div
            class="bg-surface-container-highest pixel-box p-4 flex flex-col justify-between pixel-box-hover border-l-4 border-l-error/50">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-error text-3xl">call_made</span>
                <span
                    class="bg-error/20 text-error font-label-sm text-xs px-2 py-1 uppercase border border-error/50">Keluar
                    (Periode)</span>
            </div>
            <div>
                <div class="font-headline-lg text-headline-lg text-error">
                    -{{ number_format($stats['total_out_period']) }}
                </div>
                <div class="font-label-sm text-xs text-on-surface-variant mt-2 uppercase italic">Berdasarkan filter
                    aktif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Activity Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mt-stack-md">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-surface-container pixel-box p-6 flex flex-col">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h2 class="font-headline-md text-headline-md text-on-surface uppercase flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">trending_up</span>
                    TREND ARUS BARANG
                </h2>
                <div class="flex flex-wrap gap-2">
                    <form id="trend-filter-form" action="{{ route('admin.dashboard') }}" method="GET"
                        class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <input type="hidden" name="chart_range" value="{{ request('chart_range', '7') }}">

                        <select id="parent-category-filter" name="parent_category_id" onchange="handleParentChange()"
                            class="bg-stone-950 text-on-surface pixel-input px-3 py-1 font-label-sm text-xs uppercase focus:outline-none appearance-none pr-8 relative">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent_category_id') == $parent->id ? 'selected' : '' }}>
                                    {{ strtoupper($parent->name) }}
                                </option>
                            @endforeach
                        </select>

                        <select id="sub-category-filter" name="category_id" onchange="this.form.submit()"
                            class="bg-stone-950 text-on-surface pixel-input px-3 py-1 font-label-sm text-xs uppercase focus:outline-none appearance-none pr-8">
                            <option value="">Semua Sub-Kategori</option>
                            @if(request('parent_category_id'))
                                @php $selParent = $categories->firstWhere('id', request('parent_category_id')); @endphp
                                @if($selParent)
                                    @foreach($selParent->children as $child)
                                        <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>
                                            {{ strtoupper($child->name) }}
                                        </option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </form>

                    <div class="flex bg-stone-950 pixel-input">
                        @foreach(['7' => '7H', '30' => '30H', '90' => '90H'] as $val => $label)
                            <a href="{{ request()->fullUrlWithQuery(['chart_range' => $val]) }}"
                                class="px-3 py-1 font-label-sm text-xs uppercase transition-all {{ request('chart_range', '7') == $val ? 'bg-primary text-stone-950 font-black' : 'text-stone-400 hover:bg-stone-800' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Real Chart JS Area -->
            <div class="flex-1 min-h-[300px] bg-stone-950 pixel-input p-4 relative">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-1 bg-surface-container pixel-box p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-headline-md text-headline-md text-on-surface uppercase flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">list_alt</span>
                    LOG AKTIVITAS
                </h2>
            </div>
            <div class="flex-1 overflow-y-auto pr-2 space-y-3">
                @forelse($recent_transactions as $trx)
                    <div
                        class="bg-surface-container-high p-3 border-l-4 {{ $trx->transaction_type === 'in' ? 'border-secondary' : 'border-error' }} flex flex-col gap-2 hover:bg-surface-bright transition-colors cursor-pointer">
                        <div class="flex justify-between items-start">
                            <div class="font-label-sm text-xs text-on-surface-variant uppercase tracking-tighter">
                                {{ $trx->created_at->diffForHumans() }}
                            </div>
                            <span
                                class="{{ $trx->transaction_type === 'in' ? 'bg-secondary/20 text-secondary border-secondary/50' : 'bg-error/20 text-error border-error/50' }} font-label-sm text-[11px] px-1 border uppercase">
                                {{ $trx->transaction_type === 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </div>
                        <div>
                            <div class="font-body-lg text-sm text-on-surface leading-tight">{{ $trx->productVariant->product->name }}</div>
                            <div class="font-label-sm text-xs text-amber-500 font-black uppercase mt-0.5">{{ $trx->productVariant->weight_value }} {{ $trx->productVariant->weight_unit }}</div>
                            <div class="font-label-sm text-xs text-on-surface-variant mt-1 uppercase">Oleh:
                                {{ $trx->user->name }}
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center mt-1 pt-2 border-t-2 border-dashed border-outline-variant">
                            <div class="font-headline-md text-base text-on-surface">
                                {{ $trx->transaction_type === 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                                {{ $trx->productVariant->unit ?? 'Pcs' }}
                            </div>
                            <span
                                class="{{ $trx->status === 'approved' ? 'text-secondary' : ($trx->status === 'pending' ? 'text-tertiary' : 'text-error') }} font-label-sm text-xs uppercase flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">
                                    {{ $trx->status === 'approved' ? 'check_circle' : ($trx->status === 'pending' ? 'schedule' : 'cancel') }}
                                </span>
                                {{ strtoupper($trx->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 italic text-stone-500 text-xs">Belum ada aktivitas</div>
                @endforelse
            </div>
            <a href="{{ route('admin.audit.index') }}"
                class="w-full mt-4 py-2 bg-surface-variant text-on-surface font-label-sm text-xs uppercase pixel-box border-outline hover:bg-surface-bright transition-colors text-center cursor-pointer block">
                Lihat Semua Log ->
            </a>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const categoryData = @json($categories);

            function handleParentChange() {
                const parentId = document.getElementById('parent-category-filter').value;
                const subSelect = document.getElementById('sub-category-filter');

                subSelect.innerHTML = '<option value="">Semua Sub-Kategori</option>';

                if (!parentId) {
                    document.getElementById('trend-filter-form').submit();
                    return;
                }

                const parent = categoryData.find(c => c.id == parentId);
                if (parent && parent.children) {
                    parent.children.forEach(child => {
                        const opt = document.createElement('option');
                        opt.value = child.id;
                        opt.text = child.name.toUpperCase();
                        subSelect.add(opt);
                    });
                }

                document.getElementById('trend-filter-form').submit();
            }

            const ctx = document.getElementById('inventoryChart').getContext('2d');

            // Pixel-themed chart colors
            const primaryColor = '#ffb68c';
            const secondaryColor = '#a8d47a';
            const errorColor = '#ffb4ab';
            const gridColor = 'rgba(255, 255, 255, 0.05)';

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chart_data['labels']) !!},
                    datasets: [
                        {
                            label: 'Masuk',
                            data: {!! json_encode($chart_data['in']) !!},
                            borderColor: secondaryColor,
                            backgroundColor: 'rgba(168, 212, 122, 0.1)',
                            borderWidth: 4,
                            fill: true,
                            stepped: true, // Making it look more "pixelated" or discrete
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointBackgroundColor: secondaryColor
                        },
                        {
                            label: 'Keluar',
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
                        legend: {
                            display: false
                        },
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
                            ticks: {
                                color: '#a28c81',
                                font: { family: 'Space Grotesk', size: 10 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: {
                                color: '#a28c81',
                                font: { family: 'Space Grotesk', size: 10 }
                            }
                        }
                    }
                }
            });

            // Auto-refresh every 30 seconds for "real-time" feel
            setTimeout(() => {
                window.location.reload();
            }, 30000);
        </script>
    @endpush
</x-app-layout>