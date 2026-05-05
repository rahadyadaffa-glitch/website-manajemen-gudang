<!-- TopNavBar (Shared Component) -->
<header class="bg-stone-800 dark:bg-stone-900 flex justify-between items-center w-full px-6 h-16 border-b-4 border-stone-950 shadow-[inset_2px_2px_0px_#57534e,inset_-2px_-2px_0px_#1c1917] shrink-0 sticky top-0 z-40">
    <div class="flex items-center gap-4">
        <!-- Mobile Sidebar Toggle -->
        <button type="button" class="text-stone-400 md:hidden hover:text-stone-200"
            onclick="document.getElementById('sidebar').classList.remove('hidden'); document.getElementById('sidebar-overlay').classList.remove('hidden')">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <div class="text-xl font-black text-amber-500 uppercase italic truncate max-w-[200px] md:max-w-none">
            @if(auth()->user()->minimarket)
                {{ auth()->user()->minimarket->name }}
            @else
                Central Office
            @endif
        </div>
    </div>

    <div class="flex items-center gap-6 font-['Inter'] font-bold tracking-tight uppercase">
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs text-stone-400 leading-none">SIGNED IN AS</p>
                <p class="text-sm text-amber-500 font-black truncate max-w-[100px]">{{ auth()->user()->name }}</p>
            </div>
            <div class="w-10 h-10 pixel-box border-amber-500 bg-amber-500 flex items-center justify-center text-stone-950 font-black overflow-hidden">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </div>
</header>
