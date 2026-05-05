<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-8">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">AUDIT TRAIL</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic uppercase text-xs font-black">
                Riwayat aktivitas sistem & pergerakan stok 24 jam terakhir
            </p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="resetFilters()" class="pixel-btn bg-surface-variant text-on-surface px-6 py-2 font-black text-xs uppercase flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">restart_alt</span>
                Reset Filter
            </button>
        </div>
    </div>

    <!-- Enhanced Filter Section -->
    <div class="bg-surface-container pixel-box p-8 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-end">
            <!-- Minimarket Filter -->
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Pilih Cabang</label>
                <select id="minimarket-filter"
                    class="w-full bg-stone-950 border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase">
                    <option value="">SELURUH CABANG</option>
                    @foreach($minimarkets as $minimarket)
                        <option value="{{ $minimarket->id }}" {{ request('minimarket_id') == $minimarket->id ? 'selected' : '' }}>
                            {{ strtoupper($minimarket->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Filter -->
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Tanggal</label>
                <input type="date" id="date-filter" value="{{ request('date', now()->toDateString()) }}"
                    class="w-full bg-stone-950 border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase">
            </div>

            <!-- Time Range -->
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">Rentang Waktu</label>
                <div class="flex items-center gap-4">
                    <div class="flex-1 relative">
                        <input type="time" id="time-start" value="{{ request('time_start') }}"
                            class="w-full bg-stone-950 border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase">
                        <span class="absolute -top-2 left-3 bg-surface-container px-1 text-[8px] font-black text-stone-500 uppercase">Mulai</span>
                    </div>
                    <span class="text-stone-500 font-black">KE</span>
                    <div class="flex-1 relative">
                        <input type="time" id="time-end" value="{{ request('time_end') }}"
                            class="w-full bg-stone-950 border-2 border-outline-variant text-on-surface px-4 py-3 focus:outline-none focus:border-amber-500 pixel-border font-black text-xs uppercase">
                        <span class="absolute -top-2 left-3 bg-surface-container px-1 text-[8px] font-black text-stone-500 uppercase">Selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit List Container -->
    <div id="audit-container" class="relative min-h-[400px]">
        <div id="loading-spinner" class="absolute inset-0 bg-stone-950/50 backdrop-blur-[1px] z-10 flex items-center justify-center opacity-0 pointer-events-none transition-opacity">
            <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
        </div>
        
        <div id="audit-list">
            @include('superadmin.audit.partials._audit_table', ['logs' => $logs])
        </div>
    </div>

    @push('scripts')
    <script>
        let filters = {
            minimarket_id: "{{ request('minimarket_id', '') }}",
            date: "{{ request('date', now()->toDateString()) }}",
            time_start: "{{ request('time_start', '') }}",
            time_end: "{{ request('time_end', '') }}"
        };

        document.addEventListener('DOMContentLoaded', () => {
            const mFilter = document.getElementById('minimarket-filter');
            const dFilter = document.getElementById('date-filter');
            const tStart = document.getElementById('time-start');
            const tEnd = document.getElementById('time-end');

            const performFetch = () => {
                const spinner = document.getElementById('loading-spinner');
                const list = document.getElementById('audit-list');
                
                spinner.classList.remove('opacity-0', 'pointer-events-none');

                const params = new URLSearchParams(filters);
                fetch(`{{ route('superadmin.audit.index') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    list.innerHTML = html;
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                    window.history.replaceState(null, '', `?${params.toString()}`);
                })
                .catch(error => {
                    console.error('Audit fetch failed:', error);
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                });
            };

            mFilter.addEventListener('change', () => {
                filters.minimarket_id = mFilter.value;
                performFetch();
            });

            dFilter.addEventListener('change', () => {
                filters.date = dFilter.value;
                performFetch();
            });

            [tStart, tEnd].forEach(el => {
                el.addEventListener('change', () => {
                    filters.time_start = tStart.value;
                    filters.time_end = tEnd.value;
                    performFetch();
                });
            });

            // Re-bind pagination clicks for AJAX
            document.addEventListener('click', (e) => {
                if (e.target.closest('.ajax-pagination a')) {
                    e.preventDefault();
                    const url = new URL(e.target.closest('a').href);
                    const page = url.searchParams.get('page');
                    filters.page = page;
                    performFetch();
                    window.scrollTo({ top: document.getElementById('audit-container').offsetTop - 100, behavior: 'smooth' });
                }
            });

            window.resetFilters = () => {
                filters = { minimarket_id: '', date: "{{ now()->toDateString() }}", time_start: '', time_end: '' };
                mFilter.value = '';
                dFilter.value = filters.date;
                tStart.value = '';
                tEnd.value = '';
                performFetch();
            };
        });
    </script>
    @endpush
</x-app-layout>
