<section id="menu" class="py-20 bg-[#FAF6F0] text-[#2C1810] relative overflow-hidden border-b border-[#E8DFD3]"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- رأس القسم --}}
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
            <div
                class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-[#E07513]/10 text-[#E07513] text-xs font-bold">
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

        {{-- 1. بطاقات الأقسام البصرية --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
            @foreach ($this->categories as $category)
                @php
                    $isSelected = $selectedCategorySlug === $category->slug;
                @endphp
                <div wire:key="cat-card-{{ $category->id }}"
                    wire:click="selectCategory('{{ $isSelected ? 'all' : $category->slug }}')"
                    class="group relative rounded-3xl p-5 cursor-pointer transition-all duration-300 overflow-hidden flex flex-col justify-between select-none border {{ $isSelected ? 'bg-gradient-to-b from-[#2E0F0B] to-[#1F0906] text-white border-[#E07513] ring-2 ring-[#E07513]/30 shadow-xl transform -translate-y-1' : 'bg-white hover:bg-[#FFFDF9] text-[#2C1810] border-stone-200 hover:border-[#E07513]/40 shadow-xs hover:shadow-md' }}">

                    @if ($category->image_path)
                        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-300 {{ $isSelected ? 'opacity-20' : 'opacity-10 group-hover:opacity-15' }}"
                            style="background-image: url('{{ asset($category->image_path) }}');"></div>
                    @endif

                    <div class="relative z-10 space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <div
                                class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-lg {{ $isSelected ? 'bg-[#E07513] text-white shadow-md' : 'bg-[#FAF4ED] text-[#E07513] border border-[#E07513]/20' }}">
                                🍽️
                            </div>

                            <span
                                class="text-[10px] font-extrabold px-2.5 py-1 rounded-full {{ $isSelected ? 'bg-white/20 text-amber-200' : 'bg-stone-100 text-stone-600' }}">
                                {{ $category->menu_items_count }} {{ __('messages.menu.dishes') ?? 'أطباق' }}
                            </span>
                        </div>

                        <div>
                            <h3
                                class="font-black text-sm sm:text-base leading-snug tracking-tight {{ $isSelected ? 'text-white' : 'text-[#2C0D0A] group-hover:text-[#E07513]' }}">
                                {{ $category->localized_name }}
                            </h3>
                            @if ($category->localized_description)
                                <p
                                    class="text-[11px] leading-relaxed mt-1 line-clamp-2 {{ $isSelected ? 'text-stone-300' : 'text-stone-500' }}">
                                    {{ $category->localized_description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div
                        class="relative z-10 pt-3 mt-3 border-t border-stone-200/50 flex items-center justify-between text-[10px] font-bold">
                        <span class="{{ $isSelected ? 'text-amber-300' : 'text-[#E07513]' }}">
                            {{ $isSelected ? '✓ محدد' : 'استعراض الأطباق' }}
                        </span>
                        <span class="font-mono text-stone-400">#{{ $category->sort_order }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 2. شريط التبويبات الفوري --}}
        <div
            class="flex flex-wrap items-center justify-center gap-2 mb-8 bg-[#EFE8DC] p-1.5 rounded-2xl max-w-4xl mx-auto border border-[#DFD5C6]">
            <button wire:click="selectCategory('all')"
                class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all cursor-pointer {{ $selectedCategorySlug === 'all' ? 'bg-[#2C0D0A] text-white shadow-sm' : 'text-stone-700 hover:text-stone-900 hover:bg-white/60' }}">
                <span>{{ __('messages.menu.all') ?? 'جميع الأصناف' }}</span>
            </button>

            @foreach ($this->categories as $cat)
                <button wire:key="tab-btn-{{ $cat->id }}" wire:click="selectCategory('{{ $cat->slug }}')"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer {{ $selectedCategorySlug === $cat->slug ? 'bg-[#E07513] text-white shadow-sm' : 'text-stone-700 hover:text-stone-900 hover:bg-white/60' }}">
                    <span>{{ $cat->localized_name }}</span>
                    <span
                        class="text-[10px] px-1.5 py-0.2 rounded-full {{ $selectedCategorySlug === $cat->slug ? 'bg-black/25 text-white' : 'bg-stone-200 text-stone-700' }}">
                        {{ $cat->menu_items_count }}
                    </span>
                </button>
            @endforeach
        </div>

        {{-- 3. شبكة الأطباق مع زر "أضف إلى السلة" الذكي --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            wire:loading.class="opacity-60 transition-opacity" wire:target="selectCategory, addToCart, updateQuantity">
        @forelse($this->filteredItems as $item)
            @php
                $inCart = isset($cart[$item->id]);
                $cartQty = $inCart ? $cart[$item->id]['quantity'] : 0;
            @endphp
            <div wire:key="item-{{ $item->id }}"
                class="bg-white rounded-3xl p-6 border transition-all duration-300 flex flex-col justify-between text-right relative hover:shadow-xl {{ $item->is_featured ? 'border-[#E07513]/40 ring-1 ring-[#E07513]/25 shadow-sm' : 'border-stone-200 hover:border-[#E07513]/30 shadow-xs' }}">
                <div>
                    <div class="flex items-start justify-between gap-3 mb-2.5">
                        <div class="space-y-0.5">
                            <h4 class="text-base font-extrabold text-[#2C0D0A] flex items-center gap-1.5">
                                <span>{{ $item->localized_name }}</span>
                                @if ($item->is_featured)
                                    <span title="طبق الشيف الملكي">⭐</span>
                                @endif
                            </h4>
                        </div>

                        <div
                            class="bg-[#FAF4ED] px-3.5 py-1.5 rounded-2xl border border-[#E07513]/25 text-center shrink-0">
                            <span
                                class="text-base font-black text-[#E07513]">€{{ number_format($item->price, 2) }}</span>
                        </div>
                    </div>

                    <p class="text-xs text-stone-600 leading-relaxed mt-2 line-clamp-3">
                        {{ $item->localized_description }}
                    </p>
                </div>

                {{-- زر السلة  : إما إضافة أو زيادة ونقصان --}}
                <div class="pt-4 mt-4 border-t border-stone-100 flex items-center justify-between gap-2">
                    <span class="text-[11px] font-bold text-[#E07513] bg-[#E07513]/10 px-2.5 py-1 rounded-lg">
                        {{ $item->category?->localized_name }}
                    </span>

                    @if (!$inCart)
                        <button type="button" wire:click="addToCart({{ $item->id }})"
                            class="px-3.5 py-2 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-extrabold rounded-xl text-xs shadow-xs hover:shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                            <span>🛒</span>
                            <span>{{ __('messages.menu.addToCart') ?? 'أضف إلى السلة' }}</span>
                        </button>
                        
                    @else
                        <div class="flex items-center gap-1.5 bg-[#FAF4ED] p-1 rounded-xl border border-[#E07513]/30">
                            <button type="button" wire:click="updateQuantity({{ $item->id }}, -1)"
                                class="w-6 h-6 rounded-lg bg-white text-[#2C0D0A] hover:bg-stone-200 flex items-center justify-center font-bold text-xs shadow-xs cursor-pointer">
                                -
                            </button>
                            <span class="w-5 text-center font-black text-xs text-[#2C0D0A]">
                                {{ $cartQty }}
                            </span>
                            <button type="button" wire:click="updateQuantity({{ $item->id }}, 1)"
                                class="w-6 h-6 rounded-lg bg-[#E07513] text-white hover:bg-[#c2620a] flex items-center justify-center font-bold text-xs shadow-xs cursor-pointer">
                                +
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-stone-500">
                {{ __('messages.menu.empty') ?? 'لا توجد أطباق متاحة حالياً' }}
            </div>
        @endforelse
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
                                <input    type="number" 
    wire:model="party_size" 
    label="عدد الأشخاص" 
    min="1" 
    max="20"
    required
                                   
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
