

<section id="menu" class="py-20 bg-[#FAF6F0] text-[#2C1810] relative overflow-hidden border-b border-[#E8DFD3]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- رأس القسم --}}
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-[#E07513]/10 text-[#E07513] text-xs font-bold">
                <span>✨</span>
                <span>{{ __('messages.menu.badge') ?? 'الأصناف الملكية' }}</span>
            </div>

            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#2C0D0A]">
                {{ __('messages.menu.title') ?? 'أقسام المأكولات اليمنية التراثية' }}
            </h2>

            <p class="text-stone-600 text-xs sm:text-sm">
                {{ __('messages.menu.subtitle') ?? 'قائمة غنية مصنفة بعناية وفق أعلى معايير الجودة والمذاق اليماني الأصيل' }}
            </p>
        </div>

        {{-- 1. بطاقات الأقسام البصرية الفاخرة --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
            @foreach($this->categories as $category)
                @php
                    $isSelected = ($selectedCategorySlug === $category->slug);
                @endphp
                <div wire:key="cat-card-{{ $category->id }}"
                     wire:click="selectCategory('{{ $isSelected ? 'all' : $category->slug }}')"
                     class="group relative rounded-3xl p-5 cursor-pointer transition-all duration-300 overflow-hidden flex flex-col justify-between select-none border {{ $isSelected ? 'bg-gradient-to-b from-[#2E0F0B] to-[#1F0906] text-white border-[#E07513] ring-2 ring-[#E07513]/30 shadow-xl transform -translate-y-1' : 'bg-white hover:bg-[#FFFDF9] text-[#2C1810] border-stone-200 hover:border-[#E07513]/40 shadow-xs hover:shadow-md' }}">

                    @if($category->image_path)
                        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-300 {{ $isSelected ? 'opacity-20' : 'opacity-10 group-hover:opacity-15' }}"
                             style="background-image: url('{{ asset($category->image_path) }}');"></div>
                    @endif

                    <div class="relative z-10 space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-lg {{ $isSelected ? 'bg-[#E07513] text-white shadow-md' : 'bg-[#FAF4ED] text-[#E07513] border border-[#E07513]/20' }}">
                                🍽️
                            </div>

                            <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full {{ $isSelected ? 'bg-white/20 text-amber-200' : 'bg-stone-100 text-stone-600' }}">
                                {{ $category->menu_items_count }} {{ __('messages.menu.dishes') ?? 'أطباق' }}
                            </span>
                        </div>

                        <div>
                            <h3 class="font-black text-sm sm:text-base leading-snug tracking-tight {{ $isSelected ? 'text-white' : 'text-[#2C0D0A] group-hover:text-[#E07513]' }}">
                                {{ $category->localized_name }}
                            </h3>
                            @if($category->localized_description)
                                <p class="text-[11px] leading-relaxed mt-1 line-clamp-2 {{ $isSelected ? 'text-stone-300' : 'text-stone-500' }}">
                                    {{ $category->localized_description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="relative z-10 pt-3 mt-3 border-t border-stone-200/50 flex items-center justify-between text-[10px] font-bold">
                        <span class="{{ $isSelected ? 'text-amber-300' : 'text-[#E07513]' }}">
                            {{ $isSelected ? '✓ محدد' : 'استعراض الأطباق' }}
                        </span>
                        <span class="font-mono text-stone-400">#{{ $category->sort_order }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 2. شريط التبويبات الفوري --}}
        <div class="flex flex-wrap items-center justify-center gap-2 mb-8 bg-[#EFE8DC] p-1.5 rounded-2xl max-w-4xl mx-auto border border-[#DFD5C6]">
            <button wire:click="selectCategory('all')"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all cursor-pointer {{ $selectedCategorySlug === 'all' ? 'bg-[#2C0D0A] text-white shadow-sm' : 'text-stone-700 hover:text-stone-900 hover:bg-white/60' }}">
                <span>{{ __('messages.menu.all') ?? 'جميع الأصناف' }}</span>
            </button>

            @foreach($this->categories as $cat)
                <button wire:key="tab-btn-{{ $cat->id }}"
                        wire:click="selectCategory('{{ $cat->slug }}')"
                        class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer {{ $selectedCategorySlug === $cat->slug ? 'bg-[#E07513] text-white shadow-sm' : 'text-stone-700 hover:text-stone-900 hover:bg-white/60' }}">
                    <span>{{ $cat->localized_name }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $selectedCategorySlug === $cat->slug ? 'bg-black/25 text-white' : 'bg-stone-200 text-stone-700' }}">
                        {{ $cat->menu_items_count }}
                    </span>
                </button>
            @endforeach
        </div>

        {{-- 3. شبكة الأطباق --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.class="opacity-60 transition-opacity">
            @forelse($this->filteredItems as $item)
                <div wire:key="item-{{ $item->id }}"
                     class="bg-white rounded-3xl p-6 border transition-all duration-300 flex flex-col justify-between text-right relative hover:shadow-xl {{ $item->is_chef_special ? 'border-[#E07513]/40 ring-1 ring-[#E07513]/25 shadow-sm' : 'border-stone-200 hover:border-[#E07513]/30 shadow-xs' }}">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-2.5">
                            <div class="space-y-0.5">
                                <h4 class="text-base font-extrabold text-[#2C0D0A] flex items-center gap-1.5">
                                    <span>{{ $item->localized_name }}</span>
                                    @if($item->is_chef_special)
                                        <span title="طبق الشيف الملكي">⭐</span>
                                    @endif
                                </h4>
                            </div>

                            <div class="bg-[#FAF4ED] px-3.5 py-1.5 rounded-2xl border border-[#E07513]/25 text-center shrink-0">
                                <span class="text-base font-black text-[#E07513]">€{{ number_format($item->price, 2) }}</span>
                            </div>
                        </div>

                        <p class="text-xs text-stone-600 leading-relaxed mt-2 line-clamp-3">
                            {{ $item->localized_description }}
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-stone-100 flex items-center justify-between">
                        <span class="text-[11px] font-bold text-[#E07513] bg-[#E07513]/10 px-2.5 py-0.5 rounded-lg">
                            {{ $item->category?->localized_name }}
                        </span>

                        <a href="#reservation" class="text-xs font-bold text-[#2C0D0A] hover:text-[#E07513] transition-colors">
                            {{ __('messages.menu.orderNow') ?? 'احجز لتذوقه' }} ←
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-stone-500">
                    لا توجد أطباق متاحة حالياً في هذا القسم.
                </div>
            @endforelse
        </div>

    </div>
</section>
