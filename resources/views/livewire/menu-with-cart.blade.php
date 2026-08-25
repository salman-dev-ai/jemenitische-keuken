<section id="menu" class="py-10 bg-[#FAF6F0] text-[#2C1810] relative overflow-hidden border-b border-[#E8DFD3]"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        {{-- 1. رأس القسم وبطاقات الأقسام البصرية --}}
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-700 text-xs font-bold border border-amber-500/20">
                <span>✨</span>
                <span>{{ __('messages.menu.badge') ?? 'الأصناف الملكية' }}</span>
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
                                🍽️</div>
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
                            <span class="text-lg">{{ $selectedCategorySlug === 'all' ? '👑' : '🍽️' }}</span>
                            <span class="font-bold text-sm">{{ __('messages.menu.all') ?? 'جميع الأصناف' }}</span>
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
                                <span class="text-lg">{{ $isSelected ? '✨' : '🔸' }}</span>
                                <span class="font-bold text-sm">{{ $cat->localized_name }}</span>
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
            <div class="lg:hidden col-span-1 overflow-x-auto pb-2 -mx-4 px-4 no-scrollbar">
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
            <main class="col-span-1 lg:col-span-9">

                {{-- عنوان القسم الحالي (يظهر فقط على الشاشات الكبيرة للتوضيح) --}}
                <div class="hidden lg:flex items-center justify-between mb-6 pb-4 border-b border-stone-200">
                    <h3 class="text-xl font-black text-[#2C0D0A] flex items-center gap-2">
                        @if ($selectedCategorySlug === 'all')
                            <span>👑</span> جميع الأطباق
                        @else
                            <span>🍽️</span>
                            {{ $this->categories->firstWhere('slug', $selectedCategorySlug)?->localized_name ?? 'الأطباق' }}
                        @endif
                    </h3>
                    <span class="text-xs font-bold text-stone-500 bg-stone-100 px-3 py-1 rounded-full">
                        {{ $this->filteredItems->count() }} طبق متاح
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
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-stone-50 text-stone-300 text-5xl">
                                        🍲</div>
                                @endif

                                {{-- شارات الطبق --}}
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    @if ($item->is_featured)
                                        <span
                                            class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                                            ⭐ مميز
                                        </span>
                                    @endif
                                    @if ($item->is_spicy)
                                        <span
                                            class="px-2.5 py-1 bg-red-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                                            🌶️ حار
                                        </span>
                                    @endif
                                </div>

                                {{-- السعر يطفو فوق الصورة --}}
                                <div
                                    class="absolute bottom-3 right-3 px-3 py-1.5 bg-white/95 backdrop-blur-sm rounded-xl shadow-lg border border-stone-100">
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

                                            <span>🛒</span>
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
                                                title="حذف الطبق نهائياً من السلة">

                                                <span wire:loading.remove
                                                    wire:target="removeFromCart({{ $item->id }})">🗑️</span>
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
                            <div class="text-5xl mb-3">🍽️</div>
                            <h3 class="text-lg font-bold text-stone-700">لا توجد أطباق في هذا القسم حالياً</h3>
                            <p class="text-sm text-stone-500">يرجى اختيار قسم آخر أو العودة لاحقاً</p>
                        </div>
                    @endforelse
                </div>


            </main>
        </div>

        إضافة CSS لإخفاء شريط التمرير في الجوال مع الحفاظ على وظيفته
        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>


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
                            🛒
                        </div>
                        <span
                            class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-amber-400 text-stone-950 text-[11px] font-black flex items-center justify-center">
                            {{ $this->totalCartCount }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-amber-200 font-bold block">سلة الأطباق الملكية جاهزة</span>
                        <span class="text-sm sm:text-base font-black text-white">
                            المجموع: €{{ number_format($this->totalCartAmount, 2) }}
                        </span>
                    </div>
                </div>

                <button type="button" wire:click="$set('isCartModalOpen', true)"
                    class="px-5 py-2.5 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-black rounded-2xl text-xs sm:text-sm shadow-md transition-all flex items-center gap-2 cursor-pointer shrink-0">
                    <span>إتمام الطلب والحجز الآن</span>
                    <span>←</span>
                </button>
            </div>
        </div>
    @endif

    {{-- 5. نافذة السلة وتأكيد الحجز الفعلي (Cart Drawer / Checkout Modal) --}}
    @if ($isCartModalOpen)
        <div class="fixed inset-0 z-50 overflow-hidden" dir="rtl">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-xs" wire:click="$set('isCartModalOpen', false)">
            </div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-0 sm:pl-10">
                <div
                    class="w-screen max-w-md md:max-w-lg bg-[#FAF6F0] text-[#2C1810] shadow-2xl flex flex-col justify-between border-l border-[#E8DFD3]">

                    {{-- رأس النافذة --}}
                    <div
                        class="p-5 bg-gradient-to-r from-[#2A0D0A] to-[#1A0604] text-white flex items-center justify-between border-b border-[#E07513]/30">
                        <div class="flex items-center gap-2.5">
                            <span class="text-2xl">👑</span>
                            <div>
                                <h3 class="font-black text-base text-white">سلة الأطباق وتثبيت الحجز</h3>
                                <p class="text-xs text-amber-200">{{ $this->totalCartCount }} أصناف مختارة</p>
                            </div>
                        </div>
                        <button wire:click="$set('isCartModalOpen', false)"
                            class="text-white hover:text-amber-300 text-xl font-bold cursor-pointer">✕</button>
                    </div>

                    {{-- نموذج الحجز والطلب الفعلي --}}
                    <div class="flex-1 overflow-y-auto p-5 space-y-4 text-right">
                        <h4 class="font-bold text-xs text-stone-600 border-b pb-2">الأطباق المختارة للوليمة:</h4>

                        <div class="space-y-2">
                            @foreach ($cart as $id => $cartItem)
                                <div
                                    class="bg-white p-3 rounded-2xl border border-stone-200 flex items-center justify-between shadow-xs">
                                    <div>
                                        <div class="font-bold text-xs text-[#2C0D0A]">{{ $cartItem['name'] }}</div>
                                        <div class="text-xs font-black text-[#E07513]">
                                            €{{ number_format($cartItem['price'] * $cartItem['quantity'], 2) }}</div>
                                    </div>
                                    <div class="flex items-center gap-1.5 bg-[#FAF6F0] p-1 rounded-xl">
                                        <button type="button" wire:click="updateQuantity({{ $id }}, -1)"
                                            class="w-6 h-6 bg-white rounded text-xs font-bold shadow-xs cursor-pointer">-</button>
                                        <span
                                            class="w-5 text-center text-xs font-bold">{{ $cartItem['quantity'] }}</span>
                                        <button type="button" wire:click="updateQuantity({{ $id }}, 1)"
                                            class="w-6 h-6 bg-[#E07513] text-white rounded text-xs font-bold shadow-xs cursor-pointer">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form wire:submit="checkout" class="space-y-3 pt-4 border-t border-stone-200">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">الاسم الكريم *</label>
                                <input type="text" wire:model="customer_name" required
                                    placeholder="مثال: صالح اليافعي"
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">رقم الهاتف / الواتساب
                                    *</label>
                                <input type="tel" wire:model="customer_phone" required
                                    placeholder="+31 6 1234 5678"
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">البريد الإلكتروني *</label>
                                <input type="email" wire:model="customer_email" required
                                    placeholder="example@domain.com"
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513]">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1">تاريخ الحضور *</label>
                                    <input type="date" wire:model="reservation_date" required
                                        class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1">وقت الحضور *</label>
                                    <input type="time" wire:model="reservation_time" required
                                        class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">نوع الطلب</label>
                                <select wire:model="order_type"
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513]">
                                    <option value="dine_in">تناول داخل المطعم (جلسة ديوان / طاولة عائلية)</option>
                                    <option value="takeaway">استلام سفري ساخن</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">ملاحظات خاصة للشيف</label>
                                <textarea wire:model="special_requests" rows="2"
                                    placeholder="مثال: نرجو تجهيز شاي عدني مع الوجبة وتقديم المندي ساخناً..."
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513] resize-none"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">عدد الأشخاص *</label>
                                <input type="number" wire:model="party_size" label="عدد الأشخاص" min="1"
                                    max="20" required
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-stone-200 text-xs outline-hidden focus:border-[#E07513]">
                            </div>



                            <div
                                class="bg-[#FAF4ED] p-3 rounded-xl border border-[#E07513]/25 flex justify-between font-black text-sm text-[#2C0D0A]">
                                <span>المجموع الإجمالي:</span>
                                <span class="text-[#E07513]">€{{ number_format($this->totalCartAmount, 2) }}</span>
                            </div>

                            <button type="submit"
                                class="w-full py-3.5 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-black rounded-xl text-sm shadow-md transition-all cursor-pointer">
                                تأكيد الحجز وتثبيت الطلب الآن 👑
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endif

    </div>
</section>
