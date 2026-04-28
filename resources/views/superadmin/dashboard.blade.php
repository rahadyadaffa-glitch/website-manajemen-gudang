<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Global Dashboard</h1>
                <p class="text-sm text-gray-500 font-medium mt-1">Pantau performa seluruh jaringan minimarket Anda</p>
            </div>
            
                <div class="flex items-center space-x-2">
                    <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                        <a href="{{ route('superadmin.dashboard') }}" 
                           class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Semua
                        </a>
                        <a href="{{ route('superadmin.dashboard', ['date' => now()->toDateString()]) }}" 
                           class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Hari Ini
                        </a>
                    </div>
                    
                    <form action="{{ route('superadmin.dashboard') }}" method="GET" 
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center">
            <div class="p-3 bg-blue-50 rounded-xl mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Minimarket</p>
                <p class="text-3xl font-black text-gray-900">{{ $stats['total_minimarkets'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center">
            <div class="p-3 bg-green-50 rounded-xl mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Barang Masuk</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_in']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center">
            <div class="p-3 bg-red-50 rounded-xl mr-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Barang Keluar</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_out']) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Chart Area -->
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 space-y-4 md:space-y-0">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Trend Inventory Global</h3>
                
                <div class="flex items-center space-x-6">
                    <!-- Range Selector Pills -->
                    <form action="{{ route('superadmin.dashboard') }}" method="GET" class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        @foreach(['7' => '7D', '30' => '30D', '90' => '90D', 'all' => 'ALL'] as $val => $label)
                            <button type="submit" name="chart_range" value="{{ $val }}"
                                class="px-3 py-1.5 text-[10px] font-black rounded-lg transition-all {{ request('chart_range', '30') == $val ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </form>

                    <div class="flex items-center space-x-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 bg-blue-500 rounded-full mr-2 shadow-sm shadow-blue-200"></span>
                            In
                        </div>
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full mr-2 shadow-sm shadow-red-200"></span>
                            Out
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-80">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru Seluruh Jaringan</h3>
            </div>
            <div class="p-6">
                <ul class="space-y-6">
                    @foreach($recent_activities as $activity)
                        <li class="relative flex gap-x-4">
                            <div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">
                                <div class="w-px bg-gray-200"></div>
                            </div>
                            <div class="relative flex h-6 w-6 flex-none items-center justify-center bg-white">
                                <div class="h-2 w-2 rounded-full bg-gray-200 ring-1 ring-gray-300"></div>
                            </div>
                            <div class="flex-auto">
                                <div class="flex justify-between items-start">
                                    <p class="text-sm font-semibold text-gray-900">{{ $activity['user'] }}</p>
                                    <time class="flex-none text-xs text-gray-500">{{ $activity['time'] }}</time>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $activity['action'] }}: <span class="font-medium text-blue-600">{{ $activity['target'] }}</span>
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('inventoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_data['labels']) !!},
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: {!! json_encode($chart_data['in']) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Barang Keluar',
                        data: {!! json_encode($chart_data['out']) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
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
                        padding: 12,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
