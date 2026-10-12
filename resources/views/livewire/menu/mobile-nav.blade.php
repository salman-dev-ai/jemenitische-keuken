{{-- شريط الأقسام الأفقي (جوال) — تبديل فوري عبر Alpine --}}
<div
    class="lg:hidden col-span-1 overflow-x-auto pb-2 -mx-4 px-4 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
    <div class="flex items-center gap-2 min-w-max">
        <button type="button" @click="selected = 'all'"
            :class="selected === 'all'
                ? 'bg-[#2C0D0A] text-white shadow-md'
                : 'bg-white text-stone-600 border border-stone-200'"
            class="px-4 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all">
            {{ __('messages.menu.all') ?? 'الكل' }}
        </button>

        @foreach ($this->categories as $cat)
            <button type="button" wire:key="mobile-tab-{{ $cat->id }}" x-data="{ slug: @js($cat->slug) }"
                @click="selected = slug"
                :class="selected === slug
                    ? 'bg-[#E07513] text-white shadow-md shadow-amber-500/20'
                    : 'bg-white text-stone-600 border border-stone-200'"
                class="px-4 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2">
                <span>{{ $cat->localized_name }}</span>
                <span :class="selected === slug ? 'bg-black/20 text-white' : 'bg-stone-100 text-stone-600'"
                    class="text-[10px] px-1.5 py-0.5 rounded-full">
                    {{ $cat->menu_items_count }}
                </span>
            </button>
        @endforeach
    </div>
</div>
