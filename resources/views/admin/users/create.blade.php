<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center self-start px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-black rounded-xl hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest mr-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <div>
                <nav class="flex text-xs font-black uppercase tracking-widest text-gray-400 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">DASHBOARD</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li><a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition-colors">KELOLA USER</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-blue-600">TAMBAH PETUGAS</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    TAMBAH PETUGAS GUDANG
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Daftarkan petugas baru untuk mengelola operasional gudang</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/30">
                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Formulir Data Petugas</h3>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="Contoh: Budi Santoso">
                        @error('name') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="budi_staff">
                            @error('username') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="budi@example.com">
                            @error('email') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Password</label>
                            <input type="password" name="password" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="••••••••">
                            @error('password') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-xs font-black rounded-xl hover:bg-gray-200 transition-all uppercase tracking-widest">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-xs font-black rounded-xl hover:bg-blue-700 transition-all shadow-sm uppercase tracking-widest">
                        Simpan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
