<x-app-layout>
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-6">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">KELOLA ADMIN</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit">
                Manajemen akun administrator untuk setiap cabang minimarket
            </p>
        </div>
        <a href="{{ route('superadmin.admins.create') }}"
            class="pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 px-5 py-2.5 font-label-sm text-xs font-black uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">person_add</span>
            Tambah Admin
        </a>
    </div>

    <!-- Admins Grid -->
    <div class="space-y-4">
        <!-- Table Header (hidden on mobile) -->
        <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-2 text-on-surface-variant font-label-sm text-xs uppercase font-black tracking-widest">
            <div class="col-span-3">NAMA & USERNAME</div>
            <div class="col-span-3">EMAIL</div>
            <div class="col-span-2 text-center">MINIMARKET</div>
            <div class="col-span-2 text-center">STATUS</div>
            <div class="col-span-2 text-right">AKSI</div>
        </div>

        @forelse($admins as $admin)
            <div
                class="bg-surface-container pixel-border p-4 flex flex-col md:grid md:grid-cols-12 md:items-center gap-4 hover:bg-surface-container-high transition-colors group">
                <div class="col-span-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 pixel-box bg-primary flex items-center justify-center text-stone-950 font-black text-base">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-black text-on-surface text-sm uppercase group-hover:text-primary transition-colors">{{ $admin->name }}</p>
                            <p class="text-xs text-on-surface-variant font-mono mt-0.5">@ {{ $admin->username }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-3">
                    <p class="text-xs font-black text-on-surface-variant uppercase">{{ $admin->email }}</p>
                </div>
                <div class="col-span-2 text-center">
                    <span class="inline-block px-3 py-1 bg-stone-950 text-amber-500 border-2 border-amber-500/50 text-xs font-black pixel-border uppercase">
                        {{ $admin->minimarket->name }}
                    </span>
                </div>
                <div class="col-span-2 text-center">
                    @if($admin->is_active)
                        <span class="inline-flex items-center px-3 py-1 bg-secondary/10 text-secondary border-2 border-secondary/50 text-xs font-black pixel-border uppercase">
                            <span class="w-1.5 h-1.5 bg-secondary rounded-full mr-2 animate-pulse"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 bg-red-900/20 text-red-400 border-2 border-red-500/50 text-xs font-black pixel-border uppercase">Nonaktif</span>
                    @endif
                </div>
                <div class="col-span-2 flex gap-2 md:justify-end">
                    <a href="{{ route('superadmin.admins.edit', $admin) }}"
                        class="pixel-btn bg-surface-variant hover:bg-stone-700 text-on-surface p-2 font-label-sm text-xs uppercase flex items-center justify-center" title="Edit Admin">
                        <span class="material-symbols-outlined text-sm">edit</span>
                    </a>
                    <form id="delete-form-{{ $admin->id }}" action="{{ route('superadmin.admins.destroy', $admin) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                            onclick="confirmDelete('delete-form-{{ $admin->id }}', '{{ $admin->name }}')"
                            class="pixel-btn bg-red-500/20 hover:bg-red-500/30 text-red-400 p-2 font-label-sm text-xs uppercase flex items-center justify-center" title="Hapus Admin">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-surface-container pixel-border p-20 text-center text-on-surface-variant italic text-base uppercase font-black tracking-widest bg-stone-950/20">
                <span class="material-symbols-outlined text-5xl mb-4 opacity-20 block">person_off</span>
                Belum ada data administrator.
            </div>
        @endforelse
    </div>
</x-app-layout>
