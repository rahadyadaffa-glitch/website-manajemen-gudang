<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('superadmin.admins.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Admin: {{ $admin->name }}</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('superadmin.admins.update', $admin) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $admin->username) }}" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="minimarket_id" class="block text-sm font-semibold text-gray-700 mb-1">Penugasan Minimarket</label>
                        <select name="minimarket_id" id="minimarket_id" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @foreach($minimarkets as $mm)
                                <option value="{{ $mm->id }}" {{ old('minimarket_id', $admin->minimarket_id) == $mm->id ? 'selected' : '' }}>
                                    {{ $mm->name }} ({{ $mm->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('minimarket_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-1">Status Akun</label>
                        <select name="is_active" id="is_active" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="1" {{ old('is_active', $admin->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $admin->is_active) == 0 ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('is_active') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <hr class="my-2 border-gray-100">
                        <p class="text-xs text-gray-500 mb-4">Kosongkan password jika tidak ingin mengubahnya.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-100">
                        Perbarui Data Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
