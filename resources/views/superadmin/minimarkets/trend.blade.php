<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-xs font-black uppercase tracking-widest text-gray-400 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('superadmin.dashboard') }}" class="hover:text-blue-600 transition-colors">DASHBOARD</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li><a href="{{ route('superadmin.minimarkets.index') }}" class="hover:text-blue-600 transition-colors">KELOLA MINIMARKET</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-blue-600">TREND ANALYSIS</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    TREND: {{ strtoupper($minimarket->name) }}
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Visualisasi performa barang masuk dan keluar 30 hari terakhir</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    <a href="{{ route('superadmin.minimarkets.trend', $minimarket) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.minimarkets.trend', [$minimarket, 'date' => now()->toDateString()]) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                </div>
                
                <form action="{{ route('superadmin.minimarkets.trend', $minimarket) }}" method="GET" 
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

    <div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Produk (Terkini)</p>
            <h4 class="text-2xl font-black text-gray-900">{{ number_format($stats['total_products']) }}</h4>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Stok Saat Ini</p>
            <h4 class="text-2xl font-black text-gray-900">{{ number_format($stats['total_stock']) }}</h4>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-1">Masuk (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">+{{ number_format($stats['recent_in']) }}</h4>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Keluar (Periode)</p>
            <h4 class="text-2xl font-black text-gray-900">-{{ number_format($stats['recent_out']) }}</h4>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Navigation Tabs -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('superadmin.minimarkets.show', $minimarket) }}" 
               class="px-5 py-2 text-xs font-bold rounded-xl transition-all {{ Route::is('superadmin.minimarkets.show') ? 'bg-white border-2 border-blue-600 text-blue-600 shadow-sm' : 'bg-gray-50 border border-gray-200 text-gray-500 hover:bg-white' }}">
                STOK BARANG
            </a>
            <a href="{{ route('superadmin.minimarkets.trend', $minimarket) }}" 
               class="px-5 py-2 text-xs font-bold rounded-xl transition-all {{ Route::is('superadmin.minimarkets.trend') ? 'bg-white border-2 border-blue-600 text-blue-600 shadow-sm' : 'bg-gray-50 border border-gray-200 text-gray-500 hover:bg-white' }}">
                GRAFIK TREND
            </a>
        </div>

        <!-- Trend Chart Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-base font-black text-gray-900 tracking-tight uppercase">Analisis Pergerakan Stok (30 Hari)</h3>
                
                <div class="flex items-center space-x-6 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> Masuk
                    </div>
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> Keluar
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="h-[400px]">
                    <canvas id="branchTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Section (Audit Log Style) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30 flex items-center justify-between">
                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Log Transaksi Terakhir</h3>
                <a href="#" class="text-xs font-bold text-blue-600 hover:underline uppercase tracking-tight">Selengkapnya &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Waktu</th>
                            <th class="px-6 py-3">Barang</th>
                            <th class="px-6 py-3 text-center">Tipe</th>
                            <th class="px-6 py-3 text-right">Jumlah</th>
                            <th class="px-6 py-3">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-[11px] font-medium text-gray-500 whitespace-nowrap">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ $trx->product->name }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $trx->product->sku }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($trx->transaction_type === 'in')
                                        <span class="text-[10px] font-bold text-green-600 uppercase">MASUK</span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-600 uppercase">KELUAR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-black text-gray-900">
                                    {{ number_format($trx->quantity) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600">
                                    {{ $trx->user->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                    Belum ada transaksi tercatat
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('branchTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chart_data['labels']) !!},
                    datasets: [
                        {
                            label: 'Masuk',
                            data: {!! json_encode($chart_data['in']) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#3b82f6',
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Keluar',
                            data: {!! json_encode($chart_data['out']) !!},
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#ef4444',
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
                            backgroundColor: '#1f2937',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            ticks: { font: { size: 11, weight: 'bold' }, color: '#9ca3af' } 
                        },
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }, 
                            ticks: { font: { size: 11, weight: 'bold' }, color: '#9ca3af', padding: 10 } 
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
