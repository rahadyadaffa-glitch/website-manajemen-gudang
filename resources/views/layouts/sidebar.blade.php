@php
    $user = auth()->user();
    $role = $user->role->name;
@endphp

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-24 px-6 border-b border-gray-200 bg-white">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 object-contain">
                <span class="text-2xl font-black text-blue-600 tracking-tighter uppercase">WMS</span>
            </a>
            <button class="lg:hidden text-white hover:text-gray-200"
                onclick="document.getElementById('sidebar').classList.add('-translate-x-full')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-1">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            @if($role === 'superadmin')
                <!-- Superadmin Links -->
                <a href="{{ route('superadmin.minimarkets.index') }}"
                    class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->routeIs('superadmin.minimarkets.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="font-medium">Kelola Minimarket</span>
                </a>

                <a href="{{ route('superadmin.admins.index') }}"
                    class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->routeIs('superadmin.admins.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium">Kelola Admin</span>
                </a>
                <a href="{{ route('superadmin.reports.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('superadmin/reports*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('superadmin/reports*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 2v-6m10 10V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2z" />
                    </svg>
                    Laporan Konsolidasi
                </a>
                <a href="{{ route('superadmin.audit.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('superadmin/audit-logs*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('superadmin/audit-logs*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Audit Trail
                </a>
            @endif

            @if($role === 'admin')
                <!-- Admin Links -->
                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('admin/products*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/products*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Kelola Produk
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('admin/users*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/users*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelola User
                </a>
                <a href="{{ route('admin.approvals.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('admin/approvals*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/approvals*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Approval Stok
                </a>
                <a href="{{ route('admin.audit.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.audit.index') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.audit.index') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Audit Trail
                </a>
            @endif

            @if($role === 'user')
                <!-- User Links -->
                <a href="{{ route('user.input.masuk.create') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('user/input-barang-masuk*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('user/input-barang-masuk*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Input Barang Masuk
                </a>
                <a href="{{ route('user.input.keluar.create') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('user/input-barang-keluar*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('user/input-barang-keluar*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Input Barang Keluar
                </a>
                <a href="{{ route('user.history.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium {{ request()->is('user/history*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('user/history*') ? 'text-blue-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    History Transaksi
                </a>
            @endif
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                <div
                    class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate capitalize">{{ $role }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 lg:hidden hidden"
    onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden')"></div>