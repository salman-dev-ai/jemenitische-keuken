{{-- بطاقات الأقسام — الاختيار فوري عبر Alpine (selected) بلا طلب خادم --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-12">
    @foreach ($this->categories as $category)
        <div wire:key="cat-card-{{ $category->id }}" x-data="{ slug: @js($category->slug) }"
            @click="selected = selected === slug ? 'all' : slug"
            :class="selected === slug
                ? 'border-amber-500 shadow-xl shadow-amber-500/20 scale-[1.02]'
                : 'border-transparent hover:border-stone-200 shadow-md hover:shadow-xl'"
            class="group relative rounded-2xl overflow-hidden cursor-pointer transition-all duration-500 border-2"
            role="button" tabindex="0" @keydown.enter="selected = selected === slug ? 'all' : slug">

            <div class="aspect-[4/3] overflow-hidden bg-stone-200">
                @if ($category->image_path)
                    <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->localized_name }}"
                        loading="lazy" width="400" height="300"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-stone-100">
                        <x-lucide-utensils class="w-8 h-8 text-stone-400" aria-hidden="true" />
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-stone-900/90 via-stone-900/40 to-transparent">
                </div>
            </div>

            <div class="absolute bottom-0 inset-x-0 p-4 text-white">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-bold text-sm sm:text-base leading-tight">{{ $category->localized_name }}</h3>
                    <span :class="selected === slug ? 'bg-amber-500 text-white' : 'bg-white/20 text-amber-100'"
                        class="text-[10px] font-bold px-2 py-0.5 rounded-full">
                        {{ $category->menu_items_count }}
                    </span>
                </div>
                @if ($category->localized_description)
                    <p
                        class="text-[11px] text-stone-300 line-clamp-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        {{ $category->localized_description }}
                    </p>
                @endif
            </div>
        </div>
    @endforeach
</div>
