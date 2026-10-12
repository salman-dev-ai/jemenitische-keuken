{{-- القائمة الجانبية (شاشات كبيرة) — تبديل فوري عبر Alpine --}}
<aside class="hidden lg:block lg:col-span-3 mt-20">
    <div class="sticky top-24 bg-white rounded-2xl border border-stone-200 shadow-sm p-2 space-y-1">

        <button type="button" @click="selected = 'all'"
            :class="selected === 'all'
                ? 'bg-[#2C0D0A] text-white shadow-md'
                : 'text-stone-600 hover:bg-stone-50 hover:text-[#2C0D0A]'"
            class="w-full text-start flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-300">
            <div class="flex items-center gap-3">
                <x-lucide-layout-grid x-show="selected === 'all'" class="h-5 w-5" aria-hidden="true" />
                <x-lucide-circle x-show="selected !== 'all'" class="h-2 w-2 fill-current" aria-hidden="true" />
                <span class="text-sm font-bold">{{ __('messages.menu.all') }}</span>
            </div>
        </button>

        <div class="h-px bg-stone-100 my-1"></div>

        @foreach ($this->categories as $cat)
            <button type="button" wire:key="tab-btn-{{ $cat->id }}" x-data="{ slug: @js($cat->slug) }"
                @click="selected = slug"
                :class="selected === slug
                    ? 'bg-[#E07513] text-white shadow-md shadow-amber-500/20'
                    : 'text-stone-600 hover:bg-stone-50 hover:text-[#2C0D0A]'"
                class="group w-full text-start flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-300">
                <div class="flex items-center gap-3">
                    <x-lucide-check x-show="selected === slug" class="h-4 w-4" aria-hidden="true" />
                    <x-lucide-circle x-show="selected !== slug" class="h-2 w-2 fill-current" aria-hidden="true" />
                    <span class="font-bold text-sm">{{ $cat->localized_name }}</span>
                </div>
                <span :class="selected === slug ? 'bg-white/20 text-white' : 'bg-stone-100 text-stone-500 group-hover:bg-stone-200'"
                    class="text-[10px] font-black px-2 py-1 rounded-lg transition-colors">
                    {{ $cat->menu_items_count }}
                </span>
            </button>
        @endforeach
    </div>
</aside>
