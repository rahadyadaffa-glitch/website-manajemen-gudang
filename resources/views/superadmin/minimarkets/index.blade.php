<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-4 border-b-4 border-surface-variant mb-6">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-amber-500 uppercase">Kelola Minimarket</h1>
            <p class="text-on-surface-variant mt-2 border-l-4 border-amber-500 pl-4 bg-surface-container-high/50 py-2 w-fit italic uppercase text-xs font-black">
                Daftar seluruh cabang minimarket dalam jaringan VOXEL WMS
            </p>
        </div>
        <a href="{{ route('superadmin.minimarkets.create') }}" class="pixel-btn bg-primary text-stone-950 px-6 py-3 font-label-sm text-xs uppercase flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add_circle</span>
            Tambah Minimarket Baru
        </a>
    </div>

    <!-- Minimarket List Table -->
    <div class="bg-surface-container pixel-box p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-stone-950/50 text-xs font-black text-on-surface-variant uppercase tracking-widest border-b-2 border-outline-variant">
                    <tr>
                        <th class="px-8 py-5">Kode Cabang</th>
                        <th class="px-8 py-5">Informasi Minimarket</th>
                        <th class="px-8 py-5">Lokasi & Kontak</th>
                        <th class="px-8 py-5 text-center">Status Operasional</th>
                        <th class="px-8 py-5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-outline-variant">
                    @forelse($minimarkets as $minimarket)
                        <tr class="hover:bg-surface-container-high transition-colors group">
                            <td class="px-8 py-6">
                                <span class="bg-stone-950 text-amber-500 px-3 py-1 text-sm font-mono font-black pixel-border uppercase">
                                    {{ $minimarket->code }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-base font-black text-on-surface uppercase group-hover:text-primary transition-colors">{{ $minimarket->name }}</div>
                                <div class="text-xs text-on-surface-variant font-mono mt-1">ID: {{ substr($minimarket->id, 0, 8) }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-on-surface italic truncate max-w-xs">{{ $minimarket->address }}</div>
                                <div class="text-xs text-amber-500/70 font-black uppercase mt-1">
                                    {{ $minimarket->city }}, {{ $minimarket->phone ?? 'No Phone' }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($minimarket->status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 bg-secondary/10 text-secondary text-xs font-black pixel-border uppercase">
                                        <span class="w-2 h-2 bg-secondary rounded-full mr-2 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-stone-900 text-stone-500 text-xs font-black pixel-border uppercase">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('superadmin.minimarkets.show', $minimarket) }}" class="pixel-btn bg-surface-variant text-on-surface p-2 hover:bg-stone-700 transition-colors" title="Lihat Dashboard Cabang">
                                        <span class="material-symbols-outlined text-sm">dashboard</span>
                                    </a>
                                    <a href="{{ route('superadmin.minimarkets.edit', $minimarket) }}" class="pixel-btn bg-amber-500/20 text-amber-500 p-2 hover:bg-amber-500/30 transition-colors" title="Edit Data Cabang">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    <form id="delete-form-{{ $minimarket->id }}" action="{{ route('superadmin.minimarkets.destroy', $minimarket) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                            onclick="confirmDelete('delete-form-{{ $minimarket->id }}', '{{ $minimarket->name }}')"
                                            class="pixel-btn bg-red-500/20 text-red-400 p-2 hover:bg-red-500/30 transition-colors" title="Arsipkan Cabang">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-on-surface-variant italic font-black uppercase tracking-widest bg-stone-950/20">
                                <span class="material-symbols-outlined text-5xl mb-4 opacity-20 block">storefront</span>
                                Belum ada data minimarket terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
