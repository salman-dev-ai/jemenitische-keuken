<section id="menu" class="py-10 bg-[#FAF6F0] text-[#2C1810] relative overflow-hidden border-b border-[#E8DFD3]"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        {{-- 1. رأس القسم وبطاقات الأقسام البصرية --}}
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-700 text-xs font-bold border border-amber-500/20">
                <x-lucide-sparkles class="w-4 h-4" /> <span>{{ __('messages.menu.badge') ?? 'الأصناف الملكية' }}</span>
            </div>
            <h2 class="text-3xl sm:text-5xl font-black text-stone-900 tracking-tight">
                {{ __('messages.menu.title') ?? 'أقسام المأكولات اليمنية التراثية' }}
            </h2>
            <p class="text-stone-600 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                {{ __('messages.menu.subtitle') ?? 'قائمة غنية مصنفة بعناية وفق أعلى معايير الجودة، لتمنحك مذاقاً يمنياً أصيلاً بلمسة عصرية في قلب هولندا' }}
            </p>
        </div>

        {{-- بطاقات الأقسام --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-12">
            @foreach ($this->categories as $category)
                @php $isSelected = $selectedCategorySlug === $category->slug; @endphp

                <div wire:key="cat-card-{{ $category->id }}"
                    wire:click="selectCategory('{{ $isSelected ? 'all' : $category->slug }}')"
                    class="group relative rounded-2xl overflow-hidden cursor-pointer transition-all duration-500 border-2 {{ $isSelected ? 'border-amber-500 shadow-xl shadow-amber-500/20 scale-[1.02]' : 'border-transparent hover:border-stone-200 shadow-md hover:shadow-xl' }}">

                    {{-- صورة القسم --}}
                    <div class="aspect-[4/3] overflow-hidden bg-stone-200">
                        @if ($category->image_path)
                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->localized_name }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-stone-100 text-stone-400 text-4xl">
                                <x-lucide-utensils class="w-8 h-8 text-stone-400" />
                            </div>
                        @endif
                        {{-- تدرج لوني لضمان قراءة النص --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-stone-900/90 via-stone-900/40 to-transparent">
                        </div>
                    </div>

                    {{-- محتوى البطاقة --}}
                    <div class="absolute bottom-0 inset-x-0 p-4 text-white">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-sm sm:text-base leading-tight">{{ $category->localized_name }}
                            </h3>
                            <span
                                class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isSelected ? 'bg-amber-500 text-white' : 'bg-white/20 text-amber-100' }}">
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

        {{-- حاوية التخطيط الرئيسية: تقسيم الشاشة إلى قائمة جانبية ومحتوى --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            {{-- ========================================================= --}}
            {{-- 1. القائمة الجانبية العمودية (تظهر على الشاشات الكبيرة) --}}
            {{-- ========================================================= --}}
            <aside class="hidden lg:block lg:col-span-3  mt-20">
                <div class="sticky  top-24 bg-white rounded-2xl border border-stone-200 shadow-sm p-2 space-y-1">

                    {{-- زر "جميع الأصناف" --}}
                    <button wire:click="selectCategory('all')"
                        class="w-full text-right flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-300 group
                {{ $selectedCategorySlug === 'all'
                    ? 'bg-[#2C0D0A] text-white shadow-md'
                    : 'text-stone-600 hover:bg-stone-50 hover:text-[#2C0D0A]' }}">
                        <div class="flex items-center gap-3">
                            @if ($selectedCategorySlug === 'all')
                                <x-lucide-layout-grid class="h-5 w-5" />
                            @else
                                <x-lucide-circle class="h-2 w-2 fill-current" />
                            @endif

                            <span class="text-sm font-bold">
                                {{ __('messages.menu.all') }}
                            </span>
                        </div>


                    </button>

                    <div class="h-px bg-stone-100 my-1"></div>

                    {{-- أزرار الأقسام --}}
                    @foreach ($this->categories as $cat)
                        @php $isSelected = $selectedCategorySlug === $cat->slug; @endphp

                        <button wire:key="tab-btn-{{ $cat->id }}"
                            wire:click="selectCategory('{{ $cat->slug }}')"
                            class="w-full text-right flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-300 group
                    {{ $isSelected
                        ? 'bg-[#E07513] text-white shadow-md shadow-amber-500/20'
                        : 'text-stone-600 hover:bg-stone-50 hover:text-[#2C0D0A]' }}">

                            <div class="flex items-center gap-3">
                                @if ($isSelected)
                                    <x-lucide-check class="h-4 w-4" />
                                @else
                                    <x-lucide-circle class="h-2 w-2 fill-current" />
                                @endif <span
                                    class="font-bold text-sm">{{ $cat->localized_name }}</span>
                            </div>

                            {{-- شارة عدد الأطباق --}}
                            <span
                                class="text-[10px] font-black px-2 py-1 rounded-lg transition-colors
                        {{ $isSelected ? 'bg-white/20 text-white' : 'bg-stone-100 text-stone-500 group-hover:bg-stone-200' }}">
                                {{ $cat->menu_items_count }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </aside>

            {{-- ========================================================= --}}
            {{-- 2. شريط التمرير الأفقي (يظهر فقط على الجوال) --}}
            {{-- ========================================================= --}}
            <div class="lg:hidden col-span-1 overflow-x-auto pb-2 -mx-4 px-4 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                <div class="flex items-center gap-2 min-w-max">
                    <button wire:click="selectCategory('all')"
                        class="px-4 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all
                {{ $selectedCategorySlug === 'all'
                    ? 'bg-[#2C0D0A] text-white shadow-md'
                    : 'bg-white text-stone-600 border border-stone-200' }}">
                        {{ __('messages.menu.all') ?? 'الكل' }}
                    </button>

                    @foreach ($this->categories as $cat)
                        @php $isSelected = $selectedCategorySlug === $cat->slug; @endphp
                        <button wire:key="mobile-tab-{{ $cat->id }}"
                            wire:click="selectCategory('{{ $cat->slug }}')"
                            class="px-4 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2
                    {{ $isSelected
                        ? 'bg-[#E07513] text-white shadow-md shadow-amber-500/20'
                        : 'bg-white text-stone-600 border border-stone-200' }}">
                            <span>{{ $cat->localized_name }}</span>
                            <span
                                class="text-[10px] px-1.5 py-0.5 rounded-full {{ $isSelected ? 'bg-black/20 text-white' : 'bg-stone-100 text-stone-600' }}">
                                {{ $cat->menu_items_count }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- 3. شبكة الأطباق (تأخذ المساحة المتبقية) --}}
            {{-- ========================================================= --}}
            <div class="col-span-1 lg:col-span-9">

                {{-- عنوان القسم الحالي (يظهر فقط على الشاشات الكبيرة للتوضيح) --}}
                <div class="hidden lg:flex items-center justify-between mb-6 pb-4 border-b border-stone-200">
                    <h3 class="text-xl font-black text-[#2C0D0A] flex items-center gap-2">
                        @if ($selectedCategorySlug === 'all')
                            <x-lucide-crown class="w-5 h-5" />{{ __('messages.menu.all') }}
                        @else
                           <x-lucide-utensils class="w-5 h-5" />
                            {{ $this->categories->firstWhere('slug', $selectedCategorySlug)?->localized_name ?: __('messages.menu.dishes') }}
                        @endif
                    </h3>
                    <span class="text-xs font-bold text-stone-500 bg-stone-100 px-3 py-1 rounded-full">
                        {{ $this->filteredItems->count() }} {{ trans_choice('messages.menu.dishes', $this->filteredItems->count()) }}
                    </span>
                </div>


                {{-- 2. شبكة الأطباق --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                    wire:loading.class="opacity-50 pointer-events-none" wire:target="selectCategory">

                    @forelse($this->filteredItems as $item)
                        @php
                            $inCart = isset($cart[$item->id]);
                            $cartQty = $inCart ? $cart[$item->id]['quantity'] : 0;
                        @endphp

                        <div wire:key="item-{{ $item->id }}"
                            class="group bg-white rounded-3xl overflow-hidden border border-stone-100 shadow-sm hover:shadow-2xl hover:shadow-stone-200/50 transition-all duration-300 flex flex-col">

                            {{-- صورة الطبق --}}
                            <div class="relative aspect-[4/3] overflow-hidden bg-stone-100">
                                @if ($item->image_path)
                                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->localized_name }}"
                                        loading="lazy"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-stone-50 text-stone-300 text-5xl">
                                        <x-lucide-soup class="w-10 h-10 text-stone-300" /></div>
                                @endif

                                {{-- شارات الطبق --}}
                                <div class="absolute top-3 start-3 flex flex-col gap-2">
                                    @if ($item->is_featured)
                                        <span
                                            class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                                            	<x-lucide-star class="w-3 h-3 fill-white" aria-hidden="true" /> {{ __('messages.menu.featured') }}
                                        </span>
                                    @endif
                                    @if ($item->is_spicy)
                                        <span
                                            class="px-2.5 py-1 bg-red-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                                            <x-lucide-flame class="w-3 h-3" aria-hidden="true" /> {{ __('messages.menu.spicy') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- السعر يطفو فوق الصورة --}}
                                <div
                                    class="absolute bottom-3 end-3 px-3 py-1.5 bg-white/95 backdrop-blur-sm rounded-xl shadow-lg border border-stone-100">
                                    <span
                                        class="text-sm font-black text-stone-900">€{{ number_format($item->price, 2) }}</span>
                                </div>
                            </div>

                            {{-- تفاصيل الطبق --}}
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex-1">
                                    <h4 class="text-lg font-black text-stone-900 mb-1.5 leading-snug">
                                        {{ $item->localized_name }}
                                    </h4>
                                    <p class="text-xs text-stone-500 leading-relaxed line-clamp-2 mb-3">
                                        {{ $item->localized_description }}
                                    </p>

                                    {{-- مسببات الحساسية --}}
                                    @if (!empty($item->allergens))
                                        <div class="flex flex-wrap gap-1 mb-3">
                                            @foreach (is_array($item->allergens) ? $item->allergens : json_decode($item->allergens, true) as $allergen)
                                                <span
                                                    class="text-[9px] font-bold px-2 py-0.5 bg-stone-100 text-stone-600 rounded-md border border-stone-200">
                                                    {{ $allergen }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- زر السلة الذكي --}}
                                <div class="pt-4 border-t border-stone-100 mt-auto">
                                    @if (!$inCart)
                                        <span wire:loading wire:target="addToCart({{ $item->id }})"
                                            class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>

                                        <button type="button" wire:click="addToCart({{ $item->id }})"
                                            class="w-full py-3 bg-stone-900 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition-all duration-300 flex items-center justify-center gap-2 group-hover:shadow-lg">

                                            <x-lucide-shopping-cart class="w-4 h-4" />
                                            <span>{{ __('messages.menu.addToCart') ?? 'أضف إلى السلة' }}</span>
                                        </button>
                                    @else
                                        <div
                                            class="flex items-center justify-between bg-amber-50 border border-amber-200 rounded-xl p-1">

                                            <button type="button" wire:click="updateQuantity({{ $item->id }}, -1)"
                                                class="w-9 h-9 rounded-lg bg-white text-stone-900 hover:bg-red-50 hover:text-red-600 flex items-center justify-center font-bold text-lg shadow-sm transition-colors">
                                                −
                                            </button>


                                            {{-- 1. زر الحذف الكامل من السلة --}}
                                            <button type="button" wire:click="removeFromCart({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="removeFromCart({{ $item->id }})"
                                                class="w-9 h-9 rounded-lg bg-white text-red-500 hover:bg-red-100 hover:text-red-600 flex items-center justify-center shadow-sm transition-all disabled:opacity-50 disabled:cursor-wait"
                                                title="{{ __('messages.menu.removeFromCart') }}">

                                                <span wire:loading.remove
                                                    wire:target="removeFromCart({{ $item->id }})"><x-lucide-trash-2 class="w-4 h-4" /></span>
                                                <span wire:loading wire:target="removeFromCart({{ $item->id }})"
                                                    class="w-4 h-4 border-2 border-red-500/30 border-t-red-600 rounded-full animate-spin"></span>
                                            </button>
                                            <span
                                                class="font-black text-stone-900 text-base w-8 text-center">{{ $cartQty }}</span>
                                            <button type="button" wire:click="updateQuantity({{ $item->id }}, 1)"
                                                class="w-9 h-9 rounded-lg bg-amber-500 text-white hover:bg-amber-600 flex items-center justify-center font-bold text-lg shadow-sm transition-colors">
                                                +
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                {{-- زر السلة الذكي مع تحميل مخصص لكل زر على حدة --}}


                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full text-center py-16 bg-stone-50 rounded-3xl border border-dashed border-stone-300">
                            <div class="text-5xl mb-3">	<x-lucide-utensils class="w-4 h-4" /></div>
                            <h3 class="text-lg font-bold text-stone-700">{{ __('messages.menu.empty') }}</h3>
                        </div>
                    @endforelse
                </div>


            </div>
        </div>

    </div>

    {{-- 4. الشريط العائم: سلة الأطباق     --}}
    @if (count($cart) > 0)
        <div
            class="fixed bottom-5 inset-x-4 sm:inset-x-auto sm:right-6 sm:left-6 max-w-2xl mx-auto z-40 animate-fade-in">
            <div
                class="bg-gradient-to-r from-[#2A0D0A] via-[#1E0907] to-[#2A0D0A] text-white p-3.5 sm:p-4 rounded-3xl shadow-2xl border-2 border-[#E07513] flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div
                            class="w-11 h-11 rounded-2xl bg-[#E07513] text-white flex items-center justify-center font-bold shadow-md text-lg">
                          <x-lucide-shopping-bag class="w-6 h-6" />
                        </div>
                        <span
                            class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-amber-400 text-stone-950 text-[11px] font-black flex items-center justify-center">
                            {{ $this->totalCartCount }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-amber-200 font-bold block">{{ __('messages.menu.cartReady')  }}</span>
                        <span class="text-sm sm:text-base font-black text-white">
                            {{ __('messages.menu.total')  }}: €{{ number_format($this->totalCartAmount, 2) }}
                        </span>
                    </div>
                </div>

                <button type="button" wire:click="$set('isCartModalOpen', true)"
                    class="px-5 py-2.5 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-black rounded-2xl text-xs sm:text-sm shadow-md transition-all flex items-center gap-2 cursor-pointer shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
{{--  'إتمام الطلب والحجز الآن' --}}
                    <span>{{ __('messages.menu.checkout')  }}</span>
                    <x-lucide-arrow-left class="w-4 h-4 rtl:rotate-0 ltr:rotate-180" aria-hidden="true" />
                </button>
            </div>
        </div>
    @endif

    {{-- 5. نافذة السلة وتأكيد الحجز الفعلي (Cart Drawer / Checkout Modal) --}}
    @if ($isCartModalOpen)
        <div class="fixed inset-0 z-50 overflow-hidden" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
             role="dialog" aria-modal="true" aria-labelledby="cart-modal-title"
             x-data @keydown.window.escape="$wire.set('isCartModalOpen', false)">
            {{-- خلفية معتمة --}}
            <div class="absolute inset-0 bg-stone-900/70 backdrop-blur-sm transition-opacity"
                wire:click="$set('isCartModalOpen', false)" aria-hidden="true"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-0 sm:pl-10">
                <div
                    class="w-screen max-w-md md:max-w-lg bg-[#FAF6F0] text-[#2C1810] shadow-2xl flex flex-col justify-between border-l border-[#E8DFD3] animate-slide-in-right">

                    {{-- رأس النافذة --}}
                    <div
                        class="p-5 bg-gradient-to-r from-[#2A0D0A] to-[#1A0604] text-white flex items-center justify-between border-b border-[#E07513]/30 shrink-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center text-xl">
                                <x-lucide-crown class="w-5 h-5" /></div>
                            <div>
                                <h3 class="font-black text-base text-white">{{ __('messages.menu.cart') ?? 'سلة الأطباق وتثبيت الحجز' }}</h3>
                                <p class="text-xs text-amber-200">{{ $this->totalCartCount }} {{ __('messages.menu.items') ?? 'أصناف مختارة' }}</p>
                            </div>
                        </div>
                        <button wire:click="$set('isCartModalOpen', false)"
                            class="text-stone-400 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- محتوى النافذة (قابل للتمرير) --}}
                    <div class="flex-1 overflow-y-auto p-5 space-y-5">

                        {{-- قائمة الأطباق --}}
                        @if(count($cart) === 0)
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mb-4 border-2 border-stone-200">
                                    <x-lucide-shopping-cart class="w-10 h-10 text-stone-300" />
                                </div>
                                <h3 class="text-sm font-black text-stone-700 mb-2">{{ __('messages.menu.empty') ?? 'السلة فارغة' }}</h3>
                                <p class="text-xs text-stone-500 max-w-[200px] mb-6">لم تقم بإضافة أي أطباق لوليمتك حتى الآن.</p>
                                <button type="button" @click="$wire.set('isCartModalOpen', false)"
                                    class="px-5 py-2.5 bg-stone-900 text-white font-bold rounded-xl text-xs hover:bg-stone-800 transition-colors">
                                    تصفح المنيو الآن
                                </button>
                            </div>
                        @else
                            <div>
                                <h4
                                    class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <span><x-lucide-utensils class="w-4 h-4" /></span> {{ __('messages.menu.selectedDishes') ?? 'الأطباق المختارة للوليمة' }}
                                </h4>
                                <div class="space-y-3">
                                    @foreach ($cart as $id => $cartItem)
                                        <div
                                            class="bg-white p-3 rounded-xl border border-stone-200 flex items-center justify-between shadow-sm group hover:border-amber-200 transition-colors">
                                            <div class="flex-1 min-w-0">
                                                <div class="font-bold text-xs text-[#2C0D0A] truncate">
                                                    {{ $cartItem['name'] }}</div>
                                                <div class="text-xs font-black text-[#E07513] mt-0.5">
                                                    €{{ number_format($cartItem['price'] * $cartItem['quantity'], 2) }}
                                                </div>
                                            </div>

                                            <div
                                                class="flex items-center gap-2 bg-stone-50 p-1 rounded-lg border border-stone-200">
                                                {{-- زر الحذف السريع --}}
                                                <button type="button" wire:click="removeFromCart({{ $id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="removeFromCart({{ $id }})"
                                                    class="w-7 h-7 flex items-center justify-center text-stone-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors disabled:opacity-50"
                                                    title="{{ __('messages.menu.removeFromCart') }}">
                                                    <span wire:loading.remove
                                                        wire:target="removeFromCart({{ $id }})"><x-lucide-trash-2 class="w-3 h-3" /></span>
                                                    <span wire:loading wire:target="removeFromCart({{ $id }})"
                                                        class="w-3 h-3 border border-red-500 border-t-transparent rounded-full animate-spin"></span>
                                                </button>

                                                <div class="w-px h-5 bg-stone-300"></div>

                                                <button type="button"
                                                    wire:click="updateQuantity({{ $id }}, -1)"
                                                    wire:loading.attr="disabled"
                                                    wire:target="updateQuantity({{ $id }})"
                                                    class="w-7 h-7 bg-white rounded text-stone-900 hover:bg-red-50 hover:text-red-600 flex items-center justify-center font-bold text-sm shadow-sm transition-colors disabled:opacity-50">−</button>

                                                <span class="w-5 text-center text-xs font-black text-[#2C0D0A]">
                                                    <span wire:loading.remove
                                                        wire:target="updateQuantity({{ $id }}), removeFromCart({{ $id }})">{{ $cartItem['quantity'] }}</span>
                                                    <span wire:loading
                                                        wire:target="updateQuantity({{ $id }}), removeFromCart({{ $id }})"
                                                        class="w-3 h-3 border-2 border-amber-500/30 border-t-amber-600 rounded-full animate-spin inline-block"></span>
                                                </span>

                                                <button type="button"
                                                    wire:click="updateQuantity({{ $id }}, 1)"
                                                    wire:loading.attr="disabled"
                                                    wire:target="updateQuantity({{ $id }})"
                                                    class="w-7 h-7 bg-[#E07513] text-white rounded hover:bg-amber-600 flex items-center justify-center font-bold text-sm shadow-sm transition-colors disabled:opacity-50">+</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        {{-- نموذج بيانات الحجز --}}
                        <form wire:submit="checkout" class="space-y-4 pt-4 border-t border-stone-200">
                            <h4
                                class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-1 flex items-center gap-2">
                                <span>	<x-lucide-clipboard-list class="w-4 h-4" /></span> {{ __('messages.reservation.contactDetails') }}
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">{{ __('messages.reservation.fullName') }} <span class="text-rose-500" aria-hidden="true">*</span></label>
                                    <input type="text" wire:model="customer_name" required
                                        placeholder="{{ __('messages.reservation.fullNamePlaceholder') }}"
                                        autocomplete="name"
                                        class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all">
                                    @error('customer_name') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">{{ __('messages.reservation.phone') }} <span class="text-rose-500" aria-hidden="true">*</span></label>
                                    <input type="tel" wire:model="customer_phone" required
                                        placeholder="+31 6 1234 5678"
                                        dir="ltr"
                                        autocomplete="tel"
                                        class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all">
                                    @error('customer_phone') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">{{ __('messages.reservation.email') ?? __('messages.contact.form.email') }}</label>
                                    <input type="email" wire:model="customer_email"
                                        placeholder="example@domain.com"
                                        autocomplete="email"
                                        class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all">
                                    @error('customer_email') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">{{ __('messages.reservation.reservationDate') }} <span class="text-rose-500" aria-hidden="true">*</span></label>
                                    <input type="date" wire:model="reservation_date" required
                                        min="{{ now()->toDateString() }}"
                                        class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all">
                                    @error('reservation_date') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">{{ __('messages.reservation.preferredTime') }} <span class="text-rose-500" aria-hidden="true">*</span></label>
                                    <input type="time" wire:model="reservation_time" required
                                        class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all">
                                    @error('reservation_time') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">{{ __('messages.reservation.serviceType') }}</label>
                                    <select wire:model="order_type"
                                        class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all appearance-none">
                                        <option value="dine_in">{{ __('messages.reservation.dineIn') }}</option>
                                        <option value="takeaway">{{ __('messages.reservation.takeaway') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="cart-party-size" class="block text-xs font-bold text-stone-700 mb-1.5">
                                        {{ __('messages.reservation.partySize') }}
                                    </label>
                                    <input id="cart-party-size" type="number" wire:model="party_size" min="1" max="20"
                                        required
                                        class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all">
                                    @error('party_size') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">{{ __('messages.reservation.specialRequests') }}</label>
                                <textarea wire:model="special_requests" rows="2"
                                    placeholder="{{ __('messages.reservation.requestsPlaceholderMenu') }}"
                                    class="w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all resize-none"></textarea>
                            </div>

                            {{-- ملخص السعر وزر الإرسال --}}
                            <div class="pt-4 border-t border-stone-200 mt-6 space-y-4">
                                <div
                                    class="bg-[#FAF4ED] p-4 rounded-xl border border-[#E07513]/25 flex justify-between items-center">
                                    <span class="text-sm font-bold text-stone-700">{{ __('messages.reservation.orderTotal') }}</span>
                                    <span
                                        class="text-xl font-black text-[#E07513]">€{{ number_format($this->totalCartAmount, 2) }}</span>
                                </div>

                                <button type="submit" wire:loading.attr="disabled"
                                    class="w-full py-4 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-black rounded-xl text-sm shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">

                                    <span wire:loading.remove>{{ __('messages.reservation.confirmReservation') }} <x-lucide-crown class="w-5 h-5 inline-block" /></span>

                                    <span wire:loading
                                        class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                    <span wire:loading>{{ __('messages.reservation.processingReservation') }}</span>
                                </button>

                                <p class="text-[10px] text-center text-stone-400 flex items-center justify-center gap-1">
                                    <x-lucide-shield-check class="w-3 h-3" />
                                    <span>{{ __('messages.reservation.smsNotice') }}</span>
                                </p>
                            </div>

                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    @endif


</section>
