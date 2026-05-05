<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-6">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">KELOLA USER GUDANG</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic uppercase text-xs font-black">
                Kelola akses petugas gudang di cabang {{ auth()->user()->minimarket->name }}
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-5 py-2.5 font-label-sm text-xs font-black uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">person_add</span>
            Tambah Petugas
        </a>
    </div>

    <!-- AJAX Search Bar -->
    <div class="bg-surface-container pixel-border p-4 mb-6">
        <div class="flex items-center bg-stone-950 pixel-input focus-within:ring-2 focus-within:ring-amber-500/50 transition-all group overflow-hidden">
            <span class="material-symbols-outlined pl-4 text-on-surface-variant group-focus-within:text-amber-500 pointer-events-none">search</span>
            <input type="text" id="search-input" value="{{ request('search') }}"
                class="w-full bg-transparent border-none text-on-surface pl-3 pr-4 py-3 focus:ring-0 font-black text-sm uppercase"
                placeholder="Ketik untuk mencari nama, username, atau email petugas..." />
        </div>
    </div>

    <!-- User List Container -->
    <div id="user-container" class="relative min-h-[300px]">
        <div id="loading-spinner" class="absolute inset-0 bg-stone-950/50 backdrop-blur-[1px] z-10 flex items-center justify-center opacity-0 pointer-events-none transition-opacity">
            <div class="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <div id="user-list">
            <!-- Table Header (hidden on mobile) -->
            <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-2 text-on-surface-variant font-label-sm text-[10px] uppercase font-black tracking-widest bg-stone-900/50 mb-2">
                <div class="col-span-4">NAMA LENGKAP</div>
                <div class="col-span-4">USERNAME / EMAIL</div>
                <div class="col-span-2 text-center">STATUS</div>
                <div class="col-span-2 text-right">AKSI</div>
            </div>

            <div id="user-list-body">
                @include('admin.users.partials._user_list', ['users' => $users])
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const listBody = document.getElementById('user-list-body');
            const spinner = document.getElementById('loading-spinner');
            let debounceTimer;

            const performFetch = (search = '', page = 1) => {
                spinner.classList.remove('opacity-0', 'pointer-events-none');

                const params = new URLSearchParams({ search, page });
                fetch(`{{ route('admin.users.index') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    listBody.innerHTML = html;
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                    window.history.replaceState(null, '', `?${params.toString()}`);
                })
                .catch(error => {
                    console.error('User fetch failed:', error);
                    spinner.classList.add('opacity-0', 'pointer-events-none');
                });
            };

            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    performFetch(searchInput.value);
                }, 400);
            });

            // AJAX Pagination
            document.addEventListener('click', (e) => {
                if (e.target.closest('.ajax-pagination a')) {
                    e.preventDefault();
                    const url = new URL(e.target.closest('a').href);
                    const page = url.searchParams.get('page');
                    performFetch(searchInput.value, page);
                    window.scrollTo({ top: document.getElementById('user-container').offsetTop - 100, behavior: 'smooth' });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
