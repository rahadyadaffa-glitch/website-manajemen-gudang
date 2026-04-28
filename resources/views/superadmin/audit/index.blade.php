<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 leading-tight">Audit Trail</h1>
                <p class="text-sm text-gray-500 font-medium mt-1">Rekapitulasi lengkap aktivitas gudang & minimarket</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Date Pills -->
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner">
                    <a href="{{ route('superadmin.audit.index', request()->except('date')) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ !request('date') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.audit.index', array_merge(request()->all(), ['date' => now()->toDateString()])) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ request('date') == now()->toDateString() ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                </div>

                <form action="{{ route('superadmin.audit.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    @foreach(request()->except(['date', 'time_start', 'time_end', 'minimarket_id']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <!-- Custom Date -->
                    <div class="relative flex items-center bg-gray-100 px-4 py-2 rounded-xl hover:bg-white hover:shadow-sm transition-all cursor-pointer border border-transparent hover:border-blue-100"
                        onclick="this.querySelector('input[type=date]').showPicker()">
                        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 mr-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-bold pointer-events-none {{ request('date') && request('date') != now()->toDateString() ? 'text-blue-600' : 'text-gray-600' }}">
                            {{ request('date') && request('date') != now()->toDateString() ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Pilih Tanggal' }}
                        </span>
                    </div>

                    <!-- Time Range Filter (Select) -->
                    <div class="flex items-center bg-gray-100 rounded-xl px-3 py-1.5 border border-gray-200">
                        <svg class="w-3.5 h-3.5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        
                        <div class="flex items-center space-x-1">
                            <div class="flex flex-col">
                                <label class="text-[7px] font-black text-gray-400 uppercase leading-none">Dari Jam</label>
                                <select name="time_start" onchange="this.form.submit()"
                                    class="bg-transparent border-none p-0 text-[11px] font-bold text-gray-700 focus:ring-0 h-4 cursor-pointer">
                                    <option value="">--</option>
                                    @for($i=0; $i<24; $i++)
                                        @php $val = sprintf('%02d:00', $i); @endphp
                                        <option value="{{ $val }}" {{ request('time_start') == $val ? 'selected' : '' }}>{{ sprintf('%02d:00', $i) }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <span class="text-gray-300 mx-1">/</span>
                            
                            <div class="flex flex-col">
                                <label class="text-[7px] font-black text-gray-400 uppercase leading-none">Sampai</label>
                                <select name="time_end" onchange="this.form.submit()"
                                    class="bg-transparent border-none p-0 text-[11px] font-bold text-gray-700 focus:ring-0 h-4 cursor-pointer">
                                    <option value="">--</option>
                                    @php 
                                        $startHour = request('time_start') ? (int)substr(request('time_start'), 0, 2) : -1;
                                    @endphp
                                    @for($i=0; $i<24; $i++)
                                        @php 
                                            $val = sprintf('%02d:59', $i); 
                                            $disabled = $i < $startHour;
                                        @endphp
                                        <option value="{{ $val }}" 
                                            {{ request('time_end') == $val ? 'selected' : '' }}
                                            {{ $disabled ? 'disabled class=text-gray-300' : '' }}>
                                            {{ sprintf('%02d:59', $i) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Minimarket Select -->
                    <div class="relative">
                        <select name="minimarket_id" onchange="this.form.submit()" 
                            class="appearance-none bg-white border border-gray-200 pl-4 pr-10 py-2 rounded-xl text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer shadow-sm">
                            <option value="">SEMUA CABANG</option>
                            @foreach($minimarkets as $mm)
                                <option value="{{ $mm->id }}" {{ request('minimarket_id') == $mm->id ? 'selected' : '' }}>
                                    {{ strtoupper($mm->name) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    @if(request()->anyFilled(['date', 'time_start', 'time_end', 'minimarket_id']))
                        <a href="{{ route('superadmin.audit.index') }}" class="text-[10px] font-black text-red-500 hover:underline uppercase tracking-tight">Reset</a>
                    @endif

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-black shadow-sm transition-all flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        CARI
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimarket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-[11px] font-black text-gray-900">{{ $log->created_at->format('d/m/Y') }}</p>
                                <p class="text-[10px] font-bold text-blue-500">{{ $log->created_at->format('H:i') }} <span class="text-[8px] opacity-70">WIB</span></p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $log->minimarket->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $log->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user->role->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->transaction_type == 'in')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        Barang Masuk
                                    </span>
                                @elseif($log->transaction_type == 'out')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Barang Keluar
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                        Penyesuaian
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $log->product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $log->product->sku }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-black {{ $log->transaction_type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $log->transaction_type == 'in' ? '+' : '-' }}{{ number_format($log->quantity) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada riwayat aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
