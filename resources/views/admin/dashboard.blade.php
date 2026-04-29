<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    STORE DASHBOARD
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Ringkasan stok & performa cabang {{ $minimarket->name }}</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.dashboard', ['date' => now()->toDateString()]) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                </div>
                
                <form action="{{ route('admin.dashboard') }}" method="GET" 
                    onclick="this.querySelector('input').showPicker()"
                    class="relative flex items-center bg-gray-100 px-4 py-2 rounded-xl hover:bg-white hover:shadow-sm transition-all cursor-pointer border border-transparent hover:border-blue-100 {{ request('date') && request('date') != now()->toDateString() ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 mr-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-bold pointer-events-none {{ request('date') && request('date') != now()->toDateString() ? 'text-blue-600' : 'text-gray-600' }}">
                        {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Custom' }}
                    </span>
                </form>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Stok</p>
            <h4 class="text-2xl font-black text-gray-900">{{ number_format($stats['total_items']) }}</h4>
            <p class="text-[10px] text-gray-400 mt-2 font-medium">Seluruh produk tersedia</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Menunggu Approval</p>
            <h4 class="text-2xl font-black text-gray-900">{{ $stats['pending_approval'] }}</h4>
            <a href="{{ route('admin.approvals.index') }}" class="text-[10px] text-amber-600 mt-2 font-bold hover:underline">Lihat semua &rarr;</a>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-1">Masuk (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">+{{ number_format($stats['total_in_period']) }}</h4>
            <p class="text-[10px] text-gray-400 mt-2 font-medium">Berdasarkan filter aktif</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-red-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Keluar (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">-{{ number_format($stats['total_out_period']) }}</h4>
            <p class="text-[10px] text-gray-400 mt-2 font-medium">Berdasarkan filter aktif</p>
        </div>
    </div>

    <!-- Chart & Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div>
                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Trend Arus Barang</h3>
                <p class="text-xs text-gray-500 mt-1 font-medium italic">Data berdasarkan transaksi yang telah disetujui</p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <!-- Tiered Category Filter (Identical to Kelola Produk) -->
                <form id="trend-filter-form" action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="date" value="{{ request('date') }}">
                    <input type="hidden" name="chart_range" value="{{ request('chart_range', '7') }}">
                    
                    <select id="parent-category-filter" name="parent_category_id" onchange="handleParentChange()" 
                        class="bg-gray-100 border-none rounded-xl text-xs font-black text-gray-600 px-4 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer min-w-[180px]">
                        <option value="">SEMUA KATEGORI</option>
                        @foreach($categories as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_category_id') == $parent->id ? 'selected' : '' }}>
                                {{ strtoupper($parent->name) }}
                            </option>
                        @endforeach
                    </select>

                    <select id="sub-category-filter" name="category_id" onchange="this.form.submit()" 
                        class="bg-gray-100 border-none rounded-xl text-xs font-black text-gray-600 px-4 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer min-w-[180px] {{ !request('parent_category_id') ? 'opacity-50' : '' }}"
                        {{ !request('parent_category_id') ? 'disabled' : '' }}>
                        <option value="">SEMUA SUB-KATEGORI</option>
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

                <!-- Range Selector -->
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    @foreach(['7' => '7H', '30' => '30H', '90' => '90H'] as $val => $label)
                        <a href="{{ request()->fullUrlWithQuery(['chart_range' => $val]) }}" 
                           class="px-3 py-1.5 text-[10px] font-black rounded-lg transition-all {{ request('chart_range', '7') == $val ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="h-[300px]">
            <canvas id="inventoryChart"></canvas>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30 flex items-center justify-between">
            <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Log Aktivitas Terbaru</h3>
            <a href="{{ route('admin.approvals.index') }}" class="text-xs font-bold text-blue-600 hover:underline uppercase tracking-tight">Kelola Approval &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Barang / Petugas</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recent_transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-[11px] font-medium text-gray-500 whitespace-nowrap">
                                {{ $trx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-900">{{ $trx->product->name }}</p>
                                <p class="text-[9px] text-gray-400 font-medium uppercase tracking-tighter">Oleh: {{ $trx->user->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trx->transaction_type === 'in')
                                    <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-lg uppercase">MASUK</span>
                                @else
                                    <span class="text-[10px] font-black text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">KELUAR</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-black text-gray-900">
                                {{ number_format($trx->quantity) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trx->status === 'pending')
                                    <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-lg uppercase">PENDING</span>
                                @elseif($trx->status === 'approved')
                                    <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-lg uppercase">APPROVED</span>
                                @else
                                    <span class="text-[10px] font-black text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">REJECTED</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                Belum ada aktivitas tercatat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const categoryData = @json($categories);

        function handleParentChange() {
            const parentId = document.getElementById('parent-category-filter').value;
            const subSelect = document.getElementById('sub-category-filter');
            
            if (!parentId) {
                subSelect.disabled = true;
                subSelect.innerHTML = '<option value="">SEMUA SUB-KATEGORI</option>';
                subSelect.classList.add('opacity-50');
                document.getElementById('trend-filter-form').submit();
                return;
            }

            const parent = categoryData.find(c => c.id === parentId);
            subSelect.innerHTML = '<option value="">SEMUA SUB-KATEGORI</option>';
            if (parent && parent.children) {
                parent.children.forEach(child => {
                    const opt = document.createElement('option');
                    opt.value = child.id;
                    opt.text = child.name.toUpperCase();
                    subSelect.add(opt);
                });
            }
            subSelect.disabled = false;
            subSelect.classList.remove('opacity-50');
            document.getElementById('trend-filter-form').submit();
        }

        const ctx = document.getElementById('inventoryChart').getContext('2d');
        
        const inGradient = ctx.createLinearGradient(0, 0, 0, 400);
        inGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        inGradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        const outGradient = ctx.createLinearGradient(0, 0, 0, 400);
        outGradient.addColorStop(0, 'rgba(239, 68, 68, 0.2)');
        outGradient.addColorStop(1, 'rgba(239, 68, 68, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_data['labels']) !!},
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: {!! json_encode($chart_data['in']) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: inGradient,
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Barang Keluar',
                        data: {!! json_encode($chart_data['out']) !!},
                        borderColor: '#ef4444',
                        backgroundColor: outGradient,
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#ef4444',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                        padding: 15,
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleFont: { size: 12, weight: '900', family: 'Inter' },
                        bodyFont: { size: 12, weight: '600', family: 'Inter' },
                        cornerRadius: 12
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: '700' }, color: '#9ca3af' } },
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0, 0, 0, 0.04)', drawBorder: false },
                        ticks: { font: { size: 10, weight: '700' }, color: '#9ca3af', padding: 10 }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
