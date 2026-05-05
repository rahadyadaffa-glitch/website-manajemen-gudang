<div class="space-y-4">
    @forelse($users as $user)
        <div class="bg-surface-container pixel-border p-4 flex flex-col md:grid md:grid-cols-12 md:items-center gap-4 hover:bg-surface-container-high transition-colors group">
            <div class="col-span-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 pixel-box bg-primary flex items-center justify-center text-stone-950 font-black text-sm">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-black text-on-surface uppercase group-hover:text-primary transition-colors text-sm">{{ $user->name }}</p>
                        <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-widest">{{ $user->role->name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <div class="text-xs font-black text-on-surface uppercase tracking-tight">{{ $user->username }}</div>
                <div class="text-[10px] text-stone-500 font-mono">{{ $user->email }}</div>
            </div>
            <div class="col-span-2 text-center">
                @if($user->is_active)
                    <span class="inline-block px-3 py-1 bg-secondary/10 text-secondary border-2 border-secondary/20 text-[10px] font-black pixel-border uppercase">AKTIF</span>
                @else
                    <span class="inline-block px-3 py-1 bg-red-900/20 text-red-400 border-2 border-red-500/20 text-[10px] font-black pixel-border uppercase tracking-tighter">NONAKTIF</span>
                @endif
            </div>
            <div class="col-span-2 flex gap-2 md:justify-end">
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="pixel-btn bg-surface-variant hover:bg-stone-700 text-on-surface p-2.5 font-label-sm text-[10px] uppercase flex items-center justify-center" title="Edit User">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                </a>
                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                        onclick="confirmDelete('delete-form-{{ $user->id }}', '{{ $user->name }}')"
                        class="pixel-btn bg-red-900/20 hover:bg-red-900/40 text-red-400 p-2.5 font-label-sm text-[10px] uppercase flex items-center justify-center" title="Nonaktifkan User">
                        <span class="material-symbols-outlined text-[18px]">block</span>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-surface-container pixel-border p-16 text-center text-on-surface-variant italic font-black uppercase tracking-widest opacity-30">
            <span class="material-symbols-outlined text-5xl mb-4 block">person_off</span>
            Belum ada petugas gudang yang terdaftar.
        </div>
    @endforelse

    @if($users->hasPages())
        <div class="mt-8 ajax-pagination">
            {{ $users->links() }}
        </div>
    @endif
</div>
