@php
    $user = auth()->user();
    $role = $user->role->name;
@endphp

<!-- SideNavBar (Shared Component) -->
<aside id="sidebar"
    class="bg-stone-900 dark:bg-[#1a1a1a] h-screen w-80 border-r-4 border-stone-950 shadow-[inset_2px_0px_0px_#44403c] flex flex-col overflow-y-auto hidden md:flex shrink-0 sticky top-0 transition-transform duration-300 ease-in-out z-50">
    <div class="p-6 border-b-4 border-black">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-amber-500 text-3xl" data-weight="fill">warehouse</span>
            <div>
                <h2 class="text-lg font-black text-amber-500 font-headline-md text-headline-md tracking-tight uppercase">
                    Inventory</h2>
                <p class="font-label-sm text-label-sm text-stone-400 uppercase">Voxel System v1.0</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 py-4 px-2 space-y-2">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->routeIs('dashboard') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>

        @if($role === 'superadmin')
            <!-- Superadmin Links -->
            <a href="{{ route('superadmin.minimarkets.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->routeIs('superadmin.minimarkets.*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">store</span>
                Manage Store
            </a>

            <a href="{{ route('superadmin.admins.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->routeIs('superadmin.admins.*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                Manage Admin
            </a>

            <a href="{{ route('superadmin.products.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->routeIs('superadmin.products.*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">inventory_2</span>
                Master Products
            </a>

            <a href="{{ route('superadmin.reports.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->is('superadmin/reports*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">analytics</span>
                Reports
            </a>

            <a href="{{ route('superadmin.audit.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->is('superadmin/audit-logs*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">history</span>
                Audit Trail
            </a>
        @endif

        @if($role === 'admin')
            <!-- Admin Links -->
            <a href="{{ route('admin.products.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->is('admin/products*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">inventory_2</span>
                Product List
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->is('admin/users*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">group</span>
                Manage User
            </a>

            <a href="{{ route('admin.approvals.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->is('admin/approvals*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">fact_check</span>
                Stock Approval
            </a>

            <a href="{{ route('admin.audit.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->routeIs('admin.audit.index') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">history</span>
                Audit Trail
            </a>
        @endif

        @if($role === 'user')
            <!-- User Links -->
            <a href="{{ route('user.inventory.create', ['type' => 'inputmasuk']) }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->is('user/inventory/create*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">inventory</span>
                Input Barang
            </a>
            <a href="{{ route('user.history.index') }}"
                class="flex items-center gap-3 p-3 font-bold text-sm uppercase transition-transform hover:scale-[1.02] active:translate-x-1 active:translate-y-1 {{ request()->is('user/history*') ? 'bg-amber-500 text-stone-950 border-2 border-t-white/30 border-l-white/30 border-b-black/40 border-r-black/40' : 'text-stone-400 hover:bg-stone-800' }}">
                <span class="material-symbols-outlined">history</span>
                History
            </a>
        @endif
    </nav>

    <div class="p-4 border-t-4 border-black">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 pixel-box bg-amber-500 flex items-center justify-center text-stone-950 font-black">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-base text-stone-200 truncate max-w-[140px]">{{ $user->name }}</span>
                <span class="font-label-sm text-xs uppercase text-stone-500">{{ $role }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 font-bold text-xs uppercase flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">logout</span>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/60 md:hidden hidden"
    onclick="document.getElementById('sidebar').classList.add('hidden'); this.classList.add('hidden')"></div>